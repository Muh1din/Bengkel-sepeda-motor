<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password | Nirwana Garage</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-ui-main text-ui-text font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="w-full bg-ui-card border border-ui-border rounded-3xl shadow-2xl overflow-hidden p-8 sm:p-10">

            {{-- Brand --}}
            <div class="text-center mb-8">
                <img src="{{ asset('logo/logo.jpeg') }}" alt="Logo Nirwana Garage"
                    class="w-16 h-16 mx-auto mb-4 rounded-2xl shadow-md object-cover border-2 border-brand-gold">

                <h1 class="text-2xl font-bold text-brand-navy tracking-tight">
                    Nirwana Garage
                </h1>

                <p class="text-sm text-brand-steel mt-1">
                    Account Security
                </p>
            </div>

            {{-- Heading --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-brand-gold"></i>

                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel">
                        Account Recovery
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-brand-navy">
                    Buat Password Baru
                </h2>

                <p class="text-sm text-brand-steel mt-2 leading-relaxed">
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
                    <label for="email" class="block text-sm font-semibold text-ui-text mb-2">
                        Alamat Email
                    </label>

                    <div class="relative">
                        <i data-lucide="mail"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-brand-steel"></i>

                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                            placeholder="nama@email.com" autocomplete="email" required autofocus
                            class="w-full pl-12 pr-4 py-3 rounded-xl bg-ui-main border border-ui-border
                                   text-ui-text placeholder:text-brand-steel
                                   focus:outline-none focus:ring-2 focus:ring-brand-gold
                                   focus:border-brand-gold transition
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
                    <label for="password" class="block text-sm font-semibold text-ui-text mb-2">
                        Password Baru
                    </label>

                    <div class="relative">
                        <i data-lucide="lock"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-brand-steel"></i>

                        <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                            autocomplete="new-password" required
                            class="w-full pl-12 pr-12 py-3 rounded-xl bg-ui-main border border-ui-border
                                   text-ui-text placeholder:text-brand-steel
                                   focus:outline-none focus:ring-2 focus:ring-brand-gold
                                   focus:border-brand-gold transition
                                   @error('password') border-red-500 focus:ring-red-400 @enderror">

                        <button type="button" data-password-toggle data-target="password"
                            aria-label="Tampilkan password" aria-controls="password"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-steel hover:text-brand-gold transition">
                            <span data-eye-show>
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </span>

                            <span data-eye-hide class="hidden">
                                <i data-lucide="eye-off" class="w-5 h-5"></i>
                            </span>
                        </button>
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
                    <label for="password_confirmation" class="block text-sm font-semibold text-ui-text mb-2">
                        Konfirmasi Password
                    </label>

                    <div class="relative">
                        <i data-lucide="lock-keyhole"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-brand-steel"></i>

                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Masukkan ulang password" autocomplete="new-password" required
                            class="w-full pl-12 pr-12 py-3 rounded-xl bg-ui-main border border-ui-border
                                   text-ui-text placeholder:text-brand-steel
                                   focus:outline-none focus:ring-2 focus:ring-brand-gold
                                   focus:border-brand-gold transition">

                        <button type="button" data-password-toggle data-target="password_confirmation"
                            aria-label="Tampilkan password" aria-controls="password_confirmation"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-steel hover:text-brand-gold transition">
                            <span data-eye-show>
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </span>

                            <span data-eye-hide class="hidden">
                                <i data-lucide="eye-off" class="w-5 h-5"></i>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2
                           bg-brand-navy hover:bg-[#23314a]
                           text-white font-bold py-3 px-4 rounded-xl
                           border border-brand-gold/20
                           transition duration-200 shadow-lg">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    Reset Password
                </button>
            </form>

            {{-- Back to Login --}}
            <div class="mt-6 pt-5 border-t border-ui-border text-center">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold
                           text-brand-steel hover:text-brand-navy transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Login
                </a>
            </div>

        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-brand-steel mt-6">
            &copy; {{ date('Y') }} Nirwana Garage. All rights reserved.
        </p>

    </div>

    {{-- Show / Hide Password --}}
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);

                if (!input) return;

                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';

                button.querySelector('[data-eye-show]').classList.toggle('hidden', isPassword);
                button.querySelector('[data-eye-hide]').classList.toggle('hidden', !isPassword);

                button.setAttribute(
                    'aria-label',
                    isPassword ? 'Sembunyikan password' : 'Tampilkan password'
                );
            });
        });
    </script>

</body>

</html>
