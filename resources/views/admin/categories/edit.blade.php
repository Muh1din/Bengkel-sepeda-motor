<x-layouts.admin title="Edit Kategori">

    <div class="w-full">

        <div class="bg-ui-card border border-ui-border rounded-xl">

            <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-5">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Nama Kategori
                        </label>

                        <input type="text" id="name" name="name"
                            value="{{ old('name', $category->name) }}"
                            placeholder="Contoh: Oli, Sparepart, Bahan Cat"
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

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block mb-1.5 text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>

                        <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi kategori (opsional)"
                            class="w-full rounded-lg border border-ui-border
                                   bg-white px-3 py-2.5 text-sm
                                   resize-none
                                   focus:border-brand-navy
                                   focus:ring-1 focus:ring-brand-navy">{{ old('description', $category->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-ui-border">

                    <a href="{{ route('admin.categories.index') }}"
                        class="rounded-lg border border-ui-border
                               px-4 py-2.5
                               text-sm font-medium text-gray-600
                               hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-brand-navy
                               px-4 py-2.5
                               text-sm font-semibold text-white
                               hover:bg-slate-800">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts.admin>
