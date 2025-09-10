<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'image', 'name', 'price', 'sizes', 'stock', 'description', 'category', 'views'
    ];
    
    protected $casts = [
        'sizes' => 'array',
        'image' => 'array',
    ];
}