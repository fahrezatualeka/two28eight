@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">Semua Produk</h2>

        {{-- ✅ Grid 2 kolom: Filter (kiri) dan Produk (kanan) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            {{-- ✅ Kolom Filter --}}
            <aside class="bg-gray-50 p-5 rounded-lg border h-fit">
                <h3 class="text-lg font-semibold mb-4">Filter Produk</h3>

                {{-- 🔥 Form Filter --}}
                <form id="filterForm" action="{{ route('product') }}" method="GET">
                    {{-- Input tersembunyi untuk menjaga nilai sort --}}
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- 🔥 Filter Kategori --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-2">Kategori</label>
                        {{-- ✅ onchange="this.form.submit()" untuk submit otomatis --}}
                        <select name="category" class="w-full border rounded p-2 filter-input" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                
                    {{-- 🔥 Filter Harga --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-2">Harga</label>
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full border p-2 mb-2 rounded filter-input-price">
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full border p-2 rounded filter-input-price">
                    </div>
                </form>
            </aside>

            {{-- ✅ Kolom Produk --}}
            <main class="md:col-span-3">
                 {{-- ✅ Dropdown untuk Opsi Pengurutan --}}
                <div class="flex justify-end mb-4">
                    <form id="sortForm" action="{{ route('product') }}" method="GET">
                        {{-- Mempertahankan nilai filter saat sort diubah --}}
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        <label for="sort" class="font-medium mr-2">Urutkan:</label>
                        {{-- ✅ onchange="this.form.submit()" untuk submit otomatis --}}
                        <select name="sort" id="sort" class="border p-2 rounded" onchange="this.form.submit()">
                            <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </form>
                </div>
                <div class="border-t"></div>
                <br>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @empty
                        <p class="col-span-full text-center text-gray-500">Produk tidak ditemukan.</p>
                    @endforelse
                </div>

                {{-- ✅ Pagination --}}
                <div class="mt-6">{{ $products->withQueryString()->links() }}</div>
            </main>

        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('filterForm');
        const filterPriceInputs = document.querySelectorAll('.filter-input-price');
    
        // Khusus untuk input harga, submit ketika user selesai mengetik (debounce)
        filterPriceInputs.forEach(input => {
            let typingTimer;
            input.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => filterForm.submit(), 800); // submit setelah 0.8s
            });
        });
    });
</script>
