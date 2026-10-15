<section id="tentang" class="py-16 sm:py-20 lg:py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            <!-- Left: Image -->
            <div class="relative" data-scroll="fade-left">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl group">
                    <img src="{{ asset('logo/logo.jpeg') }}" alt="Workshop Activity"
                        class="w-full h-72 sm:h-112.5 lg:h-137.5 object-cover transition-transform duration-700 group-hover:scale-105">
                    {{-- note: sm:h-[450px] diganti ke  sm:h-112.5, lg:h-[550px] diganti ke lg:h-137.5 --}}
                    <div
                        class="absolute inset-0 bg-brand-navy/20 group-hover:bg-transparent transition-colors duration-500">
                    </div>
                </div>
                <div
                    class="absolute -bottom-5 -right-5 sm:-bottom-6 sm:-right-6 bg-brand-gold p-6 sm:p-8 rounded-3xl shadow-xl z-30 transform hover:-rotate-3 transition-transform">
                    <div class="text-white">
                        <span class="block text-3xl sm:text-4xl font-display font-black leading-none italic">2+</span>
                        <span
                            class="block text-[9px] font-bold uppercase tracking-widest mt-1">Tahun<br>Pengalaman</span>
                    </div>
                </div>
                <div
                    class="absolute -top-4 -left-4 w-24 h-24 sm:w-32 sm:h-32 border-l-4 border-t-4 border-brand-gold/30 rounded-tl-[2.5rem] -z-10">
                </div>
            </div>

            <!-- Right: Content -->
            <div data-scroll="fade-right">
                <div
                    class="flex items-center gap-3 text-brand-gold text-[11px] font-bold tracking-[0.3em] uppercase mb-4">
                   - Our Story
                </div>
                <h3 class="section-title font-display text-brand-navy uppercase leading-tight mb-7">
                    DIBANGUN DARI <span class="text-gold-gradient italic">PASSION</span><br>BUKAN SEKADAR BISNIS.
                </h3>

                <div class="space-y-4 text-brand-steel leading-relaxed text-sm sm:text-base">
                    <p><span class="font-bold text-brand-navy">Nirwana Garage</span> lahir dari kecintaan kami
                        terhadap estetika motor dan performa mesin yang mumpuni.</p>
                    <p>Kami memahami bahwa motor bagi Anda bukan sekadar alat transportasi, melainkan identitas.
                        Itulah alasan kami tidak pernah berkompromi dengan kualitas material.</p>
                </div>

                <!-- Key Points -->
                <div class="mt-8 grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-ui-main rounded-xl flex items-center justify-center text-brand-gold shrink-0">
                            <i class="fa-solid fa-microscope text-sm"></i>
                        </div>
                        <div>
                            <h5 class="font-black text-brand-navy text-xs uppercase">Detail Oriented</h5>
                            <p class="text-[11px] text-brand-steel mt-0.5">Presisi hingga baut terkecil.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-10 h-10 bg-ui-main rounded-xl flex items-center justify-center text-brand-gold shrink-0">
                            <i class="fa-solid fa-shield-alt text-sm"></i>
                        </div>
                        <div>
                            <h5 class="font-black text-brand-navy text-xs uppercase">Guaranteed</h5>
                            <p class="text-[11px] text-brand-steel mt-0.5">Garansi hasil pengerjaan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
