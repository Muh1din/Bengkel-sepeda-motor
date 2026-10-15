<x-layouts.admin title="Edit Kategori Proyek">
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <a href="{{ route('admin.project-categories.index') }}"
                class="text-sm font-medium text-brand-steel transition hover:text-brand-navy">
                &larr; Kembali ke Kategori Proyek
            </a>
            <p class="mt-1 text-sm text-brand-steel">
                Perbarui informasi kategori proyek portofolio.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl border border-ui-border bg-white p-6">
            <form action="{{ route('admin.project-categories.update', $projectCategory) }}" method="POST"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Kategori --}}
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-ui-text">
                        Nama Kategori
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="text" id="name" name="name"
                        value="{{ old('name', $projectCategory->name) }}"
                        placeholder="Contoh: Repaint, Restorasi, Body Repair" maxlength="100" required autofocus
                        class="w-full rounded-lg border border-ui-border px-4 py-3 text-sm text-ui-text outline-none transition placeholder:text-gray-400 focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/10">

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-2 text-xs text-brand-steel">
                        Maksimal 100 karakter. Nama kategori harus unik.
                    </p>
                </div>

                {{-- Informasi Slug --}}
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-sm font-semibold text-ui-text">
                        Slug Saat Ini
                    </p>

                    <p class="mt-1 break-all text-sm text-brand-steel">
                        {{ $projectCategory->slug }}
                    </p>

                    <p class="mt-2 text-xs text-brand-steel">
                        Slug akan diperbarui otomatis berdasarkan nama kategori baru.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-ui-border pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.project-categories.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-ui-border px-5 py-2.5 text-sm font-semibold text-ui-text transition hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-navy px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-steel">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
