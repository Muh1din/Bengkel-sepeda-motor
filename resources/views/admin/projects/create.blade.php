<x-layouts.admin title="Tambah Proyek">
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500">
                    Tambahkan proyek baru ke dalam portfolio Nirwana Garage.
                </p>
            </div>

            <a href="{{ route('admin.projects.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-ui-border px-4 py-2 text-sm font-medium text-ui-text transition hover:bg-gray-100">
                Kembali
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-700">
                    Terdapat kesalahan pada input:
                </p>

                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Create Project Form --}}
        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- Project Information --}}
            <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-ui-text">
                        Informasi Proyek
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Masukkan informasi utama proyek yang akan ditampilkan.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Project Title --}}
                    <div>
                        <label for="title" class="mb-2 block text-sm font-medium text-ui-text">
                            Nama Proyek <span class="text-red-500">*</span>
                        </label>

                        <input type="text" id="title" name="title" value="{{ old('title') }}" maxlength="150"
                            required placeholder="Contoh: Honda Vario Full Repaint"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vehicle Name --}}
                    <div>
                        <label for="vehicle_name" class="mb-2 block text-sm font-medium text-ui-text">
                            Nama Kendaraan <span class="text-red-500">*</span>
                        </label>

                        <input type="text" id="vehicle_name" name="vehicle_name" value="{{ old('vehicle_name') }}"
                            maxlength="100" required placeholder="Contoh: Honda Vario 160"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">

                        @error('vehicle_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Project Category --}}
                    <div>
                        <label for="project_category_id" class="mb-2 block text-sm font-medium text-ui-text">
                            Kategori Proyek <span class="text-red-500">*</span>
                        </label>

                        <select id="project_category_id" name="project_category_id" required
                            class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">
                            <option value="">Pilih kategori</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('project_category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('project_category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($categories->isEmpty())
                            <p class="mt-2 text-sm text-amber-600">
                                Belum ada kategori. Buat kategori terlebih dahulu sebelum menambahkan proyek.
                            </p>
                        @endif
                    </div>

                    {{-- Completion Date --}}
                    <div>
                        <label for="completed_at" class="mb-2 block text-sm font-medium text-ui-text">
                            Tanggal Selesai
                        </label>

                        <input type="date" id="completed_at" name="completed_at" value="{{ old('completed_at') }}"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">

                        @error('completed_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Summary --}}
                    <div class="md:col-span-2">
                        <label for="summary" class="mb-2 block text-sm font-medium text-ui-text">
                            Ringkasan Proyek <span class="text-red-500">*</span>
                        </label>

                        <textarea id="summary" name="summary" rows="3" maxlength="255" required
                            placeholder="Jelaskan secara singkat hasil pengerjaan proyek ini."
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">{{ old('summary') }}</textarea>

                        <p class="mt-1 text-xs text-gray-500">
                            Maksimal 255 karakter.
                        </p>

                        @error('summary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label for="description" class="mb-2 block text-sm font-medium text-ui-text">
                            Deskripsi Proyek
                        </label>

                        <textarea id="description" name="description" rows="6"
                            placeholder="Jelaskan proses pengerjaan, kondisi awal kendaraan, dan hasil akhir."
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label for="status" class="mb-2 block text-sm font-medium text-ui-text">
                            Status Publikasi <span class="text-red-500">*</span>
                        </label>

                        <select id="status" name="status" required
                            class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm outline-none transition focus:border-brand-steel focus:ring-2 focus:ring-brand-steel/20">
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>
                                Draft
                            </option>

                            <option value="published" @selected(old('status') === 'published')>
                                Published
                            </option>
                        </select>

                        <p class="mt-1 text-xs text-gray-500">
                            Draft tidak ditampilkan pada halaman portfolio publik.
                        </p>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Project Images --}}
            <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-ui-text">
                        Gambar Proyek
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Unggah foto hasil pengerjaan. Gambar akan disimpan dalam format WebP.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Cover Image --}}
                    <div>
                        <label for="cover" class="mb-2 block text-sm font-medium text-ui-text">
                            Gambar Cover <span class="text-red-500">*</span>
                        </label>

                        <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp"
                            required
                            class="block w-full rounded-lg border border-ui-border text-sm
                       file:mr-4 file:border-0 file:bg-gray-100
                       file:px-4 file:py-2.5 file:font-medium
                       hover:file:bg-gray-200">

                        <p class="mt-1 text-xs text-gray-500">
                            Format JPEG, PNG, atau WebP. Maksimal 5 MB.
                        </p>

                        @error('cover')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Gallery Images --}}
                    <div class="md:col-span-2">
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-medium text-ui-text">
                                Gambar Galeri
                            </label>

                            <button type="button" id="add-gallery"
                                class="rounded-lg border border-ui-border px-3 py-2 text-sm font-medium text-ui-text transition hover:bg-gray-100">
                                + Tambah Gambar
                            </button>
                        </div>

                        <div id="gallery-container" class="space-y-3">
                            <div class="gallery-item flex items-center gap-3">
                                <input type="file" name="gallery[]" accept="image/jpeg,image/png,image/webp"
                                    class="block w-full rounded-lg border border-ui-border text-sm
                       file:mr-4 file:border-0 file:bg-gray-100
                       file:px-4 file:py-2.5 file:font-medium
                       hover:file:bg-gray-200">
                            </div>
                        </div>

                        <p class="mt-2 text-xs text-gray-500">
                            Opsional. Tambahkan input sesuai jumlah gambar yang ingin diunggah.
                            Setiap gambar maksimal 5 MB.
                        </p>

                        @error('gallery')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('gallery.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Before Images --}}
                    <div>
                        <label for="before" class="mb-2 block text-sm font-medium text-ui-text">
                            Foto Sebelum Pengerjaan
                        </label>

                        <input type="file" id="before" name="before[]"
                            accept="image/jpeg,image/png,image/webp" multiple
                            class="block w-full rounded-lg border border-ui-border text-sm
                       file:mr-4 file:border-0 file:bg-gray-100
                       file:px-4 file:py-2.5 file:font-medium
                       hover:file:bg-gray-200">

                        <p class="mt-1 text-xs text-gray-500">
                            Opsional. Maksimal 5 gambar, masing-masing 5 MB.
                        </p>

                        @error('before')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('before.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- After Images --}}
                    <div>
                        <label for="after" class="mb-2 block text-sm font-medium text-ui-text">
                            Foto Setelah Pengerjaan
                        </label>

                        <input type="file" id="after" name="after[]" accept="image/jpeg,image/png,image/webp"
                            multiple
                            class="block w-full rounded-lg border border-ui-border text-sm
                       file:mr-4 file:border-0 file:bg-gray-100
                       file:px-4 file:py-2.5 file:font-medium
                       hover:file:bg-gray-200">

                        <p class="mt-1 text-xs text-gray-500">
                            Opsional. Maksimal 5 gambar, masing-masing 5 MB.
                        </p>

                        @error('after')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('after.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.projects.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-ui-border px-5 py-2.5 text-sm font-medium text-ui-text transition hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit" @disabled($categories->isEmpty())
                    class="inline-flex items-center justify-center rounded-lg bg-brand-navy px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-steel disabled:cursor-not-allowed disabled:opacity-50">
                    Simpan Proyek
                </button>
            </div>

        </form>

    </div>

    @push('scripts')
        @vite('resources/js/admin/create-admin-project.js')
    @endpush
</x-layouts.admin>
