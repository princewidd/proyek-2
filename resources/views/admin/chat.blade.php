<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inbox Chat - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-white text-gray-900 antialiased min-h-full flex flex-col">

    <!-- NAVBAR -->
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-sm shrink-0">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-3 h-14">
            <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-white transition -ml-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('img/logoLR.png') }}" class="h-9 w-9 rounded-full object-cover">
                <span class="text-white font-bold text-lg tracking-wide">ZERO SHOP</span>
            </a>

            <div class="flex-1"></div>

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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6">

            <div class="mb-4">
                <h1 class="text-lg font-bold text-[#1e2a44]">Inbox Chat</h1>
                <p class="text-xs text-gray-500 mt-1">Percakapan pelanggan dengan CS. Balas di sini buat nego harga &amp; bantu pembeli.</p>
            </div>

            @if ($chats->isEmpty())
                <div class="rounded-2xl bg-[#1e2a44] p-8 text-center">
                    <p class="text-sm text-white/60 font-semibold">Belum ada chat masuk</p>
                    <p class="text-xs text-white/40 mt-1">Percakapan pelanggan akan muncul di sini.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($chats as $c)
                        <a href="{{ route('admin.chat.show', $c) }}"
                            class="flex items-center gap-3 rounded-xl border border-gray-200 p-3.5 hover:border-[#1e2a44]/40 hover:shadow-sm transition bg-white">
                            <div class="w-10 h-10 rounded-full bg-[#1e2a44] text-[#f5a844] flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($c->pelanggan->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-[#1e2a44] truncate">{{ $c->pelanggan->name }}</p>
                                    @if ($c->pelanggan->isOnline())
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0" title="Online"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 truncate mt-0.5">
                                    {{ $c->detailChats->last()?->pesan ?? 'Belum ada pesan' }}
                                </p>
                            </div>
                            <div class="text-right shrink-0 space-y-1">
                                @if ($c->last_message_at)
                                    <p class="text-[10px] text-gray-400">{{ $c->last_message_at->format('d M, H:i') }}</p>
                                @endif
                                @if ($unread->get($c->id))
                                    <span class="inline-flex items-center justify-center min-w-5 h-5 px-1.5 rounded-full bg-[#f5a844] text-[#1e2a44] text-[10px] font-bold">
                                        {{ $unread->get($c->id) }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</body>

</html>
