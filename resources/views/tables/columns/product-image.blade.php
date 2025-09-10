@php
    use Illuminate\Support\Facades\Storage;
    $images = $getState();
    if (is_string($images)) {
        $images = json_decode($images, true);
    }
@endphp

@if(is_array($images) && count($images) > 0)
    <div class="product-image-wrapper" 
         style="max-width:150px; display:flex; gap:4px; overflow-x:auto; padding:3px;">
        @foreach($images as $img)
            <div class="thumb" style="flex:0 0 auto; width:40px; height:40px; 
                border:1px solid #ddd; border-radius:4px; overflow:hidden;">
                <img src="{{ Storage::url($img) }}" 
                     alt="gambar" 
                     style="width:100%; height:100%; object-fit:cover; display:block;">
            </div>
        @endforeach
    </div>
@else
    <span class="text-gray-400">Tidak ada gambar</span>
@endif