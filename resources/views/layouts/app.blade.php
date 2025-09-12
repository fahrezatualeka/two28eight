<!DOCTYPE html>
<html lang="id">
    <head>
        @livewireStyles
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- ✅ Tambahkan baris ini di sini --}}
        <title>two28eight</title>
    
        <link rel="icon" type="image/x-icon" href="{{ Storage::url('tw.png') }}">
        
        <!-- ✅ Google Fonts Poppins -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sf-pro-display/1.0.0/sf-pro-display.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Font Inter dan styling umum untuk tombol chat -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Inter', 'Poppins', sans-serif;
            }
            .whatsapp-popup-wrapper {
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                z-index: 50;
            }

            .whatsapp-popup-content {
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            }

            .whatsapp-popup-wrapper:hover .whatsapp-popup-content {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .whatsapp-chat-bubble {
                position: relative;
                max-width: 200px;
                background-color: white;
                border-radius: 0.75rem;
                padding: 0.75rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .whatsapp-chat-bubble::after {
                content: '';
                position: absolute;
                bottom: -10px;
                right: 10px;
                width: 0;
                height: 0;
                border-left: 10px solid transparent;
                border-right: 10px solid transparent;
                border-top: 10px solid white;
            }

            /* Untuk QR code agar tetap responsif */
            .qr-code-box {
                background-color: #ffffff;
                padding: 1rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }
        </style>
    
        @vite('resources/css/app.css')
    </head>
<body class="bg-gray-900 font-sans capitalize">

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Konten --}}
    <main class="min-h-screen">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')
    
    <!-- ✅ Kontainer untuk Tombol dan Popup Chat WhatsApp -->
    <div class="whatsapp-popup-wrapper group">
        <!-- Konten Popup yang Muncul saat Hover -->
        <div class="whatsapp-popup-content absolute bottom-20 right-0 w-80 mb-2">
            <div class="bg-[#128C7E] text-white p-4 rounded-t-lg flex items-center space-x-2">
                <i class="fa-brands fa-whatsapp text-2xl"></i>
                <span class="font-bold text-xl">WhatsApp</span>
            </div>
            <!-- Chat Bubble -->
            <div class="whatsapp-chat-bubble -mt-1 rounded-t-none">
                <p class="text-sm text-gray-700">Halo! Apa yang bisa saya bantu?</p>
            </div>
            <!-- QR Code Section -->
            <div class="qr-code-box mt-4">
                <div class="flex justify-center">
                    <!-- URL untuk QR Code Generator. Ganti nomor telepon Anda di sini. -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://wa.me/6282225048894&size=150x150" alt="WhatsApp QR Code" class="w-full h-auto max-w-[150px]">
                </div>
                <p class="text-center text-sm text-gray-500 mt-2">Scan the code</p>
            </div>
            <!-- Tombol Buka WhatsApp -->
            <a href="https://wa.me/6282225048894" target="blank"
               class="mt-4 w-full flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-colors">
                <span>Kirim pertanyaan</span>
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        </div>

        <!-- Tombol Utama "Chat Kami" yang akan terlihat -->
        <div class="bg-green-500 hover:bg-green-600 text-white font-bold p-4 rounded-full shadow-lg transition-transform transform hover:scale-110 flex items-center space-x-2 cursor-pointer">
            <i class="fa-brands fa-whatsapp text-4xl"></i>
            {{-- <span class="text-lg">Chat Kami</span> --}}
        </div>
    </div>

    @livewireScripts
</body>
</html>
