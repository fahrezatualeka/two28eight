<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\IOFactory;

class CheckoutController extends Controller
{

    private function getOngkirFromWord($subdistrictId)
    {
        $filePath = storage_path('app/ongkir/DAFTAR_ONGKIR_JNT.docx');
        if (!file_exists($filePath)) {
            Log::error('File ongkir tidak ditemukan: ' . $filePath);
            return null;
        }

        try {
            $phpWord = IOFactory::load($filePath);
            
            // Log untuk memeriksa isi file (bisa dihapus setelah debugging)
            // Log::info('Mulai membaca file Word...');

            // Ambil semua teks dari dokumen
            $fullText = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $fullText .= $element->getText() . "\n";
                    } else if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                        foreach ($element->getRows() as $row) {
                            foreach ($row->getCells() as $cell) {
                                foreach ($cell->getElements() as $ce) {
                                    if (method_exists($ce, 'getText')) {
                                        $fullText .= $ce->getText() . ' ';
                                    }
                                }
                                $fullText .= "\t";
                            }
                            $fullText .= "\n";
                        }
                    }
                }
            }

            // Memproses teks baris per baris
            $lines = explode("\n", $fullText);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Memisahkan baris berdasarkan spasi atau tab
                $parts = preg_split('/\s+/', $line);

                // Asumsi format: [kode_kecamatan] [nama_kecamatan] ... ECO [biaya_eco] EZ [biaya_ez]
                // Contoh: 1101010 TEUPAH SELATAN ECO 51500 EZ 74000
                // Asumsi format untuk JND: [kode_kecamatan] [nama_kecamatan] ... NDX [biaya_ndx]
                // Namun, kita akan menggunakan pola yang lebih fleksibel
                
                // Cari ID kecamatan di awal baris
                if (isset($parts[0]) && $parts[0] == $subdistrictId) {
                    $costs = [
                        'eco' => null,
                        'ez'  => null,
                        'jnd' => null,
                    ];

                    // Iterasi untuk mencari biaya
                    for ($i = 1; $i < count($parts); $i++) {
                        $keyword = strtoupper($parts[$i]);
                        $cost = isset($parts[$i + 1]) ? (int) preg_replace('/\D/', '', $parts[$i+1]) : null;
                        
                        if ($cost !== null) {
                            if ($keyword === 'ECO') {
                                $costs['eco'] = $cost;
                            } elseif ($keyword === 'EZ') {
                                $costs['ez'] = $cost;
                            } elseif ($keyword === 'JND') {
                                $costs['jnd'] = $cost;
                            }
                        }
                    }
                    
                    // Log::info('Ongkir ditemukan untuk ' . $subdistrictId, $costs);
                    return $costs;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error membaca file Word: ' . $e->getMessage());
        }

        // Log::info('Ongkir tidak ditemukan untuk ' . $subdistrictId);
        return null;
    }

    public function calculateShippingCost(Request $request)
    {
        $request->validate([
            'subdistrict_id'=> 'required|string',
            'courier'       => 'required|string',
        ]);
    
        $subdistrictId = $request->subdistrict_id;
        $courier = strtolower($request->courier);
    
        $ongkir = $this->getOngkirFromWord($subdistrictId);
    
        if (!$ongkir) {
            return response()->json([
                'eco' => null,
                'ez'  => null,
                'jnd' => null,
            ]);
        }
    
        if ($courier === 'all') {
            return response()->json($ongkir); // kirim semua (eco, ez, jnd)
        }
    
        if (isset($ongkir[$courier]) && $ongkir[$courier] !== null) {
            return response()->json([
                'cost' => $ongkir[$courier],
                'estimate' => '2-4 hari',
            ]);
        }
    
        return response()->json([
            'cost' => 0,
            'estimate' => 'Tidak tersedia',
        ]);
    }
    
    private function sendWhatsAppNotification($order)
    {
        $fonnteToken = 'NehkJetr9zN3JaXXXqJb';
        $adminPhoneNumber = '082248302960';
        $formattedAdminNumber = preg_replace('/^0/', '62', $adminPhoneNumber);
        $grandTotal = $order->total_price;
        $message = "Halo Admin! \n\n" .
            "Pesanan baru telah masuk! \n" .
            "Berikut detail pesanan: \n" .
            "Nomor Pesanan: " . $order->order_number . "\n" .
            "Nama Pelanggan: " . $order->nama . "\n" .
            "Total Pembayaran: Rp" . number_format($grandTotal, 0, ',', '.') . "\n\n" .
            "Silakan cek bukti pembayaran yang dikirim dan lakukan verifikasi secepatnya.";

        try {
            Http::withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                'target' => $formattedAdminNumber,
                'message' => $message,
            ]);
            Log::info('WhatsApp notification sent successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'order_id' => 'required|exists:orders,id',
        ]);
        $file = $request->file('bukti_pembayaran');
        $filename = uniqid('bukti_') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('bukti_pembayaran', $filename);
        $order = Order::find($request->order_id);
        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }
        $order->bukti_pembayaran = $filename;
        $order->status = 'Menunggu Verifikasi';
        $order->save();
        $this->sendWhatsAppNotification($order);
        return redirect()->route('home')->with('success', 'Bukti pembayaran berhasil dikirim kan. Status anda saat ini Menunggu verifikasi oleh admin. Selalu pantau status pengiriman anda di menu (Status Pesanan) menggunakan nomor pesanan anda');
    }

    public function getProvinces()
    {
        try {
            $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error('Error fetching provinces from emsifa API: ' . $e->getMessage());
            return [];
        }
    }

    public function getCities($provinceCode)
    {
        try {
            $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinceCode}.json");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error('Error fetching cities from emsifa API: ' . $e->getMessage());
            return [];
        }
    }

    public function getSubdistricts($cityCode)
    {
        try {
            $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$cityCode}.json");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error('Error fetching subdistricts from emsifa API: ' . $e->getMessage());
            return [];
        }
    }

    private function getProvinceNameById($provinceCode)
    {
        $provinces = $this->getProvinces();
        foreach ($provinces as $province) {
            if ($province['id'] == $provinceCode) {
                return $province['name'];
            }
        }
        return null;
    }

    private function getCityNameById($provinceCode, $cityCode)
    {
        $cities = $this->getCities($provinceCode);
        foreach ($cities as $city) {
            if ($city['id'] == $cityCode) {
                return $city['name'];
            }
        }
        return null;
    }

    private function getSubdistrictNameById($cityCode, $subdistrictCode)
    {
        $subdistricts = $this->getSubdistricts($cityCode);
        foreach ($subdistricts as $subdistrict) {
            if ($subdistrict['id'] == $subdistrictCode) {
                return $subdistrict['name'];
            }
        }
        return null;
    }

    public function multiple(Request $request)
    {
        $selected = explode(',', $request->selected_products);
        $cart = session()->get('cart', []);
        $checkoutItems = [];
        $subtotal = 0;
        foreach ($selected as $id) {
            if (isset($cart[$id])) {
                $checkoutItems[$id] = $cart[$id];
                $subtotal += $cart[$id]['price'] * $cart[$id]['quantity'];
            }
        }
        $provinces = $this->getProvinces();
        session()->put('checkout_items', $checkoutItems);
        return view('checkout', compact('checkoutItems', 'subtotal', 'provinces'));
    }

    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $size = $request->get('size', 'S');
        $qty = (int) $request->get('quantity', 1);
        $checkoutItems = [
            $id => [
                'id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'size' => $size,
                'image' => is_array($product->image) ? $product->image : json_decode($product->image, true),
            ]
        ];
        $subtotal = $product->price * $qty;
        $provinces = $this->getProvinces();
        session()->put('checkout_items', $checkoutItems);
        return view('checkout', compact('checkoutItems', 'subtotal', 'provinces'));
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            $provinsi_name = $this->getProvinceNameById($request->provinsi);
            $kota_name = $this->getCityNameById($request->provinsi, $request->kota);
            // Tambahkan baris ini
            $kecamatan_name = $this->getSubdistrictNameById($request->kota, $request->kecamatan);
    
            $shipping_cost = (int) $request->input('shipping_cost', 0);
            $subtotal_price = (int) $request->input('subtotal_price', 0);
    
            $order = Order::create([
                'order_number' => '28-' . strtoupper(uniqid()),
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kecamatan' => $kecamatan_name, // Ditambahkan
                'kota' => $kota_name,
                'provinsi' => $provinsi_name,
                'kode_pos' => $request->kode_pos,
                'telepon' => $request->telepon,
                'metode_pengiriman' => $request->metode_pengiriman,
                'shipping_cost' => $shipping_cost,
                'status' => 'Menunggu Pembayaran',
                'total_price' => 0,
            ]);
            
            $checkoutItems = session()->get('checkout_items', []);

            foreach ($checkoutItems as $item) {
                $dbProduct = Product::find($item['id']);
                if ($dbProduct) {
                    $price = $dbProduct->price;
                    
                    // Update stock
                    if ($dbProduct->sizes) {
                        $sizes = is_string($dbProduct->sizes) ? json_decode($dbProduct->sizes, true) : $dbProduct->sizes;
                        if (is_array($sizes)) {
                            $updatedSizes = collect($sizes)->map(function ($sizeItem) use ($item) {
                                if ($sizeItem['size'] === $item['size']) {
                                    $sizeItem['stock'] -= $item['quantity'];
                                }
                                return $sizeItem;
                            });
                            $dbProduct->sizes = json_encode($updatedSizes);
                        }
                    } else {
                        $dbProduct->stock -= $item['quantity'];
                    }
                    $dbProduct->save();

                    $productImage = is_string($dbProduct->image) ? $dbProduct->image : json_encode($dbProduct->image);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $dbProduct->id,
                        'name' => $dbProduct->name,
                        'price' => $price,
                        'size' => $item['size'],
                        'quantity' => $item['quantity'],
                        'image' => $productImage,
                    ]);
                }
            }
            
            $finalTotal = $subtotal_price + $shipping_cost;
            $order->update(['total_price' => $finalTotal]);

            session()->forget('checkout_items');

            DB::commit();
            $order->refresh();

            return view('checkout-success', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'nama' => $order->nama,
                'alamat' => $order->alamat,
                'kecamatan' => $order->kecamatan,
                'kota' => $order->kota,
                'provinsi' => $order->provinsi,
                'kode_pos' => $order->kode_pos,
                'telepon' => $order->telepon,
                'metode_pengiriman' => $order->metode_pengiriman,
                'shipping_cost' => $shipping_cost,
                'products' => $order->items,
                'subtotal' => $subtotal_price,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Checkout process failed: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }
}