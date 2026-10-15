<x-layouts.admin title="Stok Masuk">

    <main class="w-full space-y-6">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ui-border pb-4">
            <div>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola pergerakan persediaan dan pastikan setiap perubahan stok tercatat dalam sistem.
                </p>
            </div>

            {{-- STATISTICS --}}
            <div class="flex items-center gap-3">

                <div class="bg-white px-4 py-2 rounded-lg border border-ui-border text-center">
                    <span class="block text-xs text-slate-500 font-medium">
                        Total Transaksi
                    </span>

                    <span class="text-lg font-
                    bold text-brand-navy">
                        {{ $totalTransactions }}
                    </span>
                </div>

                <div class="bg-white px-4 py-2 rounded-lg border border-ui-border text-center">
                    <span class="block text-xs text-slate-500 font-medium">
                        Item Masuk Bulan Ini
                    </span>

                    <span class="text-lg font-bold text-emerald-600">
                        {{ $totalItemsThisMonth }}
                    </span>
                </div>

            </div>
        </div>


        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div id="successToast"
                class="fixed top-5 right-5 z-50 bg-slate-900 text-white px-4 py-3 rounded-lg shadow-xl text-sm flex items-center gap-3 transition duration-300">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>

                <span>
                    {{ session('success') }}
                </span>
            </div>
        @endif


        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <p class="font-semibold mb-1">
                    Stock In gagal diproses.
                </p>

                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- FORM + PREVIEW --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- FORM --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-ui-border shadow-sm">

                <div class="border-b border-ui-border pb-3 mb-5">
                    <h2 class="text-lg font-bold text-brand-navy">
                        Form Stock In Baru
                    </h2>

                    <p class="text-xs text-slate-500 mt-1">
                        Tambahkan material yang diterima ke dalam stok gudang.
                    </p>
                </div>


                <form action="{{ route('admin.stock-in.store') }}" method="POST" class="space-y-5">
                    @csrf


                    {{-- MATERIAL --}}
                    <div>
                        <label for="material_id"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Pilih Material
                            <span class="text-red-500">*</span>
                        </label>

                        <select name="material_id" id="material_id" required
                            class="w-full px-3 py-2.5 border border-ui-border rounded-lg text-sm bg-white focus:ring-2 focus:ring-brand-gold focus:outline-none">
                            <option value="">
                                -- Pilih Material --
                            </option>

                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}" data-name="{{ $material->name }}"
                                    data-barcode="{{ $material->barcode }}" data-stock="{{ $material->stock }}"
                                    data-unit="{{ $material->unit }}"
                                    data-minimum-stock="{{ $material->minimum_stock }}" @selected(old('material_id') == $material->id)>
                                    [{{ $material->barcode }}]
                                    {{ $material->name }}
                                    — Stok: {{ $material->stock }} {{ $material->unit }}
                                </option>
                            @endforeach
                        </select>

                        @error('material_id')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- QUANTITY --}}
                    <div>
                        <label for="quantity"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Jumlah Masuk
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input type="number" name="quantity" id="quantity" min="1"
                                value="{{ old('quantity') }}" required placeholder="Contoh: 10"
                                class="w-full px-3 py-2.5 border border-ui-border rounded-lg text-sm focus:ring-2 focus:ring-brand-gold focus:outline-none">

                            <span id="unitLabel"
                                class="absolute right-10 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">
                                -
                            </span>
                        </div>

                        @error('quantity')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- NOTES --}}
                    <div>
                        <label for="notes"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                            Catatan
                            <span class="text-slate-400 font-normal">
                                (Opsional)
                            </span>
                        </label>

                        <textarea name="notes" id="notes" rows="3" maxlength="1000" placeholder="Contoh: Restock material."
                            class="w-full px-3 py-2.5 border border-ui-border rounded-lg text-sm focus:ring-2 focus:ring-brand-gold focus:outline-none">{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- ACTION --}}
                    <div class="pt-4 border-t border-ui-border flex justify-end">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm shadow transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v12M6 12h12" />
                            </svg>

                            <span>
                                Proses Stock In
                            </span>
                        </button>
                    </div>

                </form>
            </div>


            {{-- STOCK PREVIEW --}}
            <div class="bg-white p-6 rounded-xl border border-ui-border shadow-sm flex flex-col justify-between">

                <div>

                    <h3
                        class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-ui-border pb-3 mb-4">
                        Proyeksi Stok
                    </h3>

                    {{-- MATERIAL --}}
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 mb-4">

                        <span class="text-xs text-slate-400 block">
                            Material Dipilih
                        </span>

                        <span id="previewMaterialName" class="font-bold text-brand-navy text-base block mt-1">
                            -
                        </span>

                        <span id="previewMaterialBarcode" class="text-xs font-mono text-slate-500 block mt-0.5">
                            Barcode: -
                        </span>

                    </div>


                    {{-- STOCK CALCULATION --}}
                    <div class="grid grid-cols-3 gap-2 text-center">

                        {{-- STOCK BEFORE --}}
                        <div class="p-3 bg-slate-100 rounded-lg">

                            <span class="text-[10px] text-slate-500 uppercase font-bold block">
                                Stok Awal
                            </span>

                            <span id="previewStockBefore" class="text-sm font-bold text-slate-700 block mt-1">
                                0
                            </span>

                        </div>


                        {{-- STOCK IN --}}
                        <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-200">

                            <span class="text-[10px] text-emerald-600 uppercase font-bold block">
                                + Masuk
                            </span>

                            <span id="previewQuantity" class="text-sm font-bold text-emerald-600 block mt-1">
                                0
                            </span>

                        </div>


                        {{-- STOCK AFTER --}}
                        <div class="p-3 bg-brand-navy rounded-lg text-white">

                            <span class="text-[10px] text-slate-300 uppercase font-bold block">
                                Stok Akhir
                            </span>

                            <span id="previewStockAfter" class="text-sm font-bold block mt-1">
                                0
                            </span>

                        </div>

                    </div>


                    {{-- STOCK STATUS --}}
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs mt-4">

                        <div class="flex justify-between items-center">

                            <span class="text-slate-500">
                                Status Stok:
                            </span>

                            <span id="previewStatus" class="font-bold text-slate-400">
                                -
                            </span>

                        </div>

                    </div>

                </div>


                {{-- AUDIT --}}
                <div class="text-xs text-slate-500 bg-amber-50 p-3 rounded-lg border border-amber-200 mt-6">

                    <span class="font-semibold text-amber-800">
                        Sistem Audit:
                    </span>

                    Transaksi Stock In akan memperbarui stok material dan
                    mencatat perubahan stok pada riwayat inventory.

                </div>

            </div>
        </div>


        {{-- HISTORY --}}
        <section class="bg-white rounded-xl border border-ui-border shadow-sm overflow-hidden">

            <div class="p-4 border-b border-ui-border flex justify-between items-center bg-slate-50">

                <div>
                    <h3 class="font-bold text-brand-navy text-sm">
                        Riwayat Stock In
                    </h3>

                    <p class="text-xs text-slate-500 mt-0.5">
                        Transaksi barang masuk terbaru.
                    </p>
                </div>

                <span class="text-xs font-medium text-slate-500">
                    {{ $stockIns->total() }} Transaksi
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead
                        class="bg-slate-100 text-slate-600 border-b border-ui-border font-semibold text-xs uppercase tracking-wider">

                        <tr>
                            <th class="p-4">
                                Tanggal
                            </th>

                            <th class="p-4">
                                Material
                            </th>

                            <th class="p-4 text-center">
                                Stok Sebelum
                            </th>

                            <th class="p-4 text-center">
                                Qty Masuk
                            </th>

                            <th class="p-4 text-center">
                                Stok Sesudah
                            </th>

                            <th class="p-4">
                                Catatan
                            </th>
                        </tr>

                    </thead>


                    <tbody class="divide-y divide-ui-border">

                        @forelse ($stockIns as $stockIn)
                            <tr class="hover:bg-slate-50 transition">

                                <td class="p-4 text-slate-500 whitespace-nowrap">
                                    {{ $stockIn->created_at->format('d M Y H:i') }}
                                </td>


                                <td class="p-4">

                                    <div class="font-medium text-slate-800">
                                        {{ $stockIn->material->name }}
                                    </div>

                                    <div class="text-xs text-slate-400 font-mono mt-0.5">
                                        {{ $stockIn->material->barcode }}
                                    </div>

                                </td>


                                <td class="p-4 text-center text-slate-600">
                                    {{ $stockIn->stock_before }}
                                    {{ $stockIn->material->unit }}
                                </td>


                                <td class="p-4 text-center">

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs">
                                        +{{ $stockIn->quantity }}
                                        {{ $stockIn->material->unit }}
                                    </span>

                                </td>


                                <td class="p-4 text-center font-bold text-brand-navy">
                                    {{ $stockIn->stock_after }}
                                    {{ $stockIn->material->unit }}
                                </td>


                                <td class="p-4 text-slate-500">
                                    {{ $stockIn->notes ?: '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">
                                    Belum ada transaksi Stock In.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($stockIns->hasPages())
                <div class="p-4 border-t border-ui-border">
                    {{ $stockIns->links() }}
                </div>
            @endif

        </section>

    </main>

    @push('scripts')
        @vite('resources/js/admin/stock-preview.js')
    @endpush
</x-layouts.admin>
