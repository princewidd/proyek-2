<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tabs[$tab] }} - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans bg-white text-gray-900 antialiased min-h-full flex flex-col" x-data="{
    tab: @js($tab),
    search: '',
    gameFilter: '',
    statusFilter: '',
    showFilters: false
}">

    <!-- NAVBAR -->
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-sm shrink-0">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3 h-14">

            <a href="{{ route('beranda') }}" class="text-white/80 hover:text-white transition -ml-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <a href="{{ route('beranda') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('img/logoLR.png') }}" class="h-9 w-9 rounded-full object-cover">
                <span class="text-white font-bold text-lg tracking-wide">ZERO SHOP</span>
            </a>

            <div class="flex-1"></div>

            <a href="{{ route('admin.chat') }}" title="Inbox Chat"
                class="text-white/80 hover:text-white p-1 relative">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M8 9h8m-9.5 11L4 21V5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6.5z" />
                </svg>
            </a>

            <a href="{{ route('admin.akun.create') }}"
                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-[#f5a844] text-[#1e2a44] text-sm font-bold hover:bg-[#f5b867] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add
            </a>

            <a href="{{ route('profile.edit') }}" class="text-white/80 hover:text-white p-1">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10" />
                    <circle cx="12" cy="9" r="3.2" />
                    <path d="M5.5 19.5c1.2-3 3.6-4.5 6.5-4.5s5.3 1.5 6.5 4.5" />
                </svg>
            </a>
        </div>
    </header>

    <main class="flex-1 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-4">

            <!-- KARTU SELLER -->
            <div class="rounded-xl bg-[#1e2a44] shadow-md p-4 sm:p-5">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ asset('img/logoLR.png') }}"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-[#f5a844]/60">
                        <div>
                            <p class="text-sm sm:text-base font-bold text-white">ZeroShop</p>
                            <p class="text-[11px] sm:text-xs text-white/60">Seller Tiers: Gold</p>
                        </div>
                    </div>

                    <div class="flex-1"></div>

                    <div class="sm:text-right">
                        <p class="text-[11px] text-white/60">Rating</p>
                        <p class="text-base sm:text-lg font-bold text-[#f5a844]">5.0</p>
                    </div>
                </div>

                <!-- MENU -->
                <div class="mt-4 overflow-x-auto">
                    <div class="flex justify-center gap-1.5 rounded-xl bg-white/10 p-1.5 w-max min-w-full mx-auto">

                        @foreach ([
        'my-listing' => 'My Listing',
        'dashboard' => 'Dashboard',
        'payout' => 'Payout Options',
        'purchases' => 'My Purchases',
        'favourites' => 'My Favourites',
        'reviews' => 'My Reviews',
    ] as $key => $label)
                            <button type="button"
                                @click.prevent="
                                    tab = '{{ $key }}';
                                    const u = new URL(location.href);
                                    u.searchParams.set('tab', '{{ $key }}');
                                    history.replaceState(null, '', u);
                                "
                                class="shrink-0 whitespace-nowrap text-center text-[11px] sm:text-xs px-3 py-1.5 rounded-lg font-semibold transition"
                                :class="tab === '{{ $key }}'
                                    ?
                                    'text-[#1e2a44] bg-[#f5a844]' :
                                    'text-white/70 hover:text-white hover:bg-white/10'">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- NOTIFIKASI -->
            @if (session('success'))
                <div class="rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                    <p class="font-semibold mb-1">Periksa kembali input lu:</p>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- MY LISTING -->
            <section x-show="tab === 'my-listing'" x-cloak>

                <!-- SEARCH DAN FILTER -->
                <div class="flex flex-col sm:flex-row gap-2 mb-4">

                    <input type="text" x-model="search" placeholder="Cari nama akun atau game..."
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-[#f5a844] focus:outline-none">

                    <button @click="showFilters = !showFilters"
                        class="px-4 py-2 rounded-lg bg-[#1e2a44] text-white text-sm font-semibold">
                        Filters
                    </button>
                </div>

                <div x-show="showFilters" x-cloak class="rounded-xl bg-gray-100 p-4 mb-4 space-y-3">

                    <div>
                        <label class="text-xs font-semibold text-gray-600">Game</label>
                        <select x-model="gameFilter" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                            <option value="">Semua Game</option>
                            @foreach ($listingAkun->pluck('game')->unique()->sort() as $game)
                                <option value="{{ $game }}">{{ $game }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600">Status</label>
                        <select x-model="statusFilter" class="w-full mt-1 rounded-lg border-gray-300 text-sm">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="terjual">Terjual</option>
                        </select>
                    </div>

                    <button @click="search = ''; gameFilter = ''; statusFilter = ''"
                        class="text-sm text-red-600 font-semibold">
                        Reset Filter
                    </button>
                </div>

                <!-- DAFTAR AKUN -->
                <div class="space-y-3">

                    @forelse ($listingAkun as $listing)
                        <div x-data="{
                            openEdit: false,
                            searchText: @js(strtolower($listing->judul . ' ' . $listing->game)),
                            gameText: @js($listing->game),
                            statusText: @js($listing->status)
                        }"
                            x-show="
                                (!search || searchText.includes(search.toLowerCase()))
&&
                                (!gameFilter || gameFilter === gameText) &&
                                (!statusFilter || statusFilter === statusText)
                            "
                            class="rounded-xl bg-[#1e2a44] p-3 sm:p-4 shadow-sm">

                            <div class="flex items-center gap-3 sm:gap-4">

                                <div class="w-14 h-12 sm:w-16 sm:h-14 rounded-lg overflow-hidden bg-[#2a3a5c] shrink-0">

                                    @if ($listing->gambar)
                                        <img src="{{ str_starts_with($listing->gambar, 'akun-game/')
                                            ? asset('storage/' . $listing->gambar)
                                            : asset($listing->gambar) }}"
                                            class="w-full h-full object-cover" alt="{{ $listing->judul }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-[10px] text-white/50 font-semibold">AKUN</span>
                                        </div>
                                    @endif

                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-white line-clamp-2">
                                        {{ $listing->judul }}
                                    </p>
                                    <p class="text-xs text-white/60 truncate mt-0.5">
                                        Accounts / {{ $listing->game }}
                                        {{ $listing->server ? '• ' . $listing->server : '' }}
                                    </p>
                                    <p class="text-xs text-white/50 mt-1">
                                        {{ $listing->created_at->translatedFormat('d F Y') }}
                                    </p>
                                </div>

                                <div class="shrink-0 text-right">
                                    <p class="text-[11px] text-white/60">Price</p>
                                    <p class="text-sm font-semibold text-white">
                                        Rp {{ number_format($listing->harga, 0, ',', '.') }}
                                    </p>

                                    <span
                                        class="inline-flex mt-1 px-2 py-1 rounded-full text-[10px] font-semibold
                                        {{ $listing->status === 'tersedia'
                                            ? 'text-[#f5a844] border border-[#f5a844]/50'
                                            : 'text-white/60 border border-white/30' }}">
                                        {{ $listing->status === 'tersedia' ? 'Available' : ucfirst($listing->status) }}
                                    </span>
                                </div>

                                <!-- TOMBOL EDIT -->
                                <!-- TOMBOL EDIT -->
                                <a href="{{ route('admin.akun.edit', $listing->id) }}"
                                    class="text-[#f5a844] hover:text-white p-2 rounded-lg hover:bg-white/10 transition"
                                    title="Edit akun">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4Z" />
                                    </svg>
                                </a>

                                <!-- TOMBOL DELETE -->
                                <form action="{{ route('admin.akun.destroy', $listing->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" style="color: #f87171;"
                                        class="p-2 rounded-lg hover:bg-red-500/15 hover:text-red-400 transition"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                            </div>

                            <!-- MODAL EDIT -->
                            <div x-show="openEdit" x-cloak @keydown.escape.window="openEdit = false"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                                @click.self="openEdit = false">
                                <div
                                    class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-5">

                                    <div class="flex justify-between items-center mb-5">
                                        <h2 class="text-lg font-bold text-[#1e2a44]">Edit Akun Game</h2>
                                        <button @click="openEdit = false" class="text-gray-500 text-xl">✕</button>
                                    </div>

                                    <form action="{{ route('admin.akun.update', $listing->id) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="text-sm font-semibold">Nama Game</label>
                                            <input type="text" name="game" value="{{ $listing->game }}"
                                                required class="w-full rounded-lg border-gray-300 mt-1">
                                        </div>

                                        <div>
                                            <label class="text-sm font-semibold">Judul Akun</label>
                                            <input type="text" name="judul" value="{{ $listing->judul }}"
                                                required class="w-full rounded-lg border-gray-300 mt-1">
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="text-sm font-semibold">Harga</label>
                                                <input type="number" name="harga" value="{{ $listing->harga }}"
                                                    min="0" required
                                                    class="w-full rounded-lg border-gray-300 mt-1">
                                            </div>

                                            <div>
                                                <label class="text-sm font-semibold">Server</label>
                                                <input type="text" name="server" value="{{ $listing->server }}"
                                                    placeholder="Asia / Global"
                                                    class="w-full rounded-lg border-gray-300 mt-1">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="text-sm font-semibold">Status</label>
                                            <select name="status" required
                                                class="w-full rounded-lg border-gray-300 mt-1">
                                                <option value="tersedia" @selected($listing->status === 'tersedia')>Tersedia
                                                </option>
                                                <option value="terjual" @selected($listing->status === 'terjual')>Terjual
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="text-sm font-semibold">Deskripsi</label>
                                            <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 mt-1">{{ $listing->deskripsi }}</textarea>
                                        </div>

                                        <div>
                                            <label class="text-sm font-semibold">Ganti Gambar Utama</label>
                                            <input type="file" name="gambar"
                                                accept="image/jpeg,image/png,image/webp" class="w-full text-sm mt-1">
                                        </div>

                                        <div class="flex justify-end gap-2 pt-3">
                                            <button type="button" @click="openEdit = false"
                                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm">
                                                Batal
                                            </button>

                                            <button type="submit"
                                                class="px-4 py-2 rounded-lg bg-[#1e2a44] text-white text-sm font-semibold">
                                                Simpan Perubahan
                                            </button>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>

                    @empty
                        <div
                            class="rounded-xl border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500">
                            Belum ada akun game yang terdaftar.
                        </div>
                    @endforelse

                </div>

                <!-- PAKET JOKI -->
                <div style="margin-top: 28px;">
                    <p style="margin-bottom: 12px;"
                        class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                        Paket Joki
                    </p>

                    <div class="space-y-3">

                        @forelse ($listingJoki as $listing)
                            <div class="flex items-center gap-3 sm:gap-4 rounded-xl bg-[#1e2a44] p-3 sm:p-4 shadow-sm">

                                <div
                                    class="w-14 h-12 sm:w-16 sm:h-14 rounded-lg bg-[#2a3a5c] shrink-0 flex items-center justify-center">
                                    <span class="text-[10px] text-white/50 font-semibold">JOKI</span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-white">{{ $listing->nama_paket }}</p>
                                    <p class="text-xs text-white/60">Joki / {{ $listing->game }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-[11px] text-white/60">Price</p>
                                    <p class="text-sm font-semibold text-white">
                                        Rp {{ number_format($listing->harga, 0, ',', '.') }}
                                    </p>
                                </div>

                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-semibold text-[#f5a844] border border-[#f5a844]/50">
                                    Available
                                </span>

                            </div>

                        @empty
                            <div
                                class="rounded-xl border border-dashed border-gray-300 p-10 text-center text-sm text-gray-500">
                                Belum ada paket joki yang terdaftar.
                            </div>
                        @endforelse

                    </div>
                </div>
            </section>

            <!-- DASHBOARD -->
            <section x-show="tab === 'dashboard'" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs text-white/70">Pesanan Menunggu</p>
                        <p class="mt-1 text-3xl font-bold text-[#f5a844]">
                            {{ $pesanan->where('status', 'pending')->count() }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs text-[#f5a844]">Pesanan Aktif</p>
                        <p class="mt-1 text-3xl font-bold text-white">
                            {{ $pesanan->where('status', 'proses')->count() }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs text-white/70">Pesanan Selesai</p>
                        <p class="mt-1 text-3xl font-bold text-white">
                            {{ $pesanan->where('status', 'selesai')->count() }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs text-white/70">Total Akun</p>
                        <p class="mt-1 text-3xl font-bold text-white">
                            {{ $totalAkun }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs text-white/70">Akun Tersedia</p>
                        <p class="mt-1 text-3xl font-bold text-[#f5a844]">
                            {{ $akunTersedia }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-[#1e2a44] p-5 shadow-md">
                        <p class="text-xs text-white/70">Paket Joki</p>
                        <p class="mt-1 text-3xl font-bold text-white">
                            {{ $totalPaketJoki }}
                        </p>
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
                                <p class="text-sm font-semibold text-white">
                                    {{ $p->paketJoki?->nama_paket ?? 'Paket terhapus' }}
                                </p>
                                <p class="text-xs text-white/60">{{ $p->email_akun }}</p>
                            </div>

                            <div class="text-xs text-white/60">
                                {{ $p->created_at->translatedFormat('d F Y') }}
                            </div>

                            <span
                                class="px-3 py-1 rounded-full text-[11px] font-semibold text-white border border-white/40">
                                {{ ucfirst($p->status) }}
                            </span>

                        </div>

                    @empty
                        <div
                            class="rounded-xl border border-dashed border-gray-300 p-14 text-center text-sm text-gray-500">
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
