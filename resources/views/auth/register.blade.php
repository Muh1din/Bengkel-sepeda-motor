<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | Nirwana Garage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-ui-main text-ui-text font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <!-- Card Container -->
    <div class="w-full max-w-md bg-ui-card border border-ui-border rounded-3xl shadow-2xl overflow-hidden p-8 sm:p-10">

        <!-- BAGIAN LOGO & KOP KORPORAT -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo/logo.jpeg') }}" alt="Logo"
                class="w-16 h-16 mx-auto mb-4 rounded-2xl shadow-md object-cover border-2 border-brand-gold">

            <h1 class="text-2xl font-bold text-brand-navy tracking-tight">Nirwana Garage</h1>
            <p class="text-sm text-brand-steel mt-1.5">Lengkapi data di bawah ini untuk pendaftaran akun.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 p-4 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Form Registrasi -->
        <form id="registerForm" class="space-y-4" action="{{ route('register.store') }}" method="POST">
            @csrf
            <!-- Nama  -->
            <div>
                <label for="name" class="block text-sm font-semibold mb-1 text-brand-navy">Nama</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-3 bg-ui-main border border-ui-border rounded-xl text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-gold focus:border-transparent transition duration-200"
                    placeholder="Masukkan nama">
            </div>

            <!-- Email untuk Login -->
            <div>
                <label for="email" class="block text-sm font-semibold mb-1 text-brand-navy">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-3 bg-ui-main border border-ui-border rounded-xl text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-gold focus:border-transparent transition duration-200"
                    placeholder="nama@gmail.com">
            </div>

            <!-- Nomor HP / Kontak Pelanggan -->
            <div>
                <label for="phone" class="block text-sm font-semibold mb-1 text-brand-navy">Nomor HP</label>
                <div
                    class="flex rounded-xl shadow-sm border border-ui-border overflow-hidden focus-within:ring-2 focus-within:ring-brand-gold focus-within:border-transparent transition duration-200 bg-ui-main">
                    <input type="tel" id="phone" name="phone" required
                        class="w-full px-4 py-3 bg-ui-main text-ui-text focus:outline-none" placeholder="081234567890">
                </div>
            </div>

            <!-- Password Utama -->
            <div>
                <label for="password" class="block text-sm font-semibold mb-1 text-brand-navy">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-ui-main border border-ui-border rounded-xl text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-gold focus:border-transparent transition duration-200 pr-12"
                        placeholder="********">

                    <button type="button" onclick="toggleField('password', 'eyeClosedIcon', 'eyeOpenIcon')"
                        aria-label="Tampilkan password"
                        class="absolute right-3 text-brand-steel hover:text-brand-navy focus:outline-none p-1 z-10 cursor-pointer">
                        <svg id="eyeClosedIcon" class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                            </path>
                        </svg>
                        <svg id="eyeOpenIcon" class="w-5 h-5 hidden pointer-events-none" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="confirmPassword" class="block text-sm font-semibold mb-1 text-brand-navy">Konfirmasi
                    Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="confirmPassword" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-ui-main border border-ui-border rounded-xl text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-gold focus:border-transparent transition duration-200 pr-12"
                        placeholder="********">

                    <button type="button"
                        onclick="toggleField('confirmPassword', 'eyeClosedIconConfirm', 'eyeOpenIconConfirm')"
                        aria-label="Tampilkan konfirmasi password"
                        class="absolute right-3 text-brand-steel hover:text-brand-navy focus:outline-none p-1 z-10 cursor-pointer">
                        <svg id="eyeClosedIconConfirm" class="w-5 h-5 pointer-events-none" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                            </path>
                        </svg>
                        <svg id="eyeOpenIconConfirm" class="w-5 h-5 hidden pointer-events-none" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </button>
                </div>
                <p id="passwordError" class="text-xs text-red-500 mt-1 hidden">Konfirmasi password tidak sesuai.</p>
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                class="w-full py-3.5 px-4 bg-brand-navy hover:bg-[#23314a] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-2 border border-brand-gold/20 mt-2">
                <span id="btnText">Daftar</span>
                <!-- Loading Spinner -->
                <svg id="loadingSpinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-brand-gold"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </button>
        </form>

        <!-- Footer Card -->
        <div class="mt-6 text-center text-sm text-brand-steel">
            Sudah terdaftar sebagai pelanggan? <a href="{{ route('login') }}"
                class="text-brand-gold font-semibold hover:underline">Masuk</a>
        </div>
    </div>
</body>

</html>
