<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    // ✅ Redirect otomatis ke halaman index setelah edit data
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // ✅ Notifikasi custom untuk edit
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Produk berhasil diperbarui!')
            ->success();
    }
}