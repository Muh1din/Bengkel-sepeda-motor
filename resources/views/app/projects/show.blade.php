<x-layouts.projects>

    <main class="mx-auto max-w-6xl px-6 py-8 sm:px-12 sm:py-12">

        <!-- PROJECT TITLE -->
        <section class="mt-20">

            <span class="text-xs font-bold uppercase tracking-widest text-brand-gold">
                {{ $project->category->name }}
            </span>

            <h1 class="mt-2 text-3xl font-black uppercase sm:text-4xl">
                {{ $project->title }}
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-6 text-steel">
                {{ $project->summary }}
            </p>

        </section>


        @php
            $cover = $project->images->firstWhere('image_type', 'cover');

            $galleryImages = $project->images->where('image_type', 'gallery')->sortBy('sort_order');

            $beforeImages = $project->images->where('image_type', 'before')->sortBy('sort_order');

            $afterImages = $project->images->where('image_type', 'after')->sortBy('sort_order');
        @endphp


        <!-- MAIN IMAGE -->
        <section class="mt-10">

            @if ($cover)
                <img id="mainImage" src="{{ asset('storage/' . $cover->image_path) }}"
                    alt="{{ $cover->caption ?? $project->title }}"
                    class="h-64 w-full cursor-pointer rounded-xl bg-gray-100 object-cover sm:h-[450px]">
            @else
                <div
                    class="flex h-64 w-full items-center justify-center rounded-xl bg-gray-100 text-sm text-gray-500 sm:h-[450px]">
                    Belum ada gambar utama.
                </div>
            @endif


            <!-- THUMBNAILS -->
            @if ($cover || $galleryImages->isNotEmpty())

                <div class="mt-3 flex gap-3 overflow-x-auto pb-2">

                    @if ($cover)
                        <button type="button" onclick="changeImage(this)"
                            data-image="{{ asset('storage/' . $cover->image_path) }}"
                            data-alt="{{ $cover->caption ?? $project->title }}" class="shrink-0">
                            <img src="{{ asset('storage/' . $cover->image_path) }}"
                                alt="{{ $cover->caption ?? $project->title }}"
                                class="h-16 w-20 rounded-lg object-cover sm:h-20 sm:w-28">
                        </button>
                    @endif


                    @foreach ($galleryImages as $image)
                        <button type="button" onclick="changeImage(this)"
                            data-image="{{ asset('storage/' . $image->image_path) }}"
                            data-alt="{{ $image->caption ?? $project->title }}" class="shrink-0">
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $image->caption ?? $project->title }}"
                                class="h-16 w-20 rounded-lg object-cover sm:h-20 sm:w-28" loading="lazy">
                        </button>
                    @endforeach

                </div>

            @endif

        </section>


        <!-- BEFORE & AFTER -->
        @if ($beforeImages->isNotEmpty() && $afterImages->isNotEmpty())

            <section class="mt-12">

                <h2 class="text-xl font-black uppercase">
                    Before <span class="text-brand-gold">&</span> After
                </h2>

                <p class="mt-2 text-sm text-steel">
                    Dokumentasi kondisi kendaraan sebelum dan sesudah pengerjaan.
                </p>

                <div class="mt-5 grid gap-6 sm:grid-cols-2">

                    <!-- BEFORE -->
                    <div>

                        <div class="relative overflow-hidden rounded-xl bg-gray-100">

                            <img src="{{ asset('storage/' . $beforeImages->first()->image_path) }}"
                                alt="{{ $beforeImages->first()->caption ?? 'Sebelum pengerjaan' }}"
                                class="h-56 w-full object-cover sm:h-72" loading="lazy">

                            <span
                                class="absolute left-3 top-3 rounded bg-navy px-3 py-1.5 text-xs font-bold uppercase text-white">
                                Before
                            </span>

                        </div>

                        <p class="mt-2 text-sm font-bold">
                            Sebelum Pengerjaan
                        </p>

                        @if ($beforeImages->first()->caption)
                            <p class="mt-1 text-sm text-steel">
                                {{ $beforeImages->first()->caption }}
                            </p>
                        @endif

                    </div>


                    <!-- AFTER -->
                    <div>

                        <div class="relative overflow-hidden rounded-xl bg-gray-100">

                            <img src="{{ asset('storage/' . $afterImages->first()->image_path) }}"
                                alt="{{ $afterImages->first()->caption ?? 'Setelah pengerjaan' }}"
                                class="h-56 w-full object-cover sm:h-72" loading="lazy">

                            <span
                                class="absolute left-3 top-3 rounded bg-brand-gold px-3 py-1.5 text-xs font-bold uppercase text-white">
                                After
                            </span>

                        </div>

                        <p class="mt-2 text-sm font-bold">
                            Setelah Pengerjaan
                        </p>

                        @if ($afterImages->first()->caption)
                            <p class="mt-1 text-sm text-steel">
                                {{ $afterImages->first()->caption }}
                            </p>
                        @endif

                    </div>

                </div>

            </section>

        @endif


        <!-- PROJECT DESCRIPTION -->
        @if ($project->description)
            <section class="mt-12 border-t border-border pt-8">

                <h2 class="text-xl font-black uppercase">
                    Tentang Proyek
                </h2>

                <p class="mt-4 max-w-3xl whitespace-pre-line text-sm leading-7 text-steel">{{ $project->description }}
                </p>

            </section>
        @endif


        <!-- PROJECT INFORMATION -->
        <section class="mt-8 rounded-xl bg-ui-main p-5">

            <h2 class="text-lg font-black uppercase">
                Informasi Proyek
            </h2>

            <div class="mt-4 grid grid-cols-2 gap-5 sm:grid-cols-3">

                <div>
                    <p class="text-sm text-steel">
                        Kategori
                    </p>

                    <p class="mt-1 font-bold">
                        {{ $project->category->name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-steel">
                        Kendaraan
                    </p>

                    <p class="mt-1 font-bold">
                        {{ $project->vehicle_name }}
                    </p>
                </div>

                @if ($project->completed_at)
                    <div>
                        <p class="text-sm text-steel">
                            Tanggal Selesai
                        </p>

                        <p class="mt-1 font-bold">
                            {{ $project->completed_at->format('d M Y') }}
                        </p>
                    </div>
                @endif

            </div>

        </section>

    </main>


    <!-- IMAGE PREVIEW -->
    <div id="imagePreview" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 p-5"
        role="dialog" aria-modal="true" aria-label="Pratinjau gambar" onclick="closePreview()">

        <button type="button" class="absolute right-5 top-5 text-4xl text-white" aria-label="Tutup gambar">
            &times;
        </button>

        <img id="previewImage" src="" alt="Pratinjau foto proyek"
            class="max-h-[85vh] max-w-full rounded-lg object-contain">

    </div>


    <!-- JAVASCRIPT -->
    <script>
        function changeImage(button) {
            const mainImage = document.getElementById('mainImage');

            if (!mainImage) {
                return;
            }

            mainImage.src = button.dataset.image;
            mainImage.alt = button.dataset.alt;
        }

        const mainImage = document.getElementById('mainImage');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');

        if (mainImage) {
            mainImage.addEventListener('click', function() {
                previewImage.src = this.src;
                previewImage.alt = this.alt;

                imagePreview.classList.remove('hidden');
                imagePreview.classList.add('flex');
            });
        }

        function closePreview() {
            imagePreview.classList.add('hidden');
            imagePreview.classList.remove('flex');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePreview();
            }
        });
    </script>

</x-layouts.projects>
