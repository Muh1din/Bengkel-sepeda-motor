<x-layouts.customer title="Tracking Service">
   <section id="tracking" class="content-section px-4 sm:px-6 lg:px-8 py-4 space-y-6">
    <div class="max-w-4xl mx-auto bg-white border border-gray-100 rounded-2xl shadow-sm p-4 sm:p-6 space-y-6">
        
        <!-- HEADER STATUS -->
        <div class="border-b border-gray-100 pb-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Status Real-time</span>
                <h3 class="text-xl font-bold text-brand-navy mt-0.5">Servis Sedang Dikerjakan</h3>
                <p class="text-xs text-brand-steel mt-1">
                    Kendaraan: <strong class="text-gray-700">Honda Vario 160 (B 1234 XYZ)</strong> | Kode: <strong class="text-brand-navy">#BK-2026-001</strong>
                </p>
            </div>
            <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200/60 rounded-full text-xs font-semibold flex items-center gap-1.5 self-start sm:self-auto">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Proses Pengerjaan
            </span>
        </div>

        <!-- TIMELINE CONTAINER -->
        <div class="py-2">
            <h4 class="text-sm font-bold text-gray-800 mb-6">Progres Pengerjaan</h4>
            
            <div id="tracking-timeline" class="relative border-l-2 border-gray-200 ml-4 sm:ml-36 space-y-8">
                
                <!-- Step 1: Selesai -->
                <div class="relative pl-6 sm:pl-8">
                    <!-- Date badge (Visible on Desktop) -->
                    <div class="hidden sm:block absolute -left-36 top-0.5 w-28 text-right text-xs text-brand-steel">
                        <p class="font-semibold text-gray-700">18 Sep 2026</p>
                        <p class="text-[11px]">09:00 WIB</p>
                    </div>
                    <!-- Icon Indicator -->
                    <div class="absolute -left-2.25 top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white ring-4 ring-green-100 flex items-center justify-center"></div>
                    <!-- Content -->
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-1">
                        <div class="flex justify-between items-center">
                            <h5 class="font-bold text-xs sm:text-sm text-gray-800">Booking Dikonfirmasi</h5>
                            <span class="sm:hidden text-[10px] text-brand-steel">09:00 WIB</span>
                        </div>
                        <p class="text-xs text-gray-600">Jadwal servis telah dikonfirmasi oleh sistem. Motor telah didaftarkan di bengkel.</p>
                    </div>
                </div>

                <!-- Step 2: Selesai -->
                <div class="relative pl-6 sm:pl-8">
                    <div class="hidden sm:block absolute -left-36 top-0.5 w-28 text-right text-xs text-brand-steel">
                        <p class="font-semibold text-gray-700">18 Sep 2026</p>
                        <p class="text-[11px]">09:15 WIB</p>
                    </div>
                    <div class="absolute -left-2.25 top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white ring-4 ring-green-100"></div>
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-1">
                        <div class="flex justify-between items-center">
                            <h5 class="font-bold text-xs sm:text-sm text-gray-800">Inspeksi Awal Selesai</h5>
                            <span class="sm:hidden text-[10px] text-brand-steel">09:15 WIB</span>
                        </div>
                        <p class="text-xs text-gray-600">Teknisi (Mas Budi) mengecek kondisi oli, filter udara, dan komponen CVT.</p>
                    </div>
                </div>

                <!-- Step 3: Sedang Berjalan (Active Step) -->
                <div class="relative pl-6 sm:pl-8">
                    <div class="hidden sm:block absolute -left-36 top-0.5 w-28 text-right text-xs text-brand-steel">
                        <p class="font-semibold text-brand-navy">18 Sep 2026</p>
                        <p class="text-[11px] text-amber-600 font-medium">09:30 WIB (Sekarang)</p>
                    </div>
                    <div class="absolute -left-2.25 top-0.5 w-5 h-5 rounded-full bg-amber-500 border-2 border-white ring-4 ring-amber-100 flex items-center justify-center animate-bounce">
                        <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                    </div>
                    <div class="bg-amber-50/60 p-4 rounded-xl border border-amber-200/60 space-y-2">
                        <div class="flex justify-between items-center">
                            <h5 class="font-bold text-xs sm:text-sm text-amber-900">Perbaikan & Replacement</h5>
                            <span class="sm:hidden text-[10px] text-amber-700 font-medium">09:30 WIB</span>
                        </div>
                        <p class="text-xs text-amber-800">Sedang dilakukan pembersihan CVT, penggantian Oli MPX2, dan pengecekan ketebalan kampas rem.</p>
                        
                        <!-- Progress Bar mini -->
                        <div class="w-full bg-amber-200/60 h-1.5 rounded-full overflow-hidden mt-2">
                            <div class="bg-amber-500 h-full w-3/5 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Menunggu -->
                <div class="relative pl-6 sm:pl-8 opacity-60">
                    <div class="hidden sm:block absolute -left-36 top-0.5 w-28 text-right text-xs text-brand-steel">
                        <p class="font-medium text-gray-500">Estimasi</p>
                        <p class="text-[11px]">11:00 WIB</p>
                    </div>
                    <div class="absolute -left-2.25 top-1 w-4 h-4 rounded-full bg-gray-300 border-2 border-white"></div>
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-1">
                        <div class="flex justify-between items-center">
                            <h5 class="font-bold text-xs sm:text-sm text-gray-700">Final Quality Control</h5>
                            <span class="sm:hidden text-[10px] text-brand-steel">11:00 WIB</span>
                        </div>
                        <p class="text-xs text-gray-500">Uji jalan dan pemeriksaan akhir kelistrikan sebelum diserahkan ke pemilik.</p>
                    </div>
                </div>

                <!-- Step 5: Menunggu -->
                <div class="relative pl-6 sm:pl-8 opacity-60">
                    <div class="hidden sm:block absolute -left-36 top-0.5 w-28 text-right text-xs text-brand-steel">
                        <p class="font-medium text-gray-500">Estimasi</p>
                        <p class="text-[11px]">11:30 WIB</p>
                    </div>
                    <div class="absolute -left-2.25 top-1 w-4 h-4 rounded-full bg-gray-300 border-2 border-white"></div>
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-1">
                        <div class="flex justify-between items-center">
                            <h5 class="font-bold text-xs sm:text-sm text-gray-700">Siap Diambil & Pembayaran</h5>
                            <span class="sm:hidden text-[10px] text-brand-steel">11:30 WIB</span>
                        </div>
                        <p class="text-xs text-gray-500">Motor selesai dikerjakan dan siap diambil di kasir.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- ESTIMASI BIAYA & SPAREPART -->
        <div class="border-t border-gray-100 pt-5 space-y-3">
            <h4 class="text-xs font-bold text-brand-steel uppercase tracking-wider">Rincian Estimasi Biaya & Suku Cadang</h4>
            
            <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Jasa Servis Berkala</span>
                    <span class="font-medium text-gray-800">Rp 65.000</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Oli Mesin AHM MPX2 0.8L</span>
                    <span class="font-medium text-gray-800">Rp 54.000</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Pembersihan Paket CVT</span>
                    <span class="font-medium text-gray-800">Rp 35.000</span>
                </div>
                <div class="pt-2 border-t border-gray-200 flex justify-between text-sm font-bold text-brand-navy">
                    <span>Total Perkiraan</span>
                    <span>Rp 154.000</span>
                </div>
            </div>
        </div>

    </div>
</section>
</x-layouts.customer>
