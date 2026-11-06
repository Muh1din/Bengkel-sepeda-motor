<footer class="bg-brand-navy text-white pt-16 sm:pt-20 lg:pt-24 pb-8 safe-bottom relative overflow-hidden">
    <div
        class="absolute top-0 right-0 w-64 sm:w-96 h-64 sm:h-96 bg-brand-gold/5 rounded-full blur-[120px] pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 sm:gap-12 lg:gap-16 mb-14 sm:mb-20">

            <!-- Brand -->
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                        <img src="{{ asset('logo/logo.jpeg') }}" alt="Nirwana Garage Logo"
                            class="w-full h-full object-contain">
                    </div>

                    <span class="text-2xl font-display font-black tracking-tighter uppercase">
                        Nirwana <span class="text-brand-gold">Garage.</span>
                    </span>
                </div>
                <p class="text-brand-silver leading-relaxed max-w-sm mb-7 italic text-sm">
                    "Membangun mesin dengan presisi, merestorasi dengan hati. Standar baru workshop otomotif."
                </p>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/nirwana.garage77?vrfl=MTA3aWd1eGlpYjRmMA=="
                        class="w-11 h-11 border border-white/10 rounded-xl flex items-center justify-center hover:bg-brand-gold hover:border-brand-gold transition-all duration-400">
                        <i class="fa-brands fa-instagram text-base"></i>
                    </a>
                    <a href="https://www.tiktok.com/@nirwana.garage77?_r=1&_t=ZS-9BBHWIAx5SY"
                        class="w-11 h-11 border border-white/10 rounded-xl flex items-center justify-center hover:bg-brand-gold hover:border-brand-gold transition-all duration-400">
                        <i class="fa-brands fa-tiktok text-base"></i>
                    </a>
                    <a href="https://wa.me/6285811975195?text=Halo%20Nirwana%20Garage%2C%20saya%20ingin%20bertanya%20seputar%20layanan%20servis%20dan%20perawatan%20motor.%20Mohon%20informasi%20lebih%20lanjut.%20Terima%20kasih."
                        target="_blank" rel="noopener noreferrer" aria-label="Hubungi Nirwana Garage melalui WhatsApp"
                        class="w-11 h-11 border border-white/10 rounded-xl flex items-center justify-center hover:bg-brand-gold hover:border-brand-gold transition-all duration-400">
                        <i class="fa-brands fa-whatsapp text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div class="lg:col-span-3">
                <h4 class="text-brand-gold font-black uppercase tracking-[0.3em] text-[10px] mb-6">Eksplorasi</h4>
                <ul class="space-y-4 text-sm font-bold">
                    <li>
                        <a href="{{ url('/#tentang') }}"
                            class="text-brand-silver hover:text-white flex items-center gap-3 group transition-all">
                            <span class="w-0 h-px bg-brand-gold group-hover:w-4 transition-all duration-300"></span>
                            Tentang Kami
                        </a>
                    </li>
                    <li><a href="{{ url('/#layanan') }} "
                            class="text-brand-silver hover:text-white flex items-center gap-3 group transition-all"><span
                                class="w-0 h-px bg-brand-gold group-hover:w-4 transition-all duration-300"></span>Layanan
                            Spesialis</a></li>
                    <li><a href="#booking"
                            class="text-brand-silver hover:text-white flex items-center gap-3 group transition-all"><span
                                class="w-0 h-px bg-brand-gold group-hover:w-4 transition-all duration-300"></span>Reservasi
                            Antrian</a></li>
                    <li>
                        <a href="{{ url('/#galeri') }}"
                            class="text-brand-silver hover:text-white flex items-center gap-3 group transition-all">
                            <span class="w-0 h-px bg-brand-gold group-hover:w-4 transition-all duration-300"></span>
                            Galeri Kami
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Info -->
            <div class="lg:col-span-4">
                <h4 class="text-brand-gold font-black uppercase tracking-[0.3em] text-[10px] mb-6">Workshop</h4>
                <div class="space-y-5">
                    <div>
                        <p class="text-brand-silver text-[10px] font-black uppercase tracking-widest mb-1">Lokasi
                        </p>
                        <p class="font-bold text-sm">Jl. Pondok Labu, Depok Jawa Barat</p>
                    </div>
                    <div>
                        <p class="text-brand-silver text-[10px] font-black uppercase tracking-widest mb-1">Waktu
                            Operasional</p>
                        <p class="font-bold text-sm">Senin - Sabtu (09.00 - 18.00 WIB)</p>
                    </div>
                    <div>
                        <p class="text-brand-silver text-[10px] font-black uppercase tracking-widest mb-1">Layanan
                            Darurat</p>
                        <p class="font-bold text-sm text-brand-gold italic">Towing Ready 24/7</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row justify-between items-center gap-5">
            <div class="text-[10px] font-bold text-brand-silver uppercase tracking-[0.15em] text-center sm:text-left">
                &copy; 2026 Nirwana Garage. <span class="text-white/20 ml-1">Crafted for Excellence.</span>
            </div>
        </div>
    </div>
</footer>
