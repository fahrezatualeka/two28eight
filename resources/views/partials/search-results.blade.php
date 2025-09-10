<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-6">
    @foreach($products as $product)
        @php
            $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
            $hasSecondImage = $images && count($images) > 1;
        @endphp

        <div class="bg-white rounded-lg overflow-hidden transition {{ $hasSecondImage ? 'group' : '' }}">
            <a href="{{ route('product.show', $product->id) }}">
                <div class="relative w-full" style="aspect-ratio:1/1; overflow:hidden;">
                    @if($images && count($images) > 0)
                        <img src="{{ Storage::url($images[0]) }}"
                             alt="{{ $product->name }}"
                             class="absolute inset-0 w-full h-full object-cover {{ $hasSecondImage ? 'transition-opacity duration-500 opacity-100 group-hover:opacity-0' : '' }}">
                        @if($hasSecondImage)
                            <img src="{{ Storage::url($images[1]) }}"
                                 alt="{{ $product->name }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0 group-hover:opacity-100">
                        @endif
                    @else
                        <img src="{{ asset('no-image.png') }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                </div>

                <div class="p-4 text-justify">
                    <h3 class="font-bold leading-tight">{{ $product->name }}</h3>
                    <p class="text-xl">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </a>
        </div>
    @endforeach
</div>