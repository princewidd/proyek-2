<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tabs[$tab] }} - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans bg-white text-gray-900 antialiased min-h-full flex flex-col">

    <!-- NAVBAR HEADER -->
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-sm shrink-0">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3 h-14">
            <a href="{{ route('beranda') }}" class="text-white/80 hover:text-white transition -ml-1" title="Kembali ke Beranda">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
            </a>
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 shrink-0 group">
                <img src="{{ asset('img/logoLR.png') }}" alt="ZERO SHOP" class="h-9 w-9 rounded-full object-cover group-hover:opacity-80 transition">
                <span class="text-white font-bold text-lg tracking-wide group-hover:opacity-80 transition">ZERO SHOP</span>
            </a>
            <div class="flex-1"></div>
            <a href="{{ route('dashboard', ['tab' => 'my-listing']) }}" @click.prevent="tab = 'my-listing'" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#f5a844] text-[#1e2a44] text-sm font-bold hover:bg-[#f5b867] transition" title="Tambah Listing">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                <span>Add</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="text-white/80 hover:text-white p-1">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><circle cx="12" cy="9" r="3.2"/><path d="M5.5 19.5c1.2-3 3.6-4.5 6.5-4.5s5.3 1.5 6.5 4.5"/>
                </svg>
            </a>
        </div>
    </header>

    <main class="flex-1 bg-white" x-data="{ tab: '{{ $tab }}' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">

            <!-- KARTU SELLER -->
            <div class="rounded-xl bg-[#1e2a44] shadow-md p-4 sm:p-5">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ asset('img/logoLR.png') }}" alt="ZeroShop" class="w-10 h-10 rounded-full object-cover ring-2 ring-[#f5a844]/60">
                        <div class="min-w-0">
                            <p class="text-sm sm:text-base font-bold text-white truncate">ZeroShop</p>
                            <p class="text-[11px] sm:text-xs text-white/60">Seller Tiers: Gold</p>
                        </div>
                    </div>
                    <div class="flex-1"></div>
                    <div class="sm:text-right">
                        <p class="text-[11px] text-white/60">Rating</p>
                        <p class="text-base sm:text-lg font-bold text-[#f5a844]">5.0</p>
                    </div>
                </div>

                <!-- MENU NAVIGASI -->
                <div class="mt-4 overflow-x-auto">
                    <div class="flex justify-center gap-1.5 rounded-xl bg-white/10 p-1.5 w-max min-w-full mx-auto">
                        @foreach (['my-listing' => 'My Listing', 'dashboard' => 'Dashboard', 'payout' => 'Payout Options', 'purchases' => 'My Purchases', 'favourites' => 'My Favourites', 'reviews' => 'My Reviews'] as $key => $label)
                            <button type="button"
                                    @click.prevent="tab = '{{ $key }}'; const u = new URL(location.href); u.searchParams.set('tab', '{{ $key }}'); history.replaceState(null, '', u)"
                                    class="shrink-0 whitespace-nowrap text-center text-[11px] sm:text-xs px-3 py-1.5 rounded-lg font-semibold transition"
                                    :class="tab === '{{ $key }}' ? 'text-[#1e2a44] bg-[#f5a844]' : 'text-white/70 hover:text-white hover:bg-white/10'">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MY LISTING -->
            <section x-show="tab === 'my-listing'" x-cloak>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex-1 border-t border-gray-200 hidden sm:block"></div>
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#1e2a44] text-white text-xs font-semibold hover:bg-[#2a3a5c] transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"></path></svg>
                        <span>Filters</span>
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse ($listingAkun as $listing)
                        <div class="flex items-center gap-3 sm:gap-4 rounded-xl bg-[#1e2a44] p-3 sm:p-4 shadow-sm">
                            <input type="checkbox" class="w-4 h-4 rounded border-white/40 accent-[#f5a844] shrink-0 cursor-pointer">

                            <div class="w-14 h-12 sm:w-16 sm:h-14 rounded-lg overflow-hidden bg-[#2a3a5c] shrink-0">
                                @if ($listing->gambar)
                                    <img src="{{ asset($listing->gambar) }}" alt="{{ $listing->judul }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><span class="text-[10px] text-white/50 font-semibold">AKUN</span></div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white line-clamp-2">{{ $listing->judul }}</p>
                                <p class="text-xs text-white/60 truncate mt-0.5">Accounts / {{ $listing->game }}</p>
                            </div>

                            <div class="hidden md:block shrink-0 w-40">
                                <p class="text-xs text-white/60">Labels / {{ $listing->game }}</p>
                                <p class="text-xs text-white mt-0.5">{{ $listing->created_at->translatedFormat('d F Y') }}</p>
                            </div>

                            <div class="shrink-0 text-right">
                                <p class="text-[11px] text-white/60">Price</p>
                                <p class="text-sm font-semibold text-white">Rp {{ number_format($listing->harga, 0, ',', '.') }}</p>
                            </div>

                            <span class="shrink-0 inline-flex px-3 py-1 rounded-full text-[11px] font-semibold {{ $listing->status === 'tersedia' ? 'text-[#f5a844] border border-[#f5a844]/50' : 'text-white/60 border border-white/30' }}">
                                {{ $listing->status === 'tersedia' ? 'Available' : ucfirst($listing->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500">
                            Belum ada akun game yang dititipin.
                        </div>
                    @endforelse
                </div>

                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide pt-2">Paket Joki</p>
                <div class="space-y-3">
                    @forelse ($listingJoki as $listing)
                        <div class="flex items-center gap-3 sm:gap-4 rounded-xl bg-[#1e2a44] p-3 sm:p-4 shadow-sm">
                            <input type="checkbox" class="w-4 h-4 rounded border-white/40 accent-[#f5a844] shrink-0 cursor-pointer">

                            <div class="w-14 h-12 sm:w-16 sm:h-14 rounded-lg overflow-hidden bg-[#2a3a5c] shrink-0 flex items-center justify-center">
                                <span class="text-[10px] text-white/50 font-semibold">JOKI</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white line-clamp-2">{{ $listing->nama_paket }}</p>
                                <p class="text-xs text-white/60 truncate mt-0.5">Joki / {{ $listing->game }}</p>
                            </div>

                            <div class="hidden md:block shrink-0 w-40">
                                <p class="text-xs text-white/60">Estimasi</p>
                                <p class="text-xs text-white mt-0.5">{{ $listing->estimasi_waktu ?? '-' }}</p>
                            </div>

                            <div class="shrink-0 text-right">
                                <p class="text-[11px] text-white/60">Price</p>
                                <p class="text-sm font-semibold text-white">Rp {{ number_format($listing->harga, 0, ',', '.') }}</p>
                            </div>

                            <span class="shrink-0 inline-flex px-3 py-1 rounded-full text-[11px] font-semibold text-[#f5a844] border border-[#f5a844]/50">
                                Available
                            </span>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500">
                            Belum ada paket joki yang terdaftar.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- DASHBOARD -->
            <section x-show="tab === 'dashboard'" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs font-medium text-white/70">Pesanan Menunggu</p>
                        <p class="mt-1 text-3xl font-bold text-[#f5a844]">{{ $pesanan->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs font-medium text-[#f5a844]">Pesanan Aktif</p>
                        <p class="mt-1 text-3xl font-bold text-white">{{ $pesanan->where('status', 'proses')->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs font-medium text-white/70">Pesanan Selesai</p>
                        <p class="mt-1 text-3xl font-bold text-white">{{ $pesanan->where('status', 'selesai')->count() }}</p>
                    </div>
                </div>
            </section>

            <!-- PAYOUT -->
            <section x-show="tab === 'payout'" x-cloak>
                <div class="rounded-xl border border-dashed border-gray-300 p-14 text-center text-sm text-gray-500">
                    Belum ada opsi payout yang tersedia.
                </div>
            </section>

            <!-- MY PURCHASES -->
            <section x-show="tab === 'purchases'" x-cloak>
                <div class="space-y-3">
                    @forelse ($pesanan as $p)
                        <div class="flex items-center gap-3 sm:gap-4 rounded-xl bg-[#1e2a44] p-3 sm:p-4 shadow-sm">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ $p->paketJoki?->nama_paket ?? 'Paket terhapus' }}</p>
                                <p class="text-xs text-white/60 truncate mt-0.5">{{ $p->email_akun }}</p>
                            </div>
                            <div class="hidden md:block text-xs text-white/60 shrink-0">
                                {{ $p->created_at->translatedFormat('d F Y') }}
                            </div>
                            <span class="shrink-0 inline-flex px-3 py-1 rounded-full text-[11px] font-semibold {{ $p->status === 'pending' ? 'text-[#f5a844] border border-[#f5a844]/50' : ($p->status === 'selesai' ? 'text-emerald-400 border border-emerald-400/50' : 'text-white border border-white/40') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-14 text-center text-sm text-gray-500">
                            Belum ada pembelian.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- FAVOURITES -->
            <section x-show="tab === 'favourites'" x-cloak>
                <div class="rounded-xl border border-dashed border-gray-300 p-14 text-center text-sm text-gray-500">
                    Belum ada listing favorit tersimpan.
                </div>
            </section>

            <!-- REVIEWS -->
            <section x-show="tab === 'reviews'" x-cloak>
                <div class="rounded-xl border border-dashed border-gray-300 p-14 text-center text-sm text-gray-500">
                    Belum ada review.
                </div>
            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1e2a44] shrink-0">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-white/70 text-xs">
            &copy; {{ date('Y') }} ZERO SHOP Admin. Semua hak dilindungi.
        </div>
    </footer>

</body>
</html>
