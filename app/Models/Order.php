<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'nama', 'alamat', 'kecamatan', 'kota', 'provinsi',
        'kode_pos', 'telepon', 'metode_pengiriman', 'total_price', 'status', 
        'no_resi', 'bukti_pembayaran', 'receipt_image' // Pastikan ini ada
    ];

    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'order_id');
    }
    
    

    public function user()
{
    return $this->belongsTo(User::class);
}

}