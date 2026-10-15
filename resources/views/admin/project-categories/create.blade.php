<x-layouts.admin title="Tambah Kategori">
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <a
                href="{{ route('admin.project-categories.index') }}"
                class="text-sm font-medium text-brand-steel transition hover:text-brand-navy"
            >
                &larr; Kembali ke Kategori Proyek
            </a>

            <p class="mt-1 text-sm text-brand-steel">
                Tambahkan kategori untuk mengelompokkan proyek portofolio bengkel.
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl border border-ui-border bg-white p-6">
            <form
                action="{{ route('admin.project-categories.store') }}"
                method="POST"
                class="space-y-6"
            >
                @csrf

                {{-- Nama Kategori --}}
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-semibold text-ui-text"
                    >
                        Nama Kategori
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Repaint, Restorasi, Body Repair"
                        maxlength="100"
                        required
                        autofocus
                        class="w-full rounded-lg border border-ui-border px-4 py-3 text-sm text-ui-text outline-none transition placeholder:text-gray-400 focus:border-brand-navy focus:ring-2 focus:ring-brand-navy/10"
                    >

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
                        Tentang Slug
                    </p>

                    <p class="mt-1 text-sm text-brand-steel">
                        Slug akan dibuat otomatis berdasarkan nama kategori
                        dan digunakan sebagai identitas kategori pada URL.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-ui-border pt-5 sm:flex-row sm:justify-end">
                    <a
                        href="{{ route('admin.project-categories.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-ui-border px-5 py-2.5 text-sm font-semibold text-ui-text transition hover:bg-gray-50"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-brand-navy px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-steel"
                    >
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>