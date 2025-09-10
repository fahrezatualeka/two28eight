<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    // ✅ Redirect otomatis ke halaman index setelah tambah data
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // ✅ Notifikasi custom
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Produk berhasil disimpan!')
            ->success();
    }
}