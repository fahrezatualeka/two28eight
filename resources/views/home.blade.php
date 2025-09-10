<style>
    /*
      Ini adalah penyesuaian CSS khusus untuk halaman home.
      Sebagian besar styling sekarang menggunakan Tailwind CSS.
    */
    #slider-hero {
        display: flex;
        transition: transform 0.7s ease-in-out;
    }
    #slider-hero > div {
        flex: 0 0 100%;
        position: relative;
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Promo bar pertama di bagian atas */
    #top-promo-bar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 40px;
        z-index: 55;
    }

    /* Promo bar kedua, di bawah bar pertama */
    #red-promo-bar {
        position: fixed;
        top: 40px;
        left: 0;
        width: 100%;
        height: 40px;
        z-index: 55;
    }

    /* mainNavbar perlu digeser ke bawah agar tidak tertutup dua promo bar */
    #mainNavbar {
        top: 80px; /* 40px (bar1) + 40px (bar2) */
    }

    /* Menyesuaikan jarak padding top untuk konten utama */
    .content-wrapper {
        padding-top: 25px;
    }

    /* Mobile menu perlu digeser agar tidak tertutup promo bar */
    #mobileMenu {
        padding-top: 160px; /* Sama dengan konten utama */
    }

    /* Hero section yang statis */
    #hero-section {
        height: 100vh;
        width: 100vw;
        margin-top: 160px; /* Jarak dari atas (2 promo bar + navbar) */
        position: relative;
        overflow: hidden;
    }

    #hero-section img {
        width: 100vw;
        height: 100vh;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
        transform: none !important;
    }

    /* Overlay untuk teks pada hero section */
    .hero-text-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        padding-left: 5%;
        color: black;
    }

    /* Gaya tombol pada hero section */
    .hero-button {
        background-color: black;
        color: white;
        border: 2px solid black;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: background-color 300ms, color 300ms;
    }
    .hero-button:hover {
        background-color: white;
        color: black;
    }

    /* Mengubah warna dot indicator slider agar sesuai gambar */
    .dot-hero.active {
        background-color: black;
    }
    .dot-hero {
        background-color: #d1d5db; /* gray-300 */
    }

    /* CSS tambahan untuk slider produk yang baru */
    #product-slider-wrapper {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }
    .product-slide {
        flex: 0 0 auto;
    }
</style>

@extends('layouts.app')

@section('content')

@if(session('success'))
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 8000
    });
});
</script>
@endif

<!-- ✅ PROMO BAR PERTAMA -->
{{-- <div id="top-promo-bar" class="w-full bg-black text-white flex justify-center items-center text-xs font-semibold text-center z-[55]">
    <span class="py-2">Lari bersama New Balance Run Club! - Daftar sekarang disini</span>
</div>

<!-- ✅ PROMO BAR KEDUA -->
<div id="red-promo-bar" class="w-full bg-red-600 text-white flex items-center justify-center text-sm font-semibold text-center z-[55]">
    <div class="flex-grow text-center py-2">
        <span>Precision fit starts here. Your perfect pair is just a scan away - try it on the product page.</span>
    </div>
    <div class="flex items-center px-4 py-2">
        <span class="mr-1">&gt;|0</span>
        <span class="font-normal text-xs uppercase">voluntaria</span>
    </div>
</div> --}}

