<div class="border-t"></div>

<footer class="bg-white text-black w-full">
    <div class="container mx-auto px-6 py-10 md:py-16">

        {{-- ✅ Bagian Atas: Kolom-kolom Menu (Responsive Grid) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-10 gap-y-8">
            
            {{-- PRODUK --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">PRODUK</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/topi" class="hover:text-gray-500">Topi</a></li>
                    <li><a href="/kaos" class="hover:text-gray-500">Kaos</a></li>
                    <li><a href="/kemeja" class="hover:text-gray-500">Kemeja</a></li>
                    <li><a href="/jaket" class="hover:text-gray-500">Jaket</a></li>
                    <li><a href="/hoodie" class="hover:text-gray-500">Hoodie</a></li>
                    <li><a href="/tas" class="hover:text-gray-500">Tas</a></li>
                    <li><a href="/celana" class="hover:text-gray-500">Celana</a></li>
                    <li><a href="/aksesoris" class="hover:text-gray-500">Aksesoris</a></li>
                </ul>
            </div>

            {{-- KOLEKSI --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">KOLEKSI</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-gray-500">Merek Brand</a></li>
                    <li><a href="#" class="hover:text-gray-500">Merek Brand</a></li>
                    <li><a href="#" class="hover:text-gray-500">Merek Brand</a></li>
                </ul>
            </div>

            {{-- SOSIAL MEDIA --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">Tentang Kami</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-gray-500"><i class="fa-brands fa-whatsapp"></i> Whatsapp Channel</a></li>
                    <li><a href="https://www.instagram.com/twoo28eight" target="blank" class="hover:text-gray-500"><i class="fa-brands fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>

            {{-- LEGAL --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">LEGAL</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/kebijakan-privasi" class="hover:text-gray-500">Kebijakan Privasi</a></li>
                    <li><a href="/syarat-ketentuan" class="hover:text-gray-500">Syarat dan Ketentuan</a></li>
                    <li><a href="/bantuan" class="hover:text-gray-500">FAQ</a></li>
                </ul>
            </div>
            
            {{-- SUPPORT --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">SUPPORT</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/tentang-produk" class="hover:text-gray-500">Tentang Produk</a></li>
                    <li><a href="/cara-berbelanja" class="hover:text-gray-500">Cara Berbelanja</a></li>
                    <li><a href="/pembayaran" class="hover:text-gray-500">Pembayaran</a></li>
                    <li><a href="/pengiriman" class="hover:text-gray-500">Pengiriman</a></li>
                    <li><a href="/lacak-pesanan" class="hover:text-gray-500">Status Pesanan</a></li>
                </ul>
            </div>

        </div>

    </div>

    {{-- ✅ Bagian Bawah: Hak Cipta dan Tautan Lain --}}
    <div class="bg-gray-700 text-white py-4 text-sm">
        <div class="container mx-auto px-6 text-center md:text-left flex flex-col md:flex-row justify-center">
            {{-- Teks Hak Cipta di Tengah --}}
            <div class="text-sm">
                <span style="text-transform: lowercase;">© {{ date('Y') }} | two28eight | develop by</span>
                <a href="https://www.instagram.com/fahrezatualeka" class="text-white hover:text-gray-300" target="blank" style="text-transform: lowercase;">
                    @fahrezatualeka
                </a>
            </div>
        </div>
    </div>
</footer>
