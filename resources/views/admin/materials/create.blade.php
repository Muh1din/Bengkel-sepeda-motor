<x-layouts.admin title="Tambah Material">

    <div class="w-full">

        <div class="bg-ui-card border border-ui-border rounded-xl">

            <form action="{{ route('admin.materials.store') }}" method="POST" class="p-6">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Category --}}
                    <div>
                        <label for="material_category_id" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Kategori
                        </label>

                        <select id="material_category_id" name="material_category_id"
                            class="w-full rounded-lg border border-ui-border
                                   bg-white px-3 py-2.5 text-sm text-gray-700
                                   focus:border-brand-navy
                                   focus:ring-1 focus:ring-brand-navy"
                            required>
                            <option value="">
                                Pilih kategori
                            </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('material_category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('material_category_id')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nama Material
                        </label>

                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama material"
                            class="w-full rounded-lg border border-ui-border
                                   bg-white px-3 py-2.5 text-sm
                                   focus:border-brand-navy
                                   focus:ring-1 focus:ring-brand-navy"
                            required>

                        @error('name')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Barcode --}}
                    <div>
                        <label for="barcode" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Barcode
                        </label>

                        <div class="flex gap-2">

                            <input type="text" id="barcode" name="barcode" value="{{ old('barcode') }}"
                                placeholder="Scan atau masukkan barcode"
                                class="flex-1 rounded-lg border border-ui-border
                                       bg-white px-3 py-2.5 text-sm
                                       focus:border-brand-navy
                                       focus:ring-1 focus:ring-brand-navy"
                                required>

                            <button type="button" id="scanBarcode"
                                class="shrink-0 rounded-lg bg-brand-navy
                                       px-4 py-2.5 text-sm font-medium
                                       text-white hover:bg-slate-800">
                                Scan
                            </button>

                        </div>

                        <p class="mt-1 text-xs text-gray-500">
                            Digunakan untuk mengidentifikasi material saat transaksi stok.
                        </p>

                        {{-- Barcode Scanner --}}
                        <div id="barcodeScanner"
                            class="hidden mt-3 w-full max-w-lg overflow-hidden
                                   rounded-lg border border-ui-border bg-black">
                            <video id="barcodeVideo" class="w-full" muted playsinline></video>
                        </div>

                        @error('barcode')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Unit --}}
                    <div>
                        <label for="unit" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Satuan
                        </label>

                        <input type="text" id="unit" name="unit" value="{{ old('unit') }}"
                            placeholder="Contoh: pcs, liter, meter"
                            class="w-full rounded-lg border border-ui-border
                                   bg-white px-3 py-2.5 text-sm
                                   focus:border-brand-navy
                                   focus:ring-1 focus:ring-brand-navy"
                            required>

                        @error('unit')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Minimum Stock --}}
                    <div>
                        <label for="minimum_stock" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Minimum Stok
                        </label>

                        <input type="number" id="minimum_stock" name="minimum_stock"
                            value="{{ old('minimum_stock', 0) }}" min="0" placeholder="0"
                            class="w-full rounded-lg border border-ui-border
                                   bg-white px-3 py-2.5 text-sm
                                   focus:border-brand-navy
                                   focus:ring-1 focus:ring-brand-navy"
                            required>

                        <p class="mt-1 text-xs text-gray-500">
                            Batas minimum stok sebelum material dianggap menipis.
                        </p>

                        @error('minimum_stock')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>

                        <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi material (opsional)"
                            class="w-full rounded-lg border border-ui-border
                                   bg-white px-3 py-2.5 text-sm
                                   resize-none
                                   focus:border-brand-navy
                                   focus:ring-1 focus:ring-brand-navy">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div
                    class="flex items-center justify-end gap-3
                           mt-6 pt-5 border-t border-ui-border">

                    <a href="{{ route('admin.materials.index') }}"
                        class="rounded-lg border border-ui-border
                               px-4 py-2.5 text-sm font-medium text-gray-600
                               hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-brand-navy
                               px-4 py-2.5 text-sm font-semibold text-white
                               hover:bg-slate-800">
                        Simpan Material
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts.admin>
