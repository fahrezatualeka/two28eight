@extends('layouts.guest')

@section('content')
<div class="bg-white min-h-screen px-6 py-10 text-left">
    {{-- <div class="container mx-auto pt-32 pb-10 bg-white"> --}}
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            
            {{-- KIRI: Produk Dipesan --}}
            @php 
                $subtotal = 0;
            @endphp
            <div class="border rounded-lg p-5 bg-gray-50 shadow-md w-full max-w-md h-full flex flex-col justify-between self-start">
                
                <div class="w-full">
                    <h2 class="text-xl font-bold mb-4">Produk Anda</h2>
                    @foreach($products as $item)
                        @php 
                            // Pastikan variabel $item['price'] tersedia
                            $subtotal += $item['price'] * $item['quantity'];
                            $images = is_array($item['image']) ? $item['image'] : json_decode($item['image'], true);
                        @endphp
                        <div class="flex items-start gap-4 mb-4">
                            <div class="relative inline-block">
                                <div class="w-[75px] aspect-square border rounded-md overflow-hidden">
                                    <img src="{{ $images ? Storage::url($images[0]) : asset('no-image.png') }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                    {{ $item['quantity'] }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="flex text-left items-left">
                                    <h3 class="text-lg"><strong>Twoeight - {{ $item['name'] }}</strong></h3>
                                    {{-- <p class="font-semibold text-base">
                                        Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p> --}}
                                </div>
                                <h3 class="text-gray-500">Ukuran {{ $item['size'] }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bagian Bawah: Harga Total --}}
                @php $grandTotal = $subtotal + $shipping_cost; @endphp
                <div class="mt-4 w-full border-t pt-3">
                    <p class="text-gray-700">Subtotal: <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong></p>
                    {{-- ✅ Mengambil nilai ongkos kirim dari variabel yang dikirimkan --}}
                    <p class="text-gray-700">Ongkos Kirim: <strong>Rp{{ number_format($shipping_cost, 0, ',', '.') }}</strong></p>
                    <p class="font-bold mt-1">Total: <strong>Rp{{ number_format($grandTotal, 0, ',', '.') }}</strong></p>
                </div>
            </div>

            {{-- KANAN: Informasi Pesanan & Upload Bukti Pembayaran --}}
            <div class="space-y-4 col-span-2">
                <p class="text-gray-700">
                    Terima kasih <strong>{{ $nama }}</strong><br>
                    Pesanan anda telah dibuat, nomor pesanan anda yaitu <b>{{ $order_number }}</b><br>
                </p>

                {{-- Alamat Pengiriman --}}
                <div class="border rounded-lg p-5 bg-gray-50 shadow">
                    <h3 class="font-semibold text-lg mb-3">Informasi Pengiriman</h3>
                    <p>Nama: {{ $nama }}</p>
                    <p>Alamat: {{ $alamat }}, {{ $kecamatan ?? 'Kecamatan tidak tersedia' }}, {{ $kota ?? 'Kota tidak tersedia' }}, {{ $provinsi ?? 'Provinsi tidak tersedia' }}. {{ $kode_pos }}</p>
                    <p>Telp: {{ $telepon }}</p>
                    <p>Metode Pengiriman: {{ $metode_pengiriman }}</p>
                    {{-- ✅ Tampilkan ongkos kirim di sini juga --}}
                    <p>Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                    <p>Ongkos Kirim: Rp{{ number_format($shipping_cost, 0, ',', '.') }}</p>
                    <p>Total: Rp{{ number_format($grandTotal, 0, ',', '.') }}</p>
                </div>

                {{-- Konfirmasi Pembayaran --}}
                <div class="border rounded-lg p-5 bg-gray-50 shadow">
                    <h3 class="font-semibold text-lg mb-3">Konfirmasi Pembayaran</h3>
                    <p>Silakan transfer ke rekening berikut:</p>
                    <p class="mt-2"><strong>Bank BCA</strong></p>
                    <p>Nama Rekening: <strong>Baharudin Belakolly</strong></p>
                    <p>Nomor Rekening: <strong>8320857571</strong></p>
                    <p>Total Pembayaran: <strong>Rp{{ number_format($grandTotal, 0, ',', '.') }}</strong></p>
                    <p class="text-red-500 mt-2">⚠️ Harap melakukan pembayaran dalam 24 jam dan upload bukti pembayaran di bawah ini.</p>
                    
                    {{-- ... (kode form upload pembayaran yang lainnya) ... --}}
                    <form action="{{ route('upload.bukti') }}" method="POST" enctype="multipart/form-data" class="space-y-3" id="uploadForm">
                        @csrf
                        <input type="file" name="bukti_pembayaran" id="buktiPembayaran" accept="image/*" class="border p-2 rounded w-full" required>
                        
                        <div id="previewContainer" class="relative inline-block hidden">
                            <img id="previewBukti" class="mt-3 w-64 rounded-lg shadow">
                            <button type="button" id="removeImage" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-left justify-left text-xs hover:bg-red-700" title="Hapus gambar">
                                ✕
                            </button>
                        </div>
                        
                        <p class="text-left">
                            setelah melakukan pembayaran harap upload bukti pembayaran dan tekan tombol "Kirim" sistem kami akan verifikasi bukti pembayaran anda secepatnya, dan selalu cek status pesanan anda di menu "lacak pesanan" di bagian tampilan atas pada website.
                            {{-- <br>anda juga dapat melakukan pembayaran nanti sebelum 24 jam dan upload bukti pembayaran kemudian di menu "lacak pesanan" dengan menggunakan nomor pesanan {{ $order_number }} --}}
                        </p>
                        
                        <input type="hidden" name="order_id" value="{{ $order_id }}">
                        
                        <div class="flex gap-3">
                            <button type="submit" id="btnKirim" disabled class="bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold flex-1 cursor-not-allowed" onclick="return confirm('Apakah anda yakin gambar yang anda kirim adalah bukti pembayaran?')">
                                {{-- Kirim (Langsung Pesan) --}}
                                Kirim
                            </button>
                            {{-- <a href="/" class="bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 flex-1 text-left">
                                Kembali (Bayar Nanti)
                            </a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- </div> --}}
</div>

<script>
    const fileInput = document.getElementById('buktiPembayaran');
    const previewContainer = document.getElementById('previewContainer');
    const preview = document.getElementById('previewBukti');
    const btnKirim = document.getElementById('btnKirim');
    const removeImageBtn = document.getElementById('removeImage');
    
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            previewContainer.classList.remove('hidden');
            btnKirim.disabled = false;
            btnKirim.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btnKirim.classList.add('bg-black', 'hover:bg-gray-800');
        }
    });
    
    removeImageBtn.addEventListener('click', function() {
        preview.src = '';
        previewContainer.classList.add('hidden');
        fileInput.value = '';
        btnKirim.disabled = true;
        btnKirim.classList.remove('bg-black', 'hover:bg-gray-800');
        btnKirim.classList.add('bg-gray-400', 'cursor-not-allowed');
    });
</script>
@endsection