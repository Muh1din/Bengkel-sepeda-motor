<x-layouts.owner title="Inventory">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <p class="mt-1 text-sm text-gray-500">
                Monitor persediaan material dan aktivitas pergerakan stok bengkel.
            </p>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Material --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm font-medium text-gray-500">
                    Total Jenis Barang
                </p>

                <div class="mt-3 flex items-center justify-between">
                    <p class="text-3xl font-bold text-gray-800">
                        {{ number_format($statistics['total_materials']) }}
                    </p>

                    <div class="rounded-lg bg-blue-50 p-3 text-blue-600">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>

            {{-- Low Stock --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm font-medium text-gray-500">
                    Stok Menipis
                </p>

                <div class="mt-3 flex items-center justify-between">
                    <p class="text-3xl font-bold text-amber-600">
                        {{ number_format($statistics['low_stock_materials']) }}
                    </p>

                    <div class="rounded-lg bg-amber-50 p-3 text-amber-600">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>

            {{-- Out of Stock --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm font-medium text-gray-500">
                    Stok Habis
                </p>

                <div class="mt-3 flex items-center justify-between">
                    <p class="text-3xl font-bold text-red-600">
                        {{ number_format($statistics['out_of_stock_materials']) }}
                    </p>

                    <div class="rounded-lg bg-red-50 p-3 text-red-600">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
            </div>

            {{-- Stock Movements --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm font-medium text-gray-500">
                    Total Pergerakan Stok
                </p>

                <div class="mt-3 flex items-center justify-between">
                    <p class="text-3xl font-bold text-gray-800">
                        {{ number_format($statistics['total_stock_movements']) }}
                    </p>

                    <div class="rounded-lg bg-gray-100 p-3 text-gray-600">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Material Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div
                class="flex flex-col gap-3 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Persediaan Barang
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Daftar material dan kondisi stok saat ini.
                    </p>
                </div>

                <span class="text-sm text-gray-500">
                    {{ $materials->total() }} jenis barang
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Material
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Barcode
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Kategori
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 text-right font-semibold">
                                Stok
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 text-right font-semibold">
                                Stok Minimum
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($materials as $material)
                            @php
                                if ($material->stock <= 0) {
                                    $stockStatus = 'Habis';
                                    $statusClass = 'bg-red-50 text-red-700';
                                } elseif ($material->stock <= $material->minimum_stock) {
                                    $stockStatus = 'Menipis';
                                    $statusClass = 'bg-amber-50 text-amber-700';
                                } else {
                                    $stockStatus = 'Aman';
                                    $statusClass = 'bg-green-50 text-green-700';
                                }
                            @endphp

                            <tr class="transition-colors hover:bg-gray-50">

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $material->name }}
                                    </p>

                                    @if ($material->description)
                                        <p class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                            {{ $material->description }}
                                        </p>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $material->barcode }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $material->category?->name ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-800">
                                    {{ number_format($material->stock) }}
                                    <span class="font-normal text-gray-500">
                                        {{ $material->unit }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right text-gray-600">
                                    {{ number_format($material->minimum_stock) }}
                                    {{ $material->unit }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $stockStatus }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>

                                        <p class="mt-3 font-medium text-gray-700">
                                            Belum ada data material
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Data persediaan akan ditampilkan setelah material ditambahkan.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($materials->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $materials->links() }}
                </div>
            @endif

        </div>

        {{-- Recent Stock Movements --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 p-5">
                <h2 class="text-lg font-semibold text-gray-800">
                    Riwayat Pergerakan Stok
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Sepuluh transaksi stok terbaru.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Tanggal
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Material
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Jenis Transaksi
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 text-right font-semibold">
                                Jumlah
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 text-right font-semibold">
                                Stok Sebelum
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 text-right font-semibold">
                                Stok Sesudah
                            </th>

                            <th class="whitespace-nowrap px-5 py-4 font-semibold">
                                Pengguna
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($recentMovements as $movement)
                            @php
                                $movementStyles = match ($movement->type) {
                                    'IN' => 'bg-green-50 text-green-700',
                                    'OUT' => 'bg-red-50 text-red-700',
                                    'ADJUSTMENT' => 'bg-blue-50 text-blue-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };

                                $movementLabels = [
                                    'IN' => 'Barang Masuk',
                                    'OUT' => 'Barang Keluar',
                                    'ADJUSTMENT' => 'Penyesuaian',
                                ];
                            @endphp

                            <tr class="transition-colors hover:bg-gray-50">

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $movement->created_at?->format('d M Y, H:i') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $movement->material?->name ?? 'Material tidak tersedia' }}
                                    </p>

                                    @if ($movement->work_order_id)
                                        <p class="mt-1 text-xs text-gray-500">
                                            WO:
                                            {{ $movement->workOrder?->work_order_code ?? '-' }}
                                        </p>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $movementStyles }}">
                                        {{ $movementLabels[$movement->type] ?? $movement->type }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-800">
                                    {{ number_format($movement->quantity) }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right text-gray-600">
                                    {{ number_format($movement->stock_before) }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-800">
                                    {{ number_format($movement->stock_after) }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $movement->user?->name ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada riwayat pergerakan stok.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>

</x-layouts.owner>
