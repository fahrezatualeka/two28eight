<div class="p-6 bg-white rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Kalkulator Ongkos Kirim</h2>
    
    <div class="space-y-4">
        {{-- Input Kota Asal --}}
        <div>
            <label for="origin" class="block text-sm font-medium text-gray-700">Kota Asal</label>
            <input wire:model.live="origin" type="text" id="origin" placeholder="Cari kota asal..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @if (!empty($originResults))
            <ul class="mt-2 border border-gray-300 rounded-md bg-white shadow-lg max-h-40 overflow-y-auto">
                @foreach($originResults as $result)
                <li wire:click="setOrigin('{{ $result['city_id'] }}', '{{ $result['type'] }} {{ $result['city_name'] }}')" class="cursor-pointer px-3 py-2 hover:bg-gray-100 transition-colors duration-200">
                    {{ $result['type'] }} {{ $result['city_name'] }}, {{ $result['province_name'] }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Input Kota Tujuan --}}
        <div>
            <label for="destination" class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
            <input wire:model.live="destination" type="text" id="destination" placeholder="Cari kota tujuan..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @if (!empty($destinationResults))
            <ul class="mt-2 border border-gray-300 rounded-md bg-white shadow-lg max-h-40 overflow-y-auto">
                @foreach($destinationResults as $result)
                <li wire:click="setDestination('{{ $result['city_id'] }}', '{{ $result['type'] }} {{ $result['city_name'] }}')" class="cursor-pointer px-3 py-2 hover:bg-gray-100 transition-colors duration-200">
                    {{ $result['type'] }} {{ $result['city_name'] }}, {{ $result['province_name'] }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        {{-- Input Berat Barang dan Pilihan Kurir --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="weight" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                <input wire:model.live="weight" type="number" id="weight" min="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="courier" class="block text-sm font-medium text-gray-700">Kurir</label>
                <select wire:model.live="courier" id="courier" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="jnt">J&T Express</option>
                    <option value="jne">JNE</option>
                    <option value="tiki">TIKI</option>
                    <option value="pos">POS Indonesia</option>
                    <option value="sicepat">Sicepat</option>
                    {{-- Tambahkan kurir lain sesuai kebutuhan Anda --}}
                </select>
            </div>
        </div>

        {{-- Tombol untuk Menghitung Biaya Ongkir --}}
        <div class="mt-6">
            <button wire:click="calculateCost" wire:loading.attr="disabled" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span wire:loading.remove wire:target="calculateCost">Cek Ongkir</span>
                <span wire:loading wire:target="calculateCost">Sedang menghitung...</span>
            </button>
        </div>
    </div>
    
    {{-- Tampilan Hasil Ongkos Kirim --}}
    @if (!empty($shippingCosts) && !isset($shippingCosts['error']))
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">Hasil Ongkos Kirim</h3>
        <div class="space-y-4">
            @foreach ($shippingCosts as $service)
            <div class="p-4 bg-gray-100 rounded-md border border-gray-200">
                <p class="text-lg font-semibold">{{ $service['service'] }} ({{ $service['etd'] }})</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($service['cost'], 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @elseif (isset($shippingCosts['error']))
    <div class="mt-8 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
        <p>{{ $shippingCosts['error'] }}</p>
    </div>
    @endif
</div>
