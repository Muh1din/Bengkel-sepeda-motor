<x-layouts.admin title="Kelola Slot Booking">
    <div class="space-y-6">

        {{-- Page Header --}}
        <div>
            <p class="mt-1 text-sm text-gray-500">
                Atur kapasitas maksimal booking untuk operasional Nirwana Garage.
            </p>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                <p class="text-sm font-semibold text-red-700">
                    Pengaturan gagal disimpan.
                </p>

                <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Booking Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Maximum Capacity --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">
                        Kapasitas Maksimal
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.196-2.04M17 20H7m10 0v-2c0-.728-.195-1.41-.536-2M7 20H2v-2a3 3 0 015.196-2.04M7 20v-2c0-.728.195-1.41.536-2m0 0a5.002 5.002 0 019.928 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-3xl font-bold text-ui-text">
                        {{ number_format($maxBookingSlots, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-500">
                            Slot
                        </span>
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Batas booking aktif yang dapat ditangani.
                    </p>
                </div>
            </div>

            {{-- Used Capacity --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">
                        Slot Terpakai
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-3xl font-bold text-ui-text">
                        {{ number_format($usedBookingSlots, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-500">
                            Slot
                        </span>
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Booking berstatus CONFIRMED dan IN_PROGRESS.
                    </p>
                </div>
            </div>

            {{-- Remaining Capacity --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-500">
                        Slot Tersedia
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-3xl font-bold text-ui-text">
                        {{ number_format($remainingBookingSlots, 0, ',', '.') }}
                        <span class="text-sm font-medium text-gray-500">
                            Slot
                        </span>
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Slot yang masih dapat digunakan untuk booking baru.
                    </p>
                </div>
            </div>

        </div>

        {{-- Capacity Settings --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
                <h2 class="text-base font-bold text-ui-text">
                    Pengaturan Kapasitas
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Tentukan jumlah maksimal booking aktif secara keseluruhan.
                </p>
            </div>

            <form
                action="{{ route('admin.booking-settings.update') }}"
                method="POST"
                class="space-y-5 p-5 sm:p-6"
            >
                @csrf
                @method('PUT')

                <div class="max-w-xl">
                    <label
                        for="max_booking_slots"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Kapasitas Maksimal Booking
                    </label>

                    <div class="flex items-center gap-3">
                        <input
                            type="number"
                            id="max_booking_slots"
                            name="max_booking_slots"
                            value="{{ old('max_booking_slots', $maxBookingSlots) }}"
                            min="1"
                            max="10000"
                            step="1"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#c58e4b] focus:ring-2 focus:ring-[#c58e4b]/20"
                        >

                        <span class="text-sm font-medium text-gray-500">
                            Slot
                        </span>
                    </div>

                    @error('max_booking_slots')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs leading-5 text-gray-500">
                        Masukkan bilangan bulat antara 1 dan 10.000.
                        Kapasitas tidak boleh lebih kecil daripada jumlah slot
                        yang sedang terpakai.
                    </p>
                </div>

                <div class="flex flex-col gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-gray-500">
                        Perubahan akan memengaruhi kapasitas booking di halaman utama.
                    </p>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#1b263b] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#293a56] focus:outline-none focus:ring-2 focus:ring-[#1b263b]/30 focus:ring-offset-2"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>

        {{-- Active Vehicle --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
                <h2 class="text-base font-bold text-ui-text">
                    Kendaraan Sedang Dikerjakan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Kendaraan dengan booking yang saat ini berstatus IN_PROGRESS.
                </p>
            </div>

            <div class="p-5 sm:p-6">
                @if ($activeVehicle)
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-ui-text">
                                {{ $activeVehicle->vehicle->brand ?? 'Kendaraan' }}
                                {{ $activeVehicle->vehicle->model ?? '' }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Kode Booking:
                                {{ $activeVehicle->booking_code ?? $activeVehicle->id }}
                            </p>
                        </div>

                        <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                            IN_PROGRESS
                        </span>
                    </div>
                @else
                    <div class="py-5 text-center">
                        <p class="text-sm font-medium text-gray-600">
                            Tidak ada kendaraan yang sedang dikerjakan.
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Informasi akan muncul ketika booking mulai diproses.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layouts.admin>