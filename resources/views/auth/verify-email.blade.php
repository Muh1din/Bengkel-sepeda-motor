<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email | Nirwana Garage</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md bg-brand-navy border border-slate-700 rounded-2xl shadow-2xl p-8">

        {{-- Brand Accent --}}
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 bg-slate-800 border border-brand-gold/30 rounded-2xl flex items-center justify-center">
                <i data-lucide="mail-check" class="w-10 h-10 text-brand-gold"></i>
            </div>
        </div>

        {{-- Heading --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white">
                Verifikasi Email Anda
            </h1>

            <div class="w-16 h-1 bg-brand-gold rounded-full mx-auto mt-4 mb-5"></div>

            <p class="text-sm text-brand-silver leading-relaxed">
                Terima kasih telah bergabung dengan
                <span class="text-brand-gold font-semibold">Nirwana Garage</span>.
                Sebelum melanjutkan, silakan verifikasi alamat email
                Anda melalui tautan yang telah kami kirimkan.
            </p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div
                class="mb-4 rounded-lg bg-green-500/10 border border-green-500/30 p-3 text-sm text-green-400 flex items-start gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Status Message --}}
        @if (session('status') === 'verification-link-sent')
            <div
                class="mb-4 rounded-lg bg-green-500/10 border border-green-500/30 p-3 text-sm text-green-400 flex items-start gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>
                    Tautan verifikasi baru berhasil dikirim ke alamat email Anda.
                </span>
            </div>
        @endif

        {{-- Error Message --}}
        @if ($errors->any())
            <div
                class="mb-4 rounded-lg bg-red-500/10 border border-red-500/30 p-3 text-sm text-red-400 flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Resend Verification Email --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit"
                class="w-full bg-brand-gold hover:bg-amber-500 text-brand-navy font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf

            <button type="submit"
                class="w-full bg-slate-800 hover:bg-slate-700 border border-slate-700 text-brand-silver hover:text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Keluar dari Akun
            </button>
        </form>

        {{-- Footer --}}
        <div class="border-t border-slate-700 mt-8 pt-5">
            <p class="text-center text-xs text-brand-silver leading-relaxed">
                <i data-lucide="info" class="w-3.5 h-3.5 inline-block align-middle mr-1 text-brand-gold"></i>
                Jika tidak menemukan email, periksa folder Spam atau Junk.
            </p>
        </div>

    </div>

</body>

</html>
