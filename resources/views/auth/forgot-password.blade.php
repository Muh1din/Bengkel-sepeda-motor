<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password | Nirwana Garage</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-ui-main text-ui-text font-sans antialiased min-h-screen flex items-center justify-center p-4">

    {{-- Forgot Password Card --}}
    <div class="w-full max-w-md bg-ui-card border border-ui-border rounded-3xl shadow-2xl overflow-hidden p-8 sm:p-10">

        {{-- Logo --}}
        <div class="text-center mb-8">

            <img src="{{ asset('logo/logo.jpeg') }}" alt="Logo Nirwana Garage"
                class="w-16 h-16 mx-auto mb-4 rounded-2xl shadow-md object-cover border-2 border-brand-gold">

            <h1 class="text-2xl font-bold text-brand-navy tracking-tight">
                Nirwana Garage
            </h1>

            <p class="text-sm text-brand-steel mt-1.5">
                Account Recovery
            </p>

        </div>

        {{-- Heading --}}
        <div class="text-center mb-8">

            <div
                class="inline-flex items-center justify-center w-14 h-14 rounded-2xl
                bg-brand-gold/10 border border-brand-gold/20 mb-4">

                <svg class="w-7 h-7 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>

                </svg>

            </div>

            <h2 class="text-xl font-bold text-brand-navy">
                Lupa Password?
            </h2>

            <p class="text-sm text-brand-steel mt-2 leading-relaxed">
                Jangan khawatir. Masukkan alamat email yang terdaftar
                pada akun Customer Anda. Kami akan mengirimkan tautan
                untuk mengatur ulang password.
            </p>

        </div>

        {{-- Success / Status --}}
        @if (session('status'))
            <div role="status" aria-live="polite"
                class="mb-5 p-4 bg-emerald-500/10 border border-emerald-500/20
                    rounded-xl text-emerald-600 text-sm flex items-start gap-3">

                <svg class="w-5 h-5 shrink-0 text-emerald-500 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>

                </svg>

                <span>{{ session('status') }}</span>

            </div>
        @endif

        {{-- Email Error --}}
        @error('email')
            <div role="alert"
                class="mb-5 p-4 bg-red-500/10 border border-red-500/20
                    rounded-xl text-red-600 text-sm flex items-start gap-3">

                <svg class="w-5 h-5 shrink-0 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>

                </svg>

                <span>{{ $message }}</span>

            </div>
        @enderror

        {{-- Forgot Password Form --}}
        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">

            @csrf

            {{-- Email --}}
            <div>

                <label for="email" class="block text-sm font-semibold mb-1.5 text-brand-navy">

                    Alamat Email

                </label>

                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email" placeholder="nama@email.com"
                    class="w-full px-4 py-3 bg-ui-main border
                        @error('email')
                            border-red-500 focus:ring-red-500
                        @else
                            border-ui-border focus:ring-brand-gold
                        @enderror
                        rounded-xl text-ui-text
                        focus:outline-none focus:ring-2
                        focus:border-transparent
                        transition duration-200">

                <p class="text-xs text-brand-steel mt-2">
                    Gunakan email yang terdaftar pada akun Customer Anda.
                </p>

            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3.5 px-4 bg-brand-navy hover:bg-[#23314a]
                    text-white font-semibold rounded-xl shadow-lg
                    hover:shadow-xl transition-all duration-200
                    flex items-center justify-center gap-2
                    border border-brand-gold/20 cursor-pointer">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>

                </svg>

                <span>Kirim Link Reset Password</span>

            </button>

        </form>

        {{-- Divider --}}
        <div class="flex items-center justify-center gap-3 my-6">

            <span class="h-px flex-1 bg-ui-border"></span>

            <span class="text-xs font-medium tracking-wider text-brand-steel">
                ATAU
            </span>

            <span class="h-px flex-1 bg-ui-border"></span>

        </div>

        {{-- Back to Login --}}
        <a href="{{ route('login') }}"
            class="w-full py-3 px-4 bg-ui-main hover:bg-ui-card
                text-brand-steel hover:text-brand-navy font-semibold
                rounded-xl border border-ui-border
                transition-all duration-200
                flex items-center justify-center gap-2">

            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>

            </svg>

            <span>Kembali ke Login</span>

        </a>

        {{-- Footer --}}
        <div class="mt-8 text-center">

            <p class="text-xs text-brand-steel">
                &copy; {{ date('Y') }} Nirwana Garage. All rights reserved.
            </p>

        </div>

    </div>

</body>

</html>
