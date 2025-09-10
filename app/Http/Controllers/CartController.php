<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    /**
     * Tambahkan produk ke keranjang. Jika produk dengan ukuran yang sama sudah ada,
     * tambahkan kuantitasnya dan pindahkan ke atas.
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $size = $request->size ?? 'default'; // Gunakan 'default' jika tidak ada ukuran
        $quantity = (int) $request->quantity;
        
        $cart = session()->get('cart', []);
        $foundIndex = -1;

        // Cari item yang sudah ada di keranjang dengan ID produk dan ukuran yang sama
        foreach ($cart as $index => $item) {
            if ($item['id'] === $product->id && $item['size'] === $size) {
                $foundIndex = $index;
                break;
            }
        }

        if ($foundIndex !== -1) {
            // Jika item sudah ada, update kuantitasnya
            $cart[$foundIndex]['quantity'] += $quantity;
            // Pindahkan item yang diupdate ke posisi pertama (paling atas)
            $updatedItem = $cart[$foundIndex];
            Arr::pull($cart, $foundIndex);
            array_unshift($cart, $updatedItem);
        } else {
            // Jika item belum ada, buat item baru dan tambahkan di posisi pertama
            $cartItem = [
                'id'       => $product->id,
                'name'     => $product->name,
                'size'     => $size,
                'quantity' => $quantity,
                'price'    => (int) $request->price,
                'image'    => is_array($product->image) ? $product->image : json_decode($product->image, true),
                'stock'    => $product->stock, // Tambahkan stok tunggal
            ];

            // Jika produk memiliki ukuran, ambil stok dari ukuran yang dipilih
            if (!empty($product->sizes)) {
                $sizesData = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true);
                foreach ($sizesData as $s) {
                    if ($s['size'] === $size) {
                        $cartItem['stock'] = $s['stock'];
                        break;
                    }
                }
            }

            array_unshift($cart, $cartItem);
        }

        session()->put('cart', $cart);

        // Hitung total kuantitas seluruh produk di keranjang
        $totalQty = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'    => true,
            'last_added' => reset($cart), // Mengembalikan item yang paling baru ditambahkan/diupdate
            'cartCount'  => $totalQty,
        ]);
    }

    /**
     * Hapus satu item dari keranjang berdasarkan index.
     */
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $index = (int)$request->index; // Menerima index dari request

        if (isset($cart[$index])) {
            Arr::pull($cart, $index);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    /**
     * Perbarui kuantitas item di keranjang.
     */
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $index = (int)$request->index;
    
        if (isset($cart[$index])) {
            $stock = (int) $cart[$index]['stock'];
    
            if ($request->action === 'increase' && $cart[$index]['quantity'] < $stock) {
                $cart[$index]['quantity']++;
            } elseif ($request->action === 'decrease' && $cart[$index]['quantity'] > 1) {
                $cart[$index]['quantity']--;
            }

            // Pindahkan item yang diupdate ke posisi pertama
            $updatedItem = $cart[$index];
            Arr::pull($cart, $index);
            array_unshift($cart, $updatedItem);

            session()->put('cart', $cart);
    
            // Hitung ulang total kuantitas seluruh produk di keranjang
            $totalQty = array_sum(array_column($cart, 'quantity'));
    
            return response()->json([
                'success'       => true,
                'new_quantity'  => $updatedItem['quantity'],
                'product_total' => $updatedItem['price'] * $updatedItem['quantity'],
                'stock'         => $stock,
                'cart_total_qty' => $totalQty,
            ]);
        }
    
        return response()->json(['success' => false], 400);
    }

    /**
     * Hapus banyak item dari keranjang.
     */
    public function bulkDelete(Request $request)
    {
        $cart = session()->get('cart', []);
    
        if ($request->filled('selected')) {
            $indexes = explode(',', $request->selected);
            $newCart = [];
            foreach ($cart as $index => $item) {
                if (!in_array($index, $indexes)) {
                    $newCart[] = $item;
                }
            }
            session()->put('cart', $newCart);
            return redirect()->route('cart.index')->with('success', 'Produk yang dipilih berhasil dihapus!');
        }
    
        return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang dipilih.');
    }
}
