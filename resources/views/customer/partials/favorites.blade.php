<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($favorites as $fav)
        <div class="bg-[#1e2a44] text-white rounded-xl overflow-hidden shadow flex flex-col justify-between">
            <div class="p-4 space-y-2">
                <span class="text-xs px-2.5 py-0.5 rounded bg-slate-700 text-slate-300">
                    {{ $fav->kategori ?? 'Akun Game' }}
                </span>
                <h4 class="font-bold text-base line-clamp-2">{{ $fav->judul ?? $fav->nama_item ?? 'Item Favorit' }}</h4>
                <p class="text-sm text-yellow-400 font-bold">
                    Rp {{ number_format($fav->harga ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div class="px-4 py-3 bg-slate-900/50 flex items-center justify-between gap-2">
                <a href="#" class="text-xs font-bold text-white hover:text-yellow-400 transition">
                    Lihat Produk &rarr;
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white border border-slate-200 rounded-xl p-10 text-center shadow-sm">
            <p class="text-slate-500 font-medium mb-3">Belum ada item favorit yang disimpan.</p>
            <a href="{{ route('beranda') }}" class="inline-block px-5 py-2.5 rounded-lg bg-[#1e2a44] text-white text-sm font-bold hover:bg-slate-800 transition">
                Jelajahi Game
            </a>
        </div>
    @endforelse
</div>