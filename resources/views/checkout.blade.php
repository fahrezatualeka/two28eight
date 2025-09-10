@extends('layouts.app')

@section('content')

    <div class="bg-white min-h-screen px-6 py-10 text-justify">
        <div class="container mx-auto pt-32 pb-10 bg-white">
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                {{-- Kiri: Form Pengiriman --}}
                <div class="space-y-4 col-span-2">
                    <h2 class="text-3xl font-bold mb-6">Checkout Produk</h2>
                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4 h-full">
                        @csrf
                        @foreach($checkoutItems as $item)
                            <input type="hidden" name="products[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                            <input type="hidden" name="products[{{ $item['id'] }}][quantity]" value="{{ $item['quantity'] }}">
                            <input type="hidden" name="products[{{ $item['id'] }}][size]" value="{{ $item['size'] }}">
                        @endforeach
                        
                        <input type="hidden" name="subtotal_price" id="subtotalPriceInput" value="{{ $subtotal }}">
                        <input type="hidden" name="shipping_cost" id="shippingCostInput">
                        <input type="hidden" name="subdistrict_id" id="subdistrictIdInput">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold mb-1">Nama Lengkap</label>
                                <input type="text" name="nama" class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">Telepon</label>
                                <input type="number" name="telepon" class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label for="provinsi" class="block font-semibold mb-1">Provinsi</label>
                                <select name="provinsi" id="provinsi" class="w-full border rounded-lg px-4 py-2" required>
                                    <option value="" selected disabled>- Pilih -</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province['id'] }}">{{ $province['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">Kota/Kabupaten</label>
                                <select name="kota" id="kota" class="w-full border rounded-lg px-4 py-2" required disabled>
                                    <option value="">- Pilih -</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">Kecamatan</label>
                                <select name="kecamatan" id="kecamatan" class="w-full border rounded-lg px-4 py-2" required disabled>
                                    <option value="">- Pilih -</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">Kode Pos</label>
                                <input type="number" name="kode_pos" id="kodePosInput" class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamatInput" rows="3" class="w-full border rounded-lg px-4 py-2" required></textarea>
                        </div>

                        <div>
                            <label class="block font-semibold mb-1">Metode Pengiriman</label>
                            <div id="shippingOptions" class="space-y-3">
                                <!-- Opsi pengiriman akan muncul di sini -->
                            </div>
                            <input type="hidden" name="metode_pengiriman" id="metodePengirimanInput" required>
                        </div>

                        <div class="w-full">
                            <label class="block font-semibold mb-1">Pembayaran</label>
                            <div class="border rounded-lg p-3 bg-white">
                                <p>Bank BCA</p>
                                <p>A.n. Fadil Muliatra Trimanda</p>
                                <p>No. Rekening: 1390167357</p>
                            </div>
                        </div>

                        <button type="submit" class="bg-black text-white py-3 w-full rounded-lg font-semibold group">
                            Lanjutkan pembayaran
                        </button>
                    </form>
                </div>

                {{-- Kanan: Daftar Produk --}}
                <div class="border rounded-lg p-5 bg-gray-50 shadow-md w-full max-w-md h-full flex flex-col justify-between self-start">
                    <div class="w-full">
                        <h2 class="text-xl font-bold mb-4">Produk Anda</h2>
                        @foreach($checkoutItems as $item)
                            @php
                                $productPrice = $item['price'];
                                $productQuantity = $item['quantity'];
                                $productName = $item['name'];
                                $productSize = $item['size'];
                                $productImage = $item['image'];
                                $images = is_array($productImage) ? $productImage : json_decode($productImage, true);
                            @endphp
                            <div class="flex items-start gap-4 mb-4">
                                <div class="relative inline-block">
                                    <div class="w-[75px] aspect-square border rounded-md overflow-hidden">
                                        <img src="{{ $images ? Storage::url($images[0]) : asset('no-image.png') }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                        {{ $productQuantity }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <b>2eight - {{ $productName }}</b>
                                        <p class="font-semibold text-base">
                                            Rp{{ number_format($productPrice * $productQuantity, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <p class="text-gray-500">Ukuran {{ $productSize }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Bagian Bawah: Harga Total --}}
                    <div class="mt-4 w-full">
                        <hr class="my-3">
                        <p class="text-gray-700">Subtotal: <strong id="subtotalDisplay">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong></p>
                        <p class="text-gray-700">Ongkos Kirim: <strong id="shippingCostDisplay">Rp0</strong></p>
                        <p class="font-bold mt-1">Total: <strong id="grandTotalDisplay">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const subtotal = {{ $subtotal }};
            const subtotalDisplay = document.getElementById('subtotalDisplay');
            const shippingCostDisplay = document.getElementById('shippingCostDisplay');
            const grandTotalDisplay = document.getElementById('grandTotalDisplay');
            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota');
            const kecamatanSelect = document.getElementById('kecamatan');
            const shippingCostInput = document.getElementById('shippingCostInput');
            const subdistrictIdInput = document.getElementById('subdistrictIdInput');
            const shippingOptions = document.getElementById('shippingOptions');
            const metodePengirimanInput = document.getElementById('metodePengirimanInput');
        
            function formatRupiah(number) {
                return "Rp" + number.toLocaleString('id-ID');
            }
        
            function updateTotals(shippingCost) {
                const grandTotal = subtotal + shippingCost;
                shippingCostDisplay.innerText = formatRupiah(shippingCost);
                grandTotalDisplay.innerText = formatRupiah(grandTotal);
                shippingCostInput.value = shippingCost;
            }
        
            // 🔹 Reset semua pilihan
            function resetAll() {
                kotaSelect.disabled = true;
                kecamatanSelect.disabled = true;
        
                kotaSelect.innerHTML = '<option value="" selected>- Pilih -</option>';
                kecamatanSelect.innerHTML = '<option value="" selected>- Pilih -</option>';
        
                shippingOptions.innerHTML = '<p class="text-gray-500">Pilih provinsi, kota, dan kecamatan terlebih dahulu.</p>';
                metodePengirimanInput.value = "";
        
                subdistrictIdInput.value = "";
                updateTotals(0);
            }
        
            // 🔹 Ambil daftar kota
            async function getCities(provinceCode) {
                kotaSelect.disabled = true;
                kecamatanSelect.disabled = true;
                kotaSelect.innerHTML = '<option value="">Mencari...</option>';
                kecamatanSelect.innerHTML = '<option value="">- Pilih -</option>';
                updateTotals(0);
        
                try {
                    const response = await fetch(`/checkout/get-cities/${provinceCode}`);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    const cities = await response.json();
        
                    kotaSelect.innerHTML = '<option value="" selected disabled>- Pilih -</option>';
                    if (cities.length > 0) {
                        kotaSelect.disabled = false;
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.innerText = city.name;
                            kotaSelect.appendChild(option);
                        });
                    } else {
                        kotaSelect.disabled = true;
                        kotaSelect.innerHTML = '<option value="">Tidak ada kota</option>';
                    }
                } catch (error) {
                    console.error('Error fetching cities:', error);
                    kotaSelect.disabled = true;
                    kotaSelect.innerHTML = '<option value="">Gagal memuat kota. Coba lagi nanti.</option>';
                }
            }
        
            // 🔹 Ambil daftar kecamatan
            async function getSubdistricts(cityCode) {
                kecamatanSelect.disabled = true;
                kecamatanSelect.innerHTML = '<option value="">Mencari...</option>';
                updateTotals(0);
        
                try {
                    const response = await fetch(`/checkout/get-subdistricts/${cityCode}`);
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    const subdistricts = await response.json();
        
                    kecamatanSelect.innerHTML = '<option value="" selected disabled>- Pilih -</option>';
                    if (subdistricts.length > 0) {
                        kecamatanSelect.disabled = false;
                        subdistricts.forEach(subdistrict => {
                            const option = document.createElement('option');
                            option.value = subdistrict.id;
                            option.innerText = subdistrict.name;
                            kecamatanSelect.appendChild(option);
                        });
                    } else {
                        kecamatanSelect.disabled = true;
                        kecamatanSelect.innerHTML = '<option value="">Tidak ada kecamatan</option>';
                    }
                } catch (error) {
                    console.error('Error fetching subdistricts:', error);
                    kecamatanSelect.disabled = true;
                    kecamatanSelect.innerHTML = '<option value="">Gagal memuat kecamatan. Coba lagi nanti.</option>';
                }
            }
        
            // 🔹 Event Provinsi
            provinsiSelect.addEventListener('change', function () {
                resetAll(); // reset semua setiap ganti provinsi
                if (this.value) getCities(this.value);
            });
        
            // 🔹 Event Kota
            kotaSelect.addEventListener('change', function () {
                if (this.value) getSubdistricts(this.value);
            });
        
            // 🔹 Event Kecamatan (cari ongkir)
            kecamatanSelect.addEventListener('change', async function () {
                const selectedSubdistrictId = this.value;
                subdistrictIdInput.value = selectedSubdistrictId;
                shippingOptions.innerHTML = `<p class="text-gray-500">Mencari layanan...</p>`;
                updateTotals(0);
        
                if (!selectedSubdistrictId) return;
        
                try {
                    const response = await fetch('{{ route('checkout.calculate-shipping-cost') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ subdistrict_id: selectedSubdistrictId, courier: 'all' })
                    });
        
                    if (!response.ok) throw new Error("Gagal ambil ongkir");
        
                    const data = await response.json();
                    shippingOptions.innerHTML = '';
        
                    const services = [
                        { key: 'eco', name: 'J&T ECO', estimate: '2-4 Hari', price: data.eco },
                        { key: 'ez', name: 'J&T EZ', estimate: '1-3 Hari', price: data.ez },
                        { key: 'jnd', name: 'J&T JND', estimate: '1 Hari', price: data.jnd }
                    ];
        
                    services.forEach(service => {
                        if (!service.price) return;
        
                        const option = document.createElement('div');
                        option.className = "flex items-center justify-between border rounded-lg p-3 cursor-pointer hover:bg-gray-100";
                        option.innerHTML = `
                            <div class="flex items-center gap-3">
                                <img src="{{ Storage::url('jnt.png') }}" alt="J&T" class="w-12 h-8 object-contain">
                                <div>
                                    <p class="font-semibold">${service.name}</p>
                                    <p class="text-gray-500 text-sm">${service.estimate}</p>
                                </div>
                            </div>
                            <p class="font-bold">${formatRupiah(service.price)}</p>
                        `;
        
                        option.addEventListener('click', () => {
                            document.querySelectorAll('#shippingOptions > div').forEach(div => {
                                div.classList.remove('border-black', 'bg-gray-50');
                            });
                            option.classList.add('border-black', 'bg-gray-50');
                            metodePengirimanInput.value = service.key;
                            updateTotals(service.price);
                        });
        
                        shippingOptions.appendChild(option);
                    });
        
                    if (shippingOptions.innerHTML === '') {
                        shippingOptions.innerHTML = `<p class="text-red-500">Tidak ada layanan tersedia.</p>`;
                    }
        
                } catch (error) {
                    console.error(error);
                    shippingOptions.innerHTML = `<p class="text-red-500">Gagal ambil layanan.</p>`;
                }
            });
        
            // awal load reset dulu
            resetAll();
        });
        </script>

@endsection