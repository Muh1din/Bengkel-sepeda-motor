<x-layouts.admin title="Stock Adjustment">

    <main class="w-full space-y-6">

        {{-- PAGE HEADER --}}

        <div class="border-b border-ui-border pb-4">
            <p class="mt-1 text-sm text-ui-steel">
                Sesuaikan stok material berdasarkan hasil pemeriksaan stok fisik.
            </p>
        </div>


        {{-- SUCCESS MESSAGE --}}

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- VALIDATION ERROR --}}
        @if ($errors->any())

            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">

                <p class="text-sm font-semibold text-red-700">
                    Adjustment gagal diproses.
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- CONTENT --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- FORM --}}

            <section class="lg:col-span-2 rounded-xl border border-ui-border bg-ui-card">

                <div class="border-b border-ui-border p-5">

                    <h2 class="font-semibold text-brand-navy">
                        Penyesuaian Stok
                    </h2>

                    <p class="mt-1 text-sm text-ui-steel">
                        Masukkan jumlah stok fisik yang sebenarnya.
                    </p>

                </div>


                <form action="{{ route('admin.stock-adjustments.store') }}" method="POST" class="space-y-5 p-5">

                    @csrf
                    {{-- MATERIAL --}}

                    <div>

                        <label for="material_id" class="mb-2 block text-sm font-medium text-ui-text">
                            Material
                        </label>

                        <select id="material_id" name="material_id" required
                            class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5
                                   text-sm text-ui-text outline-none
                                   focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">

                            <option value="">
                                Pilih Material
                            </option>

                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}" data-stock="{{ $material->stock }}"
                                    {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                    {{ $material->name }}
                                    — {{ $material->barcode }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    {{-- STOCK INFORMATION --}}

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>

                            <label for="current_stock" class="mb-2 block text-sm font-medium text-ui-text">
                                Stok Sistem
                            </label>

                            <input id="current_stock" type="number" value="0" readonly
                                class="w-full rounded-lg border border-ui-border bg-ui-main px-3 py-2.5
                                       text-sm text-ui-steel outline-none">

                        </div>


                        <div>

                            <label for="physical_stock" class="mb-2 block text-sm font-medium text-ui-text">
                                Stok Fisik
                            </label>

                            <input id="physical_stock" name="physical_stock" type="number" min="0"
                                step="1" value="{{ old('physical_stock') }}" required
                                placeholder="Masukkan stok fisik"
                                class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5
                                       text-sm text-ui-text outline-none
                                       focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">

                        </div>

                    </div>


                    {{-- DIFFERENCE --}}

                    <div class="rounded-lg border border-ui-border bg-ui-main p-4">

                        <div class="flex items-center justify-between">

                            <span class="text-sm font-medium text-ui-steel">
                                Selisih Stok
                            </span>

                            <span id="stock-difference" class="text-lg font-bold text-brand-navy">
                                0
                            </span>

                        </div>

                    </div>


                    {{-- NOTES --}}

                    <div>

                        <label for="notes" class="mb-2 block text-sm font-medium text-ui-text">
                            Catatan
                        </label>

                        <textarea id="notes" name="notes" rows="4" maxlength="500" placeholder="Contoh: Selisih hasil stock opname."
                            class="w-full resize-none rounded-lg border border-ui-border bg-white px-3 py-2.5
                                   text-sm text-ui-text outline-none
                                   focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">{{ old('notes') }}</textarea>

                        <p class="mt-1 text-xs text-ui-steel">
                            Jelaskan alasan dilakukan penyesuaian stok.
                        </p>

                    </div>


                    {{-- ACTION --}}

                    <div class="flex justify-end border-t border-ui-border pt-5">

                        <button id="submit-adjustment" type="submit"
                            class="inline-flex items-center rounded-lg bg-brand-navy
                                   px-5 py-2.5 text-sm font-semibold text-white
                                   hover:bg-slate-800 disabled:cursor-not-allowed
                                   disabled:opacity-50">
                            Sesuaikan Stok
                        </button>

                    </div>

                </form>

            </section>


            {{-- PROJECTION --}}

            <section class="rounded-xl border border-ui-border bg-ui-card">

                <div class="border-b border-ui-border p-5">

                    <h2 class="font-semibold text-brand-navy">
                        Kalkulasi Adjustment
                    </h2>

                    <p class="mt-1 text-sm text-ui-steel">
                        Preview perubahan stok sebelum diproses.
                    </p>

                </div>


                <div class="space-y-5 p-5">

                    <div class="flex items-center justify-between">

                        <span class="text-sm text-ui-steel">
                            Stok Saat Ini
                        </span>

                        <span id="projection-current-stock" class="font-semibold text-ui-text">
                            0
                        </span>

                    </div>


                    <div class="flex items-center justify-between">

                        <span class="text-sm text-ui-steel">
                            Stok Fisik
                        </span>

                        <span id="projection-physical-stock" class="font-semibold text-ui-text">
                            0
                        </span>

                    </div>


                    <div class="border-t border-ui-border pt-4">

                        <div class="flex items-center justify-between">

                            <span class="text-sm font-medium text-ui-steel">
                                Selisih
                            </span>

                            <span id="projection-difference" class="text-lg font-bold text-brand-navy">
                                0
                            </span>

                        </div>

                    </div>


                    <div class="rounded-lg border border-ui-border bg-ui-main p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-ui-steel">
                            Stok Setelah Adjustment
                        </p>

                        <p id="projection-final-stock" class="mt-1 text-2xl font-bold text-brand-navy">
                            0
                        </p>

                    </div>


                    <div id="adjustment-status"
                        class="rounded-lg border border-ui-border bg-ui-main p-3 text-sm text-ui-steel">
                        Pilih material dan masukkan stok fisik untuk melihat perubahan.

                    </div>

                </div>

            </section>

        </div>

    </main>


    @push('scripts')
        @vite('resources/js/admin/stock-adjustments.js')
    @endpush

</x-layouts.admin>
