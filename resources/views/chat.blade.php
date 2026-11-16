<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat CS - ZERO SHOP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 3px; }
    </style>
</head>

<body class="font-sans bg-white text-gray-900 antialiased min-h-full flex flex-col"
    x-data="chatApp()">

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

            <div class="rounded-2xl bg-[#1e2a44] shadow-md overflow-hidden" style="display: flex; flex-direction: column;">

                <!-- HEADER CHAT -->
                <div class="flex items-center gap-3 px-4 sm:px-5 py-3 border-b border-white/10">
                    <div class="w-10 h-10 rounded-full bg-[#f5a844]/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#f5a844]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-white truncate">Admin CS - ZeroShop</p>
                        <p class="text-[11px] text-white/50">Layanan pelanggan &amp; nego harga</p>
                    </div>
                    <div class="flex-1"></div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold" :class="adminOnline ? 'text-emerald-400' : 'text-white/40'">
                        <span class="w-2 h-2 rounded-full" :class="adminOnline ? 'bg-emerald-400' : 'bg-white/30'"></span>
                        <span x-text="adminOnline ? 'Online' : 'Offline'"></span>
                    </div>
                </div>

                <!-- MESSAGES -->
                <div id="chatBox" class="chat-scroll px-4 sm:px-5 py-4 space-y-3 overflow-y-auto" style="height: 60vh; min-height: 360px;">
                    <template x-if="messages.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center px-6">
                            <svg class="w-10 h-10 text-white/20 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8m-4-4v8M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-white/60 font-semibold">Belum ada pesan</p>
                            <p class="text-xs text-white/40 mt-1 leading-relaxed">Mulai ngobrol buat tanya-tanya listing atau nego harga sebelum beli.</p>
                        </div>
                    </template>

                    <template x-for="msg in messages" :key="msg.id">
                        <div :class="msg.mine ? 'flex justify-end' : 'flex justify-start'">
                            <div class="max-w-[75%] rounded-2xl px-3.5 py-2 shadow-sm"
                                :class="msg.mine ? 'bg-[#f5a844] text-[#1e2a44] rounded-br-md' : 'bg-white/10 text-white rounded-bl-md'">
                                <p class="text-[10px] font-bold mb-0.5 opacity-70" x-text="msg.mine ? 'Kamu' : msg.nama"></p>
                                <p class="text-sm leading-relaxed whitespace-pre-wrap break-words" x-text="msg.pesan"></p>
                                <p class="text-[10px] mt-1 opacity-60" x-text="msg.waktu"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- INPUT -->
                <div class="border-t border-white/10 p-3 sm:p-4 bg-[#162035]">
                    <form @submit.prevent="send()" style="display: flex; align-items: flex-end; gap: 10px;">
                        <textarea x-ref="input" x-model="draft" rows="1" maxlength="2000"
                            placeholder="Tulis pesan... (misal: boleh nego ke 450k?)"
                            @keydown.enter.prevent="send()"
                            class="flex-1 resize-none rounded-xl bg-white/10 border border-white/20 px-3.5 py-2.5 text-sm text-white placeholder-white/40 focus:border-[#f5a844] focus:ring-1 focus:ring-[#f5a844] focus:outline-none"></textarea>
                        <button type="submit" :disabled="draft.trim() === '' || sending"
                            class="shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-full bg-[#f5a844] text-[#1e2a44] font-bold disabled:opacity-40 hover:bg-[#f5b867] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        function chatApp() {
            return {
                messages: @json($initialMessages),
                adminOnline: false,
                draft: '',
                sending: false,

                init() {
                    this.scrollToBottom();
                    this.poll();
                    setInterval(() => this.poll(), 2000);
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const box = document.getElementById('chatBox');
                        box.scrollTop = box.scrollHeight;
                    });
                },

                async poll() {
                    try {
                        const res = await fetch('{{ route('chat.poll') }}', {
                            headers: { 'Accept': 'application/json' },
                        });
                        if (!res.ok) return;
                        const data = await res.json();
                        this.adminOnline = data.admin_online;
                        this.messages = data.messages;
                        this.scrollToBottom();
                    } catch (e) {
                        // diam saja, poll berikutnya
                    }
                },

                async send() {
                    if (this.draft.trim() === '' || this.sending) return;
                    this.sending = true;
                    try {
                        const res = await fetch('{{ route('chat.send') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ pesan: this.draft.trim() }),
                        });
                        if (res.ok) {
                            const data = await res.json();
                            this.messages.push(data.message);
                            this.draft = '';
                            this.scrollToBottom();
                        }
                    } catch (e) {
                        // diam saja
                    } finally {
                        this.sending = false;
                        this.$refs.input.focus();
                    }
                },
            };
        }
    </script>
</body>

</html>
