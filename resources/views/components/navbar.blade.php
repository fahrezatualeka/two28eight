<style>
    /* Mengatur ulang body dan html untuk mencegah overflow */
    html, body {
        overflow-x: hidden;
        width: 100%;
        font-family: 'Poppins', 'SF Pro Display', sans-serif;
    }

    /* Top Bar - Menu Atas */
    #topNavbar {
        height: 40px;
        color: white; /* Memastikan teks default berwarna putih */
        font-size: 0.85rem;
        position: fixed; /* Tetap di atas saat scroll */
        top: 0;
        left: 0;
        width: 100%;
        z-index: 50; /* Pastikan di atas konten lain */
    }
    
    #topNavbar .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }
    
    #topNavbar a {
        color: white; /* Memastikan semua tautan berwarna putih */
        transition: color 0.3s ease;
    }
    
    #topNavbar a:hover {
        color: #d1d5db; /* Warna abu-abu saat hover */
    }
    
    @media (min-width: 768px) {
        #topNavbar .container {
            justify-content: space-between;
        }
    }


    /* Main Navbar */
    #mainNavbar {
        height: 80px;
        background-color: white;
        box-shadow: 0 2px rgba(0, 0, 0, 0.1);
        position: fixed; /* Tetap di atas saat scroll */
        top: 40px; /* Geser ke bawah sesuai tinggi topNavbar */
        left: 0;
        width: 100%;
        z-index: 50; /* Pastikan di atas konten lain */
    }

    #mainNavbar .logo {
        font-size: 1.5rem; /* Lebih kecil untuk mobile */
        font-weight: 900;
        letter-spacing: -1px;
        color: black;
    }

    #mainNavbar .main-menu a {
        font-weight: 600;
        color: black;
    }

    /* Ikon di sisi kanan */
    .icon-wrapper .fa-solid {
        font-size: 1.1rem;
        color: black;
        transition: color 0.3s ease;
    }

    /* Search Input dalam Navbar */
    #searchInput {
        width: 0;
        opacity: 0;
        transform: scaleX(0);
        transform-origin: right;
        transition: width 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
    }
    #searchInput.show {
        width: 250px; /* Ukuran input saat tampil */
        opacity: 1;
        transform: scaleX(1);
    }
    
    /* Overlay untuk halaman hasil pencarian */
    #searchOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: black;
        z-index: 40;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    #searchOverlay.active {
        opacity: 0.5;
        pointer-events: auto;
    }

    /* Panel Hasil Pencarian */
    #searchResults {
        position: fixed;
        top: 120px; /* Di bawah topNavbar (40px) dan mainNavbar (80px) */
        left: 0;
        width: 100%;
        background: white;
        z-index: 60;
        opacity: 0;
        transform: translateY(10px);
        max-height: 0;
        overflow-y: auto;
        transition: top 0.3s ease, opacity 0.3s ease, transform 0.3s ease, max-height 0.3s ease;
    }

    #searchResults.show {
        opacity: 1;
        transform: translateY(0);
        max-height: calc(100vh - 120px); /* Disesuaikan dengan tinggi total kedua navbar */
    }
    
    /* Kelas untuk menonaktifkan scrolling */
    body.no-scroll {
        overflow: hidden !important;
    }

    /* Mobile Menu */
    #mobileMenu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: white;
        z-index: 70;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        padding-top: 120px; /* Biar tidak tertutup navbar */
    }
    
    #mobileMenu.show {
        transform: translateX(0);
    }

    /* Penyesuaian responsif */
    @media (min-width: 768px) {
        #mainNavbar .logo {
            font-size: 2.25rem;
            letter-spacing: -2px;
        }
    }
</style>

<!-- Top Navbar - Menu Atas -->
<div id="topNavbar" class="flex justify-center items-center h-10 px-6 bg-black z-40 bg-gray-700">
    <div class="container mx-auto flex flex-grow justify-center md:justify-between items-center text-sm">
        <!-- Text Tengah untuk Mobile, di Kiri untuk Desktop -->
        {{-- <div class="md:flex-grow text-center md:text-left hidden md:block text-white">
            Gratis Pengiriman! Untuk Semua Order
        </div> --}}

        <!-- Menu Kanan -->
        <div class="flex-grow flex items-center justify-center md:justify-end space-x-4">
            <a href="https://maps.app.goo.gl/mDBLLtfL84SNcnH58" class="flex items-center hover:text-gray-300 text-white" target="blank">
                <i class="fa-solid fa-location-dot mr-1"></i>
                <span class="md:inline">Lokasi Toko</span>
            </a>
            <a href="{{ route('lacak.index') }}" class="flex items-center hover:text-gray-300 text-white">
                <i class="fa-solid fa-truck mr-1"></i>
                <span class="md:inline">Status Pesanan</span>
            </a>
            <a href="/bantuan" class="flex items-center hover:text-gray-300 text-white">
                <i class="fa-solid fa-circle-question mr-1"></i>
                <span class="md:inline">Bantuan</span>
            </a>
        </div>
    </div>
