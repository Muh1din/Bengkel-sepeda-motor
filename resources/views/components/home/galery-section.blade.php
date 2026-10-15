<section id="galeri" class="scroll-mt-20 py-8 sm:py-10 bg-brand-navy relative">
    {{-- Background Decoration --}}
    <div
        class="absolute top-1/2 left-0 w-64 sm:w-96 h-64 sm:h-96 bg-brand-gold/5 rounded-full blur-[120px] -translate-y-1/2 pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <div class="text-center mb-6 sm:mb-8" data-scroll="fade-up">
            <div class="text-brand-gold text-[11px] font-bold tracking-[0.4em] uppercase mb-2">
                Masterpiece Portfolio
            </div>

            <h3 class="mega-title font-display text-white uppercase">
                HASIL <span class="text-gold-gradient italic">EKSEKUSI.</span>
            </h3>

            <div class="w-16 sm:w-24 h-0.5 bg-brand-gold mx-auto mt-4"></div>
        </div>

        {{-- Gallery Grid --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-55 sm:auto-rows-65 lg:auto-rows-75">

            @forelse ($projects as $index => $project)
                @php
                    $coverImage = $project->images->firstWhere('image_type', 'cover');
                @endphp

                {{-- Lewati proyek yang belum memiliki gambar cover --}}
                @if ($coverImage)
                    <div data-scroll="{{ $index === 0 ? 'fade-left' : ($index === 3 ? 'fade-right' : 'fade-up') }}"
                        class="gallery-item relative overflow-hidden rounded-3xl border border-white/10 group
                            {{ $index === 0 ? 'col-span-1 sm:col-span-2 row-span-1 sm:row-span-2' : '' }}
                            {{ $index === 1 ? 'col-span-1 sm:col-span-2' : '' }}">

                        {{-- Cover Image --}}
                        <img src="{{ asset('storage/' . $coverImage->image_path) }}" alt="{{ $project->title }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            loading="lazy">

                        {{-- Image Overlay --}}
                        <div class="absolute inset-0 gallery-overlay"></div>

                        {{-- Project Information --}}
                        <div
                            class="absolute z-10 {{ $index <= 1 ? 'bottom-5 sm:bottom-8 left-5 sm:left-8' : 'bottom-4 left-5' }}">

                            {{-- Category --}}
                            @if ($index <= 1 && $project->category)
                                <span
                                    class="inline-block px-3 py-1
                                        {{ $index === 0 ? 'bg-brand-gold text-white' : 'bg-brand-navy border border-brand-gold text-brand-gold' }}
                                        text-[10px] font-bold rounded-full uppercase tracking-widest">

                                    {{ $project->category->name_category }}

                                </span>
                            @endif

                            {{-- Project Title --}}
                            <h4
                                class="{{ $index <= 1 ? 'text-lg sm:text-2xl mt-2' : 'text-base sm:text-lg' }}
                                    font-display font-black text-white uppercase
                                    {{ $index >= 2 ? 'italic' : '' }}">

                                {{ $project->title }}

                            </h4>
                        </div>
                    </div>
                @endif

            @empty
                {{-- Empty State --}}
                <div class="col-span-full py-12 text-center">
                    <p class="text-brand-silver text-sm">
                        Belum ada proyek yang ditampilkan.
                    </p>
                </div>
            @endforelse

        </div>

        {{-- View All Projects --}}
        <div class="mt-6 sm:mt-8 text-center" data-scroll="fade-up">
            <a href="{{ route('projects.index') }}"
                class="inline-flex items-center gap-4 text-brand-silver hover:text-brand-gold font-bold transition-all group">

                <span class="tracking-[0.2em] uppercase text-xs">
                    Lihat Semua Proyek
                </span>

                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-brand-silver/20 flex items-center justify-center group-hover:border-brand-gold group-hover:bg-brand-gold/10 transition-all">

                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform text-sm"></i>

                </div>
            </a>
        </div>

    </div>
</section>
