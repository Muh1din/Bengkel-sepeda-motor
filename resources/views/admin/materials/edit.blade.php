<x-layouts.admin title="Edit Material">
    <div class="w-full px-8 py-6">
        <div class="mb-5">
            <h1 class="text-lg font-semibold text-brand-navy"> Edit Material </h1>
            <p class="mt-1 text-sm text-gray-500"> Perbarui informasi material. </p>
        </div>
        <div class="bg-white border border-ui-border rounded-xl shadow-sm">
            <form action="{{ route('admin.materials.update', $material) }}" method="POST" class="p-6"> @csrf
                @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-5"> {{-- Name --}} <div> <label
                            for="name" class="block mb-1.5 text-sm font-medium text-gray-700"> Nama Material
                        </label> <input type="text" id="name" name="name"
                            value="{{ old('name', $material->name) }}"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            required> @error('name')
                            <p class="mt-1 text-xs text-rose-600"> {{ $message }} </p>
                        @enderror
                    </div> {{-- Category --}} <div> <label for="category_id"
                            class="block mb-1.5 text-sm font-medium text-gray-700"> Kategori </label> <select
                            id="category_id" name="category_id"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            required>
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $material->category_id) == $category->id)> {{ $category->name }}
                                </option>
                            @endforeach
                        </select> @error('category_id')
                            <p class="mt-1 text-xs text-rose-600"> {{ $message }} </p>
                        @enderror
                    </div> {{-- Barcode --}} <div> <label for="barcode"
                            class="block mb-1.5 text-sm font-medium text-gray-700"> Barcode </label> <input
                            type="text" id="barcode" name="barcode"
                            value="{{ old('barcode', $material->barcode) }}"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm focus:border-brand-navy focus:ring-1 focus:ring-brand-navy">
                        @error('barcode')
                            <p class="mt-1 text-xs text-rose-600"> {{ $message }} </p>
                        @enderror
                    </div> {{-- Unit --}} <div> <label for="unit"
                            class="block mb-1.5 text-sm font-medium text-gray-700"> Satuan </label> <input
                            type="text" id="unit" name="unit" value="{{ old('unit', $material->unit) }}"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            required> @error('unit')
                            <p class="mt-1 text-xs text-rose-600"> {{ $message }} </p>
                        @enderror
                    </div> {{-- Minimum Stock --}} <div> <label for="minimum_stock"
                            class="block mb-1.5 text-sm font-medium text-gray-700"> Minimum Stock </label> <input
                            type="number" id="minimum_stock" name="minimum_stock" min="0"
                            value="{{ old('minimum_stock', $material->minimum_stock) }}"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            required> @error('minimum_stock')
                            <p class="mt-1 text-xs text-rose-600"> {{ $message }} </p>
                        @enderror
                    </div>
                </div> {{-- Current Stock --}} <div class="mt-5"> <label
                        class="block mb-1.5 text-sm font-medium text-gray-700"> Stock Saat Ini </label>
                    <div class="w-full rounded-lg border border-ui-border bg-gray-50 px-3 py-2.5 text-sm text-gray-600">
                        {{ $material->stock }} {{ $material->unit }} </div>
                    <p class="mt-1 text-xs text-gray-400"> Stock hanya dapat diubah melalui transaksi inventory. </p>
                </div> {{-- Actions --}} <div
                    class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-ui-border"> <a
                        href="{{ route('admin.materials.index') }}"
                        class="px-4 py-2.5 rounded-lg border border-ui-border text-sm font-medium text-gray-600 hover:bg-gray-50">
                        Batal </a> <button type="submit"
                        class="px-4 py-2.5 rounded-lg bg-brand-navy text-white text-sm font-semibold hover:bg-slate-800">
                        Simpan Perubahan </button> </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