</div>


<!-- Main Navbar -->
<nav id="mainNavbar" class="px-6 flex items-center justify-between z-50">
    <div class="container mx-auto flex items-center justify-between h-full">

        <!-- Hamburger Menu (Mobile Only) -->
        <button id="mobileMenuToggle" class="md:hidden text-black focus:outline-none">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <!-- 🔹 Logo -->
        <div class="flex items-center logo">
            <a href="/" style="text-transform: lowercase;">two28eight</a>
        </div>

        <!-- 🟢 Menu Tengah (Desktop Only) -->
        <ul class="main-menu hidden md:flex items-center space-x-8">
            <li><a href="/topi" class="hover:text-gray-500">Topi</a></li>
            <li><a href="/kaos" class="hover:text-gray-500">Kaos</a></li>
            <li><a href="/kemeja" class="hover:text-gray-500">Kemeja</a></li>
            <li><a href="/jaket" class="hover:text-gray-500">Jaket</a></li>
            <li><a href="/hoodie" class="hover:text-gray-500">Hoodie</a></li>
            <li><a href="/tas" class="hover:text-gray-500">Tas</a></li>
            <li><a href="/celana" class="hover:text-gray-500">Celana</a></li>
            <li><a href="/aksesoris" class="hover:text-gray-500">Aksesoris</a></li>
        </ul>

        <!-- 🔍 Pencarian + Profil + Cart -->
        <div class="flex items-center space-x-4 icon-wrapper">
            <!-- Search Input -->
            <input type="text" id="searchInput" placeholder="Cari produk..."
                class="px-3 py-2 rounded-lg bg-gray-100 border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none"
            >
            <!-- Search Toggle -->
            <button id="searchToggle" class="text-black focus:outline-none">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <!-- Cart Icon -->
            <div class="relative">
                <a href="{{ route('cart.index') }}" role="button" class="relative flex">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span id="cartCount" class="absolute -top-1 -right-1 bg-red-600 font-bold text-white text-xs rounded-full px-1">
                        @php
                            $totalQty = 0;
                            if(session('cart')) {
                                foreach(session('cart') as $item) {
                                    $totalQty += $item['quantity'];
                                }
                            }
                        @endphp
                        {{ $totalQty > 0 ? $totalQty : '' }}
                    </span>
                </a>
                
                {{-- Mini Cart (akan muncul di sini) --}}
                <div id="miniCart" class="absolute right-0 top-full mt-2 w-72 bg-white rounded-lg shadow-xl z-50 p-4 transition-all duration-300 transform scale-95 opacity-0 hidden">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md overflow-hidden">
                            <img id="miniCartImage" src="" alt="Product Image" class="w-full h-full object-cover">
                        </div>
                        <div class="ml-3 flex-grow">
                            <p id="miniCartName" class="text-sm font-semibold text-gray-900"></p>
                            <p id="miniCartSize" class="mt-1 text-xs text-gray-500"></p>
                            <p id="miniCartQty" class="text-xs text-gray-500"></p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p id="miniCartPrice" class="text-sm font-medium text-gray-900"></p>
                        </div>
                    </div>
                    <div class="border-t pt-3 mt-3">
                        <a href="{{ route('cart.index') }}" class="w-full text-center block bg-black text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-gray-800 transition">
                            Lihat Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu (Hidden by default) -->
<div id="mobileMenu" class="md:hidden fixed top-0 left-0 w-full h-full bg-white z-50 transform -translate-x-full transition-transform duration-300 overflow-y-auto">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Menu</h2>
            <button id="mobileMenuClose" class="text-black text-xl">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <ul class="space-y-4 text-lg font-semibold">
            <li><a href="/topi" class="block hover:text-gray-500">Topi</a></li>
            <li><a href="/kaos" class="block hover:text-gray-500">Kaos</a></li>
            <li><a href="/kemeja" class="block hover:text-gray-500">Kemeja</a></li>
            <li><a href="/jaket" class="block hover:text-gray-500">Jaket</a></li>
            <li><a href="/hoodie" class="block hover:text-gray-500">Hoodie</a></li>
            <li><a href="/tas" class="block hover:text-gray-500">Tas</a></li>
            <li><a href="/celana" class="block hover:text-gray-500">Celana</a></li>
            <li><a href="/aksesories" class="block hover:text-gray-500">Aksesoris</a></li>
        </ul>
    </div>
</div>


<!-- Panel Hasil Pencarian Full Height -->
<div id="searchOverlay"></div>
<div id="searchResults" class="hidden">
    <div class="border-t"></div>
    <div id="searchResultsContent" class="p-6"></div>
</div>

