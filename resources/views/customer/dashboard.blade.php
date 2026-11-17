<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - ZERO SHOP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-between text-slate-800">

    <!-- Navbar -->
    <nav class="bg-[#1e2a44] text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('beranda') }}" class="flex items-center gap-3 font-extrabold text-lg tracking-wider">
                <span>ZERO SHOP</span>
            </a>

            <div class="flex items-center gap-5 text-sm">
                <a href="{{ route('beranda') }}" class="text-slate-300 hover:text-white transition">Beranda</a>
                <a href="{{ route('profile.edit') }}" class="text-slate-300 hover:text-white transition">Edit Profil</a>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main x-data="{ activeTab: 'purchase' }" class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <!-- Sidebar Kiri (Profil & Menu Tab) -->
            <div class="bg-[#1e2a44] text-white rounded-xl p-5 h-fit shadow-md">
                <div class="flex items-center gap-3 pb-5 border-b border-slate-700">
                    <div class="w-12 h-12 rounded-full bg-yellow-400 text-[#1e2a44] flex items-center justify-center font-extrabold text-lg shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h3 class="font-bold text-base truncate">{{ Auth::user()->name }}</h3>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <nav class="mt-5 flex flex-col gap-2">
                    <button type="button" @click="activeTab = 'purchase'"
                        :class="activeTab === 'purchase' ? 'bg-yellow-400 text-[#1e2a44] font-bold' : 'text-slate-300 hover:bg-slate-800'"
                        class="w-full text-left px-4 py-2.5 rounded-lg transition flex items-center justify-between">
                        <span>My Purchase</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-black/20">{{ count($purchases) }}</span>
                    </button>

                    <button type="button" @click="activeTab = 'favorite'"
                        :class="activeTab === 'favorite' ? 'bg-yellow-400 text-[#1e2a44] font-bold' : 'text-slate-300 hover:bg-slate-800'"
                        class="w-full text-left px-4 py-2.5 rounded-lg transition flex items-center justify-between">
                        <span>My Favorite</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-black/20">{{ count($favorites) }}</span>
                    </button>
                </nav>
            </div>

            <!-- Area Konten Kanan -->
            <div class="md:col-span-3">
                <!-- Tab My Purchase -->
                <div x-show="activeTab === 'purchase'" x-transition>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-extrabold text-[#1e2a44]">My Purchase</h2>
                    </div>
                    @include('customer.partials.purchases')
                </div>

                <!-- Tab My Favorite -->
                <div x-show="activeTab === 'favorite'" x-cloak x-transition>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-extrabold text-[#1e2a44]">My Favorite</h2>
                    </div>
                    @include('customer.partials.favorites')
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#1e2a44] text-slate-400 text-xs text-center py-4 mt-12">
        &copy; {{ date('Y') }} ZERO SHOP. Semua hak dilindungi.
    </footer>

</body>
</html>