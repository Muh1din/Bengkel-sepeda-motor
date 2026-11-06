<x-layouts.customer title="Booking Service">

    <section id="booking" class="content-section space-y-6">

        <div class="max-w-xl bg-ui-card border border-ui-border rounded-lg shadow-sm p-6 space-y-6">

            <div>
                <h3 class="text-lg font-bold text-brand-navy">
                    Formulir Booking Servis
                </h3>

                <p class="text-xs text-brand-steel">
                    Pilih kendaraan dan tentukan jadwal servis Anda.
                </p>
            </div>

            @if (session('profile_incomplete'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {

                        const modal = document.getElementById('profile-incomplete-modal');
                        const cancelButton = document.getElementById('profile-incomplete-cancel');
                        const confirmButton = document.getElementById('profile-incomplete-confirm');

                        if (!modal) return;

                        // Tampilkan modal
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');

                        // Tombol kembali
                        cancelButton.addEventListener('click', function() {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        });

                        // Tombol lengkapi profil
                        confirmButton.addEventListener('click', function() {
                            window.location.href = "{{ route('customer.profile.edit') }}";
                        });

                        // Render Lucide Icons jika tersedia
                        if (window.lucide) {
                            lucide.createIcons();
                        }
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('customer.bookings.store') }}" class="space-y-4">
                @csrf

                {{-- Kendaraan --}}
                <div>
                    <label class="block text-xs font-medium text-brand-navy mb-1">
                        Pilih Kendaraan
                    </label>

                    <select id="booking-vehicle-select" name="vehicle_id" required
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                        <option value="">-- Pilih Kendaraan --</option>

                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->license_plate }} -
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Servis --}}
                <div>
                    <label class="block text-xs font-medium text-brand-navy mb-1">
                        Jenis Servis
                    </label>

                    <select id="booking-service-type" name="service_type" required
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                        <option value="">-- Pilih Jenis Servis --</option>
                        <option value="Servis Berkala">Servis Berkala</option>
                        <option value="Ganti Oli">Ganti Oli</option>
                        <option value="Perbaikan Mesin">Perbaikan Mesin</option>
                        <option value="Perbaikan Rem">Perbaikan Rem</option>
                        <option value="Perbaikan Kelistrikan">
                            Perbaikan Kelistrikan
                        </option>
                        <option value="Lainnya">Lainnya</option>
                    </select>

                    {{-- Jenis Servis Lainnya --}}
                    <div id="other-service-container" class="mt-2 hidden">

                        <label class="block text-xs font-medium text-brand-navy mb-1">
                            Tuliskan Jenis Servis Lainnya
                        </label>

                        <input type="text" id="other-service-input" name="other_service"
                            placeholder="Contoh: Turun mesin, ganti rantai, dll."
                            class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                    </div>
                </div>

                {{-- Tanggal & Waktu --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-medium text-brand-navy mb-1">
                            Tanggal Servis
                        </label>

                        <input type="date" id="booking-date" name="booking_date" required
                            class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy">
                    </div>

                    <div>
                        <label for="booking-time" class="block text-xs font-medium text-brand-navy mb-1.5">
                            Waktu Servis
                        </label>

                        <select id="booking-time" name="booking_time" required
                            class="w-full border border-ui-border rounded-lg px-3 py-2.5 sm:py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-navy/20 focus:border-brand-navy transition appearance-none pr-8">
                            <option value="">-- Pilih Jam Servis --</option>
                            <option value="08:00">08:00 WIB</option>
                            <option value="09:00">09:00 WIB</option>
                            <option value="10:00">10:00 WIB</option>
                            <option value="11:00">11:00 WIB</option>
                            <option value="13:00">13:00 WIB</option>
                            <option value="14:00">14:00 WIB</option>
                            <option value="15:00">15:00 WIB</option>
                            <option value="16:00">16:00 WIB</option>
                        </select>
                    </div>

                </div>

                {{-- Keluhan --}}
                <div>
                    <label class="block text-xs font-medium text-brand-navy mb-1">
                        Keluhan Kendaraan
                    </label>

                    <textarea id="booking-notes" name="complaint" rows="3"
                        placeholder="Deskripsikan keluhan atau permintaan tambahan..."
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"></textarea>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-brand-navy text-white font-medium text-sm rounded-md hover:bg-opacity-90">
                    Booking Servis
                </button>

            </form>

            <div id="profile-incomplete-modal"
                class="fixed inset-0 z-[100] hidden items-center justify-center bg-brand-navy/50 backdrop-blur-sm px-4">

                <div class="w-full max-w-sm bg-ui-card rounded-lg shadow-xl border border-ui-border">

                    <div class="p-5">

                        {{-- Header --}}
                        <div class="flex items-start gap-3 mb-6">

                            {{-- Icon --}}
                            <div
                                class="w-11 h-11 rounded-full bg-ui-main text-brand-gold border border-ui-border flex items-center justify-center shrink-0">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-brand-navy">
                                    Profil Belum Lengkap
                                </h2>

                                <p class="text-sm text-brand-steel mt-1 leading-relaxed">
                                    Silakan lengkapi profil dan nomor telepon terlebih dahulu sebelum melakukan booking
                                    servis.
                                </p>
                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="flex justify-center gap-3">

                            <button type="button" id="profile-incomplete-cancel"
                                class="px-5 py-2 rounded-md text-sm font-medium text-brand-steel border border-ui-border hover:bg-ui-main transition-colors">
                                Kembali
                            </button>

                            <button type="button" id="profile-incomplete-confirm"
                                class="px-5 py-2 rounded-md text-sm font-medium text-white bg-brand-navy hover:bg-brand-gold transition-colors duration-200">
                                Lengkapi Profil
                            </button>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const serviceSelect =
                document.getElementById('booking-service-type');

            const otherContainer =
                document.getElementById('other-service-container');

            const otherInput =
                document.getElementById('other-service-input');

            serviceSelect.addEventListener('change', function() {

                if (this.value === 'Lainnya') {

                    otherContainer.classList.remove('hidden');

                    otherInput.setAttribute('required', 'required');

                    otherInput.focus();

                } else {

                    otherContainer.classList.add('hidden');

                    otherInput.removeAttribute('required');

                    otherInput.value = '';
                }
            });
        });
    </script>

</x-layouts.customer>
