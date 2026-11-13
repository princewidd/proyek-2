<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \Illuminate\Support\Str::limit($akunGame->judul, 40) }} - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-white text-gray-900 antialiased h-full flex flex-col" x-data="{ pay: false }">

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

            <!-- BREADCRUMB -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-700">
                <a href="{{ route('beranda') }}" class="hover:text-[#1e2a44] transition shrink-0" title="Beranda">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>
                    </svg>
                </a>
                <span class="shrink-0">&gt;</span>
                <a href="{{ route('game.show', $slug) }}" class="hover:text-[#1e2a44] transition truncate">{{ $game['name'] }}</a>
                <span class="shrink-0">&gt;</span>
                <a href="{{ route('game.show', $slug) }}" class="hover:text-[#1e2a44] transition shrink-0">Account</a>
                <span class="shrink-0">&gt;</span>
                <span class="font-semibold text-[#1e2a44] truncate" title="{{ $akunGame->judul }}">{{ \Illuminate\Support\Str::limit($akunGame->judul, 40) }}</span>
            </nav>

            <!-- TABS -->
            <div class="flex gap-3">
                <a href="{{ route('game.show', $slug) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-xs sm:text-sm font-semibold bg-[#1e2a44] text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><circle cx="12" cy="9.5" r="3"/><path d="M5.5 19c1.5-3.5 4-5 6.5-5s5 1.5 6.5 5"/>
                    </svg>
                    Account
                </a>
                <a href="{{ route('game.show', ['slug' => $slug, 'tab' => 'joki']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-xs sm:text-sm font-semibold bg-[#9096a5] text-white hover:bg-[#7d8393] transition">
                    Service Piloting
                </a>
            </div>

            <!-- DETAIL PRODUK -->
            <section class="grid grid-cols-1 lg:grid-cols-[minmax(0,560px)_minmax(0,1fr)] gap-6 lg:gap-10 pb-10">
                <!-- MEDIA -->
                <div class="relative rounded-lg overflow-hidden bg-[#172038] aspect-[745/700] lg:mx-0 mx-auto w-full lg:w-auto max-w-[560px]">
                    @if ($akunGame->gambar)
                        <img src="{{ asset($akunGame->gambar) }}" alt="{{ $akunGame->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white/40 text-sm font-semibold">AKUN</div>
                    @endif
                    @if ($akunGame->deskripsi)
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 via-black/55 to-transparent p-4 sm:p-5 pt-12">
                            <p class="text-white text-sm sm:text-lg leading-relaxed">{{ $akunGame->deskripsi }}</p>
                        </div>
                    @endif
                </div>

                <!-- KARTU PRODUK -->
                <aside class="rounded-lg bg-[#1e2a44] p-5 sm:p-6 h-max">
                    <h1 class="text-white text-lg sm:text-2xl font-medium leading-snug">{{ $akunGame->judul }}</h1>
                    @if ($akunGame->deskripsi)
                        <p class="text-white/70 text-xs sm:text-sm leading-relaxed mt-3">{{ $akunGame->deskripsi }}</p>
                    @endif
                    <p class="text-white text-xl sm:text-3xl font-medium mt-5">Rp {{ number_format($akunGame->harga, 0, ',', '.') }}</p>

                    <div class="flex items-center gap-4 mt-4">
                        <button type="button" class="shrink-0 w-14 h-11 grid place-items-center rounded-md bg-white text-[#1e2a44] hover:bg-gray-200 transition" title="Chat penjual">
                            <svg class="w-6 h-6" viewBox="0 0 30 30" fill="currentColor" aria-hidden="true">
                                <path d="M5 4h20a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H12l-7 5v-5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3z"/>
                                <circle cx="10" cy="13" r="1.8" fill="#fff"/>
                                <circle cx="15" cy="13" r="1.8" fill="#fff"/>
                                <circle cx="20" cy="13" r="1.8" fill="#fff"/>
                            </svg>
                        </button>
                        @guest
                            <a href="{{ route('login') }}" class="w-full max-w-[260px] h-11 grid place-items-center rounded-md bg-white text-gray-900 text-base sm:text-xl font-medium hover:bg-gray-200 transition">Beli Sekarang</a>
                        @else
                            <button type="button" @click="pay = true" class="w-full max-w-[260px] h-11 grid place-items-center rounded-md bg-white text-gray-900 text-base sm:text-xl font-medium hover:bg-gray-200 transition">Beli Sekarang</button>
                        @endguest
                    </div>
                </aside>
            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1e2a44] shrink-0 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-white/70 text-sm">
            &copy; {{ date('Y') }} ZERO SHOP. Semua hak dilindungi.
        </div>
    </footer>

    <!-- MODAL PEMBAYARAN -->
    <div x-show="pay" x-cloak class="fixed inset-0 z-50 grid place-items-center p-4" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/50" @click="pay = false"></div>

        <form method="POST" action="{{ route('beli.akun.store', $akunGame) }}"
              x-show="pay" x-transition.scale.origin.center.duration.200ms
              class="relative w-full max-w-2xl max-h-[calc(100vh-32px)] overflow-auto bg-[#1e2a44] text-white border border-white rounded-lg">

            @csrf
            <!-- HEADER -->
            <header class="flex items-center gap-4 px-5 sm:px-7 py-4 border-b-[3px] border-white">
                <img src="{{ asset('img/beranda/zeroshop-white-49a3bd4-1.png') }}" alt="ZeroShop" class="w-11 h-11 rounded-lg object-cover shrink-0">
                <h2 class="text-xl sm:text-2xl font-medium">ZeroShop</h2>
                <button type="button" @click="pay = false" aria-label="Tutup" class="ml-auto w-10 h-10 text-3xl leading-none rounded-md hover:bg-white/10 transition">&times;</button>
            </header>

            <!-- BODY -->
            <div class="px-5 sm:px-9 py-5 space-y-4">
                <!-- PRODUK -->
                <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-7 border border-white rounded-xl p-4">
                    <div class="w-full sm:w-48 h-28 shrink-0 rounded bg-[#172038] overflow-hidden">
                        @if ($akunGame->gambar)
                            <img src="{{ asset($akunGame->gambar) }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="mt-1 text-base sm:text-xl font-medium leading-snug">{{ $akunGame->judul }}</p>
                        <p class="mt-3 text-base sm:text-xl font-medium">Rp {{ number_format($akunGame->harga, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- RINGKASAN -->
                <section class="border border-white rounded-xl px-5 sm:px-8 py-2">
                    <h3 class="text-lg sm:text-xl font-medium">Order Summary</h3>
                    <dl class="mt-1">
                        <div class="flex justify-between items-center py-2.5 text-base sm:text-xl">
                            <dt class="text-[#9aa1b2]">Item price</dt>
                            <dd class="font-medium">Rp {{ number_format($akunGame->harga, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2.5 text-base sm:text-xl border-t border-white/45">
                            <dt class="text-[#9aa1b2]">Discount</dt>
                            <dd class="font-medium">Rp 0</dd>
                        </div>
                        <div class="flex justify-between items-center py-2.5 text-base sm:text-xl border-t border-white/45">
                            <dt class="text-[#9aa1b2]">Subtotal</dt>
                            <dd class="font-medium text-[#f5a742]">Rp {{ number_format($akunGame->harga, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </section>

                <!-- METODE BAYAR -->
                <fieldset class="pl-1">
                    <legend class="text-lg sm:text-xl font-medium">Payment Method</legend>
                    <div class="flex flex-wrap gap-3 sm:gap-6 mt-4">
                        <label class="relative flex items-center justify-center gap-5 sm:gap-8 w-full sm:w-56 h-12 border border-white rounded-md text-lg sm:text-xl font-medium cursor-pointer transition has-[:checked]:bg-white has-[:checked]:text-[#1e2a44] has-[:checked]:shadow-lg">
                            <input type="radio" name="metode" value="qris" class="absolute opacity-0 pointer-events-none" required>
                            <svg class="w-7 h-7" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                <path d="M3 10V4h6M23 4h6v6M29 22v6h-6M9 28H3v-6"/>
                                <rect x="9" y="9" width="5" height="5"/><rect x="18" y="9" width="5" height="5"/>
                                <rect x="9" y="18" width="5" height="5"/><path d="M18 18h5v5"/>
                            </svg>
                            <span>QRIS</span>
                        </label>
                        <label class="relative flex items-center justify-center gap-5 sm:gap-8 w-full sm:w-56 h-12 border border-white rounded-md text-lg sm:text-xl font-medium cursor-pointer transition has-[:checked]:bg-white has-[:checked]:text-[#1e2a44] has-[:checked]:shadow-lg">
                            <input type="radio" name="metode" value="bank" class="absolute opacity-0 pointer-events-none" checked>
                            <svg class="w-7 h-7" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                                <path d="M16 3 3 10v3h26v-3zM6 15h4v9H6zM14 15h4v9h-4zM22 15h4v9h-4zM3 26h26v3H3z"/>
                            </svg>
                            <span>Bank Transfer</span>
                        </label>
                    </div>
                </fieldset>

                <!-- PAY NOW -->
                <button type="submit" class="block w-full h-12 sm:h-13 mt-2 bg-white text-[#1e2a44] text-xl sm:text-2xl font-bold rounded-full hover:bg-gray-200 transition">Pay Now</button>
            </div>
        </form>
    </div>

</body>
</html>
