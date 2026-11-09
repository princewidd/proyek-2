<x-guest-layout>
    <div class="flex flex-row min-h-[540px] bg-white relative text-gray-900">
        
        <!-- Tombol Close Pop-Up (Pojok Kanan Atas) -->
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-black transition z-20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>

        <!-- KOLOM KIRI: Logo & Brand -->
        <div class="w-1/2 p-8 md:p-12 flex flex-col justify-center items-center text-center border-r border-gray-200">
            <div class="max-w-xs space-y-4">
                <img src="/img/logoLR.png" alt="ZEROSHOP Logo" class="w-52 h-auto mx-auto object-contain">
                <div>
                    <h1 class="text-2xl font-extrabold text-[#111827] tracking-wider uppercase">ZEROSHOP</h1>
                    <p class="text-xs text-gray-400 font-medium mt-1">Game Boosting & Account Store</p>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Form Reset Password -->
        <div class="w-1/2 p-8 md:p-10 flex flex-col justify-center">
            
            <!-- Judul Utama (Center) -->
            <div class="mb-2 text-center">
                <h2 class="text-2xl font-bold text-[#111827]">Reset Password</h2>
                <p class="text-xs font-semibold text-gray-700">Buat password baru akun Anda</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-3">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 text-left">Email</label>
                    <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username"
                        placeholder="email@domain.com"
                        class="w-full px-4 py-2.5 bg-[#f3f4f6] border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-600 focus:bg-white focus:ring-1 focus:ring-gray-600 transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- New Password Input -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 text-left">Password Baru</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2.5 bg-[#f3f4f6] border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-600 focus:bg-white focus:ring-1 focus:ring-gray-600 transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 text-left">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full px-4 py-2.5 bg-[#f3f4f6] border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-600 focus:bg-white focus:ring-1 focus:ring-gray-600 transition">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <!-- Tombol Reset Password (Full Width) -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-[#1b2537] hover:bg-[#111827] text-white text-sm font-semibold rounded-xl transition flex items-center justify-center gap-2 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Reset Password</span>
                    </button>
                </div>

                <!-- Spacer -->
                <div class="pt-1"></div>

                <!-- Tombol Akses Cepat Bawah -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}" class="py-2.5 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 transform scale-x-[-1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Masuk
                    </a>

                    <a href="{{ route('register') }}" class="py-2.5 px-4 bg-gray-50 hover:bg-gray-100 border border-gray-300 rounded-xl text-xs font-semibold text-gray-700 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar
                    </a>
                </div>

            </form>
        </div>

    </div>
</x-guest-layout>