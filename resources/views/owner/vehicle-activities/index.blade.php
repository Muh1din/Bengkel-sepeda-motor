<x-layouts.owner title="Aktivitas Kendaraan">

    <div class="space-y-6">

        {{-- Page Header --}}
        <div>
            <p class="mt-1 text-sm text-slate-500">
                Pantau riwayat aktivitas kendaraan yang tercatat di Nirwana Garage.
            </p>
        </div>

        {{-- Activity Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Table Header --}}
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-800">
                    Riwayat Aktivitas Kendaraan
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi booking, pemeriksaan, dan pengerjaan kendaraan.
                </p>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left text-sm">

                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Kendaraan</th>
                            <th class="px-5 py-4">Pelanggan</th>
                            <th class="px-5 py-4">Booking</th>
                            <th class="px-5 py-4">Pemeriksaan</th>
                            <th class="px-5 py-4">Work Order</th>
                            <th class="px-5 py-4">Aktivitas Terakhir</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($vehicleActivities as $activity)
                            @php
                                $status = $activity->status;

                                $statusConfig = match ($status) {
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
                                        'label' => $status,
                                        'class' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                    ],
                                };
                            @endphp

                            <tr class="transition-colors hover:bg-slate-50">

                                {{-- Vehicle --}}
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">
                                        {{ $activity->vehicle->license_plate ?? '-' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ trim(($activity->vehicle->brand ?? '') . ' ' . ($activity->vehicle->model ?? '')) ?: '-' }}
                                    </div>
                                </td>

                                {{-- Customer --}}
                                <td class="px-5 py-4">
                                    <span class="font-medium text-slate-700">
                                        {{ $activity->customer->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- Booking --}}
                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-700">
                                        {{ $activity->booking_code }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $activity->service_type }}
                                    </div>
                                </td>

                                {{-- Inspection --}}
                                <td class="px-5 py-4">
                                    @if ($activity->inspection)
                                        <div class="font-medium text-slate-700">
                                            {{ $activity->inspection->mechanic->name ?? 'Mekanik belum tersedia' }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $activity->inspection->status ?? 'Pemeriksaan tercatat' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">
                                            Belum ada pemeriksaan
                                        </span>
                                    @endif
                                </td>

                                {{-- Work Order --}}
                                <td class="px-5 py-4">
                                    @if ($activity->workOrder)
                                        <div class="font-medium text-slate-700">
                                            {{ $activity->workOrder->work_order_code }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $activity->workOrder->mechanic->name ?? 'Mekanik belum tersedia' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">
                                            Belum ada Work Order
                                        </span>
                                    @endif
                                </td>

                                {{-- Latest Activity --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusConfig['class'] }}">
                                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current"></span>
                                        {{ $statusConfig['label'] }}
                                    </span>

                                    <div class="mt-2 text-xs text-slate-500">
                                        {{ $activity->updated_at?->format('d M Y H:i') ?? '-' }}
                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">

                                    <h3 class="font-semibold text-slate-700">
                                        Belum Ada Aktivitas Kendaraan
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Riwayat kendaraan akan muncul ketika booking tercatat di sistem.
                                    </p>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($vehicleActivities->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $vehicleActivities->links() }}
                </div>
            @endif

        </div>

    </div>

</x-layouts.owner>
