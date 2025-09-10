<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Product;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        // ✅ Urutkan produk terbaru paling atas
        public static function getEloquentQuery(): Builder
        {
            return parent::getEloquentQuery()->orderBy('created_at', 'desc');
        }

        public static function form(Form $form): Form
        {
            return $form->schema([
        
                FileUpload::make('image')
                    ->label('Gambar Produk')
                    ->directory('products')
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imagePreviewHeight('100')
                    ->reorderable()
                    ->preserveFilenames()
                    ->columnSpanFull(),
        
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required(),
        
                Select::make('category')
                    ->label('Kategori Produk')
                    ->options([
                        'topi'      => 'Topi',
                        'kaos'      => 'Kaos',
                        'kemeja'    => 'Kemeja',
                        'jaket'     => 'Jaket',
                        'hoodie'    => 'Hoodie',
                        'tas'       => 'Tas',
                        'celana'    => 'Celana',
                        'aksesoris' => 'Aksesoris',
                    ])
                    ->required()
                    ->reactive(),
        
                TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->extraAttributes([
                        'oninput' => "
                            this.value = this.value.replace(/[^0-9]/g, '');
                            this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        "
                    ])
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 0, ',', '.') : null)
                    ->dehydrateStateUsing(fn($state) => str_replace('.', '', $state)),
        
                // 🔥 Repeater hanya muncul untuk kategori yang memiliki ukuran
// 🔥 Repeater hanya muncul jika kategori sudah dipilih dan bukan kategori tertentu
Repeater::make('sizes')
    ->label('Daftar Ukuran & Stok')
    ->schema([
        Select::make('size')
            ->label('Ukuran')
            ->options(function (Get $get, $state) {
                $allSizes = ['S','M','L','XL','XXL'];
                $existing = collect($get('../../sizes') ?? [])
                    ->pluck('size')
                    ->filter()
                    ->toArray();
                if ($state && in_array($state, $existing)) {
                    $existing = array_diff($existing, [$state]);
                }
                return collect($allSizes)
                    ->reject(fn($size) => in_array($size, $existing))
                    ->mapWithKeys(fn($size) => [$size => $size])
                    ->toArray();
            })
            ->required()
            ->reactive(),
        TextInput::make('stock')
            ->label('Jumlah')
            ->numeric()
            ->required(),
    ])
    ->columns(2)
    ->minItems(1)
    ->createItemButtonLabel('Tambah Ukuran')
    ->visible(fn(Get $get) => $get('category') && !in_array($get('category'), ['topi', 'tas', 'aksesoris']))
    ->disableItemCreation(fn(Get $get) => count(
        collect($get('sizes') ?? [])->pluck('size')->filter()->toArray()
    ) >= 5),

// ✅ Kolom stok tunggal hanya muncul jika kategori sudah dipilih dan termasuk kategori tertentu
TextInput::make('stock')
    ->label('Stok')
    ->numeric()
    ->nullable()
    ->visible(fn(Get $get) => $get('category') && in_array($get('category'), ['topi', 'tas', 'aksesoris'])),
        
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
        }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\ViewColumn::make('image')
                ->label('Gambar')
                ->view('tables.columns.product-image')
                ->extraAttributes(['style' => 'width:170px; white-space:nowrap;']),

            Tables\Columns\TextColumn::make('name')
                ->label('Nama Produk')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('category')
                ->label('Kategori')
                ->sortable(),

            Tables\Columns\TextColumn::make('price')
                ->label('Harga')
                ->formatStateUsing(fn($state) => 'Rp' . number_format($state, 0, ',', '.')),

            // ✅ Kolom stok tunggal
            Tables\Columns\TextColumn::make('stock')
            ->label('Stok')
            ->formatStateUsing(fn($state, $record) =>
            in_array($record->category, ['topi', 'tas', 'aksesoris'])
                ? ($state === null || $state === '' ? '' : $state) // ✅ tampilkan kosong jika null
                : '-'
        ),

            Tables\Columns\TextColumn::make('sizes')
                ->label('Ukuran & Stok')
                ->formatStateUsing(function ($record) {
                    $sizes = is_string($record->sizes) ? json_decode($record->sizes, true) : $record->sizes;
                    if (!is_array($sizes)) return '-';
                    return collect($sizes)->map(fn($s) => "{$s['size']}: {$s['stock']}")->implode(', ');
                }),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime(),
        ])
            ->filters([
                // 🔥 Filter kategori
                SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'topi'        => 'Topi',
                        'kaos'        => 'Kaos',
                        'kemeja'      => 'Kemeja',
                        'jaket'       => 'Jaket',
                        'hoodie'      => 'Hoodie',
                        'tas'         => 'Tas',
                        'celana'      => 'Celana',
                        'aksesoris'   => 'Aksesoris',
                    ]),
            
                // 🔥 Filter ukuran berdasarkan JSON sizes
                SelectFilter::make('sizes')
                ->label('Filter Ukuran')
                ->options([
                    'S'   => 'S',
                    'M'   => 'M',
                    'L'   => 'L',
                    'XL'  => 'XL',
                    'XXL' => 'XXL',
                ])
                ->query(function ($query, array $data) {
                    if (!empty($data['value'])) {
                        $query->where('sizes', 'like', '%"size":"'.$data['value'].'"%');
                    }
                    return $query;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                    // ->after(fn() => \Filament\Notifications\Notification::make()
                    //     ->title('Produk berhasil dihapus!')
                    //     ->success()
                    //     ->send()),
            ]);
            // ->recordUrl(null);
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}