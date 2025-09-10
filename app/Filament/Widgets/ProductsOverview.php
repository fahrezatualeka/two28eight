<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProductsOverview extends BaseWidget
{
    protected static bool $isDiscovered = false; // ✅ Matikan auto load
    protected static ?string $heading = 'Daftar Produk';

    public function getTableQuery(): Builder|Relation|null
    {
        return Product::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            ViewColumn::make('image')
                ->label('Gambar')
                ->view('tables.columns.product-image')
                ->extraAttributes(['style' => 'width:170px; white-space:nowrap;']),

            TextColumn::make('name')->label('Nama Produk')->searchable(),
            TextColumn::make('price')
                ->label('Harga')
                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            TextColumn::make('stock')->label('Stok'),
        ];
    }
}