<x-layouts.owner title="Work Order">

    <div class="space-y-6">

        {{-- Page Header --}}
        <div>
            <p class="mt-1 text-sm text-slate-500">
                Pantau pekerjaan servis dan pengerjaan kendaraan di Nirwana Garage.
            </p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Total Work Orders --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Total Work Order
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-800">
                    {{ $statistics['total'] }}
                </p>
            </div>

            {{-- Active Work Orders --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Work Order Aktif
                </p>

                <p class="mt-2 text-3xl font-bold text-blue-600">
                    {{ $statistics['active'] }}
                </p>
            </div>

            {{-- Completed Work Orders --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Work Order Selesai
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-600">
                    {{ $statistics['completed'] }}
                </p>
            </div>

        </div>

        {{-- Work Order Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-800">
                    Daftar Work Order
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Informasi pekerjaan kendaraan yang tercatat dalam sistem.
                </p>
            </div>

            {{-- Search and Filter --}}
            <div class="flex flex-col gap-3 border-b border-slate-200 p-5 sm:flex-row">

                <div class="flex-1">
                    <input type="text" id="work-order-search" placeholder="Cari kode WO, booking, atau plat nomor..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">
                </div>

                <div>
                    <select id="work-order-status-filter"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20 sm:w-48">
                        <option value="">Semua Status</option>
                        <option value="IN_PROGRESS">Sedang Dikerjakan</option>
                        <option value="COMPLETED">Selesai</option>
                    </select>
                </div>

            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="w-full whitespace-nowrap text-left text-sm">

                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Kode Work Order</th>
                            <th class="px-5 py-4">Kode Booking</th>
                            <th class="px-5 py-4">Kendaraan</th>
                            <th class="px-5 py-4">Mekanik</th>
                            <th class="px-5 py-4">Tanggal Diterima</th>
                            <th class="px-5 py-4">Status</th>
                        </tr>
                    </thead>

                    <tbody id="work-order-table-body" class="divide-y divide-slate-100">

                        @forelse ($workOrders as $workOrder)
                            @php
                                $status = $workOrder->status;

                                $statusConfig = match ($status) {
                                    'IN_PROGRESS' => [
                                        'label' => 'Sedang Dikerjakan',
                                        'class' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    ],
                                    'COMPLETED' => [
                                        'label' => 'Selesai',
                                        'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    ],
                                    default => [
                                        'label' => $status,
                                        'class' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                    ],
                                };
                            @endphp

                            <tr class="work-order-row transition-colors hover:bg-slate-50"
                                data-search="{{ strtolower(
                                    ($workOrder->work_order_code ?? '') .
                                        ' ' .
                                        ($workOrder->booking->booking_code ?? '') .
                                        ' ' .
                                        ($workOrder->booking->vehicle->license_plate ?? ''),
                                ) }}"
                                data-status="{{ $status }}">

                                {{-- Work Order Code --}}
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-slate-800">
                                        {{ $workOrder->work_order_code ?? '-' }}
                                    </span>
                                </td>

                                {{-- Booking Code --}}
                                <td class="px-5 py-4">
                                    <span class="text-slate-600">
                                        {{ $workOrder->booking->booking_code ?? '-' }}
                                    </span>
                                </td>

                                {{-- Vehicle --}}
                                <td class="px-5 py-4">
                                    <span class="font-medium text-slate-700">
                                        {{ $workOrder->booking->vehicle->license_plate ?? '-' }}
                                    </span>
                                </td>

                                {{-- Mechanic --}}
                                <td class="px-5 py-4">
                                    <span class="text-slate-600">
                                        {{ $workOrder->mechanic->name ?? 'Belum ditugaskan' }}
                                    </span>
                                </td>

                                {{-- Received Date --}}
                                <td class="px-5 py-4">
                                    @if ($workOrder->received_at)
                                        <span class="text-slate-700">
                                            {{ \Carbon\Carbon::parse($workOrder->received_at)->format('d M Y') }}
                                        </span>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($workOrder->received_at)->format('H:i') }}
                                        </p>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
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

                                    <h3 class="font-semibold text-slate-700">
                                        Belum Ada Work Order
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Data Work Order akan muncul ketika tercatat dalam sistem.
                                    </p>

                                </td>
                            </tr>
                        @endforelse

                        <tr id="work-order-no-results" class="hidden">
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                                Tidak ada Work Order yang sesuai dengan pencarian.
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            @if ($workOrders->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $workOrders->links() }}
                </div>
            @endif

        </div>

    </div>



    @push('scripts')
        @vite('resources/js/owner/work-orders.js')
    @endpush
</x-layouts.owner>
