<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $game['name'] }} - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
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
                    <input type="text" name="search" placeholder="Cari game..." class="w-full rounded-full bg-white/10 text-white placeholder-white/60 pl-10 pr-4 py-2 text-sm border-0 focus:ring-2 focus:ring-white/40">
                </div>
            </form>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 bg-white" x-data="{
            tab: null,
            cat: null,
            labels: @js($kategorijoki),
            init() {
                const p = new URLSearchParams(location.search);
                this.tab = p.get('tab') === 'joki' ? 'joki' : 'account';
                this.cat = p.get('cat');
                this.$watch('tab', () => this.sync());
                this.$watch('cat', () => this.sync());
                window.addEventListener('popstate', () => {
                    const p = new URLSearchParams(location.search);
                    this.tab = p.get('tab') === 'joki' ? 'joki' : 'account';
                    this.cat = p.get('cat');
                });
            },
            sync() {
                const u = new URL(location.href);
                u.searchParams.set('tab', this.tab);
                if (this.cat) { u.searchParams.set('cat', this.cat); } else { u.searchParams.delete('cat'); }
                history.pushState(null, '', u);
            }
        }">

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
                <a href="{{ route('beranda') }}" class="hover:text-[#1e2a44] transition" title="Beranda">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>
                    </svg>
                </a>
                <span>&gt;</span>
                <a href="{{ route('beranda') }}" class="hover:text-[#1e2a44] transition truncate">{{ $game['name'] }}</a>
                <span>&gt;</span>
                <span class="font-semibold text-[#1e2a44] whitespace-nowrap" x-text="(tab === 'account' ? 'Account' : 'Service Piloting') + (tab === 'joki' && cat ? '  ›  ' + labels[cat] : '')"></span>
            </nav>

            <!-- MENU TAB: ACCOUNT / SERVICE PILOTING -->
            <div class="flex gap-1.5">
                <button type="button" @click.prevent="tab = 'account'; cat = null"
                        class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-semibold transition border border-transparent"
                        :class="tab === 'account' ? 'bg-[#1e2a44] text-white' : 'bg-[#1e2a44]/60 text-white hover:bg-[#1e2a44]/90'">
                    Account
                </button>
                <button type="button" @click.prevent="tab = 'joki'; cat = null"
                        class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-semibold transition border border-transparent"
                        :class="tab === 'joki' ? 'bg-[#1e2a44] text-white' : 'bg-[#1e2a44]/60 text-white hover:bg-[#1e2a44]/90'">
                    Service Piloting
                </button>
            </div>

            <!-- TAB: AKUN GAME -->
            <section x-show="tab === 'account'" class="space-y-4">

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($akun as $a)
                        <a href="{{ route('game.akun.show', ['slug' => $slug, 'akun' => $a->id]) }}" class="group rounded-lg overflow-hidden bg-[#1e2a44] shadow hover:shadow-lg transition duration-200 flex flex-col">
                            <div class="aspect-video overflow-hidden bg-[#2a3a5c]">
                                @if ($a->gambar)
                                    <img src="{{ asset($a->gambar) }}" alt="{{ $a->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/40 text-xs font-semibold">AKUN</div>
                                @endif
                            </div>
                            <div class="p-2 flex flex-col gap-1 flex-1">
                                <p class="text-white text-[11px] sm:text-xs font-medium line-clamp-2 flex-1">{{ $a->judul }}</p>
                                <span class="text-white text-xs sm:text-sm font-semibold">Rp {{ number_format($a->harga, 0, ',', '.') }}</span>
                            </div>
                        </a>
                    @endforeach

                    @if (count($akun) === 0)
                        <div class="col-span-full rounded-lg border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500">
                            Belum ada akun {{ $game['name'] }} yang tersedia.
                        </div>
                    @endif
                </div>
            </section>

            <!-- TAB: JOKI (SERVICE PILOTING) -->
            <section x-show="tab === 'joki'" x-cloak class="space-y-4">

                <!-- GRID KATEGORI -->
                <div x-show="cat === null">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                        @foreach ($kategorijoki as $key => $label)
                            <button type="button" @click.prevent="cat = '{{ $key }}'"
                                    class="group rounded-lg overflow-hidden bg-[#1e2a44] shadow hover:shadow-lg transition duration-200 flex flex-col text-left">
                                <div class="aspect-video bg-[#2a3a5c] flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white/50 group-hover:text-[#f5a844] group-hover:scale-110 transition duration-200" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 12h4m-2-2v4m7.5-1.5h.01M18 10.5h.01"/><path d="M17.32 5H6.68a4 4 0 0 0-3.978 3.59c-.006.052-.01.4-.016.4-.016.122-.01.247-.016.37-.006.185-.006.367-.016.554-.006.174-.016.35-.016.524v2.526a4 4 0 0 0 1.284 2.936A4 4 0 0 0 7.08 19h9.84a4 4 0 0 0 3.98-3.59l.01-.455c.007-.062.013-.126.017-.19.016-.125.01-.25.016-.374.005-.184.005-.366.015-.553.006-.174.016-.35.016-.524V11.3a4 4 0 0 0-1.285-2.936A4 4 0 0 0 16.92 5Z"/>
                                    </svg>
                                </div>
                                <div class="px-2 py-2">
                                    <span class="text-white text-xs sm:text-sm font-medium">{{ $label }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- DAFTAR PAKET PER KATEGORI -->
                @foreach ($kategorijoki as $key => $label)
                    <div x-show="cat === '{{ $key }}'" class="space-y-3">
                        <div class="flex items-center gap-3">
                            <button type="button" @click.prevent="cat = null"
                                    class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-[#1e2a44] hover:text-[#f5a844] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
                                Semua kategori
                            </button>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $label }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse ($paket->where('kategori', $key) as $p)
                                <a href="{{ route('game.joki.show', ['slug' => $slug, 'kategori' => $key]) }}" class="rounded-lg overflow-hidden bg-[#1e2a44] shadow hover:shadow-lg transition duration-200 flex flex-col">
                                    <div class="p-4 flex flex-col gap-2 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="text-white text-sm font-semibold">{{ $p->nama_paket }}</p>
                                            <span class="shrink-0 text-[#f5a844] text-sm font-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                        </div>
                                        @if ($p->estimasi_waktu)
                                            <span class="inline-flex w-max items-center rounded-full border border-white/30 px-2 py-0.5 text-[11px] text-white/80">Estimasi {{ $p->estimasi_waktu }}</span>
                                        @endif
                                        <p class="text-white/70 text-xs leading-relaxed flex-1">{{ $p->deskripsi }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full rounded-lg border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500">
                                    Belum ada paket {{ $label }} untuk {{ $game['name'] }}.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </section>

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
