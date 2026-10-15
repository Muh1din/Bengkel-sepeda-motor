<x-layouts.admin title="Stok Keluar">

    <main class="w-full space-y-6">

        {{-- PAGE HEADER --}}
        <div class="border-b border-ui-border pb-4">
            <p class="mt-1 text-sm text-ui-steel">
                Riwayat pengeluaran material berdasarkan Material Request.
            </p>
        </div>

        {{-- HISTORY --}}
        <section class="rounded-xl border border-ui-border bg-ui-card">

            <div class="border-b border-ui-border p-5">

                <h2 class="font-semibold text-brand-navy">
                    Riwayat Pengeluaran Material
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b border-ui-border bg-ui-main">

                        <tr>

                            <th class="px-5 py-3 font-semibold text-ui-text">
                                Waktu
                            </th>

                            <th class="px-5 py-3 font-semibold text-ui-text">
                                Sparepart
                            </th>

                            <th class="px-5 py-3 font-semibold text-ui-text">
                                Qty
                            </th>

                            <th class="px-5 py-3 font-semibold text-ui-text">
                                Sisa
                            </th>

                            <th class="px-5 py-3 font-semibold text-ui-text">
                                WO
                            </th>

                            <th class="px-5 py-3 font-semibold text-ui-text">
                                Petugas
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-ui-border">

                        @forelse ($stockOuts as $movement)
                            <tr class="hover:bg-ui-main/50">

                                <td class="whitespace-nowrap px-5 py-4 text-ui-steel">
                                    {{ $movement->created_at->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-5 py-4">

                                    <p class="font-medium text-ui-text">
                                        {{ $movement->material?->name ?? '-' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-ui-steel">
                                        {{ $movement->material?->barcode ?? '-' }}
                                    </p>

                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-ui-text">
                                    {{ $movement->quantity }}
                                    {{ $movement->material?->unit ?? '' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-ui-text">
                                    {{ $movement->stock_after }}
                                    {{ $movement->material?->unit ?? '' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-ui-text">
                                    {{ $movement->workOrder?->work_order_code ?? '-' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-ui-steel">
                                    {{ $movement->user?->name ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="px-5 py-12 text-center text-sm text-ui-steel">
                                    Belum ada riwayat Stock Out.
                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            @if ($stockOuts->hasPages())
                <div class="border-t border-ui-border p-4">
                    {{ $stockOuts->links() }}
                </div>
            @endif

        </section>

    </main>

</x-layouts.admin>