{{-- ✅ HERO SLIDER DENGAN TEXT OVERLAY --}}
<div id="hero-slider-section" class="relative w-screen h-screen overflow-hidden mt-20">
    {{-- ✅ Wrapper Slider --}}
    <div id="slider-hero" class="flex h-full">
        <!-- Clone Last (untuk efek infinite) -->
        <div class="flex-shrink-0 w-full h-full">
            {{-- <img src="https://placehold.co/1920x1080/4f46e5/ffffff?text=Slide+3" alt="Hero" class="w-full h-full object-cover"> --}}
            <img src="" class="w-full h-full object-cover">
        </div>
        <!-- ✅ 3 Slides Asli -->
        <div class="flex-shrink-0 w-full h-full">
            <!-- Tampilan untuk "The FuelCell SC Elite" -->
            <img src="" alt="Hero" class="w-full h-full object-cover">
            <div class="hero-text-overlay flex flex-col md:flex-row items-center bg-gray-100 rounded-lg overflow-hidden">
                <div class="max-w-lg">
                    <h1 class="text-4xl md:text-6xl font-bold mb-2">The 28.</h1>
                    <p class="text-md md:text-lg mb-6">paduan warna yang kekinian. <span class="italic">*Tampil berbeda.</span></p>
                    <a href="/all-product" class="hero-button">beli sekarang</a>
                </div>
            </div>
        </div>
        <div class="flex-shrink-0 w-full h-full bg-gray-100">
            <img src="" alt="Hero" class="w-full h-full object-cover">
        </div>
        <div class="flex-shrink-0 w-full h-full bg-gray-100">
            <img src="" alt="Hero" class="w-full h-full object-cover">
        </div>
        <!-- Clone First (untuk efek infinite) -->
        <div class="flex-shrink-0 w-full h-full">
            <img src="" alt="Hero" class="w-full h-full object-cover">
            <div class="hero-text-overlay">
                <div class="max-w-lg">
                    <h1 class="text-4xl md:text-6xl font-bold mb-2">The FuelCell SC Elite</h1>
                    <p class="text-md md:text-lg mb-6">May lead to personal bests. <span class="italic">*Training not included.</span></p>
                    <a href="/all-product" class="hero-button">Beli sekarang</a>
                </div>
                </div>
            </div>
        </div>
    {{-- ✅ Indicator Bulat Dinamis --}}
    <div id="dots-hero" class="absolute bottom-5 left-1/2 transform -translate-x-1/2 flex space-x-3">
        <span class="dot-hero w-3 h-3 rounded-full cursor-pointer transition duration-200"></span>
        <span class="dot-hero w-3 h-3 rounded-full cursor-pointer transition duration-200"></span>
        <span class="dot-hero w-3 h-3 rounded-full cursor-pointer transition duration-200"></span>
    </div>
</div>

