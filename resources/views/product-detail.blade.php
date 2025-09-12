{{-- File: resources/views/product-detail.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">Detail Produk</h2>

        @php
            $sizesData = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true);
            $hasSizes = is_array($sizesData) && count($sizesData) > 0;
            $initialStock = $hasSizes ? ($sizesData[0]['stock'] ?? 0) : ($product->stock ?? 0);
        @endphp

        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="flex flex-col-reverse md:flex-row items-center md:items-start gap-4">
                @php
                    $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
                @endphp
            
                @if($images && count($images) > 1)
                    <div class="hidden md:flex flex-col gap-2">
                        @foreach($images as $img)
                            <img src="{{ Storage::url($img) }}" 
                                 onclick="document.getElementById('mainImage').src=this.src" 
                                 class="w-20 object-cover border rounded cursor-pointer hover:opacity-75 transition aspect-square">
                        @endforeach
                    </div>
                    
                    <div class="flex md:hidden flex-wrap justify-center gap-2">
                        @foreach($images as $img)
                            <img src="{{ Storage::url($img) }}" 
                                 onclick="document.getElementById('mainImage').src=this.src" 
                                 class="w-1/5 object-cover border rounded cursor-pointer hover:opacity-75 transition aspect-square">
                        @endforeach
                    </div>
                @endif
            
                <div class="w-full relative mx-auto border rounded-lg overflow-hidden aspect-square">
                    <img id="mainImage"
                         src="{{ $images ? Storage::url($images[0]) : asset('no-image.png') }}"
                         alt="{{ $product->name }}"
                         class="absolute inset-0 w-full h-full object-cover">
                </div>
            </div>

            <div class="space-y-4">
                <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
                <div class="border-t pt-3 text-gray-700">
                    <p>{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>

            <div class="border rounded-lg p-5 bg-gray-50 shadow-md w-full max-w-md self-start">
                <p class="text-3xl font-bold mb-3">Rp{{ number_format($product->price, 0, ',', '.') }}</p>

                <p class="mb-4">Stok: <span id="currentStock">{{ $initialStock }}</span></p>

                @if($hasSizes)
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Ukuran</label>
                    <div id="sizeOptions" class="flex flex-wrap gap-3 mb-4">
                        @foreach($sizesData as $index => $s)
                            <label class="cursor-pointer">
                                <input type="radio" name="size_radio" value="{{ $s['size'] }}" 
                                       class="hidden size-radio" {{ $index == 0 ? 'checked' : '' }}
                                       data-stock="{{ $s['stock'] }}">
                                       
                                <div class="size-box px-5 py-3 border rounded-lg font-medium transition 
                                    {{ $index == 0 ? 'bg-black text-white' : 'text-black' }}">
                                    {{ $s['size'] }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif

                <input type="hidden" name="size" id="selectedSize" value="{{ $hasSizes ? ($sizesData[0]['size'] ?? 'S') : 'default' }}">

                <label class="block text-gray-700 font-semibold mb-2">Jumlah</label>
                <div class="flex items-center mb-4 w-full border rounded-lg overflow-hidden">
                    <button type="button" id="btnMinus" class="bg-gray-100 w-1/3 py-3 text-xl font-bold">−</button>
                    <input id="qtyInput" type="text" value="1" readonly 
                        class="w-1/3 text-center font-bold text-lg border-x">
                    <button type="button" id="btnPlus" class="bg-gray-100 w-1/3 py-3 text-xl font-bold">+</button>
                </div>

                <div class="space-y-3">
                    <form id="addToCartForm" action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="quantity" id="formSelectedQty" value="1">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="size" id="formSelectedSize" value="{{ $hasSizes ? ($sizesData[0]['size'] ?? 'S') : 'default' }}">
                        <button type="submit" id="addToCartBtn" class="w-full bg-transparent border text-black py-3 rounded-lg font-semibold transition">
                            + Keranjang
                        </button>
                    </form>

                    <form id="buyNowForm" action="{{ route('checkout', $product->id) }}" method="GET">
                        <input type="hidden" name="size" id="buySize" value="{{ $hasSizes ? ($sizesData[0]['size'] ?? 'S') : 'default' }}">
                        <input type="hidden" name="quantity" id="buyQty" value="1">
                        <button type="submit" id="buyNowBtn" class="w-full bg-black text-white py-3 rounded-lg font-semibold">
                            Beli Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Produk Terkait --}}
    <div class="container mx-auto px-6 py-10 bg-white">
        <div class="border-t"></div>
        <h2 class="text-2xl font-bold mt-10 mb-4">anda mungkin juga menyukai</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach(App\Models\Product::where('id','!=',$product->id)->latest()->take(4)->get() as $related)
                @php
                    $relatedImages = is_array($related->image) ? $related->image : json_decode($related->image, true);
                    $hasSecondImage = $relatedImages && count($relatedImages) > 1;
                @endphp
        
                <div class="bg-white hover:border rounded-lg overflow-hidden transition {{ $hasSecondImage ? 'group' : '' }}">
                    <a href="{{ route('product.show', $related->id) }}">
                        <div class="relative w-full" style="aspect-ratio:1/1; overflow:hidden;">
                            @if($relatedImages && count($relatedImages) > 0)
                                <img src="{{ Storage::url($relatedImages[0]) }}" 
                                     class="absolute inset-0 w-full h-full object-cover {{ $hasSecondImage ? 'transition-opacity duration-500 opacity-100 group-hover:opacity-0' : '' }}">
                                @if($hasSecondImage)
                                    <img src="{{ Storage::url($relatedImages[1]) }}" 
                                         class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100">
                                @endif
                            @else
                                <img src="{{ asset('no-image.png') }}" 
                                     class="absolute inset-0 w-full h-full object-cover">
                            @endif
                        </div>
        
                        <div class="p-4 text-left">
                            <h3 class="text-lg"><strong>Twoeight - {{ $related->name }}</strong></h3>
                            <p class="text-gray-700">{{ $related->category }}</p>
                            <h1><strong>Rp{{ number_format($related->price, 0, ',', '.') }}</strong></h1>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const qtyInput = document.getElementById('qtyInput');
        const btnMinus = document.getElementById('btnMinus');
        const btnPlus = document.getElementById('btnPlus');
        const formSelectedQty = document.getElementById('formSelectedQty');
        const buyQty = document.getElementById('buyQty');
        const formSelectedSize = document.getElementById('formSelectedSize');
        const buySize = document.getElementById('buySize');
        const currentStockEl = document.getElementById('currentStock');
        
        const addToCartBtn = document.getElementById('addToCartBtn');
        const buyNowBtn = document.getElementById('buyNowBtn');

        let maxStockValue;

        // Fungsi untuk mengelola status stok dan tombol
        function checkStockAndButtons() {
            const currentStock = parseInt(currentStockEl.innerText);
            
            if (currentStock <= 0) {
                // Nonaktifkan tombol dan ubah gaya
                addToCartBtn.disabled = true;
                buyNowBtn.disabled = true;
                btnPlus.disabled = true;
                btnMinus.disabled = true;

                addToCartBtn.classList.add('bg-gray-400', 'text-white', 'cursor-not-allowed');
                addToCartBtn.classList.remove('bg-transparent', 'text-black', 'border');

                buyNowBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                buyNowBtn.classList.remove('bg-black', 'hover:bg-gray-800');

                btnPlus.classList.add('opacity-50', 'cursor-not-allowed');
                btnMinus.classList.add('opacity-50', 'cursor-not-allowed');

                qtyInput.value = 0;
            } else {
                // Aktifkan kembali tombol dan atur gaya default
                addToCartBtn.disabled = false;
                buyNowBtn.disabled = false;

                addToCartBtn.classList.remove('bg-gray-400', 'text-white', 'cursor-not-allowed');
                addToCartBtn.classList.add('bg-transparent', 'text-black', 'border');

                buyNowBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                buyNowBtn.classList.add('bg-black', 'hover:bg-gray-800');
                
                // Pastikan tombol plus/minus juga diperbarui
                updateQty(0);
            }
        }

        // Fungsi untuk memperbarui jumlah produk
        function updateQty(change) {
            let qty = parseInt(qtyInput.value) + change;
            if (qty < 1) qty = 1;
            if (qty > maxStockValue) qty = maxStockValue;

            qtyInput.value = qty;
            formSelectedQty.value = qty;
            buyQty.value = qty;

            btnMinus.disabled = qty <= 1;
            btnPlus.disabled = qty >= maxStockValue;
            
            btnMinus.classList.toggle('opacity-50', btnMinus.disabled);
            btnMinus.classList.toggle('cursor-not-allowed', btnMinus.disabled);
            btnPlus.classList.toggle('opacity-50', btnPlus.disabled);
            btnPlus.classList.toggle('cursor-not-allowed', btnPlus.disabled);
        }

        btnMinus.addEventListener('click', () => updateQty(-1));
        btnPlus.addEventListener('click', () => updateQty(1));

        // Event listener saat pilihan ukuran berubah
        document.querySelectorAll('.size-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const selectedStock = parseInt(this.dataset.stock);
                
                maxStockValue = selectedStock;
                currentStockEl.innerText = selectedStock;
                
                qtyInput.value = 1;
                formSelectedQty.value = 1;
                buyQty.value = 1;
                
                checkStockAndButtons(); // Panggil fungsi utama
                updateQty(0); // Update tombol plus/minus

                formSelectedSize.value = this.value;
                buySize.value = this.value;

                document.querySelectorAll('.size-box').forEach(box => {
                    box.classList.remove('bg-black', 'text-white');
                    box.classList.add('text-black');
                });

                // ✅ Perbaikan: Selalu berikan latar belakang hitam saat dipilih
                this.nextElementSibling.classList.add('bg-black', 'text-white');
                this.nextElementSibling.classList.remove('text-black');
            });
        });

        // Event listener untuk tombol "Tambah Keranjang"
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Perbarui nilai form sebelum mengirim
            document.getElementById('formSelectedQty').value = qtyInput.value;
            document.getElementById('formSelectedSize').value = document.querySelector('.size-radio:checked')?.value || 'default';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;
                
                const p = data.last_added;
                const totalPrice = (p.price * p.quantity).toLocaleString('id-ID');
                const miniCart = document.getElementById('miniCart');

                if (miniCart) {
                    let imgSrc = '/no-image.png';
                    if (p.image && Array.isArray(p.image) && p.image.length > 0) {
                        imgSrc = '/storage/' + p.image[0];
                    }
                    document.getElementById('miniCartImage').src = imgSrc;
                    document.getElementById('miniCartName').innerText = p.name;
                    document.getElementById('miniCartSize').innerText = "Ukuran " + (p.size || '-');
                    document.getElementById('miniCartQty').innerText = "Jumlah " + p.quantity;
                    document.getElementById('miniCartPrice').innerText = "Rp" + totalPrice;

                    miniCart.classList.remove('hidden', 'opacity-0', 'scale-95');
                    miniCart.classList.add('opacity-100', 'scale-100');

                    setTimeout(() => {
                        miniCart.classList.remove('opacity-100', 'scale-100');
                        miniCart.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => miniCart.classList.add('hidden'), 300);
                    }, 3000);
                }

                // Update jumlah total di ikon keranjang
                const cartCountEl = document.getElementById('cartCount');
                if (cartCountEl) {
                    cartCountEl.innerText = data.cartCount;
                    if (data.cartCount > 0) {
                        cartCountEl.classList.remove('hidden');
                    } else {
                        cartCountEl.classList.add('hidden');
                    }
                }
            })
            .catch(() => alert('❌ Terjadi kesalahan koneksi.'));
        });
        
        buyNowForm.addEventListener('submit', function () {
            buySize.value = formSelectedSize.value;
            buyQty.value  = qtyInput.value;
        });

        // Set initial stock and check buttons on page load
        if (document.querySelectorAll('.size-radio').length > 0) {
            maxStockValue = parseInt(document.querySelector('.size-radio:checked').dataset.stock);
        } else {
            maxStockValue = parseInt(currentStockEl.innerText);
        }

        checkStockAndButtons();
    });
</script>
