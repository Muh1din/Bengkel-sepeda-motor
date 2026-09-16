<x-layouts.customer title="Dashboard">
    <section id="dashboard" class="content-section space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-brand-navy">Selamat datang, Budi</h2>
            <p class="text-sm text-brand-steel">Berikut adalah ikhtisar layanan servis kendaraan Anda hari ini.
            </p>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-ui-card border border-ui-border p-5 rounded-lg shadow-sm">
                <div class="text-sm font-medium text-brand-steel">Total Kendaraan</div>
                <div class="text-3xl font-bold text-brand-navy mt-2" id="stat-total-vehicles">2</div>
            </div>
            <div class="bg-ui-card border border-ui-border p-5 rounded-lg shadow-sm">
                <div class="text-sm font-medium text-brand-steel">Booking Aktif</div>
                <div class="text-3xl font-bold text-brand-gold mt-2" id="stat-active-bookings">1</div>
            </div>
            <div class="bg-ui-card border border-ui-border p-5 rounded-lg shadow-sm">
                <div class="text-sm font-medium text-brand-steel">Servis Berjalan</div>
                <div class="text-3xl font-bold text-brand-navy mt-2" id="stat-in-progress">1</div>
            </div>
            <div class="bg-ui-card border border-ui-border p-5 rounded-lg shadow-sm">
                <div class="text-sm font-medium text-brand-steel">Riwayat Servis</div>
                <div class="text-3xl font-bold text-brand-steel mt-2" id="stat-history-count">3</div>
            </div>
        </div>

        <!-- RECENT BOOKINGS -->
        <div class="bg-ui-card border border-ui-border rounded-lg shadow-sm p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-brand-navy">Booking Terbaru</h3>
                <button onclick="navigateTo('booking')" class="text-sm font-medium text-brand-gold hover:underline">Buat
                    Booking Baru</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-ui-main border-b border-ui-border text-brand-steel uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4">Kode Booking</th>
                            <th class="py-3 px-4">Kendaraan</th>
                            <th class="py-3 px-4">Jenis Servis</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dashboard-recent-bookings-tbody" class="divide-y divide-ui-border">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- STATUS SERVIS SAAT INI -->
        <div class="bg-ui-card border border-ui-border rounded-lg shadow-sm p-6 space-y-4">
            <h3 class="text-lg font-bold text-brand-navy">Status Servis Saat Ini</h3>
            <div class="border border-ui-border rounded-md p-4 bg-ui-main">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 gap-2">
                    <div>
                        <span class="text-xs font-semibold text-brand-gold uppercase tracking-wider">Sedang
                            Diproses</span>
                        <h4 class="text-base font-bold text-brand-navy">B 1234 XYZ - Honda Vario 160</h4>
                    </div>
                    <button onclick="navigateTo('tracking')"
                        class="px-3 py-1.5 bg-brand-navy text-white text-xs font-medium rounded-md hover:bg-opacity-90 self-start sm:self-auto">
                        Lihat Detail Tracking
                    </button>
                </div>

                <div class="w-full bg-ui-border rounded-full h-2.5 mb-2">
                    <div class="bg-brand-gold h-2.5 rounded-full" style="width: 71%"></div>
                </div>
                <div class="flex justify-between text-xs text-brand-steel">
                    <span>Servis Dikerjakan</span>
                    <span>70% Selesai</span>
                </div>
            </div>
        </div>
    </section>

</x-layouts.customer>
