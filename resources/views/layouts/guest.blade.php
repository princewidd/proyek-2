<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ZERO SHOP') }}</title>

        <!-- Fonts & Tailwind -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans antialiased text-gray-900 min-h-screen relative flex items-center justify-center p-4 overflow-x-hidden">
        
        <!-- Render Tampilan Beranda (Ukuran Skala Normal 100%, Tanpa Scale/Zoom) -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            @include('beranda')
        </div>

        <!-- Overlay Gelap + Blur Kaca Transparan -->
        <div class="fixed inset-0 z-10 bg-black/60 backdrop-blur-md"></div>

        <!-- Pop-Up Modal Container -->
        <div class="relative z-20 w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 my-auto">
            {{ $slot }}
        </div>

    </body>
</html>