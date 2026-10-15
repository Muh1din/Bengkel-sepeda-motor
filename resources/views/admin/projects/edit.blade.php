<x-layouts.admin title="Edit Proyek">
    @php
        $coverImage = $project->images->firstWhere('image_type', 'cover');
        $galleryImages = $project->images->where('image_type', 'gallery');
        $beforeImages = $project->images->where('image_type', 'before');
        $afterImages = $project->images->where('image_type', 'after');
    @endphp

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi dan gambar proyek portofolio.
                </p>
            </div>

            <a href="{{ route('admin.projects.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-ui-border px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                Kembali
            </a>
        </div>

        {{-- General Error --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                <p class="font-semibold text-red-700">
                    Terdapat kesalahan pada formulir.
                </p>

                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Informasi Proyek --}}
            <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                <div class="mb-6 border-b border-ui-border pb-4">
                    <h2 class="text-lg font-semibold text-brand-navy">
                        Informasi Proyek
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Informasi utama proyek yang ditampilkan pada portofolio.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Kategori --}}
                    <div>
                        <label for="project_category_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Kategori Proyek
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="project_category_id" name="project_category_id" required
                            class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">
                            <option value="">Pilih kategori</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('project_category_id', $project->project_category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('project_category_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">
                            Status Publikasi
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="status" name="status" required
                            class="w-full rounded-lg border border-ui-border bg-white px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">
                            <option value="draft" @selected(old('status', $project->status) === 'draft')>
                                Draft
                            </option>

                            <option value="published" @selected(old('status', $project->status) === 'published')>
                                Published
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <p class="mt-1 text-xs text-gray-500">
                            Draft tidak ditampilkan pada halaman portofolio publik.
                        </p>
                    </div>

                    {{-- Judul --}}
                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-medium text-gray-700">
                            Judul Proyek
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                            maxlength="150" required placeholder="Contoh: Repaint Yamaha NMAX"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kendaraan --}}
                    <div>
                        <label for="vehicle_name" class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Kendaraan
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text" id="vehicle_name" name="vehicle_name"
                            value="{{ old('vehicle_name', $project->vehicle_name) }}" maxlength="100" required
                            placeholder="Contoh: Yamaha NMAX 155"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">

                        @error('vehicle_name')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label for="completed_at" class="mb-2 block text-sm font-medium text-gray-700">
                            Tanggal Selesai
                        </label>

                        <input type="date" id="completed_at" name="completed_at"
                            value="{{ old('completed_at', $project->completed_at?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">

                        @error('completed_at')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Ringkasan --}}
                    <div class="md:col-span-2">
                        <label for="summary" class="mb-2 block text-sm font-medium text-gray-700">
                            Ringkasan Proyek
                            <span class="text-red-500">*</span>
                        </label>

                        <textarea id="summary" name="summary" rows="3" maxlength="255" required
                            placeholder="Tuliskan ringkasan singkat mengenai proyek."
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">{{ old('summary', $project->summary) }}</textarea>

                        @error('summary')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label for="description" class="mb-2 block text-sm font-medium text-gray-700">
                            Deskripsi Proyek
                        </label>

                        <textarea id="description" name="description" rows="6" placeholder="Jelaskan detail pengerjaan proyek."
                            class="w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm text-ui-text outline-none transition focus:border-brand-gold focus:ring-2 focus:ring-brand-gold/20">{{ old('description', $project->description) }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Cover --}}
            <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                <div class="mb-5 border-b border-ui-border pb-4">
                    <h2 class="text-lg font-semibold text-brand-navy">
                        Cover Proyek
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Gambar utama yang mewakili proyek pada halaman portofolio.
                    </p>
                </div>

                @if ($coverImage)
                    <div class="mb-5">
                        <p class="mb-2 text-sm font-medium text-gray-700">
                            Cover Saat Ini
                        </p>

                        <div class="max-w-md overflow-hidden rounded-lg border border-ui-border">
                            <img src="{{ asset('storage/' . $coverImage->image_path) }}"
                                alt="Cover {{ $project->title }}" class="aspect-video w-full object-cover">
                        </div>
                    </div>
                @else
                    <div
                        class="mb-5 rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
                        Cover belum tersedia.
                    </div>
                @endif

                <div>
                    <label for="cover" class="mb-2 block text-sm font-medium text-gray-700">
                        {{ $coverImage ? 'Ganti Cover' : 'Unggah Cover' }}
                    </label>

                    <input type="file" id="cover" name="cover"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="block w-full cursor-pointer rounded-lg border border-ui-border text-sm text-gray-600 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:font-medium file:text-gray-700 hover:file:bg-gray-200">

                    <p class="mt-2 text-xs text-gray-500">
                        Format JPG, JPEG, PNG, atau WebP. Maksimal 5 MB.
                        Kosongkan jika tidak ingin mengganti cover.
                    </p>

                    @error('cover')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Gambar Galeri --}}
            <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                <div class="mb-5 border-b border-ui-border pb-4">
                    <h2 class="text-lg font-semibold text-brand-navy">
                        Gambar Galeri
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Kelola gambar tambahan untuk proyek ini.
                    </p>
                </div>

                @if ($galleryImages->isNotEmpty())
                    <div class="mb-6">
                        <p class="mb-3 text-sm font-medium text-gray-700">
                            Gambar Galeri Saat Ini
                        </p>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach ($galleryImages as $image)
                                <div class="overflow-hidden rounded-lg border border-ui-border">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        alt="{{ $image->caption ?: 'Gambar galeri ' . $project->title }}"
                                        class="aspect-square w-full object-cover">

                                    <div class="p-3">
                                        <label class="flex cursor-pointer items-start gap-2 text-sm text-red-600">
                                            <input type="checkbox" name="remove_images[]"
                                                value="{{ $image->id }}" @checked(in_array($image->id, old('remove_images', [])))
                                                class="mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-500">

                                            <span>Hapus gambar</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="mb-5 text-sm text-gray-500">
                        Belum ada gambar galeri.
                    </p>
                @endif

                <div>
                    <label for="gallery" class="mb-2 block text-sm font-medium text-gray-700">
                        Tambah Gambar Galeri
                    </label>

                    <input type="file" id="gallery" name="gallery[]" multiple
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="block w-full cursor-pointer rounded-lg border border-ui-border text-sm text-gray-600 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:font-medium file:text-gray-700 hover:file:bg-gray-200">

                    <p class="mt-2 text-xs text-gray-500">
                        Maksimal 5 gambar baru dalam sekali penyimpanan. Setiap gambar maksimal 5 MB.
                    </p>

                    @error('gallery')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @error('gallery.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Gambar Before --}}
            {{-- Foto Sebelum & Sesudah --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Foto Sebelum --}}
                <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                    <div class="mb-5 border-b border-ui-border pb-4">
                        <h2 class="text-lg font-semibold text-brand-navy">
                            Foto Sebelum Pengerjaan
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Foto kondisi kendaraan sebelum pengerjaan dimulai.
                        </p>
                    </div>

                    @if ($beforeImages->isNotEmpty())
                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-3">
                                @foreach ($beforeImages as $image)
                                    <div class="overflow-hidden rounded-lg border border-ui-border">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $image->caption ?: 'Foto sebelum pengerjaan' }}"
                                            class="aspect-square w-full object-cover">

                                        <div class="p-3">
                                            <label class="flex cursor-pointer items-start gap-2 text-sm text-red-600">
                                                <input type="checkbox" name="remove_images[]"
                                                    value="{{ $image->id }}" @checked(in_array($image->id, old('remove_images', [])))
                                                    class="mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-500">

                                                <span>Hapus gambar</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="mb-5 text-sm text-gray-500">
                            Belum ada foto sebelum pengerjaan.
                        </p>
                    @endif

                    <div>
                        <label for="before" class="mb-2 block text-sm font-medium text-gray-700">
                            Tambah Foto Sebelum
                        </label>

                        <input type="file" id="before" name="before[]" multiple
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="block w-full cursor-pointer rounded-lg border border-ui-border text-sm text-gray-600 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:font-medium file:text-gray-700 hover:file:bg-gray-200">

                        <p class="mt-2 text-xs text-gray-500">
                            Maksimal 5 gambar baru dalam sekali penyimpanan.
                        </p>

                        @error('before')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('before.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Foto Sesudah --}}
                <div class="rounded-xl border border-ui-border bg-white p-5 sm:p-6">
                    <div class="mb-5 border-b border-ui-border pb-4">
                        <h2 class="text-lg font-semibold text-brand-navy">
                            Foto Sesudah Pengerjaan
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Foto hasil akhir setelah pengerjaan selesai.
                        </p>
                    </div>

                    @if ($afterImages->isNotEmpty())
                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-3">
                                @foreach ($afterImages as $image)
                                    <div class="overflow-hidden rounded-lg border border-ui-border">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $image->caption ?: 'Foto sesudah pengerjaan' }}"
                                            class="aspect-square w-full object-cover">

                                        <div class="p-3">
                                            <label class="flex cursor-pointer items-start gap-2 text-sm text-red-600">
                                                <input type="checkbox" name="remove_images[]"
                                                    value="{{ $image->id }}" @checked(in_array($image->id, old('remove_images', [])))
                                                    class="mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-500">

                                                <span>Hapus gambar</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="mb-5 text-sm text-gray-500">
                            Belum ada foto sesudah pengerjaan.
                        </p>
                    @endif

                    <div>
                        <label for="after" class="mb-2 block text-sm font-medium text-gray-700">
                            Tambah Foto Sesudah
                        </label>

                        <input type="file" id="after" name="after[]" multiple
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="block w-full cursor-pointer rounded-lg border border-ui-border text-sm text-gray-600 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:font-medium file:text-gray-700 hover:file:bg-gray-200">

                        <p class="mt-2 text-xs text-gray-500">
                            Maksimal 5 gambar baru dalam sekali penyimpanan.
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
    </div>

    {{-- Action --}}
    <div class="flex flex-col-reverse gap-3 border-t border-ui-border pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.projects.index') }}"
            class="inline-flex items-center justify-center rounded-lg border border-ui-border px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
            Batal
        </a>

        <button type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-brand-navy px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-navy/90 focus:outline-none focus:ring-2 focus:ring-brand-gold focus:ring-offset-2">
            Simpan Perubahan
        </button>
    </div>

    </form>
    </div>
</x-layouts.admin>
