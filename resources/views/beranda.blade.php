<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-100 text-gray-900 antialiased">
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <a href="{{ route('beranda') }}" class="flex items-center gap-2 shrink-0">
                    <img src="{{ asset('img/beranda/zeroshop-white-49a3bd4-1.png') }}" alt="ZERO SHOP" class="h-9 w-9 rounded object-cover">
                    <span class="text-white font-bold text-lg tracking-wide">ZERO SHOP</span>
                </a>

                <div class="hidden sm:block flex-1 max-w-md">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="hidden sm:inline-block text-sm text-white/90 hover:text-white">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm text-white/90 hover:text-white">Masuk</a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-block text-sm bg-white text-[#1e2a44] font-medium px-4 py-1.5 rounded-full hover:bg-white/90">Daftar</a>
                    @endauth
                    <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" class="text-white/90 hover:text-white">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="9" r="3.2"/><path d="M5.5 19.5c1.2-3 3.6-4.5 6.5-4.5s5.3 1.5 6.5 4.5"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="sm:hidden pb-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                </div>
            </div>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="relative rounded-2xl overflow-hidden bg-[#1e2a44]">
            <img src="{{ asset('img/beranda/zenless-zone-zero-grup-1.png') }}" alt="Promo"
                class="w-full h-auto block">
        </div>
    </section>

    @php
        $games = [
            ['name' => 'Genshin Impact', 'img' => 'image-8.png'],
            ['name' => 'Valorant', 'img' => 'image-9.png'],
            ['name' => 'Solo Leveling: Arise', 'img' => 'image-9-2.png'],
            ['name' => 'Seven Knights Re:BIRTH', 'img' => '3735642-1-2.png'],
            ['name' => 'Wuthering Waves', 'img' => 'image-2.png'],
            ['name' => 'Neverness to Everness', 'img' => '26698325-1.png'],
            ['name' => 'Roblox', 'img' => 'image-10.png'],
            ['name' => 'Aniimo', 'img' => 'image.png'],
            ['name' => 'Zenless Zone Zero', 'img' => 'image-25.png'],
            ['name' => 'Persona 5: The Phantom X', 'img' => '3735642-1.png'],
        ];
    @endphp

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">Pilih Game</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($games as $game)
                <a href="#" class="group rounded-xl overflow-hidden bg-[#1e2a44] shadow hover:shadow-lg transition duration-200">
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ asset('img/beranda/' . $game['img']) }}" alt="{{ $game['name'] }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="px-2 py-2">
                        <span class="block text-center text-white text-xs sm:text-sm font-medium truncate">{{ $game['name'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <footer class="bg-[#1e2a44] mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-white/70 text-sm">
            &copy; {{ date('Y') }} ZERO SHOP. Semua hak dilindungi.
        </div>
    </footer>
</body>
</html>
