@foreach($products as $product)
    @php
        $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
        $hasSecondImage = $images && count($images) > 1;
    @endphp
<div class="bg-white border rounded-lg overflow-hidden shadow hover:shadow-lg transition group">
    <a href="{{ route('product.show', $product->id) }}">
        <div class="relative w-full" style="aspect-ratio:3/4; overflow:hidden;">
            @if($images && count($images) > 0)
                <img src="{{ Storage::url($images[0]) }}" 
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            @else
                <img src="{{ asset('no-image.png') }}" 
                     class="absolute inset-0 w-full h-full object-cover">
            @endif
        </div>
        <div class="p-2 text-center">
            <h3 class="font-bold text-lg font-ibold leading-tight">{{ $product->name }}</h3>
            <p class="font-bold text-xs mt-1">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        </div>
    </a>
</div>
@endforeach
@if($products->isEmpty())
    <p class="col-span-4 text-center text-gray-500 text-lg">Produk tidak ditemukan</p>
@endif