@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">Twoeight - Sweat Pants</h2>

        {{-- ✅ Grid 2 kolom: Filter (kiri) dan Produk (kanan) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            {{-- ✅ Kolom Filter Harga --}}
            <aside class="bg-gray-50 p-5 rounded-lg border h-fit">
                <h3 class="text-lg font-semibold mb-4">Filter Harga</h3>

                {{-- 🔥 Form Filter Harga dan Sort. Action ke rute yang sama --}}
                <form id="filterFormTopi" action="{{ route('produk.sweatpants') }}" method="GET">
                    {{-- Input tersembunyi untuk kategori sweatpants, ini memastikan filter harga tetap di kategori yang benar --}}
                    <input type="hidden" name="category" value="sweatpants">

                    {{-- 🔥 Filter Harga Minimum --}}
                    <div class="mb-4">
                        <label for="min_price" class="block font-medium mb-2">Harga Minimum</label>
                        <input type="number" name="min_price" id="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full border p-2 rounded filter-input">
                    </div>
                
                    {{-- 🔥 Filter Harga Maksimum --}}
                    <div class="mb-4">
                        <label for="max_price" class="block font-medium mb-2">Harga Maksimum</label>
                        <input type="number" name="max_price" id="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full border p-2 rounded filter-input">
                    </div>

                    {{-- ✅ Input tersembunyi untuk menjaga nilai sort saat filter harga diubah --}}
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                </form>
            </aside>

            {{-- ✅ Kolom Produk --}}
            <main class="md:col-span-3">
                
                {{-- ✅ Dropdown untuk Opsi Pengurutan --}}
                <div class="flex justify-end mb-4">
                    <form id="sortFormTopi" action="{{ route('produk.sweatpants') }}" method="GET">
                        {{-- Mempertahankan nilai filter harga saat sort diubah --}}
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        <label for="sort" class="font-medium mr-2">Urutkan:</label>
                        <select name="sort" id="sort" class="border p-2 rounded">
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
                        <p class="col-span-full text-center text-gray-500">Tidak ada produk sweat pants yang ditemukan.</p>
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
        const filterForm = document.getElementById('filterFormTopi');
        const sortForm = document.getElementById('sortFormTopi');
        const filterInputs = document.querySelectorAll('#filterFormTopi .filter-input');
        const sortSelect = document.getElementById('sort');

        // Submit form filter harga setelah user selesai mengetik (debounce)
        filterInputs.forEach(input => {
            let typingTimer;
            input.addEventListener('keyup', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => filterForm.submit(), 800); // submit setelah 0.8s
            });
        });

        // Submit form sort saat opsi diubah
        sortSelect.addEventListener('change', () => {
            sortForm.submit();
        });
    });
</script>