{{-- ✅ KONTEN UTAMA --}}
<div class="content-wrapper bg-white min-h-screen">
    <div class="flex flex-col md:flex-row items-center bg-gray-100 rounded-lg overflow-hidden" 
        style="margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);">
        {{-- <img src="https://placehold.co/1920x225/000000/ffffff?text=New+Drop+Banner" alt="New Drop Banner" class="w-full h-[225px] object-cover"> --}}
        <img src="" class="w-full h-[225px] object-cover">
    </div>

    <div class="container mx-auto px-6 py-10">

        {{-- ✅ PRODUK RILIS TERBARU (HORIZONTAL SCROLL) --}}
        <div class="relative mb-8">
            {{-- <h2 class="text-2xl font-bold mb-2">Modern Basics.</h2>
            <p class="text-gray-600 mb-6 text-sm">Quiet confidence in every piece.</p> --}}
            <h2 class="text-2xl font-bold mb-6">Produk Rilis Terbaru</h2>

            <button id="scrollLeft" class="absolute top-1/2 -left-6 z-10 p-2 bg-white rounded-full shadow-lg transform -translate-y-1/2 block lg:block">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <!-- Wrapper Produk yang Bisa Digeser -->
            <div class="overflow-x-hidden no-scrollbar gap-6 py-4">
                <div id="product-slider-wrapper" class="flex gap-6">
                    @foreach($mostViewedProducts as $product)
                        <!-- Menggunakan kelas responsif agar tampilan sesuai dengan grid di bawahnya -->
                        <div class="flex-shrink-0 w-1/2 md:w-1/3 lg:w-1/5 product-slide">
                            @include('components.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Tombol Geser Kanan -->
            <button id="scrollRight" class="absolute top-1/2 -right-6 z-10 p-2 bg-white rounded-full shadow-lg transform -translate-y-1/2 block lg:block">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <div class="border-t"></div>

        {{-- 🔥 VIDEO SAMPUl.MP4 --}}
        <div class="w-full my-8">
            <img src="{{ Storage::url('sampul.mp4') }}" class="w-full object-cover" autoplay loop muted playsinline>
        </div>

        <div class="border-t"></div>
        <br>
        <br>


@php

$promos = [

// ['file' => 'g.jpeg', 'title' => 'Promo Spesial'],

['file' => '', 'title' => 'Promo Spesial'],

['file' => '', 'title' => 'Diskon Terbaru'],

['file' => '', 'title' => 'Koleksi Populer'],

];

@endphp



<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 text-center ">

@foreach($promos as $promo)

<div class="flex flex-col md:flex-row items-center bg-gray-100 rounded-lg overflow-hidden">

<img src="{{ Storage::url($promo['file']) }}"

class="w-full h-60 object-cover hover:scale-105 transition duration-200">

<p class="mt-2 text-lg font-semibold text-gray-800">

{{ $promo['title'] }}

</p>

</div>

@endforeach

</div>


        <div class="border-t"></div>
        
        <div class="container mx-auto px-6 py-10">

            {{-- ✅ 4 KOLOM GRID --}}
            @php
                $promoCategories = [
                    // ['title' => 'FLEXIBLE', 'desc' => 'DRESS UP THAT WILL COMPLIMENT YOUR EVERYDAY LOOK.', 'image' => 'https://placehold.co/600x400/94a3b8/ffffff?text=FLEXIBLE'],
                    ['title' => 'FLEXIBLE', 'desc' => 'test deskripsi', 'image' => ''],
                    ['title' => 'FLEXIBLE', 'desc' => 'test deskripsi', 'image' => ''],
                    ['title' => 'FLEXIBLE', 'desc' => 'test deskripsi', 'image' => ''],
                    ['title' => 'FLEXIBLE', 'desc' => 'test deskripsi', 'image' => ''],
                ];
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($promoCategories as $promo)
                    <div class="group relative rounded-lg overflow-hidden ">
                        <img src="{{ $promo['image'] }}" alt="{{ $promo['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-end p-4 text-white">
                            <h3 class="text-xl font-bold">{{ $promo['title'] }}</h3>
                            <p class="text-xs text-center">{{ $promo['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="my-10"></div>
            <div class="border-t"></div>
            <br>
            
            {{-- ✅ PRODUK REKOMENDASI UNTUK ANDA --}}
            <h2 class="text-2xl font-bold mb-6">Paling Sering Dicari</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($mostViewedProducts->take(5) as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            
            <div class="text-center py-10">
                <a href="{{ route('product') }}" 
                class="inline-block bg-transparent text-black border px-6 py-3 rounded-lg font-semibold hover:bg-black hover:text-white transition duration-200">
                Lihat semua Produk
            </a>
            </div>

            <div class="border-t"></div>
            <div class="my-10"></div>
            
            {{-- ✅ BANNER TENGAH DUA KOLOM --}}
            <div class="flex flex-col md:flex-row items-center bg-gray-100 rounded-lg overflow-hidden">
                <div class="flex-1 p-8 text-center md:text-left">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4">The 28.</h2>
                    <a href="/all-product" class="inline-block bg-transparent border-2 border-black text-black px-6 py-3 rounded-lg font-semibold hover:bg-black hover:text-white transition duration-200">Beli sekarang</a>
                </div>
                <div class="flex-1 w-full md:w-auto">
                    {{-- <img src="" alt="Shoes" class="w-full h-full object-cover"> --}}
                    <img src="" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Slider hero section
        const slider = document.getElementById("slider-hero");
        const dots = document.querySelectorAll(".dot-hero");
        const totalSlides = dots.length;
        let currentSlide = 1; 
        let isTransitioning = false;
    
        slider.style.transform = `translateX(-100%)`;
    
        function updateDots() {
            dots.forEach((d, i) => d.classList.toggle("active", i === (currentSlide - 1) % totalSlides));
        }
    
        function moveToSlide(index) {
            if (isTransitioning) return;
            isTransitioning = true;
    
            currentSlide = index;
            slider.style.transition = "transform 0.7s ease-in-out";
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    
            setTimeout(() => {
                if (currentSlide === 0) {
                    slider.style.transition = "none";
                    currentSlide = totalSlides;
                    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
                } 
                else if (currentSlide === totalSlides + 1) {
                    slider.style.transition = "none";
                    currentSlide = 1;
                    slider.style.transform = `translateX(-100%)`;
                }
                isTransitioning = false;
                updateDots();
            }, 700);
        }
    
        let autoSlide = setInterval(() => moveToSlide(currentSlide + 1), 4000);
    
        function restartAutoSlide() {
            clearInterval(autoSlide);
            autoSlide = setInterval(() => moveToSlide(currentSlide + 1), 4000);
        }
    
        dots.forEach((dot, i) => {
            dot.addEventListener("click", () => {
                moveToSlide(i + 1);
                restartAutoSlide();
            });
        });
    
        updateDots();
    });

    // Skrip untuk mengontrol tombol geser produk di bagian "Modern Basics"
    document.addEventListener("DOMContentLoaded", () => {
        const wrapper = document.getElementById('product-slider-wrapper');
        const btnLeft = document.getElementById('scrollLeft');
        const btnRight = document.getElementById('scrollRight');

        if (!wrapper || !btnLeft || !btnRight) {
            console.error("Elemen product slider tidak ditemukan.");
            return;
        }

        const slides = Array.from(wrapper.getElementsByClassName('product-slide'));
        const numSlides = slides.length;
        // Sesuaikan jumlah kloning dengan lebar produk yang ditampilkan, misal 3 produk.
        const cloneCount = 3; 
        let currentIndex = 0;
        let isTransitioning = false;
        
        // Kloning elemen di awal dan akhir untuk efek tak terbatas
        for (let i = 0; i < cloneCount; i++) {
            wrapper.appendChild(slides[i].cloneNode(true));
        }
        for (let i = numSlides - cloneCount; i < numSlides; i++) {
            wrapper.insertBefore(slides[i].cloneNode(true), slides[0]);
        }

        // Hitung lebar per slide (termasuk gap)
        const slideWidth = slides[0].offsetWidth + 24; // 24px = gap-6
        
        // Atur posisi awal agar produk pertama terlihat, setelah kloning
        wrapper.style.transform = `translateX(-${cloneCount * slideWidth}px)`;
        
        // Fungsi untuk menggeser slider
        function slideTo(index) {
            if (isTransitioning) return;
            isTransitioning = true;
            
            wrapper.style.transition = 'transform 0.5s ease-in-out';
            wrapper.style.transform = `translateX(-${(index + cloneCount) * slideWidth}px)`;

            currentIndex = index;
        }

        // Event listener untuk geser kanan
        btnRight.addEventListener('click', () => {
            slideTo(currentIndex + 1);
        });

        // Event listener untuk geser kiri
        btnLeft.addEventListener('click', () => {
            slideTo(currentIndex - 1);
        });
        
        // Reset posisi setelah transisi selesai untuk efek tak terbatas
        wrapper.addEventListener('transitionend', () => {
            if (currentIndex >= numSlides) {
                // Bergeser ke kanan melewati produk terakhir
                wrapper.style.transition = 'none';
                wrapper.style.transform = `translateX(-${cloneCount * slideWidth}px)`;
                currentIndex = 0;
            } else if (currentIndex < 0) {
                // Bergeser ke kiri melewati produk pertama
                wrapper.style.transition = 'none';
                wrapper.style.transform = `translateX(-${(numSlides - 1 + cloneCount) * slideWidth}px)`;
                currentIndex = numSlides - 1;
            }
            isTransitioning = false;
        });

        // Panggil slideTo(0) untuk memastikan posisi awal yang benar
        slideTo(0);
    });
</script>
