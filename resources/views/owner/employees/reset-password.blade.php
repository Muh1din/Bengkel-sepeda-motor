<x-layouts.owner title="Reset Password Pegawai">

    <div class="space-y-5">

        {{-- Header --}}
        <div>
            <a href="{{ route('owner.employees.index') }}" class="text-sm text-gray-500 transition hover:text-[#1b263b]">
                &larr; Kembali ke daftar pegawai
            </a>

            <h1 class="mt-3 text-xl font-semibold text-[#1b263b]">
                Reset Password Pegawai
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tetapkan password baru untuk akun pegawai.
            </p>
        </div>

        {{-- Informasi Pegawai --}}
        <div class="rounded-lg border border-gray-200 bg-white p-5">
            <h2 class="text-sm font-semibold text-[#1b263b]">
                Informasi Pegawai
            </h2>

            <div class="mt-4 space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $user->name }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="mt-1 font-medium text-gray-800">
                        {{ $user->email }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Jabatan</p>
                    <p class="mt-1 font-medium text-gray-800">
                        @switch($user->role->name)
                            @case('ServiceAdvisor')
                                Service Advisor
                            @break

                            @case('Mechanic')
                                Mekanik
                            @break

                            @case('Admin')
                                Admin
                            @break

                            @default
                                {{ $user->role->name }}
                        @endswitch
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Reset Password --}}
        <div class="rounded-lg border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-[#1b263b]">
                    Password Baru
                </h2>
            </div>

            <form action="{{ route('owner.employees.reset-password.update', $user) }}" method="POST"
                class="space-y-5 p-5">
                @csrf
                @method('PUT')

                {{-- Password --}}
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Password Baru
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="password" id="password" name="password" required minlength="8"
                        autocomplete="new-password" placeholder="Minimal 8 karakter"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#1b263b] focus:ring-1 focus:ring-[#1b263b] @error('password') border-red-500 @enderror">

                    @error('password')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Konfirmasi Password
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        minlength="8" autocomplete="new-password" placeholder="Masukkan ulang password"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#1b263b] focus:ring-1 focus:ring-[#1b263b]">
                </div>

                {{-- Peringatan --}}
                <div class="rounded-md border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm leading-6 text-gray-600">
                        Setelah password direset, berikan password baru
                        kepada pegawai melalui saluran komunikasi yang aman.
                        Jangan menyimpan atau membagikan password di tempat
                        yang dapat diakses pihak lain.
                    </p>
                </div>

                {{-- Tombol --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:justify-end">

                    <a href="{{ route('owner.employees.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-[#1b263b] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#2d3c55]">
                        Reset Password
                    </button>

                </div>

            </form>
        </div>

    </div>

</x-layouts.owner>
