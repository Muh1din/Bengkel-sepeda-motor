<x-layouts.customer title="Booking Service">
    <section id="booking" class="content-section space-y-6">
        <div class="max-w-xl bg-ui-card border border-ui-border rounded-lg shadow-sm p-6 space-y-6">
            <div>
                <h3 class="text-lg font-bold text-brand-navy">Formulir Booking Servis</h3>
                <p class="text-xs text-brand-steel">Pilih kendaraan dan tentukan jadwal servis Anda.</p>
            </div>

            <form id="booking-form" onsubmit="handleBookingSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-brand-navy mb-1">Pilih Kendaraan</label>
                    <select id="booking-vehicle-select" required
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                        <!-- Populated via JS -->
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-brand-navy mb-1">Jenis Servis</label>
                    <select id="booking-service-type" required
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                        <option value="">-- Pilih Jenis Servis --</option>
                        <option value="Servis Berkala">Servis Berkala</option>
                        <option value="Ganti Oli">Ganti Oli</option>
                        <option value="Perbaikan Mesin">Perbaikan Mesin</option>
                        <option value="Perbaikan Rem">Perbaikan Rem</option>
                        <option value="Perbaikan Kelistrikan">Perbaikan Kelistrikan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-brand-navy mb-1">Tanggal Servis</label>
                        <input type="date" id="booking-date" required
                            class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                    </div>
                    <div>
                        <label for="booking-time" class="block text-xs font-medium text-brand-navy mb-1.5">Waktu
                            Servis</label>
                        <div class="relative">
                            <select id="booking-time" required
                                class="w-full border border-ui-border rounded-lg px-3 py-2.5 sm:py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-navy/20 focus:border-brand-navy transition appearance-none pr-8">
                                <option value="">-- Pilih Jam Servis --</option>
                                <option value="08:00">08:00 WIB</option>
                                <option value="09:00">09:00 WIB</option>
                                <option value="10:00">10:00 WIB</option>
                                <option value="11:00">11:00 WIB</option>
                                <option value="13:00">13:00 WIB (Sesi Siang)</option>
                                <option value="14:00">14:00 WIB</option>
                                <option value="15:00">15:00 WIB</option>
                                <option value="16:00">16:00 WIB</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-brand-navy mb-1">Keluhan Kendaraan</label>
                    <textarea id="booking-notes" rows="3" placeholder="Deskripsikan keluhan atau permintaan tambahan..."
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"></textarea>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-brand-navy text-white font-medium text-sm rounded-md hover:bg-opacity-90">
                    Booking Servis
                </button>
            </form>
        </div>
    </section>
</x-layouts.customer>
