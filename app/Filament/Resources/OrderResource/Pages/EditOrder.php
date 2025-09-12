<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Session;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $data = $this->form->getState();

        if (isset($data['tolak_pesanan']) && $data['tolak_pesanan']) {
            try {
                $orderNumber = $this->record->order_number;
                
                // 🟢 Perbaikan: Ubah status pesanan menjadi 'Ditolak'
                $this->record->status = 'Ditolak';
                $this->record->save();

                // 🟢 Perbaikan: Kirim notifikasi sukses hanya ke Filament
                Notification::make()
                    ->title('Berhasil')
                    ->body("Pesanan #{$orderNumber} telah berhasil ditolak.")
                    ->success()
                    ->send();

                // Hentikan proses simpan dan alihkan halaman
                $this->redirect(static::getResource()::getUrl('index'));
                $this->halt();

            } catch (\Exception $e) {
                Notification::make()
                    ->title('Gagal')
                    ->body("Gagal menolak pesanan. " . $e->getMessage())
                    ->danger()
                    ->send();
                $this->halt();
            }
        }
    }

    protected function getSavedNotification(): ?Notification
    {
        // 🟢 Perbaikan: Hapus notifikasi ini, karena sudah ditangani oleh beforeSave.
        // Jika status berubah, notifikasi ini akan muncul.
        // Jika "tolak pesanan" dicentang, beforeSave sudah mengirim notifikasi dan menghentikan proses.
        return Notification::make()
            ->title('Produk berhasil diperbarui!')
            ->success();
    }


    // Hook yang dijalankan setelah data disimpan
    protected function afterSave(): void
    {
        $order = $this->getRecord();

        // Cek apakah status berubah menjadi 'Dikirim' dan ada nomor resi
        if ($order->status === 'Dikirim' && !empty($order->no_resi)) {
            $this->sendWhatsAppNotification($order);
        }
    }

    // Fungsi untuk mengirim notifikasi WhatsApp
    private function sendWhatsAppNotification($order)
    {
        // ⚠️⚠️ Ganti dengan token Fonnte API Anda
        $fonnteToken = '26vLCjPj3qScEeZzjzHw';

        // Nomor tujuan diambil dari kolom 'telepon' di data pesanan
        $customerPhoneNumber = $order->telepon;

        // 🔥🔥 KODE UTAMA: Memformat nomor telepon agar dimulai dengan '62' 🔥🔥
        // Ini akan mengubah '08123...' menjadi '628123...'
        $formattedCustomerNumber = preg_replace('/^0/', '62', $customerPhoneNumber);
        
        $message = "Halo " . $order->nama . " 👋 \n\n" .
                   "Pesanan Produk Anda dari Twoeight dengan nomor *#" . $order->order_number . "* telah berhasil dikirim! 🎉\n\n" .
                   "Nomor resi Anda adalah: *" . $order->no_resi . "*\n\n" .
                   "Anda bisa melacak pesanan Anda menggunakan nomor resi tersebut dengan mengakses https://cekresi.com/tracking/cek-resi-jnt-express.php \n" .
                   "Terima kasih telah berbelanja! 🙏";

        try {
            Http::withHeaders([
                'Authorization' => $fonnteToken,
            ])->post('https://api.fonnte.com/send', [
                // Gunakan nomor yang sudah diformat
                'target' => $formattedCustomerNumber,
                'message' => $message,
            ]);
            Log::info('WhatsApp notification sent from Filament successfully for order: ' . $order->order_number);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification from Filament: ' . $e->getMessage());
        }
    }

    // ⭐⭐⭐ FUNGSI BARU UNTUK MENGHAPUS PRODUK DARI PESANAN ⭐⭐⭐
    public function deleteOrderItem(int $orderItemId)
    {
        // Jalankan operasi dalam transaksi database untuk keamanan
        DB::transaction(function () use ($orderItemId) {
            $orderItem = OrderItem::find($orderItemId);

            if (!$orderItem) {
                // Jika produk tidak ditemukan, kirim notifikasi error
                Notification::make()
                    ->title('Gagal')
                    ->body('Produk pesanan tidak ditemukan.')
                    ->danger()
                    ->send();
                return;
            }

            $order = $orderItem->order;
            
            // Hapus produk dari pesanan
            $orderItem->delete();
            
            // Perbarui total harga pesanan setelah produk dihapus
            // Pastikan Anda memiliki relasi 'items' di model Order
            $order->total_price = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $order->save();

            // Kirim notifikasi sukses
            Notification::make()
                ->title('Berhasil')
                ->body('Produk pesanan berhasil dihapus.')
                ->success()
                ->send();
        });

        // Alihkan kembali ke halaman index (tabel pesanan)
        return redirect()->to(static::getResource()::getUrl('index'));
    }
}
