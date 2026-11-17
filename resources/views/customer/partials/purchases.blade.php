<div class="space-y-4">
    @forelse ($purchases as $item)
        <div class="bg-[#1e2a44] text-white rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-mono text-slate-400">{{ $item->kode_pembayaran ?? 'INV-' . $item->id }}</span>
                    <span class="text-xs text-slate-500">&bull;</span>
                    <span class="text-xs text-slate-400">{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}</span>
                </div>
                <h4 class="font-bold text-lg">{{ $item->nama_item ?? $item->item ?? 'Pesanan Game' }}</h4>
                <p class="text-sm text-yellow-400 font-semibold">
                    Rp {{ number_format($item->jumlah ?? $item->total ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex items-center gap-3 self-end sm:self-center">
                <span class="px-3 py-1 rounded-full text-xs font-semibold border border-yellow-400/40 bg-yellow-400/10 text-yellow-400">
                    {{ ucfirst($item->status ?? 'Pending') }}
                </span>

                @if(isset($item->id))
                    <a href="{{ route('pembayaran.show', $item->id) }}"
                       class="px-4 py-2 rounded-lg bg-white text-[#1e2a44] text-xs font-bold hover:bg-yellow-400 transition">
                        Detail
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white border border-slate-200 rounded-xl p-10 text-center shadow-sm">
            <p class="text-slate-500 font-medium mb-3">Kamu belum memiliki riwayat pembelian.</p>
            <a href="{{ route('beranda') }}" class="inline-block px-5 py-2.5 rounded-lg bg-[#1e2a44] text-white text-sm font-bold hover:bg-slate-800 transition">
                Mulai Belanja
            </a>
        </div>
    @endforelse
</div>