<x-layouts.mechanic title="Request Material">

    <div class="space-y-6">

        {{-- Page Caption --}}
        <div>
            <h1 class="text-xl font-semibold text-brand-navy">
                Request Material
            </h1>

            <p class="mt-1 text-sm text-brand-steel">
                Ajukan material yang dibutuhkan untuk pekerjaan Work Order.
            </p>
        </div>

        {{-- Work Order Information --}}
        <section class="rounded-xl border border-ui-border bg-ui-card px-5 py-4 shadow-sm">
            <div class="flex items-start gap-3">

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-navy/10 text-brand-navy">
                    <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                </div>

                <div>
                    <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                        Work Order
                    </p>

                    <h2 class="mt-1 text-sm font-semibold text-ui-text">
                        {{ $workOrder->work_order_code }}
                    </h2>

                    <p class="mt-1 text-xs text-brand-steel">
                        {{ $workOrder->booking->customer->name }}
                        ·
                        {{ $workOrder->booking->vehicle->license_plate }}
                    </p>
                </div>

            </div>
        </section>

        {{-- Request Form --}}
        <form method="POST" action="{{ route('mechanic.request-item.store') }}" class="space-y-6">
            @csrf

            {{-- Work Order --}}
            <input type="hidden" name="work_order_id" value="{{ $workOrder->id }}">

            {{-- Materials --}}
            <section class="rounded-xl border border-ui-border bg-ui-card shadow-sm">

                <div class="border-b border-ui-border px-5 py-4">

                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <h2 class="text-sm font-semibold text-ui-text">
                                Material yang Dibutuhkan
                            </h2>

                            <p class="mt-1 text-xs text-brand-steel">
                                Tambahkan material yang diperlukan untuk Work Order ini.
                            </p>
                        </div>

                        <button type="button" id="add-material"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-brand-navy px-3 py-2 text-xs font-semibold text-white transition hover:opacity-90">
                            <i data-lucide="plus" class="h-4 w-4"></i>
                            Tambah Material
                        </button>

                    </div>

                </div>

                <div class="space-y-3 px-5 py-5" id="material-items">

                    {{-- Material Item --}}
                    <div class="material-item rounded-lg border border-ui-border bg-gray-50 p-4">

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-[1fr_140px_auto]">

                            {{-- Material --}}
                            <div>

                                <label class="text-xs font-medium text-ui-text">
                                    Material
                                </label>

                                <select name="items[0][material_id]" required
                                    class="mt-1.5 w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">
                                    <option value="">
                                        Pilih material
                                    </option>

                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">
                                            {{ $material->name }}
                                            — Stok {{ $material->stock }} {{ $material->unit }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            {{-- Quantity --}}
                            <div>

                                <label class="text-xs font-medium text-ui-text">
                                    Jumlah
                                </label>

                                <div class="relative mt-1.5">

                                    <input type="number" name="items[0][quantity]" min="1" required
                                        class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 pr-12 text-sm text-ui-text outline-none transition focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">

                                    <span
                                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-brand-steel">
                                        unit
                                    </span>

                                </div>

                            </div>

                            {{-- Remove --}}
                            <div class="flex items-end">

                                <button type="button"
                                    class="remove-material inline-flex h-10.5 w-full items-center justify-center rounded-lg border border-red-200 px-3 text-xs font-semibold text-red-600 transition hover:bg-red-50 sm:w-auto">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>

                                    <span class="ml-1.5 sm:hidden">
                                        Hapus
                                    </span>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            {{-- Notes --}}
            <section class="rounded-xl border border-ui-border bg-ui-card shadow-sm">

                <div class="px-5 py-5">

                    <label for="notes" class="text-xs font-medium text-ui-text">
                        Catatan
                        <span class="font-normal text-brand-steel">
                            (opsional)
                        </span>
                    </label>

                    <textarea id="notes" name="notes" rows="4" placeholder="Tambahkan catatan jika diperlukan..."
                        class="mt-1.5 w-full resize-none rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm text-ui-text outline-none transition placeholder:text-gray-400 focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">{{ old('notes') }}</textarea>

                </div>

            </section>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a href="{{ route('mechanic.work-orders.show', $workOrder) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-ui-border px-4 py-2.5 text-sm font-semibold text-ui-text transition hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-navy px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90">
                    <i data-lucide="send" class="h-4 w-4"></i>
                    Kirim Permintaan
                </button>

            </div>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('material-items');
            const addButton = document.getElementById('add-material');

            let index = 1;


            /*
            |--------------------------------------------------------------------------
            | Material Options
            |--------------------------------------------------------------------------
            */

            const materialOptions = `
            <option value="">Pilih material</option>

            @foreach ($materials as $material)
                <option value="{{ $material->id }}">
                    {{ $material->name }}
                    — Stok {{ $material->stock }} {{ $material->unit }}
                </option>
            @endforeach
        `;


            /*
            |--------------------------------------------------------------------------
            | Trash Icon
            |--------------------------------------------------------------------------
            |
            | Icon dibuat langsung sebagai SVG.
            | Jadi tidak bergantung pada lucide.createIcons()
            | untuk element yang dibuat secara dynamic.
            |
            */

            const trashIcon = `
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
                class="h-4 w-4"
            >
                <path d="M3 6h18"></path>
                <path d="M8 6V4h8v2"></path>
                <path d="M19 6l-1 14H6L5 6"></path>
                <path d="M10 11v6"></path>
                <path d="M14 11v6"></path>
            </svg>
        `;


            /*
            |--------------------------------------------------------------------------
            | Create Material Item
            |--------------------------------------------------------------------------
            */

            function createMaterialItem(index) {

                const wrapper = document.createElement('div');

                wrapper.className =
                    'material-item rounded-lg border border-ui-border bg-gray-50 p-4';

                wrapper.innerHTML = `
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-[1fr_140px_auto]">

                    {{-- Material --}}
                    <div>

                        <label class="text-xs font-medium text-ui-text">
                            Material
                        </label>

                        <select
                            name="items[${index}][material_id]"
                            required
                            class="mt-1.5 w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                        >
                            ${materialOptions}
                        </select>

                    </div>


                    {{-- Quantity --}}
                    <div>

                        <label class="text-xs font-medium text-ui-text">
                            Jumlah
                        </label>

                        <div class="relative mt-1.5">

                            <input
                                type="number"
                                name="items[${index}][quantity]"
                                min="1"
                                required
                                class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 pr-12 text-sm text-ui-text outline-none transition focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            >

                            <span
                                class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-brand-steel"
                            >
                                unit
                            </span>

                        </div>

                    </div>


                    {{-- Remove --}}
                    <div class="flex items-end">

                        <button
                            type="button"
                            class="remove-material inline-flex h-[42px] w-full items-center justify-center rounded-lg border border-red-200 px-3 text-xs font-semibold text-red-600 transition hover:bg-red-50 sm:w-auto"
                        >

                            ${trashIcon}

                            <span class="ml-1.5 sm:hidden">
                                Hapus
                            </span>

                        </button>

                    </div>

                </div>
            `;

                return wrapper;
            }


            /*
            |--------------------------------------------------------------------------
            | Add Material
            |--------------------------------------------------------------------------
            */

            addButton.addEventListener('click', function() {

                const newItem = createMaterialItem(index);

                container.appendChild(newItem);

                index++;

            });


            /*
            |--------------------------------------------------------------------------
            | Remove Material
            |--------------------------------------------------------------------------
            */

            container.addEventListener('click', function(event) {

                const removeButton =
                    event.target.closest('.remove-material');

                if (!removeButton) {
                    return;
                }

                const items =
                    container.querySelectorAll('.material-item');


                /*
                |--------------------------------------------------------------------------
                | Jangan hapus kalau hanya tersisa 1 item
                |--------------------------------------------------------------------------
                */

                if (items.length <= 1) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Hapus material
                |--------------------------------------------------------------------------
                */

                removeButton
                    .closest('.material-item')
                    .remove();

            });

        });
    </script>

</x-layouts.mechanic>
