<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Listing - ZERO SHOP</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        @media (min-width: 768px) {
            .step-row {
                display: grid;
                grid-template-columns: 280px 1fr;
                gap: 28px;
                align-items: start;
            }
            .category-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }
        }
        @media (max-width: 767px) {
            .step-row,
            .category-grid {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }
        }
        .clean-input {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
        }
        .field-label {
            display: block;
            margin-bottom: 8px;
        }
        .upload-slot {
            width: 100%;
            height: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
    </style>
</head>

<body
    class="font-sans bg-gray-100 text-gray-900 antialiased min-h-full flex flex-col"
    x-data="editListingForm()"
>

    <!-- NAVBAR -->
    <header class="bg-[#1e2a44] sticky top-0 z-30 shadow-md shrink-0">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#f5a844] hover:text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Cancel
            </a>

            <h1 class="text-white font-bold text-base sm:text-lg tracking-wide">Edit Listing</h1>

            <a href="{{ route('beranda') }}" class="flex items-center shrink-0">
                <img src="{{ asset('img/logoLR.png') }}" alt="Logo" class="h-8 w-8 rounded-full object-cover ring-1 ring-[#f5a844]/60">
            </a>
        </div>
    </header>

    <main class="flex-1 py-8 sm:py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ERROR VALIDASI -->
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-300 text-red-800 px-4 py-3 text-sm shadow-sm">
                    <p class="font-bold mb-1">Periksa kembali input listing lu:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('admin.akun.update', $akun->id) }}"
                method="POST"
                enctype="multipart/form-data"
                style="display: flex; flex-direction: column; gap: 36px;"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="game" :value="selectedGame" required>

                <template x-for="deletedPath in deletedExtraPaths" :key="deletedPath">
                    <input type="hidden" name="hapus_gambar_tambahan[]" :value="deletedPath">
                </template>

                <!-- ================= STEP 1: STATUS & CATEGORY ================= -->
                <div class="step-row pb-8 border-b border-gray-200">
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <span class="w-7 h-7 rounded-full bg-[#1e2a44] text-[#f5a844] border-2 border-[#f5a844] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 shadow-sm">
                            1
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-[#1e2a44]">Listing Status</h2>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Atur ketersediaan stok akun game di katalog.</p>
                        </div>
                    </div>

                    <div class="category-grid">
                        <label
                            @click="status = 'tersedia'"
                            style="display: flex; align-items: flex-start; gap: 14px; padding: 16px;"
                            class="w-full cursor-pointer text-left rounded-2xl border-2 transition shadow-sm"
                            :class="status === 'tersedia'
                                ? 'bg-[#1e2a44] border-[#f5a844] text-white'
                                : 'bg-white border-gray-200 text-gray-700 hover:border-[#1e2a44]/40'"
                        >
                            <input type="radio" name="status" value="tersedia" x-model="status" class="hidden">
                            <div style="padding: 10px;" class="rounded-xl shrink-0" :class="status === 'tersedia' ? 'bg-[#f5a844]/20 text-[#f5a844]' : 'bg-gray-100 text-gray-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold" :class="status === 'tersedia' ? 'text-[#f5a844]' : 'text-[#1e2a44]'">Available (Tersedia)</p>
                                <p class="text-xs mt-1 leading-relaxed" :class="status === 'tersedia' ? 'text-white/75' : 'text-gray-500'">
                                    Akun aktif tampil di katalog dan dapat dibeli oleh pelanggan.
                                </p>
                            </div>
                        </label>

                        <label
                            @click="status = 'terjual'"
                            style="display: flex; align-items: flex-start; gap: 14px; padding: 16px;"
                            class="w-full cursor-pointer text-left rounded-2xl border-2 transition shadow-sm"
                            :class="status === 'terjual'
                                ? 'bg-[#1e2a44] border-[#f5a844] text-white'
                                : 'bg-white border-gray-200 text-gray-700 hover:border-[#1e2a44]/40'"
                        >
                            <input type="radio" name="status" value="terjual" x-model="status" class="hidden">
                            <div style="padding: 10px;" class="rounded-xl shrink-0" :class="status === 'terjual' ? 'bg-[#f5a844]/20 text-[#f5a844]' : 'bg-gray-100 text-gray-600'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold" :class="status === 'terjual' ? 'text-[#f5a844]' : 'text-[#1e2a44]'">Sold (Terjual)</p>
                                <p class="text-xs mt-1 leading-relaxed" :class="status === 'terjual' ? 'text-white/75' : 'text-gray-500'">
                                    Tandai akun sudah laku terjual dan tidak bisa dipesan lagi.
                                </p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- ================= STEP 2: SELECT GAMES ================= -->
                <div class="step-row pb-8 border-b border-gray-200">
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <span
                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 shadow-sm transition"
                            :class="selectedGame ? 'bg-[#1e2a44] text-[#f5a844] border-2 border-[#f5a844]' : 'bg-white border-2 border-gray-300 text-gray-600'"
                        >
                            2
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-[#1e2a44]">Select Games</h2>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pilih game dari akun yang dijual.</p>
                            <div x-show="selectedGame" class="mt-2.5 inline-flex items-center text-xs font-bold text-[#1e2a44] bg-[#f5a844] px-3 py-1 rounded-full shadow-sm">
                                <span x-text="selectedGame"></span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 16px;" class="bg-[#1e2a44] rounded-2xl p-5 shadow-md">
                        <!-- Search Game -->
                        <div style="display: flex; align-items: center; gap: 10px; padding: 0 14px;" class="rounded-xl bg-white/10 border border-white/15 focus-within:border-[#f5a844] focus-within:ring-1 focus-within:ring-[#f5a844]">
                            <svg class="w-4 h-4 text-white/50 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                            </svg>
                            <input
                                type="text"
                                x-model="gameSearch"
                                placeholder="Search by game name..."
                                class="clean-input w-full py-2.5 px-0 text-sm text-white placeholder-white/50"
                            >
                        </div>

                        <!-- Grid Kartu Game -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(115px, 1fr)); gap: 12px;" class="max-h-72 overflow-y-auto pr-1">
                            @foreach ($games as $g)
                                <button
                                    type="button"
                                    x-show="!gameSearch || @js(strtolower($g['name'])).includes(gameSearch.toLowerCase())"
                                    @click="selectedGame = @js($g['name'])"
                                    class="group relative rounded-xl overflow-hidden border-2 text-left transition focus:outline-none bg-[#162035]"
                                    :class="selectedGame === @js($g['name'])
                                        ? 'border-[#f5a844] ring-2 ring-[#f5a844]/40'
                                        : 'border-white/10 hover:border-white/40'"
                                >
                                    <div style="height: 88px;" class="w-full bg-[#2a3a5c] overflow-hidden">
                                        <img
                                            src="{{ asset('img/beranda/' . $g['img']) }}"
                                            alt="{{ $g['name'] }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-200"
                                        >
                                    </div>
                                    <div class="px-2 py-2 bg-[#162035]">
                                        <p class="text-xs font-semibold text-white truncate">{{ $g['name'] }}</p>
                                    </div>
                                    <div
                                        x-show="selectedGame === @js($g['name'])"
                                        class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-[#f5a844] text-[#1e2a44] flex items-center justify-center text-xs font-bold shadow"
                                    >
                                        ✓
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        @error('game')
                            <p class="text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- ================= STEP 3: PRODUCT DETAILS ================= -->
                <div class="step-row pb-8 border-b border-gray-200">
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <span class="w-7 h-7 rounded-full bg-[#1e2a44] text-[#f5a844] border-2 border-[#f5a844] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 shadow-sm">
                            3
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-[#1e2a44]">Product Details</h2>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Perbarui detail spesifikasi, harga, server, serta metode pengiriman akun.</p>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 18px;">
                        <!-- Card 1: Judul, Harga, Multiple Quantity -->
                        <div style="display: flex; flex-direction: column; gap: 18px;" class="bg-[#1e2a44] rounded-2xl p-5 shadow-md text-white">
                            <div>
                                <label class="field-label text-xs font-semibold text-white">Listing Title <span class="text-[#f5a844]">*</span></label>
                                <input
                                    type="text"
                                    name="judul"
                                    value="{{ old('judul', $akun->judul) }}"
                                    required
                                    placeholder="Contoh: AR 60 Asia | 15x B5 Archon Lengkap + Sign"
                                    class="w-full rounded-xl bg-white/10 border border-white/20 px-3.5 py-2.5 text-sm text-white placeholder-white/40 focus:border-[#f5a844] focus:ring-1 focus:ring-[#f5a844] focus:outline-none"
                                >
                            </div>

                            <div>
                                <label class="field-label text-xs font-semibold text-white">Price (IDR) <span class="text-[#f5a844]">*</span></label>
                                <div style="display: flex; align-items: center; gap: 10px; padding: 0 14px;" class="rounded-xl bg-white/10 border border-white/20 focus-within:border-[#f5a844] focus-within:ring-1 focus-within:ring-[#f5a844]">
                                    <span class="text-xs font-bold text-[#f5a844] shrink-0">Rp</span>
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        name="harga"
                                        value="{{ old('harga')
                                            ? number_format((int) preg_replace('/\D/', '', old('harga')), 0, ',', '.')
                                            : number_format($akun->harga, 0, ',', '.') }}"
                                        required
                                        placeholder="500.000"
                                        x-on:input="$el.value = $el.value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                        class="clean-input w-full py-2.5 px-0 text-sm text-white placeholder-white/40"
                                    >
                                </div>
                            </div>

                            <div>
                                <label style="display: inline-flex; align-items: center; gap: 10px;" class="cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        name="multiple_quantity"
                                        value="1"
                                        x-model="multipleQuantity"
                                        class="rounded border-white/30 bg-white/10 text-[#f5a844] focus:ring-[#f5a844]"
                                    >
                                    <span class="text-xs font-semibold text-white">Multiple quantity?</span>
                                </label>

                                <div x-show="multipleQuantity" x-cloak class="mt-3">
                                    <label class="field-label text-xs text-white/70">Jumlah Stok Tersedia</label>
                                    <input
                                        type="number"
                                        name="quantity"
                                        value="{{ old('quantity', $akun->quantity ?? 1) }}"
                                        min="1"
                                        class="w-40 rounded-xl bg-white/10 border border-white/20 px-3.5 py-2 text-sm text-white focus:border-[#f5a844] focus:outline-none"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Server, Linked, Description, Tags -->
                        <div style="display: flex; flex-direction: column; gap: 20px;" class="bg-[#1e2a44] rounded-2xl p-5 shadow-md text-white">
                            <!-- Server -->
                            <div>
                                <label class="field-label text-xs font-semibold text-white">Server</label>
                                <select
                                    name="server"
                                    class="w-full rounded-xl bg-[#2a3a5c] border border-white/20 px-3.5 py-2.5 text-sm text-white focus:border-[#f5a844] focus:ring-1 focus:ring-[#f5a844] focus:outline-none"
                                >
                                    <option value="">Please select one option</option>
                                    <option value="Asia" @selected(old('server', $akun->server) === 'Asia')>Asia</option>
                                    <option value="SEA" @selected(old('server', $akun->server) === 'SEA')>SEA (Southeast Asia)</option>
                                    <option value="America" @selected(old('server', $akun->server) === 'America')>America</option>
                                    <option value="Europe" @selected(old('server', $akun->server) === 'Europe')>Europe</option>
                                    <option value="TW/HK/MO" @selected(old('server', $akun->server) === 'TW/HK/MO')>TW / HK / MO</option>
                                    <option value="Global" @selected(old('server', $akun->server) === 'Global')>Global</option>
                                </select>
                            </div>

                            <!-- Account Linked Checkbox -->
                            <div>
                                <label style="display: inline-flex; align-items: center; gap: 10px;" class="cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        name="account_linked"
                                        value="1"
                                        @checked(old('account_linked', $akun->account_linked))
                                        class="rounded border-white/30 bg-white/10 text-[#f5a844] focus:ring-[#f5a844]"
                                    >
                                    <span class="text-xs font-medium text-white/90">Is this account already linked or registered to an existing email or phone?</span>
                                </label>
                            </div>

                            <!-- Descriptions -->
                            <div>
                                <label class="field-label text-xs font-semibold text-white">Descriptions</label>
                                <div class="rounded-xl border border-white/20 overflow-hidden bg-white/5">
                                    <div style="display: flex; align-items: center; gap: 6px;" class="px-3 py-1.5 border-b border-white/15 bg-white/5 text-xs text-white/80">
                                        <button type="button" @click="insertFormat('**', '**')" class="px-2.5 py-1 rounded hover:bg-white/10 font-bold" title="Bold">B</button>
                                        <button type="button" @click="insertFormat('_', '_')" class="px-2.5 py-1 rounded hover:bg-white/10 italic" title="Italic">I</button>
                                        <button type="button" @click="insertFormat('\n- ', '')" class="px-2.5 py-1 rounded hover:bg-white/10" title="Bullet List">• List</button>
                                    </div>
                                    <textarea
                                        x-ref="descInput"
                                        name="deskripsi"
                                        rows="4"
                                        placeholder="Tulis detail akun (Rank, Karakter/Skin, Senjata, Login via apa, dll)..."
                                        class="clean-input w-full px-3.5 py-2.5 text-sm text-white placeholder-white/40"
                                    >{{ old('deskripsi', $akun->deskripsi) }}</textarea>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="field-label text-xs font-semibold text-white">Tags</label>
                                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 8px;" class="rounded-xl bg-white/10 border border-white/20 px-3 py-2 focus-within:border-[#f5a844]">
                                    <template x-for="(tag, index) in tags" :key="index">
                                        <span style="display: inline-flex; align-items: center; gap: 6px;" class="px-2.5 py-1 rounded-lg bg-[#f5a844] text-[#1e2a44] text-xs font-bold">
                                            <span x-text="tag"></span>
                                            <button type="button" @click="removeTag(index)" class="hover:text-red-700">×</button>
                                            <input type="hidden" name="tags[]" :value="tag">
                                        </span>
                                    </template>

                                    <input
                                        type="text"
                                        x-model="tagInput"
                                        @keydown.enter.prevent="addTag()"
                                        @keydown.comma.prevent="addTag()"
                                        placeholder="Ketik tag lalu tekan Enter (misal: Sultan, Full Akses)..."
                                        class="clean-input flex-1 min-w-[180px] p-1 text-sm text-white placeholder-white/40"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= STEP 4: UPLOAD IMAGES ================= -->
                <div class="step-row pb-6">
                    <div style="display: flex; align-items: flex-start; gap: 14px;">
                        <span class="w-7 h-7 rounded-full bg-[#1e2a44] text-[#f5a844] border-2 border-[#f5a844] flex items-center justify-center text-xs font-bold shrink-0 mt-0.5 shadow-sm">
                            4
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-[#1e2a44]">Upload Images</h2>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Klik kotak Cover untuk mengganti foto utama, atau tambah/hapus screenshot tambahan.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                        <div style="display: grid; grid-template-columns: repeat(4, 110px); gap: 12px;">
                            <!-- Slot 1: Gambar Utama -->
                            <label class="upload-slot rounded-xl bg-[#1e2a44] border-2 border-dashed border-[#f5a844] hover:bg-[#263556] transition">
                                <template x-if="mainPreview">
                                    <img :src="mainPreview" style="width: 100%; height: 100%; object-fit: cover;">
                                </template>
                                <template x-if="!mainPreview">
                                    <div class="text-center p-1.5">
                                        <svg class="w-5 h-5 text-[#f5a844] mx-auto" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                                        </svg>
                                        <span class="block text-[10px] font-bold text-[#f5a844] mt-1">Cover</span>
                                    </div>
                                </template>
                                <input
                                    type="file"
                                    name="gambar"
                                    accept="image/jpeg,image/png,image/webp"
                                    @change="previewMain($event)"
                                    class="hidden"
                                >
                            </label>

                            <!-- Slot Foto Tambahan Lama yang Masih Disimpan -->
                            <template x-for="(item, idx) in existingExtras" :key="item.path">
                                <div class="upload-slot rounded-xl bg-[#1e2a44] border border-white/15" style="cursor: default;">
                                    <img :src="item.url" style="width: 100%; height: 100%; object-fit: cover;">
                                    <button
                                        type="button"
                                        @click="removeExistingExtra(idx)"
                                        style="position: absolute; top: 5px; right: 5px; width: 22px; height: 22px; border-radius: 9999px; background: rgba(239,68,68,0.95); color: #fff; font-size: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center;"
                                        title="Hapus foto ini"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </template>

                            <!-- Sisa Slot Upload Baru (Total 7 slot tambahan) -->
                            <template x-for="slot in (7 - existingExtras.length)" :key="'new-' + slot">
                                <label class="upload-slot rounded-xl bg-[#1e2a44] border border-white/15 hover:border-[#f5a844] transition">
                                    <template x-if="extraPreviews[slot - 1]">
                                        <img :src="extraPreviews[slot - 1]" style="width: 100%; height: 100%; object-fit: cover;">
                                    </template>
                                    <template x-if="!extraPreviews[slot - 1]">
                                        <div class="text-center">
                                            <svg class="w-5 h-5 text-white/40 mx-auto" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                                            </svg>
                                            <span class="block text-[10px] text-white/40 mt-0.5" x-text="'Foto ' + (existingExtras.length + slot + 1)"></span>
                                        </div>
                                    </template>
                                    <input
                                        type="file"
                                        name="gambar_tambahan[]"
                                        accept="image/jpeg,image/png,image/webp"
                                        @change="previewExtra($event, slot - 1)"
                                        class="hidden"
                                    >
                                </label>
                            </template>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-3">Format JPG, PNG, atau WEBP. Maksimal 2MB per foto. Klik Cover untuk mengganti gambar utama.</p>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;" class="pt-6 border-t border-gray-200">
                    <a
                        href="{{ route('dashboard') }}"
                        class="px-5 py-2.5 rounded-xl bg-white border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-100 transition"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-2.5 rounded-xl bg-[#f5a844] text-[#1e2a44] text-sm font-bold hover:bg-[#f5b867] shadow-md transition"
                    >
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#1e2a44] shrink-0 mt-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-white/70 text-xs">
            &copy; {{ date('Y') }} ZERO SHOP Admin. Semua hak dilindungi.
        </div>
    </footer>

    @php
        $initialMain = $akun->gambar
            ? (str_starts_with($akun->gambar, 'akun-game/') ? asset('storage/' . $akun->gambar) : asset($akun->gambar))
            : null;

        $initialExtras = collect($akun->gambar_tambahan ?? [])->map(function ($path) {
            return [
                'path' => $path,
                'url' => str_starts_with($path, 'akun-game/') ? asset('storage/' . $path) : asset($path),
            ];
        })->values();
    @endphp

    <script>
        function editListingForm() {
            return {
                status: @js(old('status', $akun->status ?? 'tersedia')),
                gameSearch: '',
                selectedGame: @js(old('game', $akun->game)),
                multipleQuantity: @js((bool) old('multiple_quantity', $akun->multiple_quantity)),
                tags: @js(old('tags', $akun->tags ?? [])),
                tagInput: '',
                mainPreview: @js($initialMain),
                existingExtras: @js($initialExtras),
                deletedExtraPaths: [],
                extraPreviews: Array(7).fill(null),

                addTag() {
                    const val = this.tagInput.trim().replace(/,$/, '');
                    if (val && !this.tags.includes(val) && this.tags.length < 10) {
                        this.tags.push(val);
                    }
                    this.tagInput = '';
                },

                removeTag(index) {
                    this.tags.splice(index, 1);
                },

                removeExistingExtra(index) {
                    const removed = this.existingExtras.splice(index, 1)[0];
                    if (removed && removed.path) {
                        this.deletedExtraPaths.push(removed.path);
                    }
                },

                insertFormat(prefix, suffix) {
                    const el = this.$refs.descInput;
                    const start = el.selectionStart;
                    const end = el.selectionEnd;
                    const text = el.value;
                    const selected = text.substring(start, end);
                    el.value = text.substring(0, start) + prefix + selected + suffix + text.substring(end);
                    el.focus();
                },

                previewMain(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.mainPreview = URL.createObjectURL(file);
                    }
                },

                previewExtra(event, index) {
                    const file = event.target.files[0];
                    if (file) {
                        this.extraPreviews[index] = URL.createObjectURL(file);
                    }
                }
            }
        }
    </script>
</body>
</html>
