<x-layouts.owner title="Laporan Penggunaan Barang">

    <div class="space-y-5">

        {{-- Header --}}
        <div>
            <p class="mt-1 text-sm text-gray-500">
                Ringkasan penggunaan barang dan pengeluaran stok inventory.
            </p>
        </div>

        {{-- Filter Tanggal --}}
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <form action="{{ route('owner.reports.item-usage') }}" method="GET"
                class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label for="start_date" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Dari Tanggal
                    </label>

                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#1b263b] focus:outline-none focus:ring-1 focus:ring-[#1b263b]">
                </div>

                <div class="flex-1">
                    <label for="end_date" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Sampai Tanggal
                    </label>

                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#1b263b] focus:outline-none focus:ring-1 focus:ring-[#1b263b]">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-md bg-[#1b263b] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#2d3c55]">
                        Terapkan
                    </button>

                    <a href="{{ route('owner.reports.item-usage') }}"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>

            @if ($errors->any())
                <div class="mt-3 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Ringkasan --}}
        <div>
            <h2 class="mb-3 text-sm font-semibold text-[#1b263b]">
                Ringkasan Penggunaan Barang
            </h2>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Total Transaksi
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-[#1b263b]">
                        {{ number_format($statistics['total_transactions']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-[#1b263b]"></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Total Barang Keluar
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-blue-700">
                        {{ number_format($statistics['total_quantity']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-blue-600"></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Jenis Barang
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-green-700">
                        {{ number_format($statistics['total_materials']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-green-600"></div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Work Order Terkait
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-amber-700">
                        {{ number_format($statistics['total_work_orders']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-amber-500"></div>
                </div>

            </div>
        </div>

        {{-- Tabel Penggunaan Barang --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-4 py-3">
                <h2 class="text-sm font-semibold text-[#1b263b]">
                    Riwayat Penggunaan Barang
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Tanggal
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Nama Barang
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Kategori
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Jumlah
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Work Order
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Petugas
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($itemUsage as $movement)
                            <tr class="transition hover:bg-gray-50">

                                <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                    {{ $movement->created_at->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">
                                        {{ $movement->material?->name ?? '-' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $movement->material?->barcode ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $movement->material?->category?->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-800">
                                    {{ number_format($movement->quantity) }}
                                    {{ $movement->material?->unit ?? '' }}
                                </td>

                                <td class="whitespace-nowrap px-4 py-3">
                                    @if ($movement->workOrder)
                                        <span class="font-medium text-[#1b263b]">
                                            {{ $movement->workOrder->work_order_code }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                    {{ $movement->user?->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">
                                    Tidak ada penggunaan barang pada periode ini.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($itemUsage->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $itemUsage->links() }}
                </div>
            @endif

        </div>

    </div>

</x-layouts.owner>
