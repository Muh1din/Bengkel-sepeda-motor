<x-layouts.customer title="Dashboard">

    <section id="dashboard" class="max-w-full space-y-6">

        <!-- Welcome Section -->
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-brand-navy tracking-tight">
                Selamat datang, {{ $customer->name }}
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-brand-steel">
                Berikut adalah ikhtisar layanan servis kendaraan Anda hari ini.
            </p>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Total Vehicles -->
            <div class="bg-ui-card border border-ui-border p-5 rounded-xl shadow-xs space-y-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                    Total Kendaraan
                </span>
                <div class="text-3xl font-extrabold text-brand-navy">
                    {{ $totalVehicles }}
                </div>
            </div>

            <!-- Active Bookings -->
            <div class="bg-ui-card border border-ui-border p-5 rounded-xl shadow-xs space-y-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                    Booking Aktif
                </span>
                <div class="text-3xl font-extrabold text-brand-navy">
                    {{ $activeBookings }}
                </div>
            </div>

            <!-- In Progress -->
            <div class="bg-ui-card border border-ui-border p-5 rounded-xl shadow-xs space-y-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                    Servis Berjalan
                </span>
                <div class="text-3xl font-extrabold text-brand-navy">
                    {{ $runningServices }}
                </div>
            </div>

            <!-- Service History -->
            <div class="bg-ui-card border border-ui-border p-5 rounded-xl shadow-xs space-y-1">
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-steel block">
                    Riwayat Servis
                </span>
                <div class="text-3xl font-extrabold text-brand-steel">
                    {{ $serviceHistory }}
                </div>
            </div>

        </div>

        <!-- RECENT BOOKINGS -->
        <div class="bg-ui-card border border-ui-border rounded-xl shadow-xs p-5 sm:p-6 space-y-4">

            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-bold text-brand-navy tracking-tight">
                    Booking Terbaru
                </h3>

                <a href="{{ route('customer.bookingService') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-brand-navy text-white text-xs sm:text-sm font-semibold hover:bg-brand-gold transition-colors duration-200 shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i> Buat Booking Baru </a>
            </div>

            <!-- Bookings List -->
            <div class="space-y-3">
                @forelse ($latestBookings as $booking)
                    @php
                        $statusClasses = match (strtolower($booking->status)) {
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'in_progress', 'sedang diproses' => 'bg-purple-50 text-purple-700 border-purple-200',
                            default => 'bg-ui-main text-brand-steel border-ui-border',
                        };

                        $statusLabels = match (strtolower($booking->status)) {
                            'pending' => 'Menunggu Konfirmasi',
                            'confirmed' => 'Terkonfirmasi',
                            'in_progress', 'sedang diproses' => 'Sedang Diproses',
                            default => ucfirst($booking->status),
                        };
                    @endphp

                    <div
                        class="border border-ui-border bg-ui-main/30 rounded-lg p-4 transition-all hover:border-brand-steel/40 flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-brand-steel tracking-wide">
                                    {{ $booking->booking_code }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border {{ $statusClasses }}">
                                    {{ $statusLabels }}
                                </span>
                            </div>

                            <h4 class="text-sm font-bold text-brand-navy">
                                {{ $booking->vehicle->license_plate ?? 'B 1234 XYZ' }} —
                                {{ $booking->vehicle->brand ?? '' }} {{ $booking->vehicle->model ?? '' }}
                            </h4>

                            <p class="text-xs text-brand-steel flex items-center gap-1.5">
                                <span>{{ $booking->service_type }}</span>
                                <span>•</span>
                                <span>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d') }} pukul
                                    {{ $booking->booking_time }}
                                </span>
                            </p>
                        </div>

                    </div>
                @empty
                    <div
                        class="py-8 text-center text-xs sm:text-sm text-brand-steel bg-ui-main/40 border border-dashed border-ui-border rounded-lg">
                        Belum ada data booking terbaru.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- CURRENT SERVICE -->
        <div class="bg-ui-card border border-ui-border rounded-xl shadow-xs p-5 sm:p-6 space-y-4">

            <h3 class="text-base sm:text-lg font-bold text-brand-navy tracking-tight">
                Status Servis Saat Ini
            </h3>

            @if ($currentService)
                <div class="border border-ui-border rounded-lg p-4 space-y-3">

                    <div class="flex justify-between items-start gap-4">

                        <div>
                            <p class="text-xs text-brand-steel">
                                Booking
                            </p>

                            <p class="text-sm font-semibold text-brand-navy">
                                {{ $currentService->booking_code }}
                            </p>
                        </div>

                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                            Sedang Berjalan
                        </span>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <p class="text-xs text-brand-steel">
                                Kendaraan
                            </p>

                            <p class="text-sm font-medium text-brand-navy">
                                {{ $currentService->vehicle->license_plate }}
                            </p>

                            <p class="text-xs text-brand-steel">
                                {{ $currentService->vehicle->brand }}
                                {{ $currentService->vehicle->model }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-brand-steel">
                                Jenis Servis
                            </p>

                            <p class="text-sm font-medium text-brand-navy">
                                {{ $currentService->service_type }}
                            </p>
                        </div>

                    </div>

                    <div class="pt-3 border-t border-ui-border">

                        <p class="text-xs text-brand-steel">
                            Keluhan
                        </p>

                        <p class="text-sm text-brand-navy">
                            {{ $currentService->complaint ?: 'Tidak ada keluhan.' }}
                        </p>

                    </div>

                </div>
            @else
                <div
                    class="py-8 text-center text-xs sm:text-sm text-brand-steel bg-ui-main/40 border border-dashed border-ui-border rounded-lg">
                    Tidak ada servis yang sedang berjalan.
                </div>
            @endif

        </div>


    </section>

</x-layouts.customer>
