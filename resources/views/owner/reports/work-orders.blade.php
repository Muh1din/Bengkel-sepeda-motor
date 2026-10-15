<x-layouts.owner title="Laporan Work Order">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <p class="mt-1 text-sm text-gray-500">
                Ringkasan pengerjaan kendaraan berdasarkan periode.
            </p>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('owner.reports.work-orders') }}"
            class="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="start_date" class="mb-1.5 block text-sm text-gray-600">
                    Tanggal Mulai
                </label>

                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#c58e4b] focus:outline-none">

                @error('start_date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1">
                <label for="end_date" class="mb-1.5 block text-sm text-gray-600">
                    Tanggal Akhir
                </label>

                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#c58e4b] focus:outline-none">

                @error('end_date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="rounded-md bg-[#1b263b] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#293852]">
                    Terapkan
                </button>

                <a href="{{ route('owner.reports.work-orders') }}"
                    class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>

        {{-- Summary --}}
        <div>
            <h2 class="mb-3 text-sm font-semibold text-[#1b263b]">
                Ringkasan Work Order
            </h2>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                {{-- Total Work Order --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Total Work Order
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-brand-navy">
                        {{ number_format($statistics['total']) }}
                    </p>


                </div>

                {{-- Sedang Dikerjakan --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Sedang Dikerjakan
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-brand-navy">
                        {{ number_format($statistics['active']) }}
                    </p>

                </div>

                {{-- Selesai --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Selesai
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-green-700">
                        {{ number_format($statistics['completed']) }}
                    </p>

                </div>

                {{-- Belum Diterima --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Belum Diterima
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-amber-700">
                        {{ number_format($statistics['not_received']) }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Report Table --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">

            <div
                class="flex flex-col gap-1 border-b border-gray-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-sm font-semibold text-[#1b263b]">
                    Detail Work Order
                </h2>

                <p class="text-xs text-gray-500">
                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                    —
                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    <span class="mx-1">·</span>
                    {{ $workOrders->total() }} data
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Kode WO
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Kendaraan
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Pelanggan
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Mekanik
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Tanggal Dibuat
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Tanggal Selesai
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium text-gray-500">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($workOrders as $workOrder)
                            @php
                                $statusConfig = match ($workOrder->status) {
                                    'PENDING' => [
                                        'label' => 'Pending',
                                        'class' => 'bg-amber-50 text-amber-700',
                                    ],
                                    'IN_PROGRESS' => [
                                        'label' => 'Sedang Dikerjakan',
                                        'class' => 'bg-blue-50 text-blue-700',
                                    ],
                                    'COMPLETED' => [
                                        'label' => 'Selesai',
                                        'class' => 'bg-green-50 text-green-700',
                                    ],
                                    'CANCELLED' => [
                                        'label' => 'Dibatalkan',
                                        'class' => 'bg-red-50 text-red-700',
                                    ],
                                    'REJECTED' => [
                                        'label' => 'Ditolak',
                                        'class' => 'bg-red-50 text-red-700',
                                    ],
                                    default => [
                                        'label' => str_replace('_', ' ', $workOrder->status),
                                        'class' => 'bg-gray-100 text-gray-700',
                                    ],
                                };
                            @endphp

                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-4 py-3.5 font-medium text-[#1b263b]">
                                    {{ $workOrder->work_order_code }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-700">
                                    <p>
                                        {{ $workOrder->booking?->vehicle?->license_plate ?? '-' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $workOrder->booking?->vehicle?->brand ?? '' }}
                                        {{ $workOrder->booking?->vehicle?->model ?? '' }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-600">
                                    {{ $workOrder->booking?->customer?->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-600">
                                    {{ $workOrder->mechanic?->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-600">
                                    {{ $workOrder->created_at?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-600">
                                    {{ $workOrder->completed_at?->format('d M Y') ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <span
                                        class="inline-flex rounded px-2 py-1 text-xs font-medium {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <p class="text-sm font-medium text-gray-700">
                                        Tidak ada data Work Order
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Coba pilih periode tanggal yang berbeda.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($workOrders->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $workOrders->links() }}
                </div>
            @endif

        </div>

    </div>

</x-layouts.owner>
