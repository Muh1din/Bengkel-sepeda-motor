<x-layouts.booking>

    @if (session('success'))
        <div id="booking-success-modal"
            class="booking-modal-overlay fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 px-4 backdrop-blur-sm"
            role="dialog" aria-modal="true" aria-labelledby="booking-success-title">
            <div class="booking-modal-content relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
                {{-- Gold Accent --}}
                <div class="h-1.5 w-full bg-[#c58e4b]"></div>

                <div class="px-6 py-8 text-center sm:px-8">

                    {{-- Success Icon --}}
                    <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-green-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    {{-- Success Message --}}
                    <h2 id="booking-success-title" class="text-2xl font-bold text-[#1b263b]">
                        Booking Berhasil!
                    </h2>

                    <p class="mt-3 text-sm leading-relaxed text-gray-500">
                        {{ session('success') }}
                        Simpan kode booking berikut untuk melacak status
                        pengerjaan kendaraan mu.
                    </p>

                    {{-- Booking Code --}}
                    <div class="mt-6 rounded-xl border border-dashed border-[#c58e4b]/60 bg-[#f8f9fa] p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
                            Kode Booking
                        </p>

                        <p id="booking-code"
                            class="mt-2 break-all text-2xl font-extrabold tracking-wider text-[#1b263b]">
                            {{ session('booking_code') }}
                        </p>

                        <button type="button" id="copy-booking-code"
                            class="mt-3 text-sm font-semibold text-[#a87335] transition hover:text-[#1b263b]">
                            Salin Kode Booking
                        </button>
                    </div>

                    {{-- Close Button --}}
                    <button type="button" id="close-booking-modal"
                        class="mt-6 w-full rounded-xl bg-[#1b263b] px-5 py-3 font-semibold text-white transition duration-200 hover:bg-[#293b59] focus:outline-none focus:ring-2 focus:ring-[#c58e4b] focus:ring-offset-2">
                        Mengerti
                    </button>

                    <p class="mt-4 text-xs text-gray-400">
                        Nirwana Garage · Premium Motorcycle Care
                    </p>
                </div>
            </div>
        </div>
    @endif
    <section id="booking" class="py-16 sm:py-20 lg:py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div @if (!session('success') && !$errors->any()) data-scroll="zoom-in" @endif
                class="bg-white rounded-[2.5rem] shadow-2xl shadow-brand-navy/10 overflow-hidden border border-ui-border">

                <div class="grid lg:grid-cols-2">

                    {{-- Left: Information --}}
                    <div class="p-8 sm:p-12 lg:p-16 bg-brand-navy text-white relative overflow-hidden">

                        <div
                            class="absolute top-0 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-brand-gold/5 rounded-full blur-[80px] pointer-events-none">
                        </div>

                        <div class="relative z-10">

                            <div class="text-brand-gold text-[11px] font-black tracking-[0.3em] uppercase mb-4">
                                Reservation
                            </div>

                            <h3 class="mega-title font-display uppercase leading-tight mb-8">
                                BOOKING<br>
                                <span class="text-gold-gradient italic">ANTRIAN.</span>
                            </h3>

                            <div class="space-y-5">

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-brand-gold shrink-0">
                                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                                    </div>

                                    <p class="font-bold text-sm text-brand-silver">
                                        Hemat waktu, tanpa antre lama di workshop.
                                    </p>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-brand-gold shrink-0">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                    </div>

                                    <p class="font-bold text-sm text-brand-silver">
                                        Prioritas pengerjaan untuk unit yang sudah booking.
                                    </p>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-brand-gold shrink-0">
                                        <i class="fa-solid fa-headset text-sm"></i>
                                    </div>

                                    <p class="font-bold text-sm text-brand-silver">
                                        Konsultasi spek & budget sebelum pengerjaan.
                                    </p>
                                </div>

                            </div>

                            <div class="mt-10 p-5 sm:p-6 rounded-2xl bg-white/5 border border-white/10">

                                <p class="text-[10px] font-bold uppercase tracking-widest text-brand-silver mb-3">
                                    Butuh Respon Cepat?
                                </p>

                                <a href="https://wa.me/62812xxxxxxxx"
                                    class="flex items-center gap-3 text-lg sm:text-xl font-black hover:text-brand-gold transition-colors">

                                    <i class="fa-brands fa-whatsapp text-2xl text-green-500"></i>

                                    0812-XXXX-XXXX
                                </a>

                            </div>

                        </div>
                    </div>

                    {{-- Right: Guest Booking Form --}}
                    <div class="p-8 sm:p-12 lg:p-16">

                        <form action="{{ route('booking.form.store') }}" method="POST" class="space-y-6">

                            @csrf

                            {{-- Booking Schedule --}}
                            <div>
                                <h4 class="text-sm font-black text-brand-navy uppercase tracking-wider mb-4">
                                    Jadwal Kedatangan
                                </h4>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                    {{-- Booking Date --}}
                                    <div>
                                        <label for="booking_date"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Tanggal Kedatangan
                                        </label>

                                        <input type="date" id="booking_date" name="booking_date"
                                            min="{{ now()->toDateString() }}" value="{{ old('booking_date') }}" required
                                            class="w-full px-4 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                        @error('booking_date')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Booking Time --}}
                                    <div>
                                        <label for="booking_time"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Waktu Kedatangan
                                        </label>

                                        <input type="time" id="booking_time" name="booking_time"
                                            value="{{ old('booking_time') }}" required
                                            class="w-full px-4 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                        @error('booking_time')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            {{-- Customer Information --}}
                            <div>
                                <h4 class="text-sm font-black text-brand-navy uppercase tracking-wider mb-4">
                                    Informasi Pelanggan
                                </h4>

                                <div class="space-y-4">

                                    {{-- Customer Name --}}
                                    <div>
                                        <label for="name"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Nama Lengkap
                                        </label>

                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Masukkan nama lengkap" maxlength="100" autocomplete="name"
                                            required
                                            class="w-full px-5 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                        @error('name')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Customer Phone --}}
                                    <div>
                                        <label for="phone"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Nomor WhatsApp
                                        </label>

                                        <input type="tel" id="phone" name="phone"
                                            value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                                            maxlength="20" autocomplete="tel" required
                                            class="w-full px-5 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                        @error('phone')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            {{-- Vehicle Information --}}
                            <div>
                                <h4 class="text-sm font-black text-brand-navy uppercase tracking-wider mb-4">
                                    Informasi Kendaraan
                                </h4>

                                <div class="space-y-4">

                                    {{-- License Plate --}}
                                    <div>
                                        <label for="license_plate"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Nomor Polisi
                                        </label>

                                        <input type="text" id="license_plate" name="license_plate"
                                            value="{{ old('license_plate') }}" placeholder="Contoh: B 1234 XYZ"
                                            maxlength="20" required
                                            class="w-full px-5 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm uppercase">

                                        @error('license_plate')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Brand and Model --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                        <div>
                                            <label for="brand"
                                                class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                                Merk Motor
                                            </label>

                                            <input type="text" id="brand" name="brand"
                                                value="{{ old('brand') }}" placeholder="Yamaha, Honda..."
                                                maxlength="100" required
                                                class="w-full px-4 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                            @error('brand')
                                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="model"
                                                class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                                Model Unit
                                            </label>

                                            <input type="text" id="model" name="model"
                                                value="{{ old('model') }}" placeholder="NMAX, ZX25R..."
                                                maxlength="100" required
                                                class="w-full px-4 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                            @error('model')
                                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                    {{-- Year and Color --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                        <div>
                                            <label for="year"
                                                class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                                Tahun Kendaraan
                                            </label>

                                            <input type="number" id="year" name="year"
                                                value="{{ old('year') }}" placeholder="Contoh: 2022"
                                                min="1901" max="{{ now()->year + 1 }}" required
                                                class="w-full px-4 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                            @error('year')
                                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="color"
                                                class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                                Warna Kendaraan
                                            </label>

                                            <input type="text" id="color" name="color"
                                                value="{{ old('color') }}" placeholder="Hitam, merah..."
                                                maxlength="50" required
                                                class="w-full px-4 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                            @error('color')
                                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                </div>
                            </div>

                            {{-- Service Information --}}
                            <div>
                                <h4 class="text-sm font-black text-brand-navy uppercase tracking-wider mb-4">
                                    Detail Pengerjaan
                                </h4>

                                <div class="space-y-4">

                                    {{-- Service Type --}}
                                    <div>
                                        <label for="service_type"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Rencana Pengerjaan
                                        </label>

                                        <select id="service_type" name="service_type" required
                                            class="w-full px-5 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm appearance-none cursor-pointer">

                                            <option value="" disabled
                                                {{ old('service_type') ? '' : 'selected' }}>
                                                Pilih layanan
                                            </option>

                                            <option value="Restorasi Full" @selected(old('service_type') === 'Restorasi Full')>
                                                Restorasi Full
                                            </option>

                                            <option value="Repaint" @selected(old('service_type') === 'Repaint')>
                                                Repaint
                                            </option>

                                            <option value="Bore Up Harian" @selected(old('service_type') === 'Bore Up Harian')>
                                                Bore Up Harian
                                            </option>

                                            <option value="Upgrade Kirian (CVT)" @selected(old('service_type') === 'Upgrade Kirian (CVT)')>
                                                Upgrade Kirian (CVT)
                                            </option>

                                            <option value="Service & Detailing" @selected(old('service_type') === 'Service & Detailing')>
                                                Service & Detailing
                                            </option>

                                            <option value="Lainnya" @selected(old('service_type') === 'Lainnya')>
                                                Lainnya
                                            </option>

                                        </select>

                                        {{-- Custom Service --}}
                                        <div id="custom_service_wrapper"
                                            class="mt-4 {{ old('service_type') === 'Lainnya' ? '' : 'hidden' }}">

                                            <label for="custom_service"
                                                class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                                Jelaskan Pengerjaan yang Diinginkan
                                            </label>

                                            <input type="text" id="custom_service" name="custom_service"
                                                value="{{ old('custom_service') }}" maxlength="100"
                                                placeholder="Contoh: Modifikasi lampu custom..."
                                                {{ old('service_type') === 'Lainnya' ? 'required' : '' }}
                                                class="w-full px-5 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm">

                                            @error('custom_service')
                                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                            @enderror

                                        </div>

                                        @error('service_type')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Complaint --}}
                                    <div>
                                        <label for="complaint"
                                            class="block text-[10px] font-black text-brand-navy uppercase tracking-[0.2em] mb-2">
                                            Catatan Tambahan (Opsional)
                                        </label>

                                        <textarea id="complaint" name="complaint" rows="3" maxlength="5000"
                                            placeholder="Contoh: Mau konsep warna bunglon..."
                                            class="w-full px-5 py-4 bg-ui-main border-2 border-transparent rounded-2xl focus:border-brand-gold focus:outline-none transition-all font-bold text-brand-navy text-sm resize-none">{{ old('complaint') }}</textarea>

                                        @error('complaint')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                class="btn-primary w-full bg-brand-gold text-white font-black py-4 sm:py-5 rounded-2xl shadow-xl shadow-brand-gold/30 hover:bg-brand-navy transition-all uppercase tracking-widest text-xs sm:text-sm">

                                <i class="fa-solid fa-calendar-check mr-2"></i>

                                AMANKAN SLOT ANTRIAN
                            </button>

                            <p class="text-center text-xs text-gray-500 leading-relaxed">
                                Pastikan informasi pelanggan dan kendaraan sudah benar
                                sebelum mengirim permohonan booking.
                            </p>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        @vite('resources/js/option-booking-form.js')
    @endpush
</x-layouts.booking>
