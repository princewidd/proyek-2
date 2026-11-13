<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran {{ $pembayaran->kode_pembayaran }} - ZERO SHOP</title>
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
                <a href="{{ route('dashboard', ['tab' => 'purchases']) }}" class="text-sm text-white/90 hover:text-white">Pesanan Saya</a>
            </div>
        </div>
    </header>

    <main class="flex-1 bg-white">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-4">

            @if (session('sukses'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                    {{ session('sukses') }}
                </div>
            @endif

            <!-- STATUS PEMBAYARAN -->
            <section class="rounded-xl bg-[#1e2a44] text-white p-6 sm:p-8">
                <div class="flex items-center justify-between gap-3">
                    <h1 class="text-lg sm:text-xl font-semibold">Status Pembayaran</h1>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold text-[#f5a844] border border-[#f5a844]/60">{{ ucfirst($pembayaran->status) }}</span>
                </div>

                <dl class="mt-5 space-y-3 text-sm sm:text-base">
                    <div class="flex justify-between gap-4 border-b border-white/20 pb-3">
                        <dt class="text-white/60">Kode Pembayaran</dt>
                        <dd class="font-semibold">{{ $pembayaran->kode_pembayaran }}</dd>
                    </div>
                    @php
                        $item = $pembayaran->transaksiAkun?->akunGame?->judul
                            ?? (($pembayaran->pesananJoki?->paketJoki?->nama_paket ?? '') . ' — ' . ($pembayaran->pesananJoki?->paketJoki?->game ?? ''));
                    @endphp
                    <div class="flex justify-between gap-4 border-b border-white/20 pb-3">
                        <dt class="text-white/60">Item</dt>
                        <dd class="text-right truncate max-w-[60%]" title="{{ $item }}">{{ $item }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-white/20 pb-3">
                        <dt class="text-white/60">Metode</dt>
                        <dd class="font-semibold">{{ $pembayaran->metode === 'qris' ? 'QRIS' : 'Bank Transfer' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-white/60">Jumlah</dt>
                        <dd class="font-semibold text-[#f5a844]">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</dd>
                    </div>
                </dl>

                <div class="mt-6 rounded-lg bg-white/10 p-4 text-xs sm:text-sm text-white/70 leading-relaxed">
                    Pesanan kamu menunggu konfirmasi admin. Setelah pembayaran dikonfirmasi, {{ $pembayaran->pesananJoki ? 'pilot akan mulai bekerja di akunmu' : 'data akun akan dikirim' }}. Fitur upload bukti pembayaran menyusul.
                </div>

                @if ($pembayaran->transaksiAkun)
                    <a href="{{ route('game.akun.show', ['slug' => \Illuminate\Support\Str::slug($pembayaran->transaksiAkun->akunGame->game), 'akun' => $pembayaran->transaksiAkun->akunGame->id]) }}"
                       class="inline-block mt-5 text-sm text-[#f5a844] hover:underline">&larr; Kembali ke detail akun</a>
                @elseif ($pembayaran->pesananJoki)
                    <a href="{{ route('dashboard', ['tab' => 'purchases']) }}"
                       class="inline-block mt-5 text-sm text-[#f5a844] hover:underline">Lihat pesanan saya &rarr;</a>
                @endif
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
