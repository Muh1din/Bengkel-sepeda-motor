<x-layouts.admin title="Riwayat Stock Movement">

    <main class="w-full space-y-6">

        {{-- PAGE HEADER --}}

        <div class="border-b border-ui-border pb-4">

            <p class="mt-1 text-sm text-ui-steel">
                Riwayat seluruh perubahan stok material dalam inventory.
            </p>

        </div>


        {{-- SUCCESS MESSAGE --}}

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- FILTER --}}

        <section class="rounded-xl border border-ui-border bg-ui-card">

            <div class="flex flex-col gap-4 p-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="text-sm font-semibold text-brand-navy">
                        Filter Movement
                    </h2>

                    <p class="mt-1 text-xs text-ui-steel">
                        Tampilkan berdasarkan jenis perubahan stok.
                    </p>

                </div>


                <div class="flex flex-wrap gap-2">

                    {{-- ALL --}}

                    <a href="{{ route('admin.stock-movements.index') }}"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition-colors
                            {{ !request()->filled('type')
                                ? 'border-brand-navy bg-brand-navy text-white'
                                : 'border-ui-border bg-white text-ui-steel hover:border-brand-navy hover:text-brand-navy' }}">
                        Semua
                    </a>


                    {{-- STOCK IN --}}

                    <a href="{{ route('admin.stock-movements.index', ['type' => 'IN']) }}"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition-colors
                            {{ request('type') === 'IN'
                                ? 'border-brand-navy bg-brand-navy text-white'
                                : 'border-ui-border bg-white text-ui-steel hover:border-brand-navy hover:text-brand-navy' }}">
                        Stock In
                    </a>


                    {{-- STOCK OUT --}}

                    <a href="{{ route('admin.stock-movements.index', ['type' => 'OUT']) }}"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition-colors
                            {{ request('type') === 'OUT'
                                ? 'border-brand-navy bg-brand-navy text-white'
                                : 'border-ui-border bg-white text-ui-steel hover:border-brand-navy hover:text-brand-navy' }}">
                        Stock Out
                    </a>


                    {{-- ADJUSTMENT --}}

                    <a href="{{ route('admin.stock-movements.index', ['type' => 'ADJUSTMENT']) }}"
                        class="rounded-lg border px-3 py-2 text-sm font-medium transition-colors
                            {{ request('type') === 'ADJUSTMENT'
                                ? 'border-brand-navy bg-brand-navy text-white'
                                : 'border-ui-border bg-white text-ui-steel hover:border-brand-navy hover:text-brand-navy' }}">
                        Adjustment
                    </a>

                </div>

            </div>

        </section>


        {{-- MOVEMENT TABLE --}}

        <section class="overflow-hidden rounded-xl border border-ui-border bg-ui-card">

            <div class="border-b border-ui-border px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="h-8 w-1 rounded-full bg-brand-navy"></div>

                    <div>

                        <h2 class="font-semibold text-brand-navy">
                            Aktivitas Stok
                        </h2>

                        <p class="mt-0.5 text-sm text-ui-steel">
                            Daftar perubahan stok material terbaru.
                        </p>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}

            <div class="overflow-x-auto">

                <table class="w-full min-w-225 text-left">

                    <thead class="border-b border-ui-border bg-ui-main">

                        <tr>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Tanggal
                            </th>

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
                                Stok Sebelum
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Stok Sesudah
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                Work Order
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-ui-steel">
                                User
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-ui-border">

                        @forelse ($stockMovements as $movement)
                            <tr class="transition-colors hover:bg-ui-main">

                                {{-- DATE --}}

                                <td class="whitespace-nowrap px-5 py-4">

                                    <p class="text-sm font-medium text-ui-text">
                                        {{ $movement->created_at->format('d M Y') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-ui-steel">
                                        {{ $movement->created_at->format('H:i') }}
                                    </p>

                                </td>


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


                                {{-- STOCK BEFORE --}}

                                <td class="px-5 py-4">

                                    <span class="text-sm font-medium text-ui-text">
                                        {{ $movement->stock_before }}
                                    </span>

                                </td>


                                {{-- STOCK AFTER --}}

                                <td class="px-5 py-4">

                                    <span class="text-sm font-semibold text-brand-navy">
                                        {{ $movement->stock_after }}
                                    </span>

                                </td>


                                {{-- WORK ORDER --}}

                                <td class="px-5 py-4">

                                    @if ($movement->workOrder)
                                        <span class="text-sm font-medium text-brand-navy">
                                            {{ $movement->workOrder->work_order_code }}
                                        </span>
                                    @else
                                        <span class="text-sm text-ui-steel">
                                            —
                                        </span>
                                    @endif

                                </td>


                                {{-- USER --}}

                                <td class="px-5 py-4">

                                    <span class="text-sm text-ui-text">
                                        {{ $movement->user->name }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="px-5 py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <i data-lucide="history" class="h-8 w-8 text-ui-steel"></i>

                                        <p class="mt-3 text-sm font-medium text-ui-text">
                                            Belum ada stock movement.
                                        </p>

                                        <p class="mt-1 text-xs text-ui-steel">
                                            Aktivitas perubahan stok akan muncul di sini.
                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if ($stockMovements->hasPages())
                <div class="border-t border-ui-border px-5 py-4">

                    {{ $stockMovements->links() }}

                </div>
            @endif

        </section>

    </main>

</x-layouts.admin>
