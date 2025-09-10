@php
    use Illuminate\Support\Facades\Storage;
    $filename = $getState();
    $imageUrl = $filename ? Storage::url('bukti_pembayaran/' . $filename) : null;
@endphp

@if ($filename && Storage::exists('bukti_pembayaran/' . $filename))
    {{-- ✅ Thumbnail --}}
    <button onclick="openModal('{{ $imageUrl }}')" class="focus:outline-none">
        <img 
            src="{{ $imageUrl }}" 
            alt="Bukti" 
            class="h-16 rounded-md shadow hover:opacity-80 transition"
        >
    </button>

    {{-- ✅ Modal --}}
    <div 
        id="popup-modal" 
        class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50"
        onclick="closeModal()"
    >
        <img 
            id="popup-image" 
            src="" 
            class="max-w-xl max-h-[90vh] rounded-lg shadow-xl border-4 border-white"
        >
    </div>

    {{-- ✅ Script --}}
    <script>
        function openModal(imageUrl) {
            const modal = document.getElementById('popup-modal');
            const img = document.getElementById('popup-image');
            img.src = imageUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('popup-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@else
    <span class="text-gray-400">belum dibayar</span>
@endif