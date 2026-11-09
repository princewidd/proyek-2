<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-100 text-gray-900 antialiased h-full flex flex-col">

    <!-- NAVBAR HEADER -->
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-sm shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                <a href="{{ route('beranda') }}" class="flex items-center gap-2 shrink-0">
                    <img src="{{ asset('img/beranda/zeroshop-white-49a3bd4-1.png') }}" alt="ZERO SHOP" class="h-9 w-9 rounded object-cover">
                    <span class="text-white font-bold text-lg tracking-wide">ZERO SHOP</span>
                </a>

                <form action="{{ route('beranda') }}" method="GET" class="hidden sm:block flex-1 max-w-md">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                    </div>
                </form>

                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm text-white/90 hover:text-white">Masuk</a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-block text-sm bg-white text-[#1e2a44] font-medium px-4 py-1.5 rounded-full hover:bg-white/90">Daftar</a>
                    @endguest
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard', ['tab' => 'my-listing']) }}" class="hidden sm:inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-full bg-[#f5a844] text-[#1e2a44] text-sm font-bold hover:bg-[#f5b867] transition" title="Tambah Listing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                            <span>Add</span>
                        </a>
                        <a href="{{ route('dashboard', ['tab' => 'my-listing']) }}" class="sm:hidden inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-full bg-[#f5a844] text-[#1e2a44] font-bold hover:bg-[#f5b867] transition" title="Tambah Listing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    @endif
                    <a href="{{ auth()->check() ? (auth()->user()->role === 'admin' ? route('dashboard') : route('profile.edit')) : route('login') }}" class="text-white/90 hover:text-white" title="{{ auth()->check() && auth()->user()->role === 'admin' ? 'Dashboard Admin' : 'Akun' }}">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><circle cx="12" cy="9" r="3.2"/><path d="M5.5 19.5c1.2-3 3.6-4.5 6.5-4.5s5.3 1.5 6.5 4.5"/>
                        </svg>
                    </a>
                </div>
            </div>

            <form action="{{ route('beranda') }}" method="GET" class="sm:hidden pb-3">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                </div>
            </form>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 bg-gray-100">
        
        <!-- CAROUSEL BANNER PROMO -->
        <!-- CAROUSEL BANNER PROMO (Slide Geser ke Kiri) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div x-data="{ 
                    activeSlide: 0, 
                    totalSlides: 3,
                    timer: null,
                    init() {
                        this.timer = setInterval(() => {
                            this.next();
                        }, 4000); // Berganti tiap 4 detik
                    },
                    next() {
                        this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                    },
                    prev() {
                        this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                    }
                }" 
                class="relative rounded-2xl overflow-hidden bg-[#1e2a44] shadow-md group">
                
                <!-- Track Pembungkus Gambar (Menggeser ke Kiri) -->
                <div class="flex transition-transform duration-700 ease-in-out"
                    :style="'transform: translateX(-' + (activeSlide * 100) + '%);'">
                    
                    <!-- Foto 1 -->
                    <div class="w-full shrink-0">
                        <img src="{{ asset('img/beranda/banner1.png') }}" alt="Promo 1" class="w-full h-auto block object-cover">
                    </div>

                    <!-- Foto 2 (Sesuaikan nama file gambar ke-2 kamu) -->
                    <div class="w-full shrink-0">
                        <img src="{{ asset('img/beranda/banner2.png') }}" alt="Promo 2" class="w-full h-auto block object-cover">
                    </div>

                    <!-- Foto 3 (Sesuaikan nama file gambar ke-3 kamu) -->
                    <div class="w-full shrink-0">
                        <img src="{{ asset('img/beranda/banner3.png') }}" alt="Promo 3" class="w-full h-auto block object-cover">
                    </div>

                </div>

                <!-- Tombol Navigasi Kiri & Kanan -->
                <button @click="prev()" 
                        class="absolute left-3 top-1/2 -translate-y-1/2 p-2 bg-black/40 hover:bg-black/70 text-white rounded-full transition opacity-0 group-hover:opacity-100 z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click="next()" 
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-2 bg-black/40 hover:bg-black/70 text-white rounded-full transition opacity-0 group-hover:opacity-100 z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>

                <!-- Indicator Dots Bawah -->
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <button @click="activeSlide = 0" :class="activeSlide === 0 ? 'bg-white w-6' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                    <button @click="activeSlide = 1" :class="activeSlide === 1 ? 'bg-white w-6' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                    <button @click="activeSlide = 2" :class="activeSlide === 2 ? 'bg-white w-6' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-lg sm:text-xl font-semibold mb-4 text-gray-900">
                @if($search !== '') Hasil pencarian: "{{ $search }}" @else Pilih Game @endif
            </h2>
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
                @if (count($games) === 0)
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p class="text-base font-medium">Game tidak ditemukan</p>
                        <a href="{{ route('beranda') }}" class="mt-2 inline-block text-sm text-[#1e2a44] underline">Reset pencarian</a>
                    </div>
                @endif
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1e2a44] shrink-0 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-white/70 text-sm">
            &copy; {{ date('Y') }} ZERO SHOP. Semua hak dilindungi.
        </div>
    </footer>

</body>
</html>