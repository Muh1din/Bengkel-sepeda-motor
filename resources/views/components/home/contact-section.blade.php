<section id="kontak" class="py-16 sm:py-20 lg:py-24 bg-ui-main">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Eyebrow -->
        <div class="flex items-center gap-3 text-brand-gold text-[10px] font-black tracking-[0.3em] uppercase mb-10">
            - Get In Touch
        </div>

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-14 items-start">

            <!-- ===== LEFT ===== -->
            <div class="lg:col-span-5 space-y-8" data-scroll="fade-left">

                <h2
                    class="font-display text-4xl sm:text-5xl font-black text-brand-navy uppercase leading-[1.1] tracking-tight">
                    KONSULTASI<br>
                    <span class="text-brand-gold italic">GRATIS.</span>
                </h2>

                <div class="space-y-5">

                    <!-- Alamat -->
                    <div class="flex gap-4 items-start group">
                        <div
                            class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center text-brand-gold text-base shadow-sm shrink-0 group-hover:bg-brand-gold group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-brand-navy uppercase tracking-widest mb-1">
                                Workshop Kami</p>
                            <p class="text-brand-steel text-sm leading-relaxed">Jl. Pondok Labu<br>Depok, Jawa
                                Barat</p>
                        </div>
                    </div>

                    <!-- Jam Kerja -->
                    <div class="flex gap-4 items-start group">
                        <div
                            class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center text-brand-gold text-base shadow-sm shrink-0 group-hover:bg-brand-gold group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-brand-navy uppercase tracking-widest mb-1">Jam
                                Kerja</p>
                            <p class="text-brand-steel text-sm leading-relaxed">
                                Senin – Sabtu: 09.00 – 18.00 WIB<br>
                                <span class="text-red-500 font-bold italic">Minggu: Tutup (Waktunya Mancing)</span>
                            </p>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="flex gap-4 items-start group">
                        <div
                            class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center text-brand-gold text-base shadow-sm shrink-0 group-hover:bg-brand-gold group-hover:text-white transition-all duration-300">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-brand-navy uppercase tracking-widest mb-1">
                                WhatsApp Langsung</p>
                            <p class="text-brand-steel text-sm">0812-XXXX-XXXX</p>
                            
                        </div>
                    </div>

                </div>

                <!-- Google Maps -->
                <div class="w-full h-52 rounded-3xl overflow-hidden border-4 border-white shadow-md">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.864006509979!2d106.79051837499!3d-6.319760793665516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ef0000000001%3A0x0!2sJl.%20Pondok%20Labu%2C%20Depok%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1715000000000!5m2!1sid!2sid"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>

            <!-- ===== RIGHT: Form ===== -->
            <div class="lg:col-span-7" data-scroll="fade-right">
                <div
                    class="bg-white rounded-[2.5rem] p-7 sm:p-10 lg:p-12 border border-ui-border shadow-2xl shadow-brand-navy/5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Nama -->
                        <div class="sm:col-span-2">
                            <label
                                class="block text-[10px] font-black text-brand-navy uppercase tracking-widest mb-2">Nama
                                Lengkap</label>
                            <input type="text" placeholder="Masukkan nama..."
                                class="w-full px-5 py-3.5 bg-ui-main border-2 border-transparent rounded-2xl text-sm font-medium text-brand-navy placeholder:text-brand-steel/60 focus:border-brand-gold focus:outline-none transition-all duration-200">
                        </div>

                        <!-- Layanan -->
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-black text-brand-navy uppercase tracking-widest mb-2">
                                Jenis Layanan
                            </label>

                            <div class="relative">
                                <select id="service_type" name="service_type" required
                                    class="w-full px-5 py-3.5 bg-ui-main border-2 border-transparent rounded-2xl text-sm font-medium text-brand-navy appearance-none cursor-pointer focus:border-brand-gold focus:outline-none transition-all duration-200 pr-10">

                                    <option value="" disabled selected>Pilih Layanan</option>
                                    <option value="Full Restorasi">Full Restorasi</option>
                                    <option value="Repaint Body">Repaint Body</option>
                                    <option value="Bore Up / Mesin">Bore Up / Mesin</option>
                                    <option value="Service Rutin">Service Rutin</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>

                                <div
                                    class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-brand-steel">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>

                            <!-- Input khusus jika memilih Lainnya -->
                            <div id="other_service_wrapper" class="hidden mt-4">
                                <label for="other_service"
                                    class="block text-[10px] font-black text-brand-navy uppercase tracking-widest mb-2">
                                    Layanan yang Diinginkan
                                </label>

                                <input type="text" id="other_service" name="other_service"
                                    placeholder="Tuliskan jenis layanan yang Anda inginkan..." maxlength="255"
                                    class="w-full px-5 py-3.5 bg-ui-main border-2 border-transparent rounded-2xl text-sm font-medium text-brand-navy placeholder:text-gray-400 focus:border-brand-gold focus:outline-none transition-all duration-200">
                            </div>
                        </div>

                        <!-- Detail -->
                        <div class="sm:col-span-2">
                            <label
                                class="block text-[10px] font-black text-brand-navy uppercase tracking-widest mb-2">Detail
                                Rencana Motor Anda</label>
                            <textarea rows="6" placeholder="Ceritakan kondisi motor dan keinginan Anda..."
                                class="w-full px-5 py-3.5 bg-ui-main border-2 border-transparent rounded-2xl text-sm font-medium text-brand-navy placeholder:text-brand-steel/60 focus:border-brand-gold focus:outline-none transition-all duration-200 resize-none"></textarea>
                        </div>

                        <!-- Divider -->
                        <div class="sm:col-span-2 border-t border-ui-border"></div>

                        <!-- Submit -->
                        <div class="sm:col-span-2">
                            <button type="button" onclick="handleKonsultasiSubmit()"
                                class="w-full bg-brand-navy text-white font-black text-sm tracking-widest uppercase py-4 sm:py-5 rounded-2xl flex items-center justify-center gap-3 hover:bg-brand-gold transition-all duration-300 group shadow-xl shadow-brand-navy/20">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                                KIRIM VIA WHATSAPP
                                <i
                                    class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform duration-200"></i>
                            </button>
                            <p class="text-center text-[11px] text-brand-steel mt-3">
                                Pesan langsung diterima via
                                <span class="text-green-600 font-bold">WhatsApp</span>
                                — respon cepat!
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
