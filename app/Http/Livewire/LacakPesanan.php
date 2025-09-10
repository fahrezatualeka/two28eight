<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Order;
use Illuminate\Support\Facades\Http; // Import Fonnte API

class LacakPesanan extends Component
{
    use WithFileUploads;

    public $bukti_pembayaran;
    public $orderNumber;
    public $order;
    public $orderId;
    public $rejectionMessage = '';

    public function updatedOrderNumber()
    {
        $this->fetchOrder();
    }

    public function search()
    {
        $this->fetchOrder();
    }

    public function fetchOrder()
    {
        if (!$this->orderNumber) {
            $this->order = null;
            $this->rejectionMessage = '';
            return;
        }

        $order = Order::where('order_number', $this->orderNumber)
                    ->with('items')
                    ->first();
        
        if (session()->has('order_rejected')) {
            $this->rejectionMessage = session('order_rejected');
            session()->forget('order_rejected');
        } else {
            $this->rejectionMessage = '';
        }

        if (!$this->order || optional($this->order)->updated_at != optional($order)->updated_at) {
            $this->order = $order;
            $this->orderId = $order?->id;
        }
    }

    // 🔥🔥 Fungsi baru untuk mengirim notifikasi WhatsApp 🔥🔥
    private function sendWhatsAppNotification($order)
    {
        // ⚠️⚠️ Ganti dengan token Fonnte API Anda
        $fonnteToken = 'NehkJetr9zN3JaXXXqJb';
        // Nomor telepon Anda
        $adminPhoneNumber = '082248302960'; 
        $ongkir = 46000;

        $grandTotal = $order->total_price + $ongkir;
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
                'target' => $adminPhoneNumber,
                'message' => $message,
            ]);
            \Log::info('WhatsApp notification sent from LacakPesanan successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to send WhatsApp notification from LacakPesanan: ' . $e->getMessage());
        }
    }


    public function uploadBukti()
    {
        $this->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
            'orderId' => 'required|exists:orders,id',
        ]);

        $order = Order::find($this->orderId);
        if (!$order) {
            $this->addError('order', 'Pesanan tidak ditemukan.');
            return;
        }

        $filename = uniqid('bukti_') . '.' . $this->bukti_pembayaran->getClientOriginalExtension();
        $this->bukti_pembayaran->storeAs('bukti_pembayaran', $filename, 'public');

        $order->bukti_pembayaran = $filename;
        $order->status = 'Menunggu Verifikasi';
        $order->save();
        
        // 🔥🔥 Panggil fungsi untuk mengirim notifikasi WhatsApp 🔥🔥
        $this->sendWhatsAppNotification($order);

        $this->reset('bukti_pembayaran');

        session()->flash('success', 'Bukti pembayaran berhasil dikirim kan. Status anda saat ini Menunggu verifikasi oleh admin. Selalu pantau status pengiriman anda di menu (Status Pesanan) menggunakan nomor pesanan anda');
        return redirect()->to('/');
    }

    public function render()
    {
        if ($this->orderNumber) {
            $this->fetchOrder();
        }

        return view('livewire.lacak-pesanan')
            ->layout('layouts.app');
    }
}
