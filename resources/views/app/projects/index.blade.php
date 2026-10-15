<x-layouts.projects>
    <!-- HEADER SECTION -->
    <header class="relative overflow-hidden bg-brand-navy py-16 md:py-24">
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute inset-0"
                style="background-image: radial-gradient(circle at 2px 2px, #bdc3c7 1px, transparent 0); background-size: 40px 40px;">
            </div>
        </div>

        <div class="relative z-10 mx-auto max-w-6xl px-6 text-center mt-10">
            <h1 class="text-5xl font-black uppercase tracking-tighter text-white md:text-7xl">
                OUR <span class="italic text-brand-gold">WORKS.</span>
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-brand-silver">
                Kumpulan karya terbaik dari workshop Nirwana Garage.
                Dari restorasi klasik hingga mesin spesifikasi balap.
            </p>
        </div>
    </header>

    <!-- CATEGORY FILTER -->
    <div class="relative z-20 mx-auto -mt-8 max-w-6xl px-6">
        <div
            class="flex flex-nowrap gap-2 overflow-x-auto whitespace-nowrap rounded-2xl border border-ui-border/50 bg-white p-2 shadow-xl scrollbar-hide md:flex-wrap md:justify-center md:overflow-visible">

            <a
                href="{{ route('projects.index') }}"
                @class([
                    'shrink-0 rounded-xl px-6 py-3 text-[10px] font-black uppercase tracking-widest transition-all',
                    'bg-brand-gold text-white shadow-lg' => !request()->filled('category'),
                    'text-brand-navy hover:bg-ui-main' => request()->filled('category'),
                ])>
                Semua
            </a>

            @foreach ($categories as $category)
                <a
                    href="{{ route('projects.index', ['category' => $category->slug]) }}"
                    @class([
                        'shrink-0 rounded-xl px-6 py-3 text-[10px] font-black uppercase tracking-widest transition-all',
                        'bg-brand-gold text-white shadow-lg' => request('category') === $category->slug,
                        'text-brand-navy hover:bg-ui-main' => request('category') !== $category->slug,
                    ])>
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- PROJECT LIST -->
    <main class="mx-auto max-w-6xl px-6 py-16">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">

            @forelse ($projects as $project)
                @php
                    $coverImage = $project->images->firstWhere('image_type', 'cover');
                @endphp

                <article
                    class="group overflow-hidden rounded-[2.5rem] border border-ui-border bg-white shadow-sm transition-all duration-500 hover:shadow-2xl">

                    <!-- PROJECT IMAGE -->
                    <div class="relative h-64 overflow-hidden bg-ui-main sm:h-72">
                        @if ($coverImage)
                            <img
                                src="{{ asset('storage/' . $coverImage->image_path) }}"
                                alt="{{ $coverImage->caption ?? $project->title }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                loading="lazy">
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-steel">
                                Belum ada gambar proyek.
                            </div>
                        @endif

                        <!-- CATEGORY BADGE -->
                        <div class="absolute left-6 top-6">
                            <span class="rounded-full bg-brand-gold px-4 py-2 text-[10px] font-black uppercase tracking-widest text-white shadow-lg">
                                {{ $project->category?->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>

                    <!-- PROJECT INFORMATION -->
                    <div class="p-8">
                        <h2 class="mb-2 text-2xl font-black uppercase text-brand-navy">
                            {{ $project->title }}
                        </h2>

                        <p class="mb-6 text-sm leading-relaxed text-brand-steel">
                            {{ $project->summary }}
                        </p>

                        <div class="flex items-center justify-between border-t border-ui-border pt-6">
                            <span class="text-xs font-semibold text-brand-steel">
                                {{ $project->vehicle_name }}
                            </span>

                            <a
                                href="{{ route('projects.show', $project->slug) }}"
                                class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-brand-gold transition-all hover:gap-3">
                                Detail
                                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-20 text-center">
                    <h2 class="text-2xl font-black uppercase text-brand-navy">
                        Project Tidak Ditemukan
                    </h2>

                    <p class="mt-2 text-brand-steel">
                        Maaf, belum ada proyek dalam kategori ini.
                    </p>

                    @if (request()->filled('category'))
                        <a
                            href="{{ route('projects.index') }}"
                            class="mt-5 inline-block text-sm font-bold text-brand-gold hover:underline">
                            Lihat Semua Proyek
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if ($projects->hasPages())
            <nav
                class="mt-16 flex flex-wrap items-center justify-center gap-4"
                aria-label="Navigasi halaman proyek">

                <!-- PREVIOUS -->
                @if ($projects->onFirstPage())
                    <span
                        class="flex h-12 w-12 cursor-not-allowed items-center justify-center rounded-full border border-ui-border opacity-40"
                        aria-disabled="true">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </span>
                @else
                    <a
                        href="{{ $projects->previousPageUrl() }}"
                        class="flex h-12 w-12 items-center justify-center rounded-full border border-ui-border transition-all hover:bg-brand-gold hover:text-white"
                        aria-label="Halaman sebelumnya">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </a>
                @endif

                <!-- PAGE NUMBERS -->
                @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                    <a
                        href="{{ $url }}"
                        @class([
                            'flex h-10 min-w-10 items-center justify-center rounded-lg px-3 text-sm font-black transition-all',
                            'bg-brand-navy text-white' => $page === $projects->currentPage(),
                            'text-brand-steel hover:bg-ui-main' => $page !== $projects->currentPage(),
                        ])
                        @if ($page === $projects->currentPage()) aria-current="page" @endif>
                        {{ $page }}
                    </a>
                @endforeach

                <!-- NEXT -->
                @if ($projects->hasMorePages())
                    <a
                        href="{{ $projects->nextPageUrl() }}"
                        class="flex h-12 w-12 items-center justify-center rounded-full border border-ui-border transition-all hover:bg-brand-gold hover:text-white"
                        aria-label="Halaman berikutnya">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </a>
                @else
                    <span
                        class="flex h-12 w-12 cursor-not-allowed items-center justify-center rounded-full border border-ui-border opacity-40"
                        aria-disabled="true">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </span>
                @endif
            </nav>
        @endif
    </main>
</x-layouts.projects>