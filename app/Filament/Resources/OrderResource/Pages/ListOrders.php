<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tambahkan tindakan lain di sini jika ada
        ];
    }

    public function mount(): void
    {
        parent::mount();

        // 🟢 Perbaikan: Tangkap pesan sukses dari session
        if (session()->has('order_deleted_success')) {
            Notification::make()
                ->title('Berhasil')
                ->body(session('order_deleted_success'))
                ->success()
                ->send();

            session()->forget('order_deleted_success');
        }
        // ⚠️ Hapus baris yang mungkin ada di sini untuk menangani session 'success'
        // jika itu menyebabkan notifikasi ganda
    }
}