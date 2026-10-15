<x-layouts.admin title="Inventory Dashboard">

    <main class="w-full">

        {{-- PAGE HEADER --}}
        <div class="border-b border-ui-border pb-4">
            <p class="mt-1 text-sm text-ui-steel">
                Ringkasan kondisi inventory dan aktivitas stok.
            </p>
        </div>


        {{-- SUMMARY --}}

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

            {{-- TOTAL MATERIAL --}}

            <div class="rounded-xl border border-ui-border bg-ui-card p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-ui-steel">
                            Total Material
                        </p>

                        <p class="mt-2 text-2xl font-bold text-brand-navy">
                            {{ $totalMaterials }}
                        </p>

                    </div>

                    <div class="rounded-lg bg-ui-main p-2.5">

                        <i data-lucide="package" class="h-5 w-5 text-brand-navy"></i>

                    </div>

                </div>

            </div>


            {{-- LOW STOCK --}}

            <div class="rounded-xl border border-ui-border bg-ui-card p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-ui-steel">
                            Stok Menipis
                        </p>

                        <p class="mt-2 text-2xl font-bold text-brand-gold">
                            {{ $lowStockMaterials->count() }}
                        </p>

                    </div>

                    <div class="rounded-lg bg-brand-gold/10 p-2.5">

                        <i data-lucide="triangle-alert" class="h-5 w-5 text-brand-gold"></i>

                    </div>

                </div>

            </div>


            {{-- PENDING REQUEST --}}

            <div class="rounded-xl border border-ui-border bg-ui-card p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-ui-steel">
                            Request Menunggu
                        </p>

                        <p class="mt-2 text-2xl font-bold text-brand-navy">
                            {{ $pendingRequests }}
                        </p>

                    </div>

                    <div class="rounded-lg bg-ui-main p-2.5">

                        <i data-lucide="clipboard-list" class="h-5 w-5 text-brand-navy"></i>

                    </div>

                </div>

            </div>


            {{-- STOCK IN --}}

            <div class="rounded-xl border border-ui-border bg-ui-card p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-ui-steel">
                            Stock In Bulan Ini
                        </p>

                        <p class="mt-2 text-2xl font-bold text-green-600">
                            {{ $stockInThisMonth }}
                        </p>

                    </div>

                    <div class="rounded-lg bg-green-50 p-2.5">

                        <i data-lucide="package-plus" class="h-5 w-5 text-green-600"></i>

                    </div>

                </div>

            </div>


            {{-- STOCK OUT --}}

            <div class="rounded-xl border border-ui-border bg-ui-card p-5">

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-ui-steel">
                            Stock Out Bulan Ini
                        </p>

                        <p class="mt-2 text-2xl font-bold text-red-600">
                            {{ $stockOutThisMonth }}
                        </p>

                    </div>

                    <div class="rounded-lg bg-red-50 p-2.5">

                        <i data-lucide="package-minus" class="h-5 w-5 text-red-600"></i>

                    </div>

                </div>

            </div>

        </section>

        {{-- MAIN CONTENT --}}
        <div class="grid grid-cols-1 mt-10 gap-6 xl:grid-cols-3">


            {{-- RECENT MOVEMENTS --}}

            <section class="xl:col-span-2 overflow-hidden rounded-xl border border-ui-border bg-ui-card">

                <div class="flex items-center justify-between border-b border-ui-border px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="h-8 w-1 rounded-full bg-brand-navy"></div>

                        <div>

                            <h2 class="font-semibold text-brand-navy">
                                Aktivitas Stock Terbaru
                            </h2>

                            <p class="mt-0.5 text-sm text-ui-steel">
                                Perubahan stok terbaru dalam inventory.
                            </p>

                        </div>

                    </div>

                    <a href="{{ route('admin.stock-movements.index') }}"
                        class="text-sm font-medium text-brand-navy hover:underline">
                        Lihat Semua
                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-162.5 text-left">

                        <thead class="border-b border-ui-border bg-ui-main">

                            <tr>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                    Material
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                    Tipe
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                    Quantity
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                    Waktu
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-ui-border">

                            @forelse ($recentMovements as $movement)
                                <tr class="h-18.25 transition-colors hover:bg-ui-main">

                                    {{-- MATERIAL --}}

                                    <td class="px-5 py-4">

                                        <p class="text-sm font-medium text-ui-text">
                                            {{ $movement->material->name }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-ui-steel">
                                            {{ $movement->material->barcode }}
                                        </p>

                                    </td>


                                    {{-- TYPE --}}

                                    <td class="px-5 py-4">

                                        @if ($movement->type === 'IN')
                                            <span
                                                class="inline-flex rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                Stock In
                                            </span>
                                        @elseif ($movement->type === 'OUT')
                                            <span
                                                class="inline-flex rounded-full border border-red-200 bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                                Stock Out
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full border border-brand-gold/30 bg-brand-gold/10 px-2.5 py-1 text-xs font-semibold text-brand-gold">
                                                Adjustment
                                            </span>
                                        @endif

                                    </td>


                                    {{-- QUANTITY --}}

                                    <td class="px-5 py-4">

                                        @if ($movement->type === 'IN')
                                            <span class="font-semibold text-green-600">
                                                +{{ $movement->quantity }}
                                            </span>
                                        @elseif ($movement->type === 'OUT')
                                            <span class="font-semibold text-red-600">
                                                -{{ $movement->quantity }}
                                            </span>
                                        @else
                                            <span class="font-semibold text-brand-gold">
                                                {{ $movement->quantity }}
                                            </span>
                                        @endif

                                    </td>


                                    {{-- TIME --}}

                                    <td class="whitespace-nowrap px-5 py-4">

                                        <p class="text-sm text-ui-text">
                                            {{ $movement->created_at->format('d M Y') }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-ui-steel">
                                            {{ $movement->created_at->format('H:i') }}
                                        </p>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="px-5 py-10 text-center">

                                        <i data-lucide="history" class="mx-auto h-8 w-8 text-ui-steel"></i>

                                        <p class="mt-3 text-sm font-medium text-ui-text">
                                            Belum ada aktivitas stok.
                                        </p>

                                        <p class="mt-1 text-xs text-ui-steel">
                                            Perubahan stok akan muncul di sini.
                                        </p>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        {{-- PLACEHOLDER ROWS --}}

                        @if ($recentMovements->count() > 0 && $recentMovements->count() < 5)

                            <tbody>

                                @for ($i = $recentMovements->count(); $i < 5; $i++)
                                    <tr class="h-18.25">

                                        <td colspan="4"></td>

                                    </tr>
                                @endfor

                            </tbody>

                        @endif

                    </table>
                    @if ($recentMovements->hasPages())
                        <div class="border-t border-ui-border px-5 py-4">
                            {{ $recentMovements->links() }}
                        </div>
                    @endif
                </div>

            </section>

            {{-- QUICK ACTIONS --}}

            <section class="overflow-hidden rounded-xl border border-ui-border bg-ui-card">

                <div class="border-b border-ui-border px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="h-8 w-1 rounded-full bg-brand-gold"></div>

                        <div>

                            <h2 class="font-semibold text-brand-navy">
                                Aksi Cepat
                            </h2>

                            <p class="mt-0.5 text-sm text-ui-steel">
                                Akses cepat untuk aktivitas inventory.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="divide-y divide-ui-border">

                    {{-- STOCK IN --}}

                    <a href="{{ route('admin.stock-in.index') }}"
                        class="group flex items-center gap-4 px-5 py-4 transition-colors hover:bg-ui-main">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ui-main">

                            <i data-lucide="package-plus" class="h-5 w-5 text-brand-navy"></i>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-semibold text-ui-text group-hover:text-brand-navy">
                                Stock In
                            </p>

                            <p class="mt-0.5 text-xs text-ui-steel">
                                Tambahkan stok material.
                            </p>

                        </div>

                        <i data-lucide="arrow-right"
                            class="h-4 w-4 shrink-0 text-ui-steel transition-transform group-hover:translate-x-0.5 group-hover:text-brand-navy"></i>

                    </a>


                    {{-- MATERIAL REQUEST --}}

                    <a href="{{ route('admin.material-requests.index') }}"
                        class="group flex items-center gap-4 px-5 py-4 transition-colors hover:bg-ui-main">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ui-main">

                            <i data-lucide="clipboard-list" class="h-5 w-5 text-brand-navy"></i>

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="flex items-center gap-2">

                                <p class="text-sm font-semibold text-ui-text group-hover:text-brand-navy">
                                    Material Request
                                </p>

                                @if ($pendingRequests > 0)
                                    <span
                                        class="inline-flex min-w-5 items-center justify-center rounded-full bg-brand-gold px-1.5 py-0.5 text-[10px] font-bold text-white">
                                        {{ $pendingRequests }}
                                    </span>
                                @endif

                            </div>

                            <p class="mt-0.5 text-xs text-ui-steel">
                                Proses permintaan dari mekanik.
                            </p>

                        </div>

                        <i data-lucide="arrow-right"
                            class="h-4 w-4 shrink-0 text-ui-steel transition-transform group-hover:translate-x-0.5 group-hover:text-brand-navy"></i>

                    </a>


                    {{-- STOCK ADJUSTMENT --}}

                    <a href="{{ route('admin.stock-adjustments.index') }}"
                        class="group flex items-center gap-4 px-5 py-4 transition-colors hover:bg-ui-main">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ui-main">

                            <i data-lucide="clipboard-pen" class="h-5 w-5 text-brand-navy"></i>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-semibold text-ui-text group-hover:text-brand-navy">
                                Stock Adjustment
                            </p>

                            <p class="mt-0.5 text-xs text-ui-steel">
                                Sesuaikan stok berdasarkan stok fisik.
                            </p>

                        </div>

                        <i data-lucide="arrow-right"
                            class="h-4 w-4 shrink-0 text-ui-steel transition-transform group-hover:translate-x-0.5 group-hover:text-brand-navy"></i>

                    </a>


                    {{-- STOCK MOVEMENT --}}

                    <a href="{{ route('admin.stock-movements.index') }}"
                        class="group flex items-center gap-4 px-5 py-4 transition-colors hover:bg-ui-main">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ui-main">

                            <i data-lucide="arrow-left-right" class="h-5 w-5 text-brand-navy"></i>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-semibold text-ui-text group-hover:text-brand-navy">
                                Stock Movement
                            </p>

                            <p class="mt-0.5 text-xs text-ui-steel">
                                Lihat seluruh riwayat perubahan stok.
                            </p>

                        </div>

                        <i data-lucide="arrow-right"
                            class="h-4 w-4 shrink-0 text-ui-steel transition-transform group-hover:translate-x-0.5 group-hover:text-brand-navy"></i>

                    </a>

                </div>

            </section>

        </div>

        {{-- MATERIAL REQUEST --}}

        <section class="overflow-hidden mt-10 rounded-xl border border-ui-border bg-ui-card">

            <div class="flex items-center justify-between border-b border-ui-border px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="h-8 w-1 rounded-full bg-brand-navy"></div>

                    <div>

                        <h2 class="font-semibold text-brand-navy">
                            Material Request Terbaru
                        </h2>

                        <p class="mt-0.5 text-sm text-ui-steel">
                            Permintaan material terbaru dari mekanik.
                        </p>

                    </div>

                </div>

                <a href="{{ route('admin.material-requests.index') }}"
                    class="text-sm font-medium text-brand-navy hover:underline">
                    Lihat Semua
                </a>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full min-w-175 text-left">

                    <thead class="border-b border-ui-border bg-ui-main">

                        <tr>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Work Order
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Mekanik
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Material
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Status
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Tanggal
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-ui-border">

                        @forelse ($recentRequests as $request)

                            <tr class="transition-colors hover:bg-ui-main">

                                {{-- WORK ORDER --}}

                                <td class="px-5 py-4">

                                    <span class="text-sm font-medium text-brand-navy">
                                        {{ $request->workOrder->work_order_code }}
                                    </span>

                                </td>


                                {{-- MECHANIC --}}

                                <td class="px-5 py-4">

                                    <span class="text-sm text-ui-text">
                                        {{ $request->mechanic->name }}
                                    </span>

                                </td>


                                {{-- MATERIAL --}}

                                <td class="px-5 py-4">

                                    <div class="space-y-1">

                                        @foreach ($request->items->take(2) as $item)
                                            <p class="text-sm text-ui-text">

                                                {{ $item->material->name }}

                                                <span class="text-ui-steel">
                                                    × {{ $item->quantity }}
                                                </span>

                                            </p>
                                        @endforeach

                                        @if ($request->items->count() > 2)
                                            <p class="text-xs text-ui-steel">
                                                + {{ $request->items->count() - 2 }} material lainnya
                                            </p>
                                        @endif

                                    </div>

                                </td>


                                {{-- STATUS --}}

                                <td class="px-5 py-4">

                                    @if ($request->status === 'PENDING')
                                        <span
                                            class="inline-flex rounded-full border border-brand-gold/30 bg-brand-gold/10 px-2.5 py-1 text-xs font-semibold text-brand-gold">
                                            Menunggu
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full border border-green-200 bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                            Dipenuhi
                                        </span>
                                    @endif

                                </td>


                                {{-- DATE --}}

                                <td class="whitespace-nowrap px-5 py-4">

                                    <p class="text-sm text-ui-text">
                                        {{ $request->created_at->format('d M Y') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-ui-steel">
                                        {{ $request->created_at->format('H:i') }}
                                    </p>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-5 py-10 text-center">

                                    <i data-lucide="clipboard-list" class="mx-auto h-8 w-8 text-ui-steel"></i>

                                    <p class="mt-3 text-sm font-medium text-ui-text">
                                        Belum ada material request.
                                    </p>

                                    <p class="mt-1 text-xs text-ui-steel">
                                        Request dari mekanik akan muncul di sini.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>

</x-layouts.admin>
