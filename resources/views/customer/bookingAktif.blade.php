<x-layouts.customer title="Booking Aktif">
    <!-- SECTION BOOKING AKTIF -->
    <section id="active-bookings" class="content-section space-y-6">
        <div>
            <p class="text-xs text-brand-steel">Daftar booking servis yang sedang berjalan atau menunggu jadwal.</p>
        </div>

        <div id="active-bookings-list" class="grid grid-cols-1 gap-4">

            <!-- Booking Item 1: Status Diproses -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3 pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs text-brand-steel">Kode Booking: <strong
                                class="text-brand-navy">#BK-20260901</strong></span>
                        <h4 class="font-bold text-gray-800 text-base">Honda Vario 160 ABS <span
                                class="text-xs font-normal text-gray-500">(B 4567 SBD)</span></h4>
                    </div>
                    <span
                        class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Sedang Dikerjakan
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs mb-4">
                    <div>
                        <p class="text-brand-steel">Jenis Servis</p>
                        <p class="font-semibold text-gray-700">Servis Berkala + Ganti Oli</p>
                    </div>
                    <div>
                        <p class="text-brand-steel">Jadwal</p>
                        <p class="font-semibold text-gray-700">18 Sep 2026 (09:30 WIB)</p>
                    </div>
                    <div>
                        <p class="text-brand-steel">Teknisi</p>
                        <p class="font-semibold text-gray-700">Mas Budi</p>
                    </div>
                    <div>
                        <p class="text-brand-steel">Perkiraan Selesai</p>
                        <p class="font-semibold text-gray-700">11:30 WIB</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg text-xs text-gray-600 mb-4">
                    <strong class="text-gray-700">Keluhan / Catatan:</strong> Suara CVT agak kasar saat akselerasi awal.
                </div>

                <div class="flex justify-end gap-2">
                    <button onclick="openDetailModal('BK-20260901')"
                        class="px-3 py-1.5 text-xs font-medium text-brand-navy border border-brand-navy rounded-lg hover:bg-brand-navy hover:text-white transition active:scale-95">
                        Lihat Detail
                    </button>
                </div>
            </div>

            <!-- Booking Item 2: Status Menunggu Konfirmasi -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3 pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs text-brand-steel">Kode Booking: <strong
                                class="text-brand-navy">#BK-20260902</strong></span>
                        <h4 class="font-bold text-gray-800 text-base">Yamaha NMAX 155 <span
                                class="text-xs font-normal text-gray-500">(B 1234 XYZ)</span></h4>
                    </div>
                    <span
                        class="px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Menunggu Jadwal
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs mb-4">
                    <div>
                        <p class="text-brand-steel">Jenis Servis</p>
                        <p class="font-semibold text-gray-700">Perbaikan Rem</p>
                    </div>
                    <div>
                        <p class="text-brand-steel">Jadwal</p>
                        <p class="font-semibold text-gray-700">20 Sep 2026 (14:00 WIB)</p>
                    </div>
                    <div>
                        <p class="text-brand-steel">Teknisi</p>
                        <p class="font-semibold text-gray-700">-</p>
                    </div>
                    <div>
                        <p class="text-brand-steel">Perkiraan Selesai</p>
                        <p class="font-semibold text-gray-700">15:30 WIB</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg text-xs text-gray-600 mb-4">
                    <strong class="text-gray-700">Keluhan / Catatan:</strong> Rem belakang kurang pakem & berdecit.
                </div>

                <div class="flex justify-end gap-2">
                    <button class="px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                        Batalkan Booking
                    </button>
                    <button onclick="openDetailModal('BK-20260902')"
                        class="px-3 py-1.5 text-xs font-medium text-brand-navy border border-brand-navy rounded-lg hover:bg-brand-navy hover:text-white transition active:scale-95">
                        Lihat Detail
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- MODAL POP-UP DETAIL BOOKING -->
    <div id="booking-detail-modal"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl relative transition-all transform">

            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <div>
                    <span class="text-xs font-semibold text-brand-steel uppercase tracking-wider">Rincian Booking</span>
                    <h4 id="modal-booking-id" class="text-lg font-bold text-brand-navy">#BK-20260901</h4>
                </div>
                <button onclick="closeDetailModal()"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="py-4 space-y-4 text-xs">
                <!-- Informasi Kendaraan -->
                <div class="bg-gray-50 p-3.5 rounded-xl space-y-2">
                    <p class="font-bold text-gray-700 text-sm">Informasi Kendaraan</p>
                    <div class="grid grid-cols-2 gap-2 text-gray-600">
                        <div><span class="text-brand-steel">Kendaraan:</span> <span id="modal-vehicle"
                                class="font-medium text-gray-800">-</span></div>
                        <div><span class="text-brand-steel">Plat Nomor:</span> <span id="modal-plate"
                                class="font-medium text-gray-800">-</span></div>
                    </div>
                </div>

                <!-- Detail Servis & Jadwal -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="border border-gray-100 p-3 rounded-xl">
                        <p class="text-brand-steel mb-1">Jenis Servis</p>
                        <p id="modal-service" class="font-semibold text-gray-800 text-sm">-</p>
                    </div>
                    <div class="border border-gray-100 p-3 rounded-xl">
                        <p class="text-brand-steel mb-1">Status saat ini</p>
                        <span id="modal-status"
                            class="inline-block px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                            -
                        </span>
                    </div>
                    <div class="border border-gray-100 p-3 rounded-xl">
                        <p class="text-brand-steel mb-1">Jadwal & Waktu</p>
                        <p id="modal-schedule" class="font-semibold text-gray-800">-</p>
                    </div>
                    <div class="border border-gray-100 p-3 rounded-xl">
                        <p class="text-brand-steel mb-1">Teknisi</p>
                        <p id="modal-technician" class="font-semibold text-gray-800">-</p>
                    </div>
                </div>

                <!-- Keluhan / Catatan -->
                <div class="border border-gray-100 p-3 rounded-xl">
                    <p class="text-brand-steel mb-1">Catatan Keluhan</p>
                    <p id="modal-notes" class="text-gray-700 italic">-</p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-3 border-t border-gray-100 flex justify-end">
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-brand-navy text-white text-xs font-medium rounded-lg hover:bg-opacity-90 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Mock Database Data untuk Modal
        const bookingDetails = {
            'BK-20260901': {
                id: '#BK-20260901',
                vehicle: 'Honda Vario 160 ABS',
                plate: 'B 4567 SBD',
                service: 'Servis Berkala + Ganti Oli',
                status: 'Sedang Dikerjakan',
                statusClass: 'bg-blue-100 text-blue-700',
                schedule: '18 Sep 2026 (09:30 WIB)',
                technician: 'Mas Budi',
                notes: 'Suara CVT agak kasar saat akselerasi awal.'
            },
            'BK-20260902': {
                id: '#BK-20260902',
                vehicle: 'Yamaha NMAX 155',
                plate: 'B 1234 XYZ',
                service: 'Perbaikan Rem',
                status: 'Menunggu Jadwal',
                statusClass: 'bg-amber-100 text-amber-700',
                schedule: '20 Sep 2026 (14:00 WIB)',
                technician: 'Belum Ditentukan',
                notes: 'Rem belakang kurang pakem & berdecit.'
            }
        };

        function openDetailModal(bookingId) {
            const data = bookingDetails[bookingId];
            if (!data) return;

            // Populate modal fields
            document.getElementById('modal-booking-id').innerText = data.id;
            document.getElementById('modal-vehicle').innerText = data.vehicle;
            document.getElementById('modal-plate').innerText = data.plate;
            document.getElementById('modal-service').innerText = data.service;

            const statusEl = document.getElementById('modal-status');
            statusEl.innerText = data.status;
            statusEl.className = `inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full ${data.statusClass}`;

            document.getElementById('modal-schedule').innerText = data.schedule;
            document.getElementById('modal-technician').innerText = data.technician;
            document.getElementById('modal-notes').innerText = `"${data.notes}"`;

            // Show Modal
            document.getElementById('booking-detail-modal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('booking-detail-modal').classList.add('hidden');
        }

        // Close modal on click outside content card
        document.getElementById('booking-detail-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>
</x-layouts.customer>
