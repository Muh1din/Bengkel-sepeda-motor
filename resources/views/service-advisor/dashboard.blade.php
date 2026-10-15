<x-layouts.service-advisor title="Dashboard">
    <section id="sec-dashboard" class="space-y-6" x-data="dashboardData()">
        <!-- HEADER -->
        <div>
            <h2 class="text-xl font-bold text-brand-navy">
                Selamat datang, {{ auth()->user()->name ?? 'Service Advisor' }}
            </h2>
            <p class="text-sm text-brand-steel">
                Berikut adalah ringkasan aktivitas operasional bengkel hari ini.
            </p>
        </div>
        @if (session('success'))
            <div id="flash-message"
                class="fixed top-5 right-5 z-100 flex items-center gap-3 rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-lg">
                <span>
                    {{ session('success') }}
                </span>

                <button type="button" onclick="document.getElementById('flash-message').remove()"
                    class="text-lg leading-none text-white/80 hover:text-white">
                    &times;
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="flash-message"
                class="fixed top-5 right-5 z-100 flex items-center gap-3 rounded-lg bg-red-600 px-4 py-3 text-sm font-medium text-white shadow-lg">
                <span>
                    {{ session('error') }}
                </span>

                <button type="button" onclick="document.getElementById('flash-message').remove()"
                    class="text-lg leading-none text-white/80 hover:text-white">
                    &times;
                </button>
            </div>
        @endif
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const flashMessage = document.getElementById("flash-message");

                if (flashMessage) {
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 3000);
                }
            });
        </script>


        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-ui-card p-4 rounded-lg border border-ui-border shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-brand-steel uppercase tracking-wider">Booking Hari Ini</p>
                    <p class="text-2xl font-bold text-brand-navy mt-1" x-text="stats.bookingToday">
                        {{ $bookingToday }} </p>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <div class="bg-ui-card p-4 rounded-lg border border-ui-border shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-brand-steel uppercase tracking-wider">Menunggu Konfirmasi</p>
                    <p class="text-2xl font-bold text-brand-navy mt-1" x-text="stats.pendingBooking">
                        {{ $pendingBooking }} </p>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-ui-card p-4 rounded-lg border border-ui-border shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-brand-steel uppercase tracking-wider">Servis Berjalan</p>
                    <p class="text-2xl font-bold text-brand-navy mt-1" x-text="stats.activeServices">
                        {{ $activeServicesList->count() }}</p>
                </div>
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <div class="bg-ui-card p-4 rounded-lg border border-ui-border shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-brand-steel uppercase tracking-wider">Work Order Aktif</p>
                    <p class="text-2xl font-bold text-brand-navy mt-1" x-text="stats.activeWo"> {{ $activeWorkOrders }}
                    </p>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- DASHBOARD SECTION: BOOKING HARI INI -->
        <div class="bg-ui-card rounded-lg border border-ui-border shadow-sm overflow-hidden">

            <div class="p-4 border-b border-ui-border flex items-center justify-between">
                <h3 class="font-bold text-brand-navy">Booking Hari Ini</h3>

                <a href="{{ route('service-advisor.bookings') }}"
                    class="text-xs text-brand-navy hover:underline font-semibold cursor-pointer">
                    Lihat Semua ->
                </a>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead
                        class="bg-ui-main text-xs uppercase text-brand-steel font-semibold border-b border-ui-border">
                        <tr>
                            <th class="px-4 py-3">Kode Booking</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Kendaraan</th>
                            <th class="px-4 py-3">Jenis Servis</th>
                            <th class="px-4 py-3">Jam</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($bookings as $booking)
                            @php
                                $statusClasses = match (strtolower($booking->status)) {
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'in_progress',
                                    'sedang diproses'
                                        => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'completed' => 'bg-green-50 text-green-700 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    'cancelled' => 'bg-slate-50 text-slate-600 border-slate-200',
                                    default => 'bg-ui-main text-brand-steel border-ui-border',
                                };
                                $statusLabels = match (strtolower($booking->status)) {
                                    'pending' => 'Menunggu Konfirmasi',
                                    'confirmed' => 'Terkonfirmasi',
                                    'in_progress', 'sedang diproses' => 'Sedang Diproses',
                                    'completed' => 'Selesai',
                                    'rejected' => 'Ditolak',
                                    'cancelled' => 'Dibatalkan',
                                    default => ucfirst($booking->status),
                                };
                            @endphp <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 font-medium text-brand-navy"> {{ $booking->booking_code }} </td>
                                <td class="px-4 py-3"> {{ $booking->customer->name }} </td>
                                <td class="px-4 py-3"> {{ $booking->vehicle->license_plate }} -
                                    {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} </td>
                                <td class="px-4 py-3"> {{ $booking->service_type }} </td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }} </td>
                                {{-- STATUS --}} <td class="px-4 py-3"> <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                        {{ $statusLabels }} </span> </td> {{-- ACTION --}} <td
                                    class="px-4 py-3 text-right"> <button type="button" data-booking-detail
                                        data-booking-code="{{ $booking->booking_code }}"
                                        data-status="{{ $booking->status }}"
                                        data-customer="{{ $booking->customer->name }}"
                                        data-phone="{{ $booking->customer->phone }}"
                                        data-vehicle="{{ $booking->vehicle->license_plate }} - {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}"
                                        data-service="{{ $booking->service_type }}"
                                        data-datetime="{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}"
                                        data-complaint="{{ $booking->complaint }}"
                                        data-rejection-reason="{{ $booking->rejection_reason }}"
                                        data-cancellation-reason="{{ $booking->cancellation_reason }}"
                                        class="text-xs font-semibold text-brand-navy hover:text-brand-gold"> Detail
                                    </button>
                                    @if ($booking->status === 'CONFIRMED')
                                        <button type="button" onclick="openReceiveVehicle( {{ $booking->id }} )"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-brand-navy text-white hover:opacity-90">
                                            Terima Kendaraan
                                        </button>
                                    @endif
                                </td>
                            </tr>


                            {{-- modal terima kendaraan --}}
                            <div id="mechanic-modal"
                                class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
                                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

                                    <div class="flex items-center justify-between mb-5">
                                        <h2 class="text-lg font-semibold text-gray-800">
                                            Pilih Mekanik
                                        </h2>

                                        <button type="button" id="close-mechanic-modal"
                                            class="text-gray-400 hover:text-gray-600">
                                            ✕
                                        </button>
                                    </div>

                                    <p class="mb-4 text-sm text-gray-500">
                                        Pilih mekanik yang akan melakukan pemeriksaan kendaraan.
                                    </p>

                                    <form
                                        action="{{ route('service-advisor.bookings.receive', ['booking' => $booking->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="space-y-3">
                                            @forelse ($mechanics as $mechanic)
                                                <label
                                                    class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 hover:bg-gray-50">

                                                    <input type="radio" name="mechanic_id"
                                                        value="{{ $mechanic->id }}" required>

                                                    <div>
                                                        <p class="font-medium text-gray-800">
                                                            {{ $mechanic->name }}
                                                        </p>

                                                        <p class="text-xs text-gray-500">
                                                            Mekanik
                                                        </p>
                                                    </div>

                                                </label>
                                            @empty
                                                <p class="text-sm text-gray-500">
                                                    Tidak ada mekanik.
                                                </p>
                                            @endforelse
                                        </div>

                                        <div class="mt-6 flex justify-end gap-2">
                                            <button type="button" id="cancel-mechanic-modal"
                                                class="rounded-lg border px-4 py-2 text-sm">
                                                Batal
                                            </button>

                                            <button type="submit"
                                                class="rounded-lg bg-brand-navy px-4 py-2 text-sm font-semibold text-white">
                                                Terima & Assign
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        @empty

                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-brand-steel">
                                    Tidak ada booking untuk hari ini.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <!-- DASHBOARD SECTION: SERVIS SEDANG BERJALAN -->
        <div class="bg-ui-card rounded-lg border border-ui-border shadow-sm p-4">

            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-brand-navy">
                    Servis Sedang Berjalan
                </h3>

                <button @click="navigateTo('service-monitoring')"
                    class="text-xs text-brand-navy hover:underline font-semibold">
                    Monitoring Detail ->
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                @forelse ($activeServicesList as $service)
                    @php
                        $workItems = $service->workOrder?->workItems ?? collect();

                        $totalItems = $workItems->count();

                        $completedItems = $workItems->where('status', 'COMPLETED')->count();

                        $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                    @endphp

                    <div
                        class="p-4 rounded-lg border border-ui-border bg-white flex flex-col justify-between space-y-3">

                        <div class="flex justify-between items-start">

                            <div>

                                <span class="text-xs font-semibold text-brand-steel">
                                    {{ $service->workOrder?->work_order_code ?? 'Belum ada WO' }}
                                </span>

                                <h4 class="font-bold text-brand-navy">
                                    {{ $service->vehicle->brand }}
                                    {{ $service->vehicle->model }}
                                </h4>

                                <p class="text-xs text-brand-steel">
                                    {{ $service->workOrder?->mechanic?->name ?? 'Belum ditugaskan' }}
                                </p>

                            </div>

                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700">
                                {{ $service->status }}
                            </span>

                        </div>

                        {{-- Progress --}}
                        @php
                            $workItems = $service->workOrder?->workItems ?? collect();

                            $totalItems = $workItems->count();

                            $completedItems = $workItems->where('status', 'COMPLETED')->count();

                            $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                        @endphp

                        <div>

                            <div class="flex justify-between text-xs font-medium text-brand-steel mb-1">

                                <span>Progres</span>

                                <span>
                                    {{ $progress }}%
                                </span>

                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2">

                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $progress }}%">
                                </div>

                            </div>

                        </div>

                    </div>


                @empty


                    <div
                        class="col-span-full py-6 text-center text-brand-steel border border-dashed border-ui-border rounded-lg">
                        Tidak ada servis yang sedang berjalan.
                    </div>
                @endforelse

            </div>

        </div>

    </section>

    <!-- MODAL DETAIL BOOKING (HIDDEN BY DEFAULT WITH 'hidden') -->
    <div id="bookingDetailModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-200">
        <div id="modalBox"
            class="bg-white rounded-2xl shadow-2xl border border-ui-border w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col text-left transform transition-all duration-200 scale-95 opacity-0">

            {{-- Header --}}
            <div class="px-6 py-5 bg-ui-main border-b border-ui-border">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-[11px] font-semibold text-brand-steel uppercase tracking-widest mb-1">
                            Detail Booking Servis
                        </p>

                        <h3 id="modalBookingCode" class="text-lg font-bold text-brand-navy tracking-tight"></h3>
                    </div>

                    <button type="button" id="closeModalBtn"
                        class="text-brand-steel hover:text-brand-navy p-2 rounded-lg hover:bg-white transition-colors"
                        aria-label="Tutup">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>

                </div>

            </div>


            {{-- Body --}}
            <div class="px-6 py-5 overflow-y-auto">

                {{-- Status --}}
                <div
                    class="flex items-center justify-between px-4 py-3 mb-5 rounded-xl border border-ui-border bg-gray-50">
                    <div>
                        <p class="text-xs font-semibold text-brand-steel uppercase tracking-wider">
                            Status Booking
                        </p>

                        <p class="text-xs text-gray-500 mt-0.5">
                            Status terkini booking
                        </p>
                    </div>

                    <span id="modalStatus" class="px-3 py-1 rounded-full text-xs font-semibold"></span>
                </div>


                {{-- Informasi Utama --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                    {{-- Pelanggan --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-4">
                            Pelanggan
                        </p>

                        <div class="space-y-4">

                            <div>
                                <p class="text-xs text-brand-steel mb-1">
                                    Nama Pelanggan
                                </p>

                                <p id="modalCustomer" class="text-sm font-semibold text-brand-navy"></p>
                            </div>

                            <div>
                                <p class="text-xs text-brand-steel mb-1">
                                    No. Telepon / WhatsApp
                                </p>

                                <p id="modalPhone" class="text-sm font-medium text-brand-navy"></p>
                            </div>

                        </div>

                    </div>


                    {{-- Kendaraan --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-4">
                            Kendaraan
                        </p>

                        <div>
                            <p class="text-xs text-brand-steel mb-1">
                                Kendaraan
                            </p>

                            <p id="modalVehicle" class="text-sm font-semibold text-brand-navy leading-relaxed"></p>
                        </div>

                    </div>


                    {{-- Servis --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-4">
                            Servis
                        </p>

                        <div>
                            <p class="text-xs text-brand-steel mb-1">
                                Jenis Servis
                            </p>

                            <p id="modalService" class="text-sm font-semibold text-brand-navy leading-relaxed"></p>
                        </div>

                    </div>


                    {{-- Jadwal --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-4">
                            Jadwal Servis
                        </p>

                        <div>
                            <p class="text-xs text-brand-steel mb-1">
                                Tanggal & Jam
                            </p>

                            <p id="modalDateTime" class="text-sm font-semibold text-brand-navy leading-relaxed"></p>
                        </div>

                    </div>

                </div>


                {{-- Keluhan --}}
                <div class="mt-4 border border-ui-border rounded-xl p-5">

                    <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-3">
                        Catatan / Keluhan
                    </p>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">

                        <p id="modalComplaint" class="text-sm leading-relaxed text-gray-700"></p>

                    </div>

                </div>

                {{-- Rejection Reason --}}
                <div id="rejection-reason-section" class="hidden mt-7 pt-6 border-t border-ui-border">

                    <p class="text-xs font-medium uppercase
                        tracking-wider text-brand-steel">
                        Alasan Penolakan
                    </p>

                    <div class="mt-2 rounded-xl bg-red-50
                        border border-red-100 px-4 py-4">

                        <p id="detail-rejection-reason" class="text-sm leading-relaxed text-red-700">
                        </p>

                    </div>
                </div>


                {{-- Cancellation Reason --}}
                <div id="cancellation-reason-section" class="hidden mt-7 pt-6 border-t border-ui-border">

                    <p
                        class="text-xs font-medium uppercase
                            tracking-wider text-brand-steel">
                        Alasan Pembatalan
                    </p>

                    <div
                        class="mt-2 rounded-xl bg-amber-50
                                border border-amber-100 px-4 py-4">

                        <p id="detail-cancellation-reason" class="text-sm leading-relaxed text-amber-700">
                        </p>

                    </div>
                </div>

            </div>


            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-ui-border flex items-center justify-end">

                <button type="button" id="closeModalFooterBtn"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-gray-100 transition-colors">
                    Tutup
                </button>

            </div>

        </div>
    </div>
</x-layouts.service-advisor>
