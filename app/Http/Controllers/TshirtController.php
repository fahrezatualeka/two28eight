<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class TshirtController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dengan memfilter langsung berdasarkan kolom 'category' dengan nilai 'tshirt'
        $query = Product::where('category', 'tshirt');

        // Ambil nilai filter harga dari request
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        // Terapkan filter harga jika ada nilainya
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // ✅ Logika baru untuk mengurutkan produk
        $sort = $request->input('sort', 'latest'); // Default: terbaru

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
        return view('produk.tshirt', [
            'products' => $products,
        ]);
    }
}
