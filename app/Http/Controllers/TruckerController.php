<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class TruckerController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dengan memfilter langsung berdasarkan kolom 'category' dengan nilai 'trucker'
        $query = Product::where('category', 'trucker');

        // Ambil nilai filter dari request
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $size = $request->input('size');
        $sort = $request->input('sort', 'latest'); // Default: terbaru

        // Terapkan filter harga jika ada nilainya
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // ✅ PERBAIKAN: Terapkan filter ukuran jika ada nilainya
        if ($size) {
            // Kita harus mencari nilai 'size' di dalam setiap objek dari array JSON
            $query->whereJsonContains('sizes->[*]->size', $size);
        }
        
        // ✅ Logika untuk mengurutkan produk
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Ambil produk yang sudah difilter dan tambahkan pagination
        $products = $query->paginate(12);

        // Kirim produk ke view
        return view('produk.trucker', [
            'products' => $products,
        ]);
    }
}
