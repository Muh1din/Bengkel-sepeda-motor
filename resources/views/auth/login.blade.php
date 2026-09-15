<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Nirwana Garage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-ui-main text-ui-text font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <!-- Login Card -->
    <div class="w-full max-w-md bg-ui-card border border-ui-border rounded-3xl shadow-2xl overflow-hidden p-8 sm:p-10">

        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo/logo.jpeg') }}" alt="Logo Nirwana Garage"
                class="w-16 h-16 mx-auto mb-4 rounded-2xl shadow-md object-cover border-2 border-brand-gold">
            <h1 class="text-2xl font-bold text-brand-navy tracking-tight">Nirwana Garage</h1>
            <p class="text-sm text-brand-steel mt-1.5">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Global Error -->
        @if (session('error'))
            <div
                class="mb-5 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-600 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Success / Status -->
        @if (session('status'))
            <div
                class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-600 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5" autocomplete="off">
            @csrf

            <!-- Identity -->
            <div>
                <label for="identity" class="block text-sm font-semibold mb-1.5 text-brand-navy">
                    Email / Nomor Telepon
                </label>
                <input type="text" id="identity" name="identity" value="{{ old('identity') }}" required autofocus
                    autocomplete="off" placeholder="Email / Nomor telepon"
                    class="w-full px-4 py-3 bg-ui-main border @error('identity') border-red-500 focus:ring-red-500 @else border-ui-border focus:ring-brand-gold @enderror rounded-xl text-ui-text focus:outline-none focus:ring-2 focus:border-transparent transition duration-200">

                @error('identity')
                    <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-semibold text-brand-navy">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-brand-steel hover:text-brand-gold transition-colors font-medium">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <div class="relative flex items-center">
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 bg-ui-main border @error('password') border-red-500 focus:ring-red-500 @else border-ui-border focus:ring-brand-gold @enderror rounded-xl text-ui-text focus:outline-none focus:ring-2 focus:border-transparent transition duration-200 pr-12">

                    <!-- Toggle Password -->
                    <button type="button" id="togglePassword" aria-label="Tampilkan password"
                        class="absolute right-3 text-brand-steel hover:text-brand-navy focus:outline-none p-1">
                        <!-- Eye Open -->
                        <svg id="eyeOpenIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <!-- Eye Closed -->
                        <svg id="eyeClosedIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 11-18 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center text-sm">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" value="1"
                        class="w-4 h-4 text-brand-gold border-ui-border rounded focus:ring-brand-gold accent-brand-gold">
                    <span class="ml-2 text-brand-steel">Ingat saya</span>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full py-3.5 px-4 bg-brand-navy hover:bg-[#23314a] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-2 border border-brand-gold/20 cursor-pointer">
                <span>Masuk</span>
            </button>
        </form>

        <!-- Register -->
        @if (Route::has('register'))
            <div class="mt-8 text-center text-sm text-brand-steel">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-brand-gold font-semibold hover:underline">
                    Daftar Sekarang
                </a>
            </div>
        @endif
    </div>

</body>

</html>
