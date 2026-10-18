<section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-brand-navy">
    <!-- Ornamen Background -->
    <div class="absolute top-0 right-0 w-1/2 h-full overflow-hidden opacity-20 pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-gold rounded-full blur-[150px]"></div>
    </div>

    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <!-- Logo Background dengan Animasi Zoom -->
        <div
            class="animate-logo absolute top-[45%] left-[110%] -translate-x-1/2 w-[120%] max-w-none pointer-events-none -z-10 select-none grayscale mix-blend-overlay
        lg:top-1/2 lg:left-[75%] lg:translate-x-0 lg:w-full lg:max-w-225">

            <img src="{{ asset('/logo/logo.jpeg') }}" alt="Nirwana Garage Logo" class="w-full h-auto object-contain">
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-center relative">

            <!-- Left Side: Content -->
            <div class="max-w-2xl">

                <!-- Title (Delay 2) -->
                <h1
                    class="animate-item delay-2 text-6xl md:text-8xl font-black leading-[0.9] text-white tracking-tighter uppercase">
                    LEBIH <br>
                    <span class="text-gradient-gold">DARI</span><br>
                    ESTETIKA.
                </h1>

                <!-- Paragraph (Delay 3) -->
                <p
                    class="animate-item delay-3 mt-8 text-brand-silver text-lg md:text-xl leading-relaxed max-w-lg border-l-2 border-brand-gold pl-6 backdrop-blur-[2px]">
                    Nirwana Garage menghadirkan perpaduan sempurna antara <span class="text-white font-bold">Restorasi
                        Presisi</span> dan <span class="text-white font-bold">Upgrade Performa</span>.
                </p>

                <!-- Buttons (Delay 4) -->
                <div class="animate-item delay-4 mt-10 flex flex-col sm:flex-row gap-5">
                    <a href="#kontak"
                        class="group relative px-10 py-5 bg-brand-gold text-white text-center font-black rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-[0_0_40px_rgba(197,142,75,0.4)]">
                        <span class="relative z-10">KONSULTASI</span>
                    </a>
                    <a href="#layanan"
                        class="px-10 py-5 border-2 border-brand-silver/20 text-white text-center font-bold rounded-2xl hover:bg-white/5 transition-all duration-300 backdrop-blur-sm">
                        LIHAT LAYANAN
                    </a>
                </div>

                {{-- Mobile: Booking Capacity Card --}}
                <div class="mt-8 lg:hidden">
                    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-md p-5 shadow-xl">

                        {{-- Status --}}
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-gold/20 text-xl">
                                ⚡
                            </div>

                            <div class="min-w-0">
                                @if ($remainingBookingSlots > 0)
                                    <div class="text-white font-bold text-sm">
                                        Slot Booking Terbuka
                                    </div>

                                    <p class="mt-1 text-xs text-brand-silver">
                                        Tersedia untuk booking baru
                                    </p>
                                @else
                                    <div class="text-white font-bold text-sm">
                                        Slot Booking Penuh
                                    </div>

                                    <p class="mt-1 text-xs text-brand-silver">
                                        Slot booking sedang penuh
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Booking Information --}}
                        <div class="mt-5 grid grid-cols-2 gap-3">

                            {{-- Remaining Slots --}}
                            <div class="rounded-xl border border-white/10 bg-black/10 p-3">
                                <p class="text-xs text-brand-silver">
                                    Sisa Slot
                                </p>

                                <p class="mt-2 text-2xl font-black text-brand-gold">
                                    {{ $remainingBookingSlots }}
                                    <span class="text-xs font-medium text-brand-silver">
                                        Slot
                                    </span>
                                </p>
                            </div>

                            {{-- Active Vehicle --}}
                            <div class="rounded-xl border border-white/10 bg-black/10 p-3">
                                <p class="text-xs text-brand-silver">
                                    Sedang Dikerjakan
                                </p>

                                <p class="mt-2 text-sm font-bold text-white leading-snug wrap-break-words">
                                    @if ($activeVehicle)
                                        {{ $activeVehicle->vehicle->brand ?? 'Kendaraan' }}
                                        {{ $activeVehicle->vehicle->model ?? '' }}
                                    @else
                                        Tidak ada kendaraan
                                    @endif
                                </p>
                            </div>

                        </div>

                        {{-- Booking Button --}}
                        @if (!request()->routeIs('booking.form.index'))
                            <button type="button" onclick="openBookingModal()" @disabled($remainingBookingSlots <= 0)
                                class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition-all
                {{ $remainingBookingSlots > 0
                    ? 'bg-brand-gold text-white hover:bg-brand-gold/90'
                    : 'cursor-not-allowed bg-white/10 text-brand-silver' }}">

                                @if ($remainingBookingSlots > 0)
                                    Booking Sekarang
                                    <span aria-hidden="true">→</span>
                                @else
                                    Slot Booking Penuh
                                @endif

                            </button>
                        @endif

                    </div>
                </div>
            </div>

            <!-- RIGHT: Floating Cards (hidden on small screens) -->
            <div class="hidden lg:grid grid-cols-2 gap-5 relative animate-item delay-5">
                <div class="glass-card float-card shine-effect p-7 rounded-3xl border border-white/10 mt-10 shadow-2xl">
                    <div class="text-brand-gold font-display text-3xl mb-1">ULTRA</div>
                    <div class="text-brand-silver text-[9px] uppercase tracking-[0.2em] font-bold">Deep Gloss
                        Restoration</div>
                    <div class="mt-4 w-8 h-0.5 bg-brand-gold/50"></div>
                    <p class="text-brand-silver/70 text-xs mt-3 leading-relaxed">Cat premium multi-layer dengan
                        sistem oven curing.</p>
                </div>
                <div class="float-card-2 shine-effect p-7 rounded-3xl border border-white/10 shadow-2xl">
                    <div class="text-brand-gold font-display text-3xl mb-1">PEAK</div>
                    <div class="text-brand-silver text-[9px] uppercase tracking-[0.2em] font-bold">Horsepower
                        Optimization</div>
                    <div class="mt-4 w-8 h-0.5 bg-brand-gold/50"></div>
                    <p class="text-brand-silver/70 text-xs mt-3 leading-relaxed">Bore up presisi untuk tenaga
                        maksimal nan durable.</p>
                </div>
                <div
                    class="col-span-2 shine-effect p-6 rounded-3xl border border-white/10 shadow-2xl flex items-center gap-5">
                    <div class="w-14 h-14 bg-brand-gold/20 rounded-2xl flex items-center justify-center text-2xl">⚡
                    </div>

                    <div>
                        @if ($remainingBookingSlots > 0)
                            <div class="text-white font-bold text-sm">
                                Slot Booking Terbuka
                            </div>
                        @else
                            <div class="text-white font-bold text-sm">
                                Slot Booking Penuh
                            </div>
                        @endif

                        <div class="flex items-center gap-4 mt-1">
                            <p class="text-brand-silver text-xs">
                                Sisa:
                                <span class="text-brand-gold font-bold">
                                    {{ $remainingBookingSlots }} Slot
                                </span>
                            </p>

                            <span class="text-white/20">|</span>

                            <p class="text-brand-silver text-xs">
                                Progress:
                                <span class="text-white font-medium">
                                    @if ($activeVehicle)
                                        {{ $activeVehicle->vehicle->brand ?? 'Kendaraan' }}
                                        {{ $activeVehicle->vehicle->model ?? '' }}
                                    @else
                                        Tidak ada kendaraan
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>

                    @if (!request()->routeIs('booking.form.index'))
                        <button type="button" onclick="openBookingModal()"
                            class="ml-auto px-4 py-2 bg-brand-gold/20 border border-brand-gold/40 text-brand-gold text-xs font-bold rounded-xl hover:bg-brand-gold hover:text-white transition-all whitespace-nowrap">
                            Booking Sekarang
                        </button>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>
