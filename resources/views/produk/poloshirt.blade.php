@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">Twoeight - Polo Shirt</h2>

        {{-- ✅ Grid 2 kolom: Filter (kiri) dan Produk (kanan) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            {{-- ✅ Kolom Filter --}}
            <aside class="bg-gray-50 p-5 rounded-lg border h-fit">
                <h3 class="text-lg font-semibold mb-4">Filter Produk</h3>

                {{-- 🔥 Form Filter --}}
                <form id="filterFormPoloshirt" action="{{ route('produk.poloshirt') }}" method="GET">
                    {{-- Input tersembunyi untuk kategori poloshirt --}}
                    <input type="hidden" name="category" value="poloshirt">

                    {{-- 🔥 Filter Harga --}}
                    <div class="mb-4">
                        <label for="min_price" class="block font-medium mb-2">Harga Minimum</label>
                        <input type="number" name="min_price" id="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full border p-2 rounded filter-input">
                    </div>
                    <div class="mb-4">
                        <label for="max_price" class="block font-medium mb-2">Harga Maksimum</label>
                        <input type="number" name="max_price" id="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full border p-2 rounded filter-input">
                    </div>
                
                    {{-- ✅ Filter Ukuran --}}
                    <div class="mb-4">
                        <label for="size" class="block font-medium mb-2">Ukuran</label>
                        <select name="size" id="size" class="w-full border rounded p-2 filter-input">
                            <option value="">- Pilih -</option>
                            <option value="S" {{ request('size') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ request('size') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ request('size') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ request('size') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL" {{ request('size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                        </select>
                    </div>

                    {{-- ✅ Input tersembunyi untuk menjaga nilai sort --}}
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                </form>
            </aside>

            {{-- ✅ Kolom Produk --}}
            <main class="md:col-span-3">
                
                {{-- ✅ Dropdown untuk Opsi Pengurutan --}}
                <div class="flex justify-end mb-4">
                    <form id="sortFormPoloshirt" action="{{ route('produk.poloshirt') }}" method="GET">
                        {{-- Mempertahankan nilai filter saat sort diubah --}}
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        @if(request('size'))
                            <input type="hidden" name="size" value="{{ request('size') }}">
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
                        <p class="col-span-full text-center text-gray-500">Tidak ada produk polo-shirt yang ditemukan.</p>
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
        const filterForm = document.getElementById('filterFormPoloshirt');
        const sortForm = document.getElementById('sortFormPoloshirt');
        const filterInputs = document.querySelectorAll('#filterFormPoloshirt .filter-input');
        const sortSelect = document.getElementById('sort');
    
        // Submit form filter harga dan ukuran setelah user selesai mengetik atau mengubah pilihan
        filterInputs.forEach(input => {
            let typingTimer;
            input.addEventListener('change', () => {
                clearTimeout(typingTimer);
                filterForm.submit();
            });

            if (input.type === 'number') {
                input.addEventListener('keyup', () => {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => filterForm.submit(), 800);
                });
            }
        });
    
        // Submit form sort saat opsi diubah
        sortSelect.addEventListener('change', () => {
            sortForm.submit();
        });
    });
</script>
