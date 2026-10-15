<x-layouts.owner title="Booking">

    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-slate-500">
                    Pantau seluruh booking layanan Nirwana Garage.
                </p>
            </div>
        </div>

        {{-- Booking Summary --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">
                        Total Booking Ditampilkan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-800">
                        {{ $bookings->total() }}
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Seluruh booking yang tercatat dalam sistem
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Booking Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Table Header --}}
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-800">
                    Daftar Booking
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi booking pelanggan yang tersimpan di sistem.
                </p>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">

                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Booking</th>
                            <th class="px-5 py-4">Pelanggan</th>
                            <th class="px-5 py-4">Kendaraan</th>
                            <th class="px-5 py-4">Layanan</th>
                            <th class="px-5 py-4">Jadwal</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($bookings as $booking)
                            @php
                                $statusConfig = match ($booking->status) {
                                    'PENDING' => [
                                        'label' => 'Menunggu',
                                        'class' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    ],
                                    'CONFIRMED' => [
                                        'label' => 'Dikonfirmasi',
                                        'class' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    ],
                                    'IN_PROGRESS' => [
                                        'label' => 'Diproses',
                                        'class' => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                                    ],
                                    'COMPLETED' => [
                                        'label' => 'Selesai',
                                        'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    ],
                                    'REJECTED' => [
                                        'label' => 'Ditolak',
                                        'class' => 'bg-red-50 text-red-700 ring-red-600/20',
                                    ],
                                    'CANCELLED' => [
                                        'label' => 'Dibatalkan',
                                        'class' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                    ],
                                    default => [
                                        'label' => $booking->status,
                                        'class' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                    ],
                                };
                            @endphp

                            <tr class="transition-colors hover:bg-slate-50">

                                {{-- Booking Code --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $booking->booking_code }}
                                    </div>
                                </td>

                                {{-- Customer --}}
                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-700">
                                        {{ $booking->customer->name ?? 'Pelanggan tidak tersedia' }}
                                    </div>
                                </td>

                                {{-- Vehicle --}}
                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-700">
                                        {{ $booking->vehicle->license_plate ?? '-' }}
                                    </div>
                                </td>

                                {{-- Service --}}
                                <td class="px-5 py-4">
                                    <span class="text-slate-600">
                                        {{ $booking->service_type }}
                                    </span>
                                </td>

                                {{-- Schedule --}}
                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusConfig['class'] }}">
                                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current"></span>

                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">

                                    <div class="flex flex-col items-center">
                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 font-semibold text-slate-700">
                                            Belum Ada Booking
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Booking pelanggan akan muncul di halaman ini.
                                        </p>
                                    </div>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $bookings->links() }}
                </div>
            @endif

        </div>

    </div>

</x-layouts.owner>
