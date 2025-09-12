<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8">Status Pesanan</h2>

        <form wire:submit.prevent="search" class="mb-6">
            <div class="flex gap-4 items-center">
                <input type="text" wire:model.defer="orderNumber" placeholder="Masukkan nomor pesanan anda"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800">Cari</button>
            </div>
        </form>

        {{-- 🟢 KOREKSI UTAMA: Satu blok IF/ELSEIF/ELSE untuk semua kondisi --}}
        @if ($order)
            @if ($order->status === 'Ditolak')
                <div class="mt-6 p-4 bg-red-100 text-red-700 rounded shadow">
                    Nomor pesanan {{ $order->order_number }} telah ditolak karena bukti pembayaran tidak sesuai.
                </div>
            @else
                <div wire:poll.7s>
                    <h3 class="text-xl font-bold text-red-500 mb-4">
                        Status Pengiriman: {{ $order->status }}
                        <span wire:loading class="text-sm text-gray-500">(Memperbarui...)</span>
                    </h3>
        
                    <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h3 class="text-xl font-bold">Detail Pengiriman</h3>
                            <br>
                            <ul class="text-sm space-y-2">
                                <li>Nama Pembeli: {{ $order->nama }}</li>
                                <li>Alamat: {{ $order->alamat }}, {{ $order->kota }}, {{ $order->provinsi }} {{ $order->kode_pos }}</li>
                                <li>Telepon: {{ $order->telepon }}</li>
                                <li>Metode Pengiriman: {{ $order->metode_pengiriman }}</li>
                                <li>Nomor Resi: {{ $order->no_resi ?? 'Belum dikirim' }}</li>
                            </ul>
                        </div>
    
                        <div class="col-span-2 space-y-4">
                            <h3 class="text-xl font-bold ">Produk yang Dipesan</h3>
                        
                            @php
                                $subtotal = 0;
                                $ongkir = 0;
                            @endphp
                        
                            <div class="border rounded-lg p-5 bg-gray-50 shadow-md w-full h-full flex flex-col justify-between self-start">
            
                                <div class="w-full">
                                    @foreach($order->items as $item)
                                        @php 
                                            $images = is_array($item->image) ? $item->image : json_decode($item->image, true);
                                            $image = $images[0] ?? 'no-image.png';
                                            $itemSubtotal = $item->price * $item->quantity;
                                            $subtotal += $itemSubtotal;
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
                                                <div class="flex justify-between items-center">
                                                    <h3 class="text-lg "><strong>Twoeight - {{ $item->name }}</strong></h3>
                                                </div>
                                                <h3 class="text-gray-500">Ukuran {{ $item->size }}</h3>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            
                                <div class="mt-4 w-full">
                                    <hr class="my-3">
                                    <p class="text-gray-700">Subtotal: Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                    <p class="text-gray-700">Ongkos Kirim: Rp{{ number_format($ongkir, 0, ',', '.') }}</p>
                                    <p class=" mt-1">Total: Rp{{ number_format($subtotal + $ongkir, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
        
                    @if ($order->status === 'Menunggu Pembayaran')
                        <div class="mt-8 border rounded-lg p-5 bg-gray-50 shadow">
                            @if (session()->has('success'))
                                <div class="bg-green-100 text-green-800 p-3 rounded mb-3">
                                    {{ session('success') }}
                                </div>
                            @endif
        
                            <h3 class="font-semibold text-lg mb-3">Konfirmasi Pembayaran</h3>
                            <p>Silakan transfer ke rekening berikut: <strong></strong></p>
                            <strong>Bank BCA</strong>
                            <p>Nama Rekening: <strong>Baharudin Belakolly</strong></p>
                            <p>Nomor Rekening: <strong>8320857571</strong></p>
                            <p>Total Pembayaran: <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong></p>
                            <p class="text-red-500 mt-2">⚠️ Harap melakukan pembayaran dalam 24 jam dan upload bukti pembayaran di bawah ini.</p>
        
                            <form wire:submit.prevent="uploadBukti" enctype="multipart/form-data" class="space-y-3">
                                <input type="file" wire:model="bukti_pembayaran" id="buktiPembayaran" accept="image/*" class="border p-2 rounded w-full">
        
                                @error('bukti_pembayaran')
                                    <p class="text-sm text-red-500">{{ $message }}</p>
                                @enderror
        
                                @if ($bukti_pembayaran)
                                    <div class="relative inline-block mt-3">
                                        <img src="{{ $bukti_pembayaran->temporaryUrl() }}" class="w-64 rounded-lg shadow">
                                        <button type="button" wire:click="$set('bukti_pembayaran', null)"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-700">
                                            ✕
                                        </button>
                                    </div>
                                @endif
        
                                <input type="hidden" wire:model.defer="orderId" value="{{ $order->id }}">
        
                                <div class="flex gap-3 mt-4">
                                    <button type="submit"
                                        @if(!$bukti_pembayaran) disabled @endif
                                        wire:loading.attr="disabled" onclick="return confirm('Apakah anda yakin gambar yang anda kirim adalah bukti pembayaran?')"
                                        class="@if($bukti_pembayaran) bg-black hover:bg-gray-800 @else bg-gray-400 cursor-not-allowed @endif text-white px-6 py-3 rounded-lg font-semibold flex-1">
                                        <span wire:loading.remove> Kirim (Langsung Pesan) </span>
                                        <span wire:loading> Mengunggah... </span>
                                    </button>
        
                                    <a href="/" class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 flex-1 text-center">Kembali (Bayar Nanti)</a>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        @elseif ($orderNumber)
            <div class="mt-6 p-4 bg-red-100 text-red-700 rounded shadow">
                Nomor pesanan tidak ditemukan.
            </div>
        @endif
    </div>
</div>