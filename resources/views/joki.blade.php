<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $labelKategori ?? 'Service Piloting' }} {{ $game['name'] }} - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-white text-gray-900 antialiased h-full flex flex-col">

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
                        <input type="text" name="search" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                    </div>
                </form>

                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm text-white/90 hover:text-white">Masuk</a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-block text-sm bg-white text-[#1e2a44] font-medium px-4 py-1.5 rounded-full hover:bg-white/90">Daftar</a>
                    @endguest
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
                    <input type="text" name="search" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                </div>
            </form>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 bg-white">

        <!-- CAROUSEL BANNER PROMO (Sama seperti beranda) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div x-data="{
                    activeSlide: 0,
                    totalSlides: 3,
                    timer: null,
                    init() {
                        this.timer = setInterval(() => {
                            this.next();
                        }, 4000);
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

                    <!-- Foto 2 -->
                    <div class="w-full shrink-0">
                        <img src="{{ asset('img/beranda/banner2.png') }}" alt="Promo 2" class="w-full h-auto block object-cover">
                    </div>

                    <!-- Foto 3 -->
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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-4">

            <!-- TABS -->
            <div class="flex gap-3">
                <a href="{{ route('game.show', $slug) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-xs sm:text-sm font-semibold bg-[#9096a5] text-white hover:bg-[#7d8393] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><circle cx="12" cy="9.5" r="3"/><path d="M5.5 19c1.5-3.5 4-5 6.5-5s5 1.5 6.5 5"/>
                    </svg>
                    Account
                </a>
                <a href="{{ route('game.show', ['slug' => $slug, 'tab' => 'joki']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-xs sm:text-sm font-semibold bg-[#1e2a44] text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 12h4m-2-2v4m7.5-1.5h.01M18 10.5h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59l-.03.23a4 4 0 0 0-.016.37l-.016.554-.016.524v2.526a4 4 0 0 0 4 4h9.84a4 4 0 0 0 4-4l-.016-.524-.016-.554a4 4 0 0 0-.016-.37l-.03-.23A4 4 0 0 0 17.32 5Z"/>
                    </svg>
                    Service Piloting
                </a>
            </div>

            @if (count($paket) > 0)
                <!-- DETAIL SERVICE -->
                <section class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,480px)] gap-6 lg:gap-10 pb-10"
                         x-data="{ harga: {{ $paket->first()->harga }} }">

                    <!-- KIRI: GAMBAR + JUDUL -->
                    <div>
                        <div class="rounded-md overflow-hidden bg-[#172038] aspect-[667/366]">
                            <img src="{{ asset('img/beranda/' . $game['img']) }}" alt="{{ $game['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <h1 class="text-[#1e2a44] text-2xl sm:text-4xl font-medium mt-5">
                            {{ $labelKategori ?? $game['name'] }} <span class="text-[#9096a5]">— {{ $game['name'] }}</span>
                        </h1>
                        @if ($kategori !== null && $paket->firstWhere('kategori', $kategori)?->deskripsi)
                            <p class="text-gray-600 text-sm leading-relaxed mt-2">{{ $paket->firstWhere('kategori', $kategori)?->deskripsi }}</p>
                        @endif
                    </div>

                    <!-- KANAN: FORM ORDER -->
                    <form class="h-max" onsubmit="return false">
                        <!-- SELLER -->
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('img/beranda/zeroshop-white-49a3bd4-1.png') }}" alt="ZeroShop" class="w-14 h-14 rounded-lg object-cover">
                            <span class="text-gray-900 text-2xl font-bold tracking-wide">ZERO SHOP</span>
                        </div>

                        <!-- PILIH PAKET -->
                        <div class="grid grid-cols-2 gap-3 sm:gap-5 mt-6">
                            @foreach ($paket as $p)
                                <label class="relative flex flex-col rounded-xl border-2 border-[#e7e9ee] bg-[#9096a5] overflow-hidden cursor-pointer transition hover:-translate-y-0.5 has-[:checked]:ring-[3px] has-[:checked]:ring-[#f2ee3a]">
                                    <input type="radio" name="paket" value="{{ $p->id }}" data-harga="{{ $p->harga }}"
                                           @checked($loop->first)
                                           @change="harga = Number($el.dataset.harga)"
                                           class="absolute opacity-0 pointer-events-none">
                                    <span class="px-3 pt-3 pb-1.5 bg-[#1e2a44] text-white text-base sm:text-xl font-medium">{{ $p->nama_paket }}</span>
                                    <span class="px-3 py-2 text-white text-sm sm:text-lg font-medium">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- RINGKASAN -->
                        <div class="mt-7 rounded-xl bg-[#9096a5] p-1">
                            <p class="px-3 pt-2 pb-4 bg-[#1e2a44] text-white text-base sm:text-xl font-medium rounded-t-[10px]" aria-live="polite">
                                Total: <span x-text="'Rp ' + Number(harga).toLocaleString('id-ID')"></span>
                            </p>
                            <div class="flex items-center gap-4 p-2.5">
                                <button type="button" class="shrink-0 w-13 h-10 grid place-items-center rounded-md bg-white text-[#1e2a44] hover:bg-gray-200 transition" title="Chat penjual">
                                    <svg class="w-5 h-5" viewBox="0 0 30 30" fill="currentColor" aria-hidden="true">
                                        <path d="M5 4h20a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H12l-7 5v-5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3z"/>
                                        <circle cx="10" cy="13" r="1.8" fill="#fff"/>
                                        <circle cx="15" cy="13" r="1.8" fill="#fff"/>
                                        <circle cx="20" cy="13" r="1.8" fill="#fff"/>
                                    </svg>
                                </button>
                                @guest
                                    <a href="{{ route('login') }}" class="flex-1 h-10 grid place-items-center rounded-md bg-white text-gray-900 text-base sm:text-xl font-medium hover:bg-gray-200 transition">Beli Sekarang</a>
                                @else
                                    <button type="button" class="flex-1 h-10 grid place-items-center rounded-md bg-white text-gray-900 text-base sm:text-xl font-medium hover:bg-gray-200 transition">Beli Sekarang</button>
                                @endguest
                            </div>
                        </div>
                    </form>
                </section>
            @else
                <div class="rounded-lg border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500">
                    Belum ada paket joki tersedia.
                    <a href="{{ route('game.show', ['slug' => $slug, 'tab' => 'joki']) }}" class="text-[#1e2a44] underline">Kembali</a>
                </div>
            @endif

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1e2a44] shrink-0 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-white/70 text-sm">
            &copy; {{ date('Y') }} ZERO SHOP. Semua hak dilindungi.
        </div>
    </footer>

</body>
</html>
