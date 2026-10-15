<section id="testimoni" class="py-16 sm:py-20 lg:py-24 bg-brand-navy relative overflow-hidden">

    <!-- Background blobs -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
        <div class="absolute -top-20 -left-20 w-64 sm:w-96 h-64 sm:h-96 bg-brand-gold/10 rounded-full blur-[100px]">
        </div>
        <div class="absolute -bottom-20 -right-20 w-64 sm:w-96 h-64 sm:h-96 bg-brand-gold/5 rounded-full blur-[100px]">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Header -->
        <div class="text-center mb-14" data-scroll="fade-up">
            <div
                class="flex items-center justify-center gap-3 text-brand-gold text-[11px] font-bold tracking-[0.3em] uppercase mb-4">
                Voice of Riders
            </div>
            <h3 class="mega-title font-display text-white uppercase leading-none mb-5">
                KATA <span class="text-gold-gradient italic">MEREKA.</span>
            </h3>
            <p class="text-brand-silver text-sm max-w-xs mx-auto leading-relaxed">
                Kepercayaan adalah segalanya. Kami menjaga setiap detail pada standar tertinggi.
            </p>
        </div>

        <!-- Slider -->
        <div class="relative" data-scroll="fade-up">
            <div class="overflow-hidden max-w-[320px] md:max-w-164 lg:max-w-248 mx-auto">
                <div id="testimonialTrack" class="flex gap-4 transition-transform duration-500 ease-in-out">

                    @forelse ($reviews as $review)
                        <article
                            class="testi-card glass-card rounded-3xl p-7 border border-white/10
                   w-[320px] hover:border-brand-gold/30
                   transition-all duration-500 shrink-0">

                            {{-- Quote Icon --}}
                            <i class="fa-solid fa-quote-left text-brand-gold/20 text-3xl mb-4 block"></i>

                            {{-- Rating --}}
                            <div class="text-brand-gold text-sm tracking-widest mb-4"
                                aria-label="Rating {{ $review->rating }} dari 5">

                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor

                            </div>

                            {{-- Review Comment --}}
                            <p class="text-brand-silver text-sm leading-relaxed italic mb-6">
                                {{ $review->comment ?: 'Pelanggan tidak memberikan komentar.' }}
                            </p>

                            {{-- Customer Information --}}
                            <div class="flex items-center gap-3">

                                {{-- Initials --}}
                                <div
                                    class="w-9 h-9 rounded-full bg-brand-gold/20
                           flex items-center justify-center
                           text-brand-gold font-black text-xs shrink-0">

                                    {{ collect(explode(' ', trim($review->customer?->name ?? 'Pelanggan')))->filter()->take(2)->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('') }}

                                </div>

                                <div class="min-w-0">
                                    <p class="text-white font-black text-xs uppercase tracking-widest">
                                        {{ $review->customer?->name ?? 'Pelanggan Nirwana Garage' }}
                                    </p>

                                    <p class="text-brand-silver/50 text-xs mt-1">
                                        {{ $review->created_at->format('d M Y') }}
                                    </p>
                                </div>

                            </div>

                        </article>
                    @empty
                        <div class="w-full py-10 text-center">
                            <p class="text-brand-silver text-sm">
                                Belum ada ulasan pelanggan yang dipublikasikan.
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>

            <!-- Arrow Left -->
            <button onclick="moveTestimonial(-1)"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white hover:border-brand-gold hover:text-brand-gold transition-all duration-300 z-10 bg-brand-navy">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M15 18l-6-6 6-6" />
                </svg>
            </button>

            <!-- Arrow Right -->
            <button onclick="moveTestimonial(1)"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 w-10 h-10 rounded-full border border-white/10 flex items-center justify-center text-white hover:border-brand-gold hover:text-brand-gold transition-all duration-300 z-10 bg-brand-navy">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M9 18l6-6-6-6" />
                </svg>
            </button>
        </div>

        <!-- Dots -->
        <div id="testimonialDots" class="flex justify-center gap-2 mt-8"></div>

        <!-- Brand Bar -->
        <div class="mt-16 pt-8 border-t border-white/10 flex flex-wrap justify-center gap-6 sm:gap-12 opacity-20 hover:opacity-40 transition-all"
            data-scroll="fade-up">
            <span class="text-white font-display text-lg sm:text-2xl tracking-tighter uppercase">Performance</span>
            <span class="text-white font-display text-lg sm:text-2xl tracking-tighter uppercase">Precision</span>
            <span class="text-white font-display text-lg sm:text-2xl tracking-tighter uppercase">Restore</span>
            <span class="text-white font-display text-lg sm:text-2xl tracking-tighter uppercase">Culture</span>
        </div>

    </div>
</section>
