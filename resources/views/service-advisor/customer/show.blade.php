<x-layouts.service-advisor title="Detail Pelanggan">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">

            <div>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap pelanggan, kendaraan, dan riwayat servis.
                </p>
            </div>

            <a href="{{ route('service-advisor.customer.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-ui-border
                       bg-ui-card px-4 py-2 text-sm font-medium text-ui-text
                       transition hover:bg-gray-50">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Kembali
            </a>

        </div>


        {{-- Data Pribadi --}}
        <div class="rounded-xl border border-ui-border bg-ui-card shadow-sm">

            <div class="border-b border-ui-border px-6 py-4">
                <h2 class="font-semibold text-brand-navy">
                    Data Pribadi
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi akun pelanggan.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                {{-- Nama --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                        Nama
                    </p>

                    <p class="mt-1 font-medium text-ui-text">
                        {{ $customer->name }}
                    </p>
                </div>

                {{-- Phone --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                        Nomor Telepon
                    </p>

                    <p class="mt-1 font-medium text-ui-text">
                        {{ $customer->phone }}
                    </p>
                </div>

                {{-- Email --}}
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                        Email
                    </p>

                    <p class="mt-1 font-medium text-ui-text">
                        {{ $customer->email ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                        Alamat
                    </p>

                    <p class="mt-1 font-medium text-ui-text">
                        {{ $customer->address ?? '-' }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Kendaraan --}}
        <div class="rounded-xl border border-ui-border bg-ui-card shadow-sm">

            <div class="border-b border-ui-border px-6 py-4">
                <h2 class="font-semibold text-brand-navy">
                    Kendaraan
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Kendaraan yang terdaftar atas nama pelanggan.
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="border-b border-ui-border bg-gray-50">

                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Kendaraan
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Nomor Polisi
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Tahun
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Warna
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($customer->vehicles as $vehicle)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">
                                        <div>

                                            <p class="font-medium text-ui-text">
                                                {{ $vehicle->brand ?? '-' }}
                                                {{ $vehicle->model ?? '' }}
                                            </p>

                                            <p class="text-xs text-gray-500">
                                                {{ $vehicle->type ?? '-' }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm font-medium text-ui-text">
                                    {{ $vehicle->license_plate ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $vehicle->year ?? '-' }}
                                </td>

                                 <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $vehicle->color ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-10 text-center">

                                    <i data-lucide="bike" class="mx-auto mb-3 h-8 w-8 text-gray-400"></i>

                                    <p class="font-medium text-ui-text">
                                        Belum ada kendaraan
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Pelanggan belum memiliki kendaraan terdaftar.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Riwayat Servis --}}
        <div class="rounded-xl border border-ui-border bg-ui-card shadow-sm">

            <div class="border-b border-ui-border px-6 py-4">

                <h2 class="font-semibold text-brand-navy">
                    Riwayat Servis
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Riwayat booking dan pekerjaan servis pelanggan.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="border-b border-ui-border bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Booking
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Kendaraan
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Layanan
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                WO
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($customer->bookings as $booking)
                            <tr class="transition hover:bg-gray-50">

                                {{-- Booking --}}
                                <td class="px-6 py-4">

                                    <p class="font-medium text-ui-text">
                                        {{ $booking->booking_code }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $booking->booking_date ?? '-' }}
                                    </p>

                                </td>

                                {{-- Vehicle --}}
                                <td class="px-6 py-4 text-sm text-gray-600">

                                    @if ($booking->vehicle)
                                        {{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}

                                        <div class="text-xs text-gray-500">
                                            {{ $booking->vehicle->license_plate }}
                                        </div>
                                    @else
                                        -
                                    @endif

                                </td>

                                {{-- Service --}}
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $booking->service_type ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-3 py-1
                                                 text-xs font-medium text-gray-700">

                                        {{ $booking->status }}

                                    </span>

                                </td>

                                {{-- Work Order --}}
                                <td class="px-6 py-4 text-sm font-medium text-brand-navy">

                                    @if ($booking->workOrder)
                                        {{ $booking->workOrder->work_order_code }}
                                    @else
                                        -
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-10 text-center">

                                    <i data-lucide="history" class="mx-auto mb-3 h-8 w-8 text-gray-400"></i>

                                    <p class="font-medium text-ui-text">
                                        Belum ada riwayat servis
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Riwayat servis pelanggan akan muncul di sini.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts.service-advisor>
