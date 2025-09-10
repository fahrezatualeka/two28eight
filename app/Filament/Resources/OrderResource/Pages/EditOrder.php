<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Order;
// Impor yang diperlukan
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $this->record->status = 'dibatalkan';
            
            $this->record->save();

            Notification::make()
                ->title('Berhasil')
                ->body("Pesanan #{$this->record->order_number} telah ditolak. Status pesanan diperbarui.")
                ->success()
                ->send();
            
            $this->halt();
        }
    }

    protected function getSavedNotification(): ?Notification
    {
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
        $fonnteToken = 'NehkJetr9zN3JaXXXqJb';

        // Nomor tujuan diambil dari kolom 'telepon' di data pesanan
        $customerPhoneNumber = $order->telepon;

        // 🔥🔥 KODE UTAMA: Memformat nomor telepon agar dimulai dengan '62' 🔥🔥
        // Ini akan mengubah '08123...' menjadi '628123...'
        $formattedCustomerNumber = preg_replace('/^0/', '62', $customerPhoneNumber);
        
        $message = "Halo " . $order->nama . "! \n\n" .
                   "Pesanan Anda dengan nomor *#" . $order->order_number . "* telah berhasil dikirim! 🎉\n\n" .
                   "Nomor resi Anda adalah: *" . $order->no_resi . "*\n\n" .
                   "Anda bisa melacak pesanan Anda menggunakan nomor tesi tersebut melalui layanan ekspedisi yang bersangkutan. \n" .
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
}
