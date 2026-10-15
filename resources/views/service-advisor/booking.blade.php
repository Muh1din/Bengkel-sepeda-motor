<x-layouts.service-advisor title="Booking">

    <section id="sec-bookings" class="space-y-4">

        {{-- Filter --}}
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-ui-card p-4 rounded-lg border border-ui-border shadow-sm">
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto flex-1">

                <input type="text" id="booking-search" placeholder="Cari Kode, Nama, Plat..."
                    class="border border-ui-border rounded-md px-3 py-1.5 text-sm w-full sm:w-64 focus:outline-none focus:border-brand-navy">

                <select id="booking-filter-status"
                    class="border border-ui-border rounded-md px-3 py-1.5 text-sm bg-white focus:outline-none focus:border-brand-navy">
                    <option value="">Semua Status</option>
                    <option value="PENDING">Menunggu</option>
                    <option value="CONFIRMED">Dikonfirmasi</option>
                    <option value="IN_PROGRESS">Diproses</option>
                    <option value="COMPLETED">Selesai</option>
                    <option value="CANCELLED">Dibatalkan</option>
                </select>

                <input type="date" id="booking-filter-date"
                    class="border border-ui-border rounded-md px-3 py-1.5 text-sm bg-white focus:outline-none focus:border-brand-navy">

            </div>
        </div>


        {{-- Flash Message --}}
        @if (session('success'))
            <div id="flash-message"
                class="fixed top-5 right-5 z-50 flex items-center gap-3 rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-lg">
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
                class="fixed top-5 right-5 z-50 flex items-center gap-3 rounded-lg bg-red-600 px-4 py-3 text-sm font-medium text-white shadow-lg">
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


        {{-- Booking Table --}}
        <div class="bg-ui-card rounded-lg border border-ui-border shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead
                        class="bg-ui-main text-xs uppercase text-brand-steel font-semibold border-b border-ui-border">
                        <tr>
                            <th class="px-4 py-3">Kode Booking</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Kendaraan</th>
                            <th class="px-4 py-3">Jenis Servis</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>


                    <tbody id="booking-table-body" class="divide-y divide-ui-border">

                        @forelse ($bookings as $booking)

                            @php
                                $statusClasses = match (strtolower($booking->status)) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };

                                $statusLabels = match (strtolower($booking->status)) {
                                    'pending' => 'Menunggu Konfirmasi',
                                    'confirmed' => 'Dikonfirmasi',
                                    'in_progress' => 'Sedang Diproses',
                                    'completed' => 'Selesai',
                                    'rejected' => 'Ditolak',
                                    'cancelled' => 'Dibatalkan',
                                    default => ucfirst(strtolower($booking->status)),
                                };
                            @endphp


                            <tr data-booking-code="{{ $booking->booking_code }}"
                                data-customer-name="{{ $booking->customer->name }}"
                                data-license-plate="{{ $booking->vehicle->license_plate }}"
                                data-service-type="{{ $booking->service_type }}" data-status="{{ $booking->status }}"
                                data-booking-date="{{ $booking->booking_date }}">

                                {{-- Booking Code --}}
                                <td class="px-4 py-3 font-semibold text-brand-navy">
                                    {{ $booking->booking_code }}
                                </td>


                                {{-- Customer --}}
                                <td class="px-4 py-3">
                                    {{ $booking->customer->name }}
                                </td>


                                {{-- Vehicle --}}
                                <td class="px-4 py-3">
                                    {{ $booking->vehicle->license_plate }}
                                </td>


                                {{-- Service Type --}}
                                <td class="px-4 py-3">
                                    {{ $booking->service_type }}
                                </td>


                                {{-- Date --}}
                                <td class="px-4 py-3">
                                    {{ $booking->booking_date }}
                                </td>


                                {{-- Time --}}
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                                </td>


                                {{-- Status --}}
                                <td class="px-4 py-3">

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                        {{ $statusLabels }}
                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="px-4 py-3 text-right">

                                    {{-- PENDING --}}
                                    @if ($booking->status === 'PENDING')
                                        {{-- Detail --}}
                                        <a href="#"
                                            onclick="openBookingDetail({{ $booking->id }}); return false;"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                            Detail
                                        </a>


                                        {{-- Confirm --}}
                                        <form action="{{ route('service-advisor.bookings.confirm', $booking) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                                                Konfirmasi
                                            </button>
                                        </form>


                                        {{-- Reject --}}
                                        <button type="button" onclick="openRejectBooking({{ $booking->id }})"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                                            Tolak
                                        </button>


                                        {{-- CONFIRMED --}}
                                    @elseif ($booking->status === 'CONFIRMED')
                                        {{-- Detail --}}
                                        <a href="#"
                                            onclick="openBookingDetail({{ $booking->id }}); return false;"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                            Detail
                                        </a>


                                        {{-- Receive Vehicle --}}
                                        <button type="button" onclick="openReceiveVehicle({{ $booking->id }})"
                                            class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-brand-navy text-white hover:opacity-90">
                                            Terima Kendaraan
                                        </button>


                                        {{-- IN PROGRESS --}}
                                    @elseif ($booking->status === 'IN_PROGRESS')
                                        @php
                                            $workOrder = $booking->workOrder;
                                        @endphp


                                        @if ($workOrder)
                                            <a href="{{ route('service-advisor.work-orders.monitoring', ['workOrder' => $workOrder->id]) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                                Monitoring
                                            </a>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-400">
                                                WO Belum Tersedia
                                            </span>
                                        @endif


                                        {{-- COMPLETED --}}
                                    @elseif ($booking->status === 'COMPLETED')
                                        <a href="#"
                                            onclick="openBookingDetail({{ $booking->id }}); return false;"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                            Detail
                                        </a>


                                        {{-- REJECTED --}}
                                    @elseif ($booking->status === 'REJECTED')
                                        <a href="#"
                                            onclick="openBookingDetail({{ $booking->id }}); return false;"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                            Detail
                                        </a>


                                        {{-- CANCELLED --}}
                                    @elseif ($booking->status === 'CANCELLED')
                                        <a href="#"
                                            onclick="openBookingDetail({{ $booking->id }}); return false;"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                            Detail
                                        </a>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-brand-steel">
                                    Belum ada booking.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- RECEIVE VEHICLE MODAL --}}
    {{-- ========================================================= --}}

    <div id="mechanic-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">

                <h2 class="text-lg font-semibold text-gray-800">
                    Pilih Mekanik
                </h2>

                <button type="button" id="close-mechanic-modal" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>

            </div>


            {{-- Description --}}
            <p class="mb-4 text-sm text-gray-500">
                Pilih mekanik yang akan melakukan pemeriksaan kendaraan.
            </p>


            {{-- Form --}}
            <form id="mechanic-form" method="POST">

                @csrf
                @method('PATCH')


                <div class="space-y-3">

                    @forelse ($mechanics as $mechanic)
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg border p-3 hover:bg-gray-50">

                            <input type="radio" name="mechanic_id" value="{{ $mechanic->id }}" required>

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


                {{-- Footer --}}
                <div class="mt-6 flex justify-end gap-2">

                    <button type="button" id="cancel-mechanic-modal" class="rounded-lg border px-4 py-2 text-sm">
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


    {{-- ========================================================= --}}
    {{-- BOOKING DETAIL MODAL --}}
    {{-- ========================================================= --}}

    <div id="booking-detail-modal"
        class="hidden fixed inset-0 z-50 items-center w-screen h-screen justify-center bg-slate-900/50 backdrop-blur-sm px-4">

        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden border border-ui-border">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-ui-border">

                <div>

                    <h2 class="text-lg font-bold text-brand-navy">
                        Detail Booking
                    </h2>

                    <p class="text-sm text-brand-steel mt-1">
                        Informasi lengkap booking pelanggan
                    </p>

                </div>


                <button type="button" onclick="closeBookingDetail()"
                    class="flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors text-xl">
                    &times;
                </button>

            </div>


            {{-- Body --}}
            <div class="px-6 py-6">

                {{-- Booking Code & Status --}}
                <div class="grid grid-cols-2 gap-5 p-5 mb-7 rounded-xl bg-slate-50 border border-ui-border">

                    {{-- Booking Code --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                            Kode Booking
                        </p>

                        <p id="detail-booking-code" class="mt-1.5 text-xl font-bold tracking-wide text-brand-navy">
                        </p>

                    </div>


                    {{-- Status --}}
                    <div class="text-right">

                        <p class="text-xs font-medium uppercase mt-1 tracking-wider text-brand-steel">
                            Status
                        </p>

                        <span id="detail-status"
                            class="inline-flex items-center mt-1 py-1.5 rounded-full text-xs font-semibold">
                        </span>

                    </div>

                </div>


                {{-- Booking Information --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">

                    {{-- Customer --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                            Pelanggan
                        </p>

                        <p id="detail-customer" class="mt-1.5 text-sm font-semibold text-ui-text">
                        </p>

                    </div>


                    {{-- Vehicle --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                            Kendaraan
                        </p>

                        <p id="detail-vehicle" class="mt-1.5 text-sm font-semibold text-ui-text">
                        </p>

                    </div>


                    {{-- Service --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                            Jenis Servis
                        </p>

                        <p id="detail-service-type" class="mt-1.5 text-sm font-semibold text-ui-text">
                        </p>

                    </div>


                    {{-- Date & Time --}}
                    <div>

                        <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                            Tanggal & Waktu
                        </p>

                        <p id="detail-date-time" class="mt-1.5 text-sm font-semibold text-ui-text">
                        </p>

                    </div>

                </div>


                {{-- Complaint --}}
                <div class="mt-7 pt-6 border-t border-ui-border">

                    <div class="flex items-center justify-between mb-2">

                        <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                            Keluhan Pelanggan
                        </p>

                    </div>


                    <div class="rounded-xl bg-gray-50 border border-ui-border px-4 py-4">

                        <p id="detail-complaint" class="text-sm leading-relaxed text-ui-text">
                        </p>

                    </div>

                </div>


                {{-- Rejected Reason --}}
                <div id="rejection-reason-section" class="hidden mt-7 pt-6 border-t border-ui-border">

                    <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                        Alasan Penolakan
                    </p>


                    <div class="mt-2 rounded-xl bg-red-50 border border-red-100 px-4 py-4">

                        <p id="detail-rejection-reason" class="text-sm leading-relaxed text-red-700">
                        </p>

                    </div>

                </div>


                {{-- Cancellation Reason --}}
                <div id="cancellation-reason-section" class="hidden mt-7 pt-6 border-t border-ui-border">

                    <p class="text-xs font-medium uppercase tracking-wider text-brand-steel">
                        Alasan Pembatalan
                    </p>


                    <div class="mt-2 rounded-xl bg-amber-50 border border-amber-100 px-4 py-4">

                        <p id="detail-cancellation-reason" class="text-sm leading-relaxed text-amber-700">
                        </p>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t border-ui-border">

                <button type="button" onclick="closeBookingDetail()"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-brand-navy bg-white border border-ui-border rounded-lg hover:bg-gray-100 transition-colors">
                    Tutup
                </button>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REJECT BOOKING MODAL --}}
    {{-- ========================================================= --}}

    <div id="reject-booking-modal"
        class="hidden fixed inset-0 z-50 w-screen h-screen items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-ui-border">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-ui-border">

                <div>

                    <h2 class="text-lg font-bold text-brand-navy">
                        Tolak Booking
                    </h2>

                    <p class="text-sm text-brand-steel mt-1">
                        Berikan alasan penolakan booking
                    </p>

                </div>


                <button type="button" onclick="closeRejectBooking()"
                    class="flex items-center justify-center w-9 h-9 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors text-xl">
                    &times;
                </button>

            </div>


            {{-- Body --}}
            <div class="px-6 py-6">

                <form id="reject-booking-form" method="POST">

                    @csrf
                    @method('PATCH')


                    <label for="rejection_reason" class="block text-sm font-semibold text-ui-text mb-2">
                        Alasan Penolakan
                    </label>


                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                        placeholder="Contoh: Jadwal servis pada tanggal tersebut sudah penuh..."
                        class="w-full px-3 py-2.5 text-sm border border-ui-border rounded-lg focus:outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy resize-none"></textarea>


                    <p class="text-xs text-brand-steel mt-2">
                        Alasan ini akan dicatat sebagai alasan penolakan booking.
                    </p>


                    {{-- Footer --}}
                    <div class="flex justify-end gap-2 mt-6">

                        <button type="button" onclick="closeRejectBooking()"
                            class="px-4 py-2 text-sm font-semibold text-brand-navy bg-white border border-ui-border rounded-lg hover:bg-gray-100 transition-colors">
                            Batal
                        </button>


                        <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            Tolak Booking
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- Booking Data for JavaScript --}}
    <script>
        window.bookings = {{ Js::from($bookings) }};
    </script>

</x-layouts.service-advisor>
