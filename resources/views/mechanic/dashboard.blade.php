<x-layouts.mechanic title="Dashboard">
    <section id="dashboard" class="page-section space-y-6">

        {{-- Welcome Greeting --}}
        <div
            class="bg-ui-card border border-ui-border rounded-lg p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">

            <div>

                <h3 class="text-2xl font-bold text-brand-navy">
                    Selamat datang, {{ auth()->user()->name }}
                </h3>

                <p class="text-sm text-gray-600 mt-1">
                    Siap melakukan servis kendaraan hari ini?
                    Cek daftar Work Order yang telah ditugaskan.
                </p>

            </div>

            <a href="#"
                class="bg-brand-navy text-white hover:bg-slate-800 px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                Lihat Semua Work Order
            </a>

        </div>


        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- WO Ditugaskan --}}
            <div class="bg-ui-card border border-ui-border rounded-lg p-4 shadow-sm flex items-center space-x-4">

                <div class="p-3 rounded-lg bg-blue-50 text-blue-600">

                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>

                    </svg>

                </div>

                <div>

                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                        WO Ditugaskan
                    </p>

                    <p class="text-2xl font-bold text-brand-navy">
                        {{ $assignedWorkOrders }}
                    </p>

                </div>

            </div>


            {{-- Menunggu Pemeriksaan --}}
            <div class="bg-ui-card border border-ui-border rounded-lg p-4 shadow-sm flex items-center space-x-4">

                <div class="p-3 rounded-lg bg-amber-50 text-amber-600">

                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>

                    </svg>

                </div>

                <div>

                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                        Menunggu Pemeriksaan
                    </p>

                    <p class="text-2xl font-bold text-brand-navy">
                        {{ $pendingInspections }}
                    </p>

                </div>

            </div>


            {{-- Sedang Dikerjakan --}}
            <div class="bg-ui-card border border-ui-border rounded-lg p-4 shadow-sm flex items-center space-x-4">

                <div class="p-3 rounded-lg bg-indigo-50 text-indigo-600">

                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>

                    </svg>

                </div>

                <div>

                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                        Sedang Dikerjakan
                    </p>

                    <p class="text-2xl font-bold text-brand-navy">
                        {{ $inProgressWorkOrders }}
                    </p>

                </div>

            </div>


            {{-- Selesai Hari Ini --}}
            <div class="bg-ui-card border border-ui-border rounded-lg p-4 shadow-sm flex items-center space-x-4">

                <div class="p-3 rounded-lg bg-emerald-50 text-emerald-600">

                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>

                    </svg>

                </div>

                <div>

                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                        Selesai Hari Ini
                    </p>

                    <p class="text-2xl font-bold text-brand-navy">
                        {{ $completedToday }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Section Work Order Terbaru --}}
        <div class="bg-ui-card border border-ui-border rounded-lg shadow-sm">

            <div class="p-4 border-b border-ui-border flex justify-between items-center">

                <h4 class="font-bold text-brand-navy">
                    Work Order Terbaru
                </h4>

                <a href="#" class="text-sm text-brand-gold hover:underline font-semibold">
                    Lihat Semua
                </a>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm text-ui-text">

                    <thead
                        class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-ui-border">

                        <tr>

                            <th class="px-4 py-3">
                                Nomor WO
                            </th>

                            <th class="px-4 py-3">
                                Kendaraan
                            </th>

                            <th class="px-4 py-3">
                                Keluhan
                            </th>

                            <th class="px-4 py-3">
                                Tanggal
                            </th>

                            <th class="px-4 py-3">
                                Status
                            </th>

                            <th class="px-4 py-3 text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-ui-border">

                        @forelse ($recentWorkOrders as $workOrder)
                            @php

                                $statusClasses = match ($workOrder->status) {
                                    'IN_PROGRESS' => 'bg-indigo-50 text-indigo-700 border-indigo-200',

                                    'COMPLETED' => 'bg-green-50 text-green-700 border-green-200',

                                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                                };

                                $statusLabels = match ($workOrder->status) {
                                    'IN_PROGRESS' => 'Sedang Dikerjakan',

                                    'COMPLETED' => 'Selesai',

                                    default => $workOrder->status,
                                };

                            @endphp


                            <tr class="hover:bg-gray-50/50">

                                {{-- Nomor WO --}}
                                <td class="px-4 py-3 font-medium text-brand-navy">

                                    {{ $workOrder->work_order_code }}

                                </td>


                                {{-- Kendaraan --}}
                                <td class="px-4 py-3">

                                    {{ $workOrder->booking->vehicle->license_plate }}

                                    -

                                    {{ $workOrder->booking->vehicle->brand }}

                                    {{ $workOrder->booking->vehicle->model }}

                                </td>


                                {{-- Keluhan --}}
                                <td class="px-4 py-3">

                                    {{ $workOrder->booking->complaint ?? '-' }}

                                </td>


                                {{-- Tanggal --}}
                                <td class="px-4 py-3">

                                    {{ $workOrder->received_at?->format('d M Y') }}

                                </td>


                                {{-- Status --}}
                                <td class="px-4 py-3">

                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">

                                        {{ $statusLabels }}

                                    </span>

                                </td>


                                {{-- Aksi --}}
                                <td class="px-4 py-3 text-right">

                                    <button type="button" data-work-order-detail
                                        data-work-order-code="{{ $workOrder->work_order_code }}"
                                        data-booking-code="{{ $workOrder->booking->booking_code }}"
                                        data-customer="{{ $workOrder->booking->customer->name }}"
                                        data-vehicle="{{ $workOrder->booking->vehicle->license_plate }} - {{ $workOrder->booking->vehicle->brand }} {{ $workOrder->booking->vehicle->model }}"
                                        data-service="{{ $workOrder->booking->service_type }}"
                                        data-complaint="{{ $workOrder->booking->complaint ?? '-' }}"
                                        data-status="{{ $workOrder->status }}"
                                        data-received-at="{{ $workOrder->received_at?->format('d M Y H:i') }}"
                                        class="text-xs font-semibold text-brand-navy hover:text-brand-gold">

                                        Detail

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="px-4 py-10 text-center text-brand-steel">

                                    Belum ada Work Order yang ditugaskan.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

    {{-- Work Order Detail Modal --}}
    <div id="work-order-detail-modal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-200">

        {{-- Modal Box --}}
        <div
            class="bg-white rounded-2xl shadow-2xl border border-ui-border w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col text-left">

            {{-- Header --}}
            <div class="px-6 py-5 bg-ui-main border-b border-ui-border">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <p class="text-[11px] font-semibold text-brand-steel uppercase tracking-widest mb-1">

                            Detail Work Order

                        </p>

                        <div class="flex items-center gap-3">

                            <h3 id="modal-work-order-code" class="text-lg font-bold text-brand-navy tracking-tight">
                            </h3>

                            <span id="modal-status"
                                class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold">
                            </span>

                        </div>

                    </div>


                    {{-- Close --}}
                    <button type="button" id="close-work-order-detail"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-brand-steel hover:text-brand-navy hover:bg-white transition-colors"
                        aria-label="Tutup">

                        <span class="text-xl leading-none">
                            &times;
                        </span>

                    </button>

                </div>

            </div>


            {{-- Body --}}
            <div class="px-6 py-5 overflow-y-auto">

                {{-- Vehicle Highlight --}}
                <div class="rounded-xl border border-ui-border bg-gray-50 p-4 mb-5">

                    <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-2">

                        Kendaraan

                    </p>

                    <p id="modal-vehicle" class="text-base font-bold text-brand-navy leading-relaxed">
                    </p>

                </div>


                {{-- Information Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- Booking --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-3">

                            Booking

                        </p>

                        <div>

                            <p class="text-xs text-brand-steel mb-1">

                                Kode Booking

                            </p>

                            <p id="modal-booking-code" class="text-sm font-semibold text-brand-navy">
                            </p>

                        </div>

                    </div>


                    {{-- Customer --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-3">

                            Pelanggan

                        </p>

                        <div>

                            <p class="text-xs text-brand-steel mb-1">

                                Nama Pelanggan

                            </p>

                            <p id="modal-customer" class="text-sm font-semibold text-brand-navy">
                            </p>

                        </div>

                    </div>


                    {{-- Service --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-3">

                            Servis

                        </p>

                        <div>

                            <p class="text-xs text-brand-steel mb-1">

                                Jenis Servis

                            </p>

                            <p id="modal-service" class="text-sm font-semibold text-brand-navy leading-relaxed">
                            </p>

                        </div>

                    </div>


                    {{-- Received At --}}
                    <div class="border border-ui-border rounded-xl p-5">

                        <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-3">

                            Penerimaan Kendaraan

                        </p>

                        <div>

                            <p class="text-xs text-brand-steel mb-1">

                                Kendaraan Diterima

                            </p>

                            <p id="modal-received-at" class="text-sm font-semibold text-brand-navy">
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Complaint --}}
                <div class="mt-4 border border-ui-border rounded-xl p-5">

                    <p class="text-[11px] font-bold text-brand-steel uppercase tracking-wider mb-3">

                        Catatan / Keluhan Customer

                    </p>

                    <div class="rounded-lg bg-gray-50 border border-gray-200 px-4 py-3">

                        <p id="modal-complaint" class="text-sm leading-relaxed text-gray-700">
                        </p>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-ui-border flex items-center justify-between">

                <p class="text-xs text-gray-500">

                    Pastikan informasi kendaraan sesuai sebelum melakukan pemeriksaan.

                </p>

                <button type="button" id="close-work-order-detail-footer"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-gray-100 transition-colors">

                    Tutup

                </button>

            </div>

        </div>

    </div>
</x-layouts.mechanic>
