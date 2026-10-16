<section id="tracking" class="py-16 sm:py-20 lg:py-24 bg-ui-main relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.025] pointer-events-none"
        style="background-image: radial-gradient(#1b263b 1px, transparent 1px); background-size: 28px 28px;">
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <div class="text-center mb-10 sm:mb-12" data-scroll="fade-up">
            <div class="text-brand-navy text-[11px] font-bold tracking-[0.3em] uppercase mb-3">
                Real-time Update
            </div>

            <h3 class="section-title font-display text-brand-navy uppercase">
                TRACKING
                <span class="text-gold-gradient italic">PROGRES.</span>
            </h3>

            <p class="mt-3 text-brand-steel text-sm">
                Pantau pengerjaan unit Anda tanpa harus bolak-balik ke workshop.
            </p>
        </div>

        <div data-scroll="zoom-in"
            class="bg-white rounded-4xl shadow-2xl shadow-brand-navy/5 border border-ui-border overflow-hidden">

            {{-- Search Form --}}
            <div class="p-6 sm:p-10 border-b border-ui-border bg-linear-to-r from-ui-main to-white">

                <form action="{{ route('guest.tracking.search') }}" method="POST" class="space-y-4">

                    @csrf

                    <label for="booking_code"
                        class="block text-brand-navy font-black text-[10px] uppercase tracking-widest">
                        Masukkan Kode Booking
                    </label>

                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                        <div class="relative grow">
                            <i
                                class="fa-solid fa-ticket absolute left-4 sm:left-5 top-1/2 -translate-y-1/2 text-brand-steel text-sm">
                            </i>

                            <input id="booking_code" name="booking_code" type="text"
                                value="{{ old('booking_code') }}" placeholder="Contoh: BK-123456" maxlength="9"
                                autocomplete="off" required
                                class="w-full pl-11 sm:pl-14 pr-4 py-4 sm:py-5 bg-white border-2 border-ui-border rounded-2xl focus:border-brand-gold focus:outline-none font-bold text-brand-navy uppercase transition-all text-sm @error('booking_code') border-red-500 @enderror">
                        </div>

                        <button id="tracking-submit" type="submit"
                            class="btn-primary bg-brand-navy text-white px-7 py-4 sm:py-5 rounded-2xl font-black hover:bg-brand-gold transition-all shadow-lg flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            CEK STATUS
                        </button>

                    </div>

                    @error('booking_code')
                        <p class="text-red-600 text-xs font-medium">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="text-brand-steel text-xs">
                        Gunakan kode booking yang diperoleh setelah melakukan pemesanan.
                    </p>

                </form>
            </div>

            {{-- Tracking Result --}}
            <div class="p-6 sm:p-10 bg-white">

                {{-- Loading State --}}
                <div id="tracking-loading" class="hidden py-12 text-center">

                    <div class="flex justify-center mb-4">
                        <i class="fa-solid fa-spinner animate-spin text-3xl text-brand-gold"></i>
                    </div>

                    <h4 class="text-brand-navy font-black text-sm uppercase">
                        Memeriksa Status Booking
                    </h4>

                    <p class="text-brand-steel text-xs mt-2">
                        Mohon tunggu, kami sedang mengambil data tracking Anda.
                    </p>

                </div>

                {{-- Tracking Result --}}
                <div id="tracking-result">

                    @if ($booking)

                        @php
                            $inspection = $booking->inspection;
                            $workOrder = $booking->workOrder;

                            $bookingStatus = strtoupper($booking->status ?? 'PENDING');
                            $inspectionStatus = strtoupper($inspection?->status ?? '');
                            $workOrderStatus = strtoupper($workOrder?->status ?? '');

                            $bookingDate = \Illuminate\Support\Carbon::parse($booking->booking_date);

                            $isBookingConfirmed = in_array($bookingStatus, ['CONFIRMED', 'IN_PROGRESS', 'COMPLETED']);

                            $isVehicleReceived =
                                $workOrder !== null || in_array($bookingStatus, ['IN_PROGRESS', 'COMPLETED']);

                            $isInspectionCompleted = $workOrder !== null || $inspectionStatus === 'COMPLETED';

                            $isWorkCompleted = $workOrderStatus === 'COMPLETED' || $bookingStatus === 'COMPLETED';

                            $isWorkInProgress = $workOrder !== null && !$isWorkCompleted;

                            $steps = [
                                [
                                    'label' => 'Booking',
                                    'done' => $isBookingConfirmed,
                                    'active' => $bookingStatus === 'PENDING',
                                    'icon' => 'fa-calendar-check',
                                    'description' => $bookingDate->format('d M Y'),
                                ],
                                [
                                    'label' => 'Kendaraan Diterima',
                                    'done' => $isVehicleReceived,
                                    'active' => $isBookingConfirmed && !$isVehicleReceived,
                                    'icon' => 'fa-motorcycle',
                                    'description' => $isVehicleReceived
                                        ? 'Kendaraan diterima'
                                        : ($isBookingConfirmed
                                            ? 'Menunggu kendaraan'
                                            : 'Menunggu konfirmasi'),
                                ],
                                [
                                    'label' => 'Pemeriksaan',
                                    'done' => $isInspectionCompleted,
                                    'active' => $inspection !== null && !$isInspectionCompleted,
                                    'icon' => 'fa-screwdriver-wrench',
                                    'description' => $isInspectionCompleted
                                        ? 'Pemeriksaan selesai'
                                        : ($inspection
                                            ? 'Sedang diperiksa'
                                            : 'Belum dimulai'),
                                ],
                                [
                                    'label' => 'Pengerjaan',
                                    'done' => $isWorkCompleted,
                                    'active' => $isWorkInProgress,
                                    'icon' => $isWorkCompleted ? 'fa-flag-checkered' : 'fa-gears',
                                    'description' => $isWorkCompleted
                                        ? 'Servis selesai'
                                        : ($isWorkInProgress
                                            ? 'Dalam pengerjaan'
                                            : 'Menunggu pengerjaan'),
                                ],
                            ];

                            $completedSteps = collect($steps)->filter(fn($step) => $step['done'])->count();

                            $progressPercent = ($completedSteps / count($steps)) * 100;

                            $statusLabels = [
                                'PENDING' => 'Menunggu Konfirmasi',
                                'CONFIRMED' => 'Booking Dikonfirmasi',
                                'IN_PROGRESS' => 'Servis Berlangsung',
                                'COMPLETED' => 'Servis Selesai',
                                'REJECTED' => 'Booking Ditolak',
                                'CANCELLED' => 'Booking Dibatalkan',
                            ];

                            $statusLabel = $statusLabels[$bookingStatus] ?? 'Status Tidak Diketahui';

                            $statusClasses = match ($bookingStatus) {
                                'COMPLETED' => 'bg-green-50 text-green-700 border-green-200',
                                'IN_PROGRESS', 'CONFIRMED' => 'bg-brand-gold/10 text-brand-gold border-brand-gold/20',
                                'REJECTED', 'CANCELLED' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-ui-main text-brand-steel border-ui-border',
                            };
                        @endphp

                        {{-- Vehicle Information --}}
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-8 sm:mb-10 gap-4">

                            <div>
                                <p class="text-brand-steel text-[10px] font-bold uppercase tracking-widest">
                                    Kendaraan
                                </p>

                                <h4 class="text-base sm:text-xl font-display font-black text-brand-navy uppercase mt-1">
                                    {{ $booking->vehicle?->brand ?? 'Kendaraan' }}
                                    {{ $booking->vehicle?->model ?? '' }}
                                </h4>

                                <p class="text-sm text-brand-steel mt-1">
                                    <i class="fa-solid fa-id-card mr-1"></i>
                                    {{ $booking->vehicle?->license_plate ?? '-' }}
                                </p>

                                <p class="text-xs text-brand-steel mt-2">
                                    Kode Booking:
                                    <span class="font-bold text-brand-navy">
                                        {{ $booking->booking_code }}
                                    </span>
                                </p>

                                @if ($workOrder)
                                    <p class="text-xs text-brand-steel mt-1">
                                        Kode Work Order:
                                        <span class="font-bold text-brand-navy">
                                            {{ $workOrder->work_order_code }}
                                        </span>
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-col items-start sm:items-end gap-2">

                                <span class="px-3 py-2 rounded-lg border font-bold text-xs {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>

                                <p class="text-xs text-brand-steel">
                                    Jadwal:
                                    {{ $bookingDate->translatedFormat('d M Y') }}
                                    {{ $booking->booking_time }}
                                </p>

                            </div>
                        </div>

                        {{-- Terminal Status Information --}}
                        @if (in_array($bookingStatus, ['REJECTED', 'CANCELLED']))
                            <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm">
                                <i class="fa-solid fa-circle-exclamation mr-2"></i>

                                @if ($bookingStatus === 'REJECTED')
                                    Booking ini ditolak oleh pihak bengkel.
                                @else
                                    Booking ini telah dibatalkan.
                                @endif
                            </div>
                        @endif

                        {{-- Progress Stepper --}}
                        <div class="relative">

                            <div class="absolute top-5 left-[12.5%] right-[12.5%] h-1 bg-ui-main hidden sm:block">
                                <div class="h-full bg-brand-gold transition-all duration-500"
                                    style="width: {{ $progressPercent }}%">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 sm:gap-4">

                                @foreach ($steps as $step)
                                    @php
                                        $isDone = $step['done'];
                                        $isActive = !$isDone && $step['active'];

                                        $circleClasses = $isDone
                                            ? 'bg-brand-gold text-white shadow-lg'
                                            : ($isActive
                                                ? 'bg-white border-4 border-brand-gold text-brand-gold shadow-lg animate-pulse'
                                                : 'bg-ui-main border-2 border-ui-border text-brand-silver');

                                        $titleClasses = $isDone
                                            ? 'text-brand-navy font-black'
                                            : ($isActive
                                                ? 'text-brand-gold font-black'
                                                : 'text-brand-silver font-bold');
                                    @endphp

                                    <div class="flex flex-col items-center text-center">

                                        <div
                                            class="relative w-10 h-10 rounded-full flex items-center justify-center z-10 mb-3 text-sm {{ $circleClasses }}">

                                            @if ($isDone)
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                <i class="fa-solid {{ $step['icon'] }} text-xs"></i>
                                            @endif

                                        </div>

                                        <h5 class="text-[10px] sm:text-xs uppercase {{ $titleClasses }}">
                                            {{ $step['label'] }}
                                        </h5>

                                        <p class="text-[10px] text-brand-steel mt-1">
                                            {{ $step['description'] }}
                                        </p>

                                    </div>
                                @endforeach

                            </div>
                        </div>

                        {{-- Service Information --}}
                        <div class="mt-8 pt-6 border-t border-ui-border">

                            <p class="text-brand-steel text-[10px] font-bold uppercase tracking-widest mb-2">
                                Jenis Layanan
                            </p>

                            <p class="text-sm font-bold text-brand-navy">
                                {{ $booking->service_type }}
                            </p>

                            @if ($booking->complaint)
                                <p class="text-brand-steel text-xs mt-4 mb-1">
                                    Keluhan pelanggan
                                </p>

                                <p class="text-sm text-ui-text">
                                    {{ $booking->complaint }}
                                </p>
                            @endif

                        </div>

                        {{-- Work Items --}}
                        @if ($workOrder && $workOrder->workItems->isNotEmpty())

                            <div class="mt-8 pt-6 border-t border-ui-border">

                                <h5 class="text-brand-navy font-black text-xs uppercase tracking-widest mb-4">
                                    Daftar Pekerjaan
                                </h5>

                                <div class="space-y-3">

                                    @foreach ($workOrder->workItems as $workItem)
                                        @php
                                            $itemStatus = strtoupper($workItem->status ?? 'PENDING');

                                            $itemStatusLabel = match ($itemStatus) {
                                                'COMPLETED' => 'Selesai',
                                                'IN_PROGRESS' => 'Sedang Dikerjakan',
                                                default => 'Menunggu',
                                            };

                                            $itemStatusClass = match ($itemStatus) {
                                                'COMPLETED' => 'text-green-700 bg-green-50',
                                                'IN_PROGRESS' => 'text-brand-gold bg-brand-gold/10',
                                                default => 'text-brand-steel bg-ui-main',
                                            };
                                        @endphp

                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 rounded-xl border border-ui-border">

                                            <span class="text-sm font-semibold text-brand-navy">
                                                {{ $workItem->name ?? ($workItem->description ?? 'Pekerjaan servis') }}
                                            </span>

                                            <span
                                                class="self-start sm:self-auto px-2 py-1 rounded-md text-[10px] font-bold {{ $itemStatusClass }}">
                                                {{ $itemStatusLabel }}
                                            </span>

                                        </div>
                                    @endforeach

                                </div>
                            </div>

                        @endif
                    @elseif ($trackingSearched)
                        {{-- Booking Not Found --}}
                        <div class="py-8 text-center">

                            <div
                                class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-magnifying-glass text-xl"></i>
                            </div>

                            <h4 class="text-brand-navy font-black text-sm uppercase">
                                Booking Tidak Ditemukan
                            </h4>

                            <p class="text-brand-steel text-xs mt-2">
                                Periksa kembali kode booking yang Anda masukkan.
                            </p>

                        </div>
                    @else
                        {{-- Initial State --}}
                        <div class="py-8 text-center">

                            <div
                                class="w-14 h-14 rounded-full bg-ui-main text-brand-steel flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-motorcycle text-xl"></i>
                            </div>

                            <h4 class="text-brand-navy font-black text-sm uppercase">
                                Pantau Progres Servis Anda
                            </h4>

                            <p class="text-brand-steel text-xs mt-2 max-w-sm mx-auto">
                                Masukkan kode booking untuk melihat status kendaraan dan perkembangan servis Anda.
                            </p>

                        </div>

                    @endif

                </div>
            </div>

            <p class="mt-6 text-center text-brand-steel text-xs" data-scroll="fade-up">
                Kendala tracking? Hubungi Admin di
                <a href="tel:08120000000" class="text-brand-navy font-bold hover:text-brand-gold transition-colors">
                    0812-XXXX-XXXX
                </a>
            </p>

        </div>
{{-- 
        @push('scripts')
            @vite('resources/js/tracking-section.js')
        @endpush --}}
</section>
