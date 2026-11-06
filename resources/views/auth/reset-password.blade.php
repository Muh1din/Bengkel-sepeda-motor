<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password | Nirwana Garage</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-md">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-400 text-slate-950 mb-4">
                <i data-lucide="wrench" class="w-8 h-8"></i>
            </div>

            <h1 class="text-2xl font-bold text-white tracking-wide">
                NIRWANA <span class="text-amber-400">GARAGE</span>
            </h1>

            <p class="text-slate-400 text-sm mt-2">
                Motorcycle Service Management
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">

            {{-- Heading --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-500"></i>

                    <span class="text-xs font-semibold uppercase tracking-wider text-amber-600">
                        Account Security
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-slate-900">
                    Buat Password Baru
                </h2>

                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Silakan buat password baru untuk akun Customer Anda.
                    Pastikan password mudah diingat dan sulit ditebak.
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf

                {{-- Token --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Alamat Email
                    </label>

                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400">
                        </i>

                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                            placeholder="nama@email.com" autocomplete="email" required autofocus
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200
                            text-slate-900 placeholder:text-slate-400
                            focus:outline-none focus:ring-2 focus:ring-amber-400
                            focus:border-amber-400 transition
                            @error('email') border-red-500 focus:ring-red-400 @enderror">
                    </div>

                    @error('email')
                        <p class="flex items-center gap-1.5 text-sm text-red-600 mt-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Baru
                    </label>

                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400">
                        </i>

                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                            autocomplete="new-password" required
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200
                            text-slate-900 placeholder:text-slate-400
                            focus:outline-none focus:ring-2 focus:ring-amber-400
                            focus:border-amber-400 transition
                            @error('password') border-red-500 focus:ring-red-400 @enderror">
                    </div>

                    @error('password')
                        <p class="flex items-center gap-1.5 text-sm text-red-600 mt-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                        Konfirmasi Password
                    </label>

                    <div class="relative">
                        <i data-lucide="lock-keyhole"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400">
                        </i>

                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Masukkan ulang password" autocomplete="new-password" required
                            class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200
                            text-slate-900 placeholder:text-slate-400
                            focus:outline-none focus:ring-2 focus:ring-amber-400
                            focus:border-amber-400 transition">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2
                    bg-amber-400 hover:bg-amber-500
                    text-slate-950 font-bold py-3 px-4 rounded-xl
                    transition duration-200 shadow-lg shadow-amber-400/20">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    Reset Password
                </button>

            </form>

            {{-- Back to Login --}}
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold
                    text-slate-600 hover:text-amber-600 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Login
                </a>
            </div>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-500 mt-6">
            &copy; {{ date('Y') }} Nirwana Garage. All rights reserved.
        </p>

    </div>

</body>

</html>
