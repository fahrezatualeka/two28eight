<!DOCTYPE html>
<html lang="id">
<head>
    @livewireStyles
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>two28eight - Checkout</title>

    <link rel="icon" type="image/x-icon" href="{{ Storage::url('tw.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sf-pro-display/1.0.0/sf-pro-display.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    
    <style>
        /* Gaya tambahan yang mungkin dibutuhkan */
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
        }
        /* Jika Anda memiliki promo bar, pastikan tidak tumpang tindih */
        .content-wrapper {
            padding-top: 0;
        }
    </style>
</head>
<body class="bg-gray-900 font-sans capitalize">
    <main class="min-h-screen">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>