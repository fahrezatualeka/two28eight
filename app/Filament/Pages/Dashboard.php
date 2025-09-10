<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    // ✅ Hanya load ProductStats secara manual
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ProductStats::class,
        ];
    }
}