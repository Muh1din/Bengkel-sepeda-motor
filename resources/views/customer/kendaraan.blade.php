<x-layouts.customer title="Kendaraan Saya">
    <section id="vehicles" class="content-section space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-brand-navy">Daftar Kendaraan</h3>
                <p class="text-xs text-brand-steel">Kelola kendaraan sepeda motor Anda.</p>
            </div>
            <button onclick="openAddVehicleModal()"
                class="px-4 py-2 bg-brand-navy text-white font-medium text-sm rounded-md hover:bg-opacity-90 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kendaraan
            </button>
        </div>

        <div id="vehicles-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- Vehicle Card 1 -->
            <div
                class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span
                            class="inline-block px-2 py-1 text-xs font-semibold bg-blue-50 text-brand-navy rounded mb-1">Honda</span>
                        <h4 class="font-bold text-gray-800 text-base">Vario 160 ABS</h4>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Aktif</span>
                </div>

                <div class="space-y-1.5 text-xs text-gray-600 mb-4">
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Plat Nomor:</span>
                        <span class="font-semibold text-gray-700">B 4567 SBD</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Tahun:</span>
                        <span class="font-semibold text-gray-700">2023</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Warna:</span>
                        <span class="font-semibold text-gray-700">Hitam Doff</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-brand-navy hover:bg-gray-50 rounded transition">Edit</button>
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded transition">Hapus</button>
                </div>
            </div>

            <!-- Vehicle Card 2 -->
            <div
                class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span
                            class="inline-block px-2 py-1 text-xs font-semibold bg-blue-50 text-brand-navy rounded mb-1">Yamaha</span>
                        <h4 class="font-bold text-gray-800 text-base">NMAX 155 Connected</h4>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Aktif</span>
                </div>

                <div class="space-y-1.5 text-xs text-gray-600 mb-4">
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Plat Nomor:</span>
                        <span class="font-semibold text-gray-700">B 1234 XYZ</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Tahun:</span>
                        <span class="font-semibold text-gray-700">2022</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Warna:</span>
                        <span class="font-semibold text-gray-700">Biru Doff</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-brand-navy hover:bg-gray-50 rounded transition">Edit</button>
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded transition">Hapus</button>
                </div>
            </div>

            <!-- Vehicle Card 3 (Servis / Non-Aktif State Example) -->
            <div
                class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span
                            class="inline-block px-2 py-1 text-xs font-semibold bg-blue-50 text-brand-navy rounded mb-1">Vespa</span>
                        <h4 class="font-bold text-gray-800 text-base">Sprint S 150</h4>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">Servis</span>
                </div>

                <div class="space-y-1.5 text-xs text-gray-600 mb-4">
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Plat Nomor:</span>
                        <span class="font-semibold text-gray-700">B 8888 VSP</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Tahun:</span>
                        <span class="font-semibold text-gray-700">2024</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-brand-steel">Warna:</span>
                        <span class="font-semibold text-gray-700">Kuning White</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-brand-navy hover:bg-gray-50 rounded transition">Edit</button>
                    <button
                        class="px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded transition">Hapus</button>
                </div>
            </div>

        </div>
    </section>
</x-layouts.customer>
