<!DOCTYPE html>
<html lang="id" class="h-full overflow-y-scroll">

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
        @media (min-width: 768px) {
            .overview-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
            .order-row-grid {
                display: grid;
                grid-template-columns: 185px 140px 1fr 140px 115px 165px;
                gap: 12px;
                align-items: center;
            }
            .balance-row-grid {
                display: grid;
                grid-template-columns: 205px 115px 145px 1fr 155px;
                gap: 14px;
                align-items: center;
            }
        }
        @media (max-width: 767px) {
            .overview-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
            .order-row-grid,
            .balance-row-grid {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body class="font-sans bg-white text-gray-900 antialiased min-h-full flex flex-col" x-data="{
    tab: @js($tab),
    search: '',
    gameFilter: '',
    statusFilter: '',
    showFilters: false,

    dashSubTab: 'order-status',
    orderFilter: 'all',
    orderSearch: '',
    copiedId: '',
    selectedOrder: null,
    orders: @js($allOrders),

    get filteredOrders() {
        return this.orders.filter(o => {
            if (this.orderFilter !== 'all' && o.category !== this.orderFilter) return false;
            if (this.orderSearch.trim() !== '') {
                const q = this.orderSearch.toLowerCase();
                const hay = (o.title + ' ' + o.buyer_name + ' ' + o.buyer_username + ' ' + o.id + ' ' + o.game).toLowerCase();
                if (!hay.includes(q)) return false;
            }
            return true;
        });
    },

    get balanceHistoryItems() {
        return this.orders
            .filter(o => o.category === 'completed')
            .filter(o => {
                if (this.orderSearch.trim() === '') return true;
                const q = this.orderSearch.toLowerCase();
                const hay = (o.title + ' ' + o.id + ' ' + o.game + ' ' + o.date).toLowerCase();
                return hay.includes(q);
            })
            .map(o => ({
                id: o.id,
                date: o.date,
                type: 'Earning',
                amount: o.price,
                source: o.title,
            }));
    },

    copyOrderId(id) {
        if (!id || id === 'N/A') return;
        navigator.clipboard?.writeText(id);
        this.copiedId = id;
        setTimeout(() => {
            if (this.copiedId === id) this.copiedId = '';
        }, 1600);
    },

    formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }
}">

    <!-- NAVBAR -->
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-sm shrink-0">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3 h-14">

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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- KARTU SELLER -->
            <div class="rounded-xl bg-[#1e2a44] shadow-md p-4 sm:p-5">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <img src="{{ asset('img/logoLR.png') }}"
                        style="width: 48px; height: 48px; min-width: 48px; aspect-ratio: 1 / 1; object-fit: contain;" class="rounded-full ring-2 ring-[#f5a844]/70 shrink-0">
                    <div>
                        <p class="text-base sm:text-lg font-bold text-white leading-tight">ZeroShop</p>
                        <p style="margin-top: 3px;" class="text-xs text-white/60">Seller Tiers : Gold</p>
                    </div>
                </div>

                <!-- MENU -->
                <div style="margin-top: 16px;" class="overflow-x-auto">
                    <div class="flex justify-center gap-1.5 rounded-xl bg-white/10 p-1.5 w-max min-w-full mx-auto">

                        @foreach ([
                            'my-listing' => 'My Listing',
                            'dashboard' => 'Dashboard',
                            'reviews' => 'My Reviews',
                        ] as $key => $label)
                            <button type="button"
                                @click.prevent="
                                    tab = '{{ $key }}';
                                    const u = new URL(location.href);
                                    u.searchParams.set('tab', '{{ $key }}');
                                    history.replaceState(null, '', u);
                                "
                                class="shrink-0 whitespace-nowrap text-center text-[11px] sm:text-xs px-3.5 py-1.5 rounded-lg font-semibold transition"
                                :class="tab === '{{ $key }}'
                                    ? 'text-[#1e2a44] bg-[#f5a844]'
                                    : 'text-white/70 hover:text-white hover:bg-white/10'">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- NOTIFIKASI -->
            @if (session('success'))
                <div style="margin-top: 16px;" class="rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div style="margin-top: 16px;" class="rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="margin-top: 16px;" class="rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm">
                    <p class="font-semibold mb-1">Periksa kembali input lu:</p>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- ================= MY LISTING ================= -->
            <section x-show="tab === 'my-listing'" x-cloak style="margin-top: 20px;">

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
                            searchText: @js(strtolower($listing->judul . ' ' . $listing->game)),
                            gameText: @js($listing->game),
                            statusText: @js($listing->status)
                        }"
                            x-show="
                                (!search || searchText.includes(search.toLowerCase())) &&
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
                    <p style="margin-bottom: 12px;" class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
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

            <!-- ================= DASHBOARD ================= -->
            <section x-show="tab === 'dashboard'" x-cloak style="display: flex; flex-direction: column; gap: 22px; margin-top: 20px;">

                <!-- Overview -->
                <div>
                    <h2 style="margin-bottom: 12px;" class="text-sm font-bold text-[#1e2a44]">Overview</h2>
                    <div class="overview-grid">
                        <!-- On Going -->
                        <div class="rounded-xl bg-[#1e2a44] p-4 shadow-sm">
                            <div class="flex items-center gap-2 text-xs text-white/70">
                                <svg class="w-4 h-4 text-white/60 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 2"/>
                                </svg>
                                <span>On Going</span>
                            </div>
                            <p class="mt-2 text-lg sm:text-xl font-bold text-white">
                                Rp {{ number_format($overview['on_going'], 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Balance -->
                        <div class="rounded-xl bg-[#1e2a44] p-4 shadow-sm">
                            <div class="flex items-center gap-2 text-xs text-white/70">
                                <svg class="w-4 h-4 text-[#f5a844] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Balance</span>
                            </div>
                            <p class="mt-2 text-lg sm:text-xl font-bold text-[#f5a844]">
                                Rp {{ number_format($overview['balance'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Listing Overview -->
                <div style="margin-top: 32px;">
                    <h2 style="margin-bottom: 14px;" class="text-sm font-bold text-[#1e2a44]">Listing Overview</h2>

                    <!-- Sub-tabs -->
                    <div class="flex flex-wrap items-center gap-5 border-b border-gray-200 mb-4">
                        @foreach ([
                            'order-status' => 'Order Status',
                            'balance-history' => 'Balance History',
                        ] as $subKey => $subLabel)
                            <button
                                type="button"
                                @click="dashSubTab = '{{ $subKey }}'"
                                class="pb-2 text-xs sm:text-sm font-semibold border-b-2 transition -mb-px"
                                :class="dashSubTab === '{{ $subKey }}'
                                    ? 'border-[#f5a844] text-[#1e2a44]'
                                    : 'border-transparent text-gray-400 hover:text-gray-700'"
                            >
                                {{ $subLabel }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Filter & Search -->
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-2 mb-4">
                        <select
                            x-show="dashSubTab === 'order-status'"
                            x-model="orderFilter"
                            class="rounded-lg bg-[#1e2a44] border border-[#1e2a44] px-3.5 py-2 text-xs font-semibold text-white focus:ring-2 focus:ring-[#f5a844] focus:outline-none sm:w-44"
                        >
                            <option value="all">All ({{ $allOrders->count() }})</option>
                            <option value="completed">Good Delivery ({{ $allOrders->where('category', 'completed')->count() }})</option>
                            <option value="on_going">On Going ({{ $allOrders->where('category', 'on_going')->count() }})</option>
                        </select>

                        <div style="display: flex; align-items: center; gap: 8px; padding: 0 12px;"
                            class="rounded-lg bg-[#1e2a44] border border-[#1e2a44] focus-within:ring-2 focus-within:ring-[#f5a844] sm:w-60">
                            <svg class="w-4 h-4 text-white/50 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input
                                type="text"
                                x-model="orderSearch"
                                :placeholder="dashSubTab === 'balance-history' ? 'Search history...' : 'Search listing...'"
                                style="background: transparent; border: none; outline: none; box-shadow: none;"
                                class="w-full py-2 px-0 text-xs text-white placeholder-white/50"
                            >
                        </div>
                    </div>

                    <!-- SUB-TAB 1: ORDER STATUS -->
                    <div x-show="dashSubTab === 'order-status'" class="space-y-3">
                        <template x-for="order in filteredOrders" :key="order.id">
                            <div class="rounded-xl bg-[#1e2a44] p-4 shadow-sm text-white">
                                <div class="order-row-grid">

                                    <!-- Buyer -->
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-[#f5a844] text-[#1e2a44] font-bold text-xs flex items-center justify-center shrink-0 uppercase">
                                            <span x-text="order.buyer_name.charAt(0)"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[11px] text-white/50">Buyer:</p>
                                            <p class="text-xs font-semibold text-white truncate" x-text="order.buyer_name"></p>
                                            <p class="text-[11px] text-white/60 truncate" x-text="order.buyer_username"></p>
                                        </div>
                                    </div>

                                    <!-- Order ID -->
                                    <div class="min-w-0">
                                        <p class="text-[11px] text-white/50">Order ID:</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-xs font-semibold text-white truncate" x-text="order.id"></span>
                                            <button type="button" @click="copyOrderId(order.id)" class="text-white/60 hover:text-[#f5a844] shrink-0" title="Salin Order ID">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span x-show="copiedId === order.id" x-cloak class="text-[10px] text-[#f5a844] font-semibold">Copied!</span>
                                    </div>

                                    <!-- Order Title -->
                                    <div class="min-w-0">
                                        <p class="text-[11px] text-white/50">Order Title:</p>
                                        <p class="text-xs font-semibold text-white line-clamp-2 mt-0.5" x-text="order.title"></p>
                                    </div>

                                    <!-- Purchase Date -->
                                    <div>
                                        <p class="text-[11px] text-white/50">Purchase Date:</p>
                                        <p class="text-xs font-medium text-white mt-0.5" x-text="order.date"></p>
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <p class="text-[11px] text-white/50">Price:</p>
                                        <p class="text-xs font-bold text-[#f5a844] mt-0.5" x-text="formatRupiah(order.price)"></p>
                                    </div>

                                    <!-- Status & Action -->
                                    <div class="flex items-center justify-between md:justify-end gap-2">
                                        <span
                                            style="padding: 5px 14px; display: inline-flex; align-items: center; justify-content: center;" class="rounded-full text-[11px] font-semibold border whitespace-nowrap text-center"
                                            :class="order.category === 'completed'
                                                ? 'bg-emerald-500/15 text-emerald-300 border-emerald-400/40'
                                                : 'bg-sky-500/15 text-sky-300 border-sky-400/40'"
                                            x-text="order.status_label"
                                        ></span>

                                        <button type="button" @click="selectedOrder = order"
                                            class="p-1.5 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition" title="Detail">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="5" cy="12" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="19" cy="12" r="2"/>
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </template>

                        <div x-show="filteredOrders.length === 0" x-cloak
                            class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500">
                            Belum ada data transaksi pada kategori ini.
                        </div>
                    </div>

                    <!-- SUB-TAB 2: BALANCE HISTORY -->
                    <div x-show="dashSubTab === 'balance-history'" x-cloak class="space-y-3">
                        <template x-for="item in balanceHistoryItems" :key="'bh-' + item.id">
                            <div class="rounded-xl bg-[#1e2a44] p-4 shadow-sm text-white">
                                <div class="balance-row-grid">

                                    <!-- Icon + Date -->
                                    <div style="display: flex; align-items: center; gap: 12px;" class="min-w-0">
                                        <div class="w-10 h-10 rounded-full bg-white/10 text-[#f5a844] flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[11px] text-white/50">Date:</p>
                                            <p class="text-xs font-semibold text-white mt-0.5" x-text="item.date"></p>
                                        </div>
                                    </div>

                                    <!-- Badge Earning -->
                                    <div>
                                        <span class="inline-flex px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-500/15 text-emerald-300 border border-emerald-400/40"
                                            x-text="item.type"></span>
                                    </div>

                                    <!-- Amount -->
                                    <div>
                                        <p class="text-[11px] text-white/50">Amount:</p>
                                        <p class="text-xs font-bold text-emerald-400 mt-0.5" x-text="'+' + formatRupiah(item.amount)"></p>
                                    </div>

                                    <!-- Source -->
                                    <div class="min-w-0">
                                        <p class="text-[11px] text-white/50">Source:</p>
                                        <p class="text-xs font-semibold text-white line-clamp-2 mt-0.5" x-text="item.source"></p>
                                    </div>

                                    <!-- Order ID -->
                                    <div class="min-w-0">
                                        <p class="text-[11px] text-white/50">Order ID:</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-xs font-semibold text-white truncate" x-text="item.id"></span>
                                            <button type="button" @click="copyOrderId(item.id)" class="text-white/60 hover:text-[#f5a844] shrink-0" title="Salin Order ID">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <span x-show="copiedId === item.id" x-cloak class="text-[10px] text-[#f5a844] font-semibold">Copied!</span>
                                    </div>

                                </div>
                            </div>
                        </template>

                        <div x-show="balanceHistoryItems.length === 0" x-cloak
                            class="rounded-xl border border-dashed border-gray-300 p-12 text-center text-sm text-gray-500">
                            Belum ada riwayat saldo masuk (Earning).
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Order -->
                <div x-show="selectedOrder" x-cloak @keydown.escape.window="selectedOrder = null"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                    @click.self="selectedOrder = null">
                    <div class="bg-[#1e2a44] text-white rounded-2xl shadow-xl w-full max-w-md p-5 border border-white/15">
                        <div class="flex items-center justify-between pb-3 border-b border-white/15">
                            <h3 class="text-base font-bold text-[#f5a844]">Detail Transaksi</h3>
                            <button type="button" @click="selectedOrder = null" class="text-white/60 hover:text-white text-lg">✕</button>
                        </div>

                        <template x-if="selectedOrder">
                            <div class="mt-4 space-y-3 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-white/60">Order ID</span>
                                    <span class="font-bold text-white" x-text="selectedOrder.id"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Kategori</span>
                                    <span class="font-semibold text-white" x-text="selectedOrder.type + ' (' + selectedOrder.game + ')'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Buyer</span>
                                    <span class="font-semibold text-white" x-text="selectedOrder.buyer_name"></span>
                                </div>
                                <div>
                                    <span class="text-white/60 block mb-1">Order Title</span>
                                    <p class="font-semibold text-white bg-white/5 p-2.5 rounded-lg" x-text="selectedOrder.title"></p>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Tanggal Transaksi</span>
                                    <span class="text-white" x-text="selectedOrder.date"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Metode Pembayaran</span>
                                    <span class="text-white" x-text="selectedOrder.metode"></span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-white/15">
                                    <span class="text-white/70 font-semibold">Total Harga</span>
                                    <span class="text-sm font-bold text-[#f5a844]" x-text="formatRupiah(selectedOrder.price)"></span>
                                </div>
                            </div>
                        </template>

                        <div class="mt-5 flex justify-end">
                            <button type="button" @click="selectedOrder = null"
                                class="px-4 py-2 rounded-xl bg-[#f5a844] text-[#1e2a44] text-xs font-bold hover:bg-[#f5b867] transition">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

            </section>

                                    <!-- REVIEWS -->
            <section x-show="tab === 'reviews'" x-cloak style="margin-top: 24px;">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-[#1e2a44]">Customer Reviews</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Ulasan dari produk akun & layanan joki yang sudah terjual</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#1e2a44] text-[#f5a844]">
                        {{ $reviews->count() }} Reviews
                    </span>
                </div>

                @if (session('success'))
                    <div class="mb-3 rounded-xl bg-emerald-600/15 border border-emerald-500/30 px-4 py-3 text-xs font-medium text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($reviews->isEmpty())
                    <div class="rounded-xl bg-[#1e2a44] p-12 text-center text-xs sm:text-sm text-white/60 shadow-sm">
                        Belum ada review dari pembeli.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($reviews as $review)
                            <div class="rounded-xl bg-[#1e2a44] px-4 py-3.5 sm:px-5 sm:py-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-3.5 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-[#f5a844] text-[#1e2a44] font-bold text-xs flex items-center justify-center shrink-0 uppercase">
                                        {{ substr($review->user?->name ?? 'P', 0, 1) }}
                                    </div>
                                    <div class="space-y-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-sm font-bold text-white">
                                                {{ $review->user?->name ?? 'Pembeli' }}
                                            </span>
                                            <span class="text-xs text-[#f5a844] tracking-wider">
                                                {{ str_repeat('?', $review->rating) }}{{ str_repeat('?', 5 - $review->rating) }}
                                            </span>
                                            <span class="text-[11px] text-white/50">
                                                � {{ $review->created_at?->format('d F Y, H:i') }}
                                            </span>
                                        </div>

                                        <p class="text-[11px] text-[#f5a844] font-medium truncate">
                                            Order Title: {{ $review->item_title }}
                                        </p>

                                        @if ($review->komentar)
                                            <p class="text-xs text-white/85 leading-relaxed pt-0.5">
                                                "{{ $review->komentar }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if (auth()->user()?->role === 'admin')
                                    <form action="{{ route('dashboard.reviews.destroy', $review->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus review ini agar tidak ada fake review?');"
                                          class="shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold border border-rose-400/50 text-rose-300 hover:bg-rose-500/20 transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1e2a44] shrink-0">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-white/70 text-xs">
            &copy; {{ date('Y') }} ZERO SHOP Admin. Semua hak dilindungi.
        </div>
    </footer>

</body>

</html>






