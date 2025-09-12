<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function index()
    {
        // ✅ Produk terbaru (1 hari setelah dibuat, hanya tampil jika <= 1 hari)
        $latestProducts = Product::where('created_at', '>=', Carbon::now()->subDay())
                                ->latest()
                                ->take(8)
                                ->get();

        // ✅ Produk paling sering dicari (urut berdasarkan views, fallback jika tidak ada kolom views)
        if (Schema::hasColumn('products', 'views')) {
            $mostViewedProducts = Product::orderBy('views', 'desc')->take(8)->get();
        } else {
            $mostViewedProducts = Product::latest()->take(8)->get(); // fallback jika kolom views belum ada
        }

        // ✅ Semua produk (urut terbaru paling kiri)
        $allProducts = Product::latest()->get();

        return view('home', compact('latestProducts', 'mostViewedProducts', 'allProducts'));
    }

    public function search(Request $request)
    {
        $keyword = $request->get('search');
        $products = Product::where('name', 'like', "%$keyword%")->get();
    
        return view('partials.search-results', compact('products'));
    }

    public function checkout($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->get('quantity', 1);
        $size = $request->get('size', 'S');
    
        return view('checkout', compact('product', 'quantity', 'size'));
    }

    public function allProduct(Request $request)
    {
        $query = Product::query();
    
        // Ambil nilai filter dan sort dari request
        $sort = $request->input('sort', 'latest');
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $size = $request->input('size');

        // 🔥 Filter kategori
        if ($category) {
            $query->where('category', $category);
        }
    
        // 🔥 Filter harga
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
    
        // 🔥 Filter ukuran
        if ($size) {
            $query->whereJsonContains('sizes->[*]->size', $size);
        }
    
        // ✅ TAMBAHAN: Logika pengurutan
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

        $products = $query->paginate(20);
    
        $categories = collect(['tshirt','poloshirt','jersey','zipperhoodie','jortspants','sweatpants','trucker','accessories'])
                        ->map(fn($c) => (object)['id' => $c, 'name' => ucfirst($c)]);
    
        return view('all-product', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product-detail', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
