<x-layouts.customer title="Riwayat Service">
    <section id="service-history" class="content-section space-y-6">
        <div>
            <p class="text-xs text-brand-steel">Daftar seluruh servis kendaraan yang pernah dilakukan.</p>
        </div>

        <!-- SEARCH AND FILTER -->
        <div
            class="bg-ui-card border border-ui-border rounded-lg p-4 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="w-full sm:w-72">
                <input type="text" id="history-search" oninput="filterHistory()"
                    placeholder="Cari kendaraan / jenis servis..."
                    class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy" />
            </div>
            <div class="w-full sm:w-48">
                <select id="history-filter-status" onchange="filterHistory()"
                    class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                    <option value="ALL">Semua Status</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
        </div>

        <!-- HISTORY TABLE -->
        <div class="bg-ui-card border border-ui-border rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-ui-main border-b border-ui-border text-brand-steel uppercase text-xs">
                        <tr>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Kendaraan</th>
                            <th class="py-3 px-4">Jenis Servis</th>
                            <th class="py-3 px-4">Keluhan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody" class="divide-y divide-ui-border">
                        <!-- Populated via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-layouts.customer>
