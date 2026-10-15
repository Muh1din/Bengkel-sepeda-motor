<x-layouts.owner title="Dashboard Pemilik">
    <div class="space-y-6" id="ownerDashboard" data-chart-labels="{{ json_encode($chartLabels) }}"
        data-booking-data="{{ json_encode($bookingChartData) }}"
        data-completed-data="{{ json_encode($completedChartData) }}"
        data-status-data="{{ json_encode($workOrderStatusData) }}">

        {{-- Header --}}
        <div>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan operasional Nirwana Garage.
            </p>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Total Booking --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Total Booking
                    </p>

                    <i data-lucide="calendar-check" class="h-5 w-5 text-slate-500"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($statistics['total_bookings']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Seluruh booking terdaftar
                </p>
            </div>

            {{-- Pending Booking --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Booking Menunggu
                    </p>

                    <i data-lucide="clock" class="h-5 w-5 text-amber-500"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($statistics['pending_bookings']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Menunggu konfirmasi
                </p>
            </div>

            {{-- Active Work Orders --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Kendaraan Diproses
                    </p>

                    <i data-lucide="wrench" class="h-5 w-5 text-blue-500"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($statistics['active_work_orders']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Work Order belum selesai
                </p>
            </div>

            {{-- Completed Work Orders --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Work Order Selesai
                    </p>

                    <i data-lucide="check-circle" class="h-5 w-5 text-emerald-500"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($statistics['completed_work_orders']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Pekerjaan telah diselesaikan
                </p>
            </div>

            {{-- Total Materials --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Total Material
                    </p>

                    <i data-lucide="package" class="h-5 w-5 text-slate-500"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($statistics['total_materials']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Jenis material terdaftar
                </p>
            </div>

            {{-- Low Stock --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">
                        Material Stok Menipis
                    </p>

                    <i data-lucide="alert-triangle" class="h-5 w-5 text-red-500"></i>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-800">
                    {{ number_format($statistics['low_stock_materials']) }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Stok mencapai batas minimum
                </p>
            </div>
        </div>

        {{-- Grafik Statistik Operasional --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            {{-- Grafik Booking dan Pekerjaan Selesai --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">

                <div class="mb-6">
                    <h2 class="text-base font-semibold text-slate-800">
                        Tren Operasional
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Booking masuk dan Work Order selesai selama tujuh hari terakhir.
                    </p>
                </div>

                <div class="relative h-72">
                    <canvas id="operationalChart"></canvas>
                </div>

            </div>

            {{-- Grafik Status Work Order --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5">

                <div class="mb-6">
                    <h2 class="text-base font-semibold text-slate-800">
                        Status Work Order
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Distribusi seluruh Work Order berdasarkan status saat ini.
                    </p>
                </div>

                <div class="relative h-72">
                    <canvas id="workOrderStatusChart"></canvas>
                </div>

            </div>

        </div>

        {{-- Aktivitas Pekerjaan Kendaraan --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">
                        Aktivitas Pekerjaan Kendaraan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Daftar pekerjaan kendaraan terbaru.
                    </p>
                </div>

                <i data-lucide="clipboard-list" class="h-5 w-5 text-slate-500"></i>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">
                                Kode WO
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                Kendaraan
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                Jenis Servis
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                Mekanik
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                Status
                            </th>

                            <th class="px-5 py-3 font-semibold">
                                Waktu Masuk
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($recentWorkOrders as $workOrder)
                            <tr class="transition-colors hover:bg-slate-50">

                                {{-- Kode WO --}}
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-800">
                                    {{ $workOrder->work_order_code }}
                                </td>

                                {{-- Kendaraan --}}
                                <td class="px-5 py-4">
                                    @if ($workOrder->booking?->vehicle)
                                        <p class="font-medium text-slate-800">
                                            {{ $workOrder->booking->vehicle->brand }}
                                            {{ $workOrder->booking->vehicle->model }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $workOrder->booking->vehicle->license_plate }}
                                        </p>
                                    @else
                                        <span class="text-slate-400">
                                            Data kendaraan tidak tersedia
                                        </span>
                                    @endif
                                </td>

                                {{-- Jenis Servis --}}
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $workOrder->booking?->service_type ?? '-' }}
                                </td>

                                {{-- Mekanik --}}
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $workOrder->mechanic?->name ?? 'Belum ditugaskan' }}
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $status = strtoupper($workOrder->status ?? '');

                                        $statusStyles = [
                                            'PENDING' => 'bg-amber-100 text-amber-700',
                                            'IN_PROGRESS' => 'bg-blue-100 text-blue-700',
                                            'COMPLETED' => 'bg-emerald-100 text-emerald-700',
                                            'CANCELLED' => 'bg-red-100 text-red-700',
                                        ];

                                        $statusLabels = [
                                            'PENDING' => 'Menunggu',
                                            'IN_PROGRESS' => 'Dalam Proses',
                                            'COMPLETED' => 'Selesai',
                                            'CANCELLED' => 'Dibatalkan',
                                        ];
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $statusStyles[$status] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $statusLabels[$status] ?? ($workOrder->status ?? 'Tidak diketahui') }}
                                    </span>
                                </td>

                                {{-- Waktu Masuk --}}
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ $workOrder->received_at?->format('d M Y H:i') ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <i data-lucide="clipboard-list" class="mx-auto h-10 w-10 text-slate-300"></i>

                                    <p class="mt-3 font-medium text-slate-700">
                                        Belum ada aktivitas pekerjaan.
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Aktivitas kendaraan akan muncul setelah Work Order tersedia.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- Footer --}}
            @if ($recentWorkOrders->isNotEmpty())
                <div class="border-t border-slate-200 px-5 py-3">
                    <p class="text-xs text-slate-500">
                        Menampilkan maksimal 5 Work Order terbaru.
                    </p>
                </div>
            @endif

        </div>


    </div>

    @push('scripts')
        @vite('resources/js/owner/chart-dashboard.js')
    @endpush
</x-layouts.owner>
