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

            <form
                method="POST"
                action="{{ route('customer.bookings.store') }}"
                class="space-y-4"
            >
                @csrf

                {{-- Kendaraan --}}
                <div>
                    <label
                        class="block text-xs font-medium text-brand-navy mb-1"
                    >
                        Pilih Kendaraan
                    </label>

                    <select
                        id="booking-vehicle-select"
                        name="vehicle_id"
                        required
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"
                    >
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
                    <label
                        class="block text-xs font-medium text-brand-navy mb-1"
                    >
                        Jenis Servis
                    </label>

                    <select
                        id="booking-service-type"
                        name="service_type"
                        required
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"
                    >
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

                        <label
                            class="block text-xs font-medium text-brand-navy mb-1"
                        >
                            Tuliskan Jenis Servis Lainnya
                        </label>

                        <input
                            type="text"
                            id="other-service-input"
                            name="other_service"
                            placeholder="Contoh: Turun mesin, ganti rantai, dll."
                            class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"
                        >
                    </div>
                </div>

                {{-- Tanggal & Waktu --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label
                            class="block text-xs font-medium text-brand-navy mb-1"
                        >
                            Tanggal Servis
                        </label>

                        <input
                            type="date"
                            id="booking-date"
                            name="booking_date"
                            required
                            class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"
                        >
                    </div>

                    <div>
                        <label
                            for="booking-time"
                            class="block text-xs font-medium text-brand-navy mb-1.5"
                        >
                            Waktu Servis
                        </label>

                        <select
                            id="booking-time"
                            name="booking_time"
                            required
                            class="w-full border border-ui-border rounded-lg px-3 py-2.5 sm:py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand-navy/20 focus:border-brand-navy transition appearance-none pr-8"
                        >
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
                    <label
                        class="block text-xs font-medium text-brand-navy mb-1"
                    >
                        Keluhan Kendaraan
                    </label>

                    <textarea
                        id="booking-notes"
                        name="complaint"
                        rows="3"
                        placeholder="Deskripsikan keluhan atau permintaan tambahan..."
                        class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full py-2.5 bg-brand-navy text-white font-medium text-sm rounded-md hover:bg-opacity-90"
                >
                    Booking Servis
                </button>

            </form>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const serviceSelect =
                document.getElementById('booking-service-type');

            const otherContainer =
                document.getElementById('other-service-container');

            const otherInput =
                document.getElementById('other-service-input');

            serviceSelect.addEventListener('change', function () {

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
