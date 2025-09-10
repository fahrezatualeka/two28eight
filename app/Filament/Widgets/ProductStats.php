<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProductStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Produk', Product::count()),
            Card::make('Total Stok', Product::sum('stock')),
            Card::make('Produk Terbaru', Product::latest()->first()?->name ?? '-'),
        ];
    }
}