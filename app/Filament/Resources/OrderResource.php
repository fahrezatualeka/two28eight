<?php

namespace App\Filament\Resources;

use App\Models\Order;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\HtmlColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ViewColumn;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([

            \Filament\Forms\Components\Placeholder::make('items_info')
            ->label('Produk yang Dipesan')
            ->content(function ($record) {
                $items = collect($record->items);
                $html = $items->map(function ($item) {
                    $image = json_decode($item->image, true)[0] ?? 'no-image.png';
                    $imageUrl = asset('storage/' . $image);
                    $price = number_format($item->price, 0, ',', '.');
                    $subtotal = number_format($item->price * $item->quantity, 0, ',', '.');
            
                    return <<<HTML
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px; border-bottom: 1px solid #ccc; padding-bottom: 8px;">
                            <img src="$imageUrl" alt="gambar" width="60" height="60" style="border-radius: 6px; object-fit: cover;">
                            <div>
                                <strong>{$item->name}</strong><br>
                                Ukuran {$item->size} | Jumlah {$item->quantity}<br>
                                Harga: Rp$price x {$item->quantity} = Rp$subtotal
                            </div>
                        </div>
                    HTML;
                })->implode('');
            
                $total = number_format($items->sum(fn ($item) => $item->price * $item->quantity), 0, ',', '.');
            
                $html .= <<<HTML
                    <div style="font-weight: bold; font-size: 14px;">
                        Total Pembayaran: Rp$total
                    </div>
                HTML;
            
                return new HtmlString($html);
            })
            ->columnSpanFull(),

            TextInput::make('order_number')->label('Nomor Pesanan')->disabled(),
            TextInput::make('nama')->label('Nama Pembeli')->disabled(),
            Textarea::make('alamat_lengkap')
            ->label('Alamat Lengkap')
            ->disabled()
            ->formatStateUsing(function ($state, $record) {
                return $record->alamat . ', ' . $record->kecamatan . ', ' . $record->kota . ', ' . $record->provinsi . '. ' . $record->kode_pos;
            })
            ->columnSpanFull(),
            TextInput::make('telepon')->disabled(),
            TextInput::make('metode_pengiriman')->label('Metode Pengiriman')->disabled(),
            
            \Filament\Forms\Components\Checkbox::make('tolak_pesanan')
                ->label('Tolak Pesanan')
                ->reactive()
                ->visible(fn ($record) => $record?->status === 'Menunggu Verifikasi')
                ->helperText('jika dicentang, berarti anda menolak pesanan tersebut dan akan dihapus otomatis.'),

            Select::make('status')
            ->label('Status Pesanan')
            ->required()
            ->reactive()
            ->native(false)
            ->preload()
            ->options(function ($get, $livewire) {
                $record = method_exists($livewire, 'getRecord') ? $livewire->getRecord() : null;
                $originalStatus = $record?->status;
        
                return match ($originalStatus) {
                    'Menunggu Pembayaran' => [
                        'Menunggu Pembayaran' => 'Menunggu Pembayaran',
                    ],
                    'Menunggu Verifikasi' => [
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Diproses' => 'Diproses',
                    ],
                    'Diproses' => [
                        'Diproses' => 'Diproses',
                        'Dikirim' => 'Dikirim',
                    ],
                    'Dikirim' => [
                        'Dikirim' => 'Dikirim',
                        'Selesai' => 'Selesai',
                    ],
                    'Selesai' => [
                        'Selesai' => 'Selesai',
                    ],
                    default => [
                        'Menunggu Verifikasi' => 'Menunggu Verifikasi',
                        'Diproses' => 'Diproses',
                        'Dikirim' => 'Dikirim',
                        'Selesai' => 'Selesai',
                    ],
                };
            })
            ->disabled(function ($get, $livewire) {
                $record = method_exists($livewire, 'getRecord') ? $livewire->getRecord() : null;
                return in_array($record?->status, ['Menunggu Pembayaran', 'Selesai']) || $get('tolak_pesanan');
            })
            ->columnSpanFull(),
        
            TextInput::make('no_resi')
            ->label('Nomor Resi')
            ->required(fn ($get) => $get('status') === 'Dikirim')
            ->visible(fn ($get) => $get('status') === 'Dikirim')
            ->disabled(false)
            ->placeholder('Masukkan nomor resi pengiriman')
            ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('Nomor Pesanan')->searchable(),
    
                TextColumn::make('total_price')
                    ->label('Total Pembayaran')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
    
                    ViewColumn::make('bukti_pembayaran')
                    ->label('Bukti Pembayaran')
                    ->view('admin.components.bukti')
                ->extraAttributes(['style' => 'width:170px; white-space:nowrap;']),
                
    
                TextColumn::make('status')->label('Status')->sortable(),
    
                TextColumn::make('no_resi')
                    ->label('Nomor Resi')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->status === 'Dikirim' ? $state : null;
                    })
                    ->placeholder('belum dikirim'),
    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordUrl(null);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