<script>
    const searchToggle = document.getElementById('searchToggle');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchOverlay = document.getElementById('searchOverlay');
    const mainNavbar = document.getElementById('mainNavbar');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    const miniCart = document.getElementById('miniCart');

    // Fungsi untuk menutup search bar dan overlay
    function closeSearchBar() {
        // Sembunyikan input pencarian
        searchInput.style.width = '0';
        searchInput.style.opacity = '0';
        searchInput.style.transform = 'scaleX(0)';
        searchInput.value = '';

        // Sembunyikan panel hasil pencarian
        searchResults.classList.remove('show');
        searchOverlay.classList.remove('active');
        setTimeout(() => searchResults.classList.add('hidden'), 300);

        // Hapus kelas no-scroll dari body
        document.body.classList.remove('no-scroll');
    }

    // Toggle Search Bar
    searchToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        const isSearchOpen = searchInput.style.width === '250px';

        if (!isSearchOpen) {
            // Tampilkan input pencarian
            searchInput.style.width = '250px';
            searchInput.style.opacity = '1';
            searchInput.style.transform = 'scaleX(1)';
            setTimeout(() => {
                searchInput.focus();
            }, 300);
            
            // Menonaktifkan scrolling segera
            document.body.classList.add('no-scroll');

        } else {
            closeSearchBar();
        }
    });

    // Menangani pencarian saat input berubah
    searchInput.addEventListener('input', function () {
        let keyword = this.value.trim();

        if (keyword.length < 1) {
            // Sembunyikan panel hasil pencarian jika keyword kosong
            searchResults.classList.remove('show');
            searchOverlay.classList.remove('active');
            setTimeout(() => searchResults.classList.add('hidden'), 300);
            return;
        }

        // Tampilkan overlay dan panel hasil pencarian
        searchOverlay.classList.add('active');
        searchResults.classList.remove('hidden');
        searchResults.classList.add('show');
        
        // Contoh fetch data (Anda harus memiliki endpoint ini)
        fetch(`/search-products?search=${keyword}`)
            .then(response => response.text())
            .then(html => {
                searchResultsContent.innerHTML = html;
            })
            .catch(err => console.error("Gagal mengambil data:", err));
    });

    // Toggle Mobile Menu
    mobileMenuToggle.addEventListener('click', function () {
        mobileMenu.classList.add('show');
        document.body.classList.add('no-scroll');
    });

    mobileMenuClose.addEventListener('click', function () {
        mobileMenu.classList.remove('show');
        document.body.classList.remove('no-scroll');
    });

    // Klik di luar area menutup search dan mobile menu
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchToggle.contains(e.target) && searchInput.style.width !== '0px') {
            closeSearchBar();
        }
        if (!mobileMenu.contains(e.target) && !mobileMenuToggle.contains(e.target) && mobileMenu.classList.contains('show')) {
            mobileMenu.classList.remove('show');
            document.body.classList.remove('no-scroll');
        }
        // Menyembunyikan mini cart saat klik di luar
        if (miniCart && !miniCart.contains(e.target) && !document.querySelector('.fa-cart-shopping').contains(e.target) && !miniCart.classList.contains('hidden')) {
            miniCart.classList.remove('scale-100', 'opacity-100');
            miniCart.classList.add('scale-95', 'opacity-0');
            setTimeout(() => miniCart.classList.add('hidden'), 300);
        }
    });

    // --- Logika Sticky Navbar telah dihapus, navbar sekarang selalu fixed di atas ---

    // --- Logika Mini Cart ---
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('last_added'))
            const lastProduct = @json(session('last_added'));
            const addedQty = lastProduct.quantity || 1;

            if (lastProduct && miniCart) {
                let imgSrc = '/no-image.png';
                if (lastProduct.image && Array.isArray(lastProduct.image) && lastProduct.image.length > 0) {
                    imgSrc = '/storage/' + lastProduct.image[0];
                }

                document.getElementById('miniCartImage').src = imgSrc;
                document.getElementById('miniCartName').innerText = lastProduct.name || '-';
                document.getElementById('miniCartSize').innerText = "Ukuran " + (lastProduct.size || '-');
                document.getElementById('miniCartQty').innerText = "Jumlah " + addedQty;
                document.getElementById('miniCartPrice').innerText = "Rp"+ ((lastProduct.price || 0) * addedQty).toLocaleString('id-ID');

                miniCart.classList.remove('hidden');
                setTimeout(() => {
                    miniCart.classList.add('scale-100', 'opacity-100');
                    miniCart.classList.remove('scale-95', 'opacity-0');
                }, 100);

                setTimeout(() => {
                    miniCart.classList.remove('scale-100', 'opacity-100');
                    miniCart.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => miniCart.classList.add('hidden'), 300);
                }, 1500);
            }
        @endif
    });

    function updateCartCount(newTotalQty) {
        const cartCountEl = document.getElementById('cartCount');
        if (newTotalQty > 0) {
            cartCountEl.innerText = newTotalQty;
            cartCountEl.classList.remove('hidden');
        } else {
            cartCountEl.innerText = '';
            cartCountEl.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('cart-updated', function(event) {
            updateCartCount(event.detail.totalQty);
        });
    });
</script>
