<x-layouts.customer title="Booking Aktif">

    <div class="max-w-full space-y-6">
        <!-- Page Header -->
        <div class="pb-2">
            <h1 class="text-xl sm:text-2xl font-bold text-brand-navy tracking-tight">
                Status & Jadwal Servis
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-brand-steel">
                Pantau status pengerjaan dan jadwal servis kendaraan Anda yang sedang berjalan.
            </p>
        </div>
        @if (session('success'))
            <div id="success-alert"
                class="flex items-center gap-3 p-4 rounded-lg border border-green-200 bg-green-50 text-green-700 transition-opacity duration-500">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>

            <script>
                setTimeout(() => {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.style.opacity = '0'; // Efek fade out
                        setTimeout(() => alert.remove(), 500); // Hapus elemen dari DOM setelah animasi fade
                    }
                }, 4000); // 4000ms = 4 detik
            </script>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-lg border border-red-200 bg-red-50 text-red-700">
                <div class="flex items-center gap-3 mb-2"> <i data-lucide="circle-alert" class="w-5 h-5"></i>
                    <p class="text-sm font-semibold"> Booking gagal dibuat. </p>
                </div>
                <ul class="ml-8 list-disc text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Bookings List -->
        <div id="active-bookings-list" class="space-y-3">
            @forelse ($bookings as $booking)
                <div>
                    <!-- CARD BOOKING -->
                    <div
                        class="bg-ui-card border border-ui-border rounded-xl p-4 sm:p-5 shadow-xs hover:border-brand-steel/40 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        <!-- Left Section: Details -->
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-brand-steel tracking-wide">
                                    {{ $booking->booking_code }}
                                </span>

                                @php
                                    $statusClasses = match (strtolower($booking->status)) {
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'in_progress',
                                        'sedang diproses'
                                            => 'bg-purple-50 text-purple-700 border-purple-200',
                                        default => 'bg-ui-main text-brand-steel border-ui-border',
                                    };

                                    $statusLabels = match (strtolower($booking->status)) {
                                        'pending' => 'Menunggu Konfirmasi',
                                        'confirmed' => 'Terkonfirmasi',
                                        'in_progress', 'sedang diproses' => 'Sedang Diproses',
                                        default => ucfirst($booking->status),
                                    };
                                @endphp

                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium border {{ $statusClasses }}">
                                    {{ $statusLabels }}
                                </span>
                            </div>

                            <h3 class="text-base sm:text-lg font-bold text-brand-navy tracking-tight">
                                {{ $booking->vehicle->license_plate ?? 'B 1234 XYZ' }} —
                                {{ $booking->vehicle->brand ?? '' }} {{ $booking->vehicle->model ?? '' }}
                            </h3>

                            <p class="text-xs text-brand-steel flex items-center gap-1.5 flex-wrap">
                                <span>{{ $booking->service_type }}</span>
                                <span>•</span>
                                <span>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }} pukul
                                    {{ $booking->booking_time }}
                                </span>
                            </p>
                        </div>

                        <!-- Right Section: Action Button -->
                        <div class="shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-ui-border/60">
                            <button type="button"
                                onclick="document.getElementById('modal-{{ $booking->id }}').showModal()"
                                class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 border border-ui-border rounded-lg text-xs font-semibold text-brand-navy bg-ui-card hover:bg-ui-main hover:border-brand-steel/50 transition-all active:scale-[0.98] cursor-pointer">
                                Lihat Detail
                            </button>
                        </div>

                    </div>

                    @if ($booking->status === 'PENDING')
                        <dialog id="cancel-modal-{{ $booking->id }}"
                            class="m-auto p-0 rounded-xl bg-transparent backdrop:bg-brand-navy/40 backdrop:backdrop-blur-xs max-w-md w-full">
                            <div class="bg-ui-card border border-ui-border rounded-xl shadow-xl overflow-hidden">

                                <div class="p-4 sm:p-5 border-b border-ui-border/80">
                                    <span
                                        class="text-[11px] font-mono font-bold text-brand-steel tracking-wider uppercase block">
                                        Pembatalan Booking
                                    </span>

                                    <h3 class="text-base font-bold text-brand-navy mt-0.5">
                                        Batalkan {{ $booking->booking_code }}?
                                    </h3>
                                </div>

                                <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST">
                                    @csrf

                                    <div class="p-4 sm:p-5 space-y-4">

                                        <div>
                                            <label for="cancellation_reason_{{ $booking->id }}"
                                                class="text-xs font-semibold uppercase tracking-wider text-brand-steel block mb-1.5">
                                                Alasan Pembatalan
                                            </label>

                                            <textarea id="cancellation_reason_{{ $booking->id }}" name="cancellation_reason" rows="4" required
                                                maxlength="1000" placeholder="Masukkan alasan pembatalan..."
                                                class="w-full rounded-lg border border-ui-border bg-ui-card px-3 py-2 text-sm text-ui-text focus:border-brand-steel focus:ring-1 focus:ring-brand-steel outline-none resize-none"></textarea>

                                            @error('cancellation_reason')
                                                <p class="mt-1 text-xs text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="p-4 border-t border-ui-border/80 bg-ui-main/30 flex justify-end gap-2">

                                        <button type="button"
                                            onclick="document.getElementById('cancel-modal-{{ $booking->id }}').close()"
                                            class="px-4 py-2 border border-ui-border bg-ui-card hover:bg-ui-main rounded-lg text-xs font-semibold text-brand-navy transition-all cursor-pointer">
                                            Kembali
                                        </button>

                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition-all cursor-pointer">
                                            Ya, Batalkan
                                        </button>

                                    </div>
                                </form>

                            </div>
                        </dialog>
                    @endif

                    <!-- MODAL HTML DIALOG -->
                    <dialog id="modal-{{ $booking->id }}"
                        class="m-auto p-0 rounded-xl bg-transparent backdrop:bg-brand-navy/40 backdrop:backdrop-blur-xs max-w-lg w-full">
                        <div class="bg-ui-card border border-ui-border rounded-xl shadow-xl overflow-hidden space-y-0">

                            <!-- Modal Header -->
                            <div
                                class="flex items-center justify-between p-4 sm:p-5 border-b border-ui-border/80 bg-ui-main/40">
                                <div>
                                    <span
                                        class="text-[11px] font-mono font-bold text-brand-steel tracking-wider uppercase block">
                                        Detail Booking
                                    </span>
                                    <h3 class="text-base font-bold text-brand-navy mt-0.5">
                                        {{ $booking->booking_code }}
                                    </h3>
                                </div>

                                <!-- Close Button -->
                                <button type="button"
                                    onclick="document.getElementById('modal-{{ $booking->id }}').close()"
                                    class="p-1.5 rounded-lg text-brand-steel hover:text-brand-navy hover:bg-ui-border/50 transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-4 sm:p-5 space-y-4 max-h-[75vh] overflow-y-auto">

                                <!-- Status & Layanan -->
                                <div
                                    class="flex items-center justify-between p-3 bg-ui-main/60 rounded-lg border border-ui-border/60">
                                    <div>
                                        <span
                                            class="text-[11px] font-semibold text-brand-steel uppercase tracking-wider block">
                                            Jenis Servis
                                        </span>
                                        <span
                                            class="text-sm font-bold text-brand-navy">{{ $booking->service_type }}</span>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                                        {{ $statusLabels }}
                                    </span>
                                </div>

                                <!-- Detail Kendaraan -->
                                <div class="space-y-1.5">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                                        Informasi Kendaraan
                                    </span>
                                    <div class="p-3 border border-ui-border rounded-lg space-y-1">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-brand-steel">Plat Nomor:</span>
                                            <span
                                                class="font-mono font-bold text-brand-navy uppercase">{{ $booking->vehicle->license_plate ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-brand-steel">Merk / Model:</span>
                                            <span class="font-medium text-ui-text">{{ $booking->vehicle->brand ?? '' }}
                                                {{ $booking->vehicle->model ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-brand-steel">Tahun / Warna:</span>
                                            <span class="font-medium text-ui-text">{{ $booking->vehicle->year ?? '-' }}
                                                / {{ $booking->vehicle->color ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Jadwal -->
                                <div class="space-y-1.5">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                                        Jadwal Kedatangan
                                    </span>
                                    <div class="p-3 border border-ui-border rounded-lg space-y-1">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-brand-steel">Tanggal Servis:</span>
                                            <span
                                                class="font-medium text-ui-text">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-brand-steel">Jam Estimasi:</span>
                                            <span class="font-medium text-ui-text">{{ $booking->booking_time }}
                                                WIB</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan / Keluhan -->
                                @if (!empty($booking->notes))
                                    <div class="space-y-1.5">
                                        <span
                                            class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                                            Catatan / Keluhan
                                        </span>
                                        <p
                                            class="p-3 bg-ui-main/50 border border-ui-border rounded-lg text-xs text-ui-text leading-relaxed">
                                            {{ $booking->notes }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                            <!-- Modal Footer -->
                            <div class="p-4 border-t border-ui-border/80 bg-ui-main/30 flex justify-between">

                                @if ($booking->status === 'PENDING')
                                    <button type="button"
                                        onclick="document.getElementById('cancel-modal-{{ $booking->id }}').showModal()"
                                        class="px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 rounded-lg text-xs font-semibold text-red-700 transition-all cursor-pointer">
                                        Batalkan Booking
                                    </button>
                                @endif

                                <button type="button"
                                    onclick="document.getElementById('modal-{{ $booking->id }}').close()"
                                    class="px-4 py-2 border border-ui-border bg-ui-card hover:bg-ui-main rounded-lg text-xs font-semibold text-brand-navy transition-all cursor-pointer">
                                    Tutup
                                </button>

                            </div>
                        </div>
                    </dialog>

                </div>
            @empty
                <div class="bg-ui-card border border-ui-border rounded-xl p-8 text-center space-y-3">
                    <div
                        class="w-12 h-12 rounded-full bg-brand-navy/5 border border-brand-navy/10 text-brand-navy mx-auto flex items-center justify-center">
                        <i data-lucide="calendar-x" class="w-6 h-6 text-brand-steel"></i>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-brand-navy">Tidak Ada Booking Aktif</h4>
                        <p class="text-xs text-brand-steel mt-1 max-w-sm mx-auto">
                            Anda belum memiliki jadwal servis yang sedang berlangsung saat ini.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.customer>
