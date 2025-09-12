<div class="border-t"></div>

<footer class="bg-white text-black w-full">
    <div class="container mx-auto px-6 py-10 md:py-16">

        {{-- ✅ Bagian Atas: Kolom-kolom Menu (Responsive Grid) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-10 gap-y-8">
            
            {{-- PRODUK --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">PRODUK</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/tshirt" class="hover:text-gray-500">T-shirt</a></li>
                    <li><a href="/poloshirt" class="hover:text-gray-500">Polo-Shirt</a></li>
                    <li><a href="/jersey" class="hover:text-gray-500">Jersey</a></li>
                    <li><a href="/zipperhoodie" class="hover:text-gray-500">Zipper & Hoodie</a></li>
                    <li><a href="/jortspants" class="hover:text-gray-500">Jorts pants</a></li>
                    <li><a href="/sweatpants" class="hover:text-gray-500">Sweat Pants</a></li>
                    <li><a href="/trucker" class="hover:text-gray-500">Trucker</a></li>
                    <li><a href="/accessories" class="hover:text-gray-500">Accessories</a></li>
                </ul>
            </div>

            {{-- KOLEKSI --}}
            {{-- <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">KOLEKSI</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-gray-500">Merek Brand</a></li>
                    <li><a href="#" class="hover:text-gray-500">Merek Brand</a></li>
                    <li><a href="#" class="hover:text-gray-500">Merek Brand</a></li>
                </ul>
            </div> --}}

            {{-- SOSIAL MEDIA --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">Tentang Kami</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="https://whatsapp.com/channel/0029VbAjO9AIXnlnVpUWMV2E" target="blank" class="hover:text-gray-500"><i class="fa-brands fa-whatsapp"></i> Whatsapp Channel</a></li>
                    <li><a href="https://www.instagram.com/twoo28eight" target="blank" class="hover:text-gray-500"><i class="fa-brands fa-instagram"></i> Instagram</a></li>
                    <li><a href="https://www.tiktok.com/@two28eightt" target="blank" class="hover:text-gray-500"><i class="fa-brands fa-tiktok"></i> Tiktok</a></li>
                </ul>
            </div>


            {{-- SUPPORT --}}
            <div>
                <h3 class="font-bold uppercase mb-4 text-sm md:text-base">SUPPORT</h3>
                <ul class="space-y-2 text-sm">
                    {{-- <li><a href="/tentang-produk" class="hover:text-gray-500">Tentang Produk</a></li> --}}
                    <li><a href="/cara-berbelanja" class="hover:text-gray-500">Cara Berbelanja</a></li>
                    <li><a href="/pembayaran" class="hover:text-gray-500">Pembayaran</a></li>
                    <li><a href="/pengiriman" class="hover:text-gray-500">Pengiriman</a></li>
                    <li><a href="/lacak-pesanan" class="hover:text-gray-500">Status Pesanan</a></li>
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
            

        </div>

    </div>

    {{-- ✅ Bagian Bawah: Hak Cipta dan Tautan Lain --}}
    <div class="bg-gray-700 text-white py-4 text-sm">
        <div class="container mx-auto px-6 text-center md:text-left flex flex-col md:flex-row justify-center">
            {{-- Teks Hak Cipta di Tengah --}}
            <div class="text-sm">
                <!--<span style="text-transform: lowercase;">© {{ date('Y') }} | two28eight | develop by</span>-->
                <!--<a href="https://www.instagram.com/fahrezatualeka" class="text-white hover:text-gray-300" target="blank" style="text-transform: lowercase;">-->
                <!--    @fahrezatualeka-->
                <!--</a>-->
                
                                <span>© {{ date('Y') }} | Twoeight</span>
            </div>
        </div>
    </div>
</footer>
