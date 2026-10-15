<x-layouts.owner title="Edit Pegawai">

    <div class="space-y-5">

        {{-- Header --}}
        <div>
            <a href="{{ route('owner.employees.index') }}" class="text-sm text-gray-500 transition hover:text-[#1b263b]">
                &larr; Kembali ke daftar pegawai
            </a>

            <h1 class="mt-3 text-xl font-semibold text-[#1b263b]">
                Edit Pegawai
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi akun pegawai.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-lg border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-[#1b263b]">
                    Informasi Akun Pegawai
                </h2>
            </div>

            <form action="{{ route('owner.employees.update', $user) }}" method="POST" class="space-y-5 p-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Nama Lengkap
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        maxlength="255" autocomplete="name"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#1b263b] focus:ring-1 focus:ring-[#1b263b] @error('name') border-red-500 @enderror">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Email
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        required maxlength="255" autocomplete="email"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#1b263b] focus:ring-1 focus:ring-[#1b263b] @error('email') border-red-500 @enderror">

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Nomor Telepon
                    </label>

                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        maxlength="20" autocomplete="tel"
                        class="w-full rounded-md border border-gray-300 px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#1b263b] focus:ring-1 focus:ring-[#1b263b] @error('phone') border-red-500 @enderror">

                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Jabatan --}}
                <div>
                    <label for="role_id" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Jabatan
                        <span class="text-red-500">*</span>
                    </label>

                    <select id="role_id" name="role_id" required
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#1b263b] focus:ring-1 focus:ring-[#1b263b] @error('role_id') border-red-500 @enderror">
                        <option value="">Pilih jabatan</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected((string) old('role_id', $user->role_id) === (string) $role->id)>
                                @switch($role->name)
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
                                        {{ $role->name }}
                                @endswitch
                            </option>
                        @endforeach
                    </select>

                    @error('role_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Informasi Password --}}
                <div class="rounded-md border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm font-medium text-gray-700">
                        Password tidak diubah
                    </p>

                    <p class="mt-1 text-xs leading-5 text-gray-500">
                        Perubahan informasi pegawai tidak akan mengubah password
                        akun. Gunakan fitur reset password jika diperlukan.
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
                        Simpan Perubahan
                    </button>

                </div>

            </form>
        </div>

    </div>

</x-layouts.owner>
