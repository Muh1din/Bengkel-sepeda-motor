<x-layouts.customer title="Riwayat Service">

    <section id="service-history" class="content-section space-y-6">

        <div>
            <p class="text-xs text-brand-steel">
                Daftar seluruh servis kendaraan yang telah selesai dilakukan.
            </p>
        </div>

        {{-- SEARCH --}}
        <div class="bg-ui-card border border-ui-border rounded-lg p-4 shadow-sm">

            <div class="w-full sm:w-72">

                <input type="text" id="history-search" oninput="filterHistory()"
                    placeholder="Cari kendaraan / jenis servis..."
                    class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy" />

            </div>

        </div>

        {{-- HISTORY TABLE --}}
        <div class="bg-ui-card border border-ui-border rounded-lg shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="bg-ui-main border-b border-ui-border text-brand-steel uppercase text-xs">

                        <tr>

                            <th class="py-3 px-4">
                                Tanggal
                            </th>

                            <th class="py-3 px-4">
                                Kendaraan
                            </th>

                            <th class="py-3 px-4">
                                Jenis Servis
                            </th>

                            <th class="py-3 px-4">
                                Keluhan
                            </th>

                            <th class="py-3 px-4">
                                Status
                            </th>

                            <th class="py-3 px-4">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="history-tbody" class="divide-y divide-ui-border">

                        @forelse ($serviceHistory as $booking)
                            <tr class="history-row hover:bg-ui-main transition-colors"
                                data-search="{{ strtolower($booking->vehicle->brand . ' ' . $booking->vehicle->model . ' ' . $booking->service_type) }}">

                                {{-- TANGGAL --}}
                                <td class="py-3 px-4 whitespace-nowrap">

                                    <p class="text-sm text-brand-navy">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    </p>

                                </td>

                                {{-- KENDARAAN --}}
                                <td class="py-3 px-4">

                                    <p class="text-sm font-medium text-brand-navy">
                                        {{ $booking->vehicle->brand }}
                                        {{ $booking->vehicle->model }}
                                    </p>

                                    <p class="text-xs text-brand-steel">
                                        {{ $booking->vehicle->license_plate }}
                                    </p>

                                </td>

                                {{-- JENIS SERVIS --}}
                                <td class="py-3 px-4">

                                    <p class="text-sm text-brand-navy">
                                        {{ $booking->service_type }}
                                    </p>

                                </td>

                                {{-- KELUHAN --}}
                                <td class="py-3 px-4 max-w-xs">

                                    <p class="text-sm text-brand-navy truncate">
                                        {{ $booking->complaint ?? '-' }}
                                    </p>

                                </td>

                                {{-- STATUS --}}
                                <td class="py-3 px-4">

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">

                                        Selesai

                                    </span>

                                </td>

                                {{-- AKSI --}}
                                <td class="py-3 px-4">

                                    <button type="button"
                                        class="text-xs font-medium text-brand-navy hover:text-brand-gold transition-colors">
                                        Detail
                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="py-10 px-4 text-center">

                                    <div
                                        class="w-12 h-12 mx-auto mb-4 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">

                                        <i data-lucide="history" class="w-6 h-6">
                                        </i>

                                    </div>

                                    <h2 class="text-base font-semibold text-brand-navy">

                                        Belum Ada Riwayat Servis

                                    </h2>

                                    <p class="text-xs text-brand-steel mt-2">

                                        Riwayat servis yang telah selesai akan muncul di sini.

                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

    <script>
        function filterHistory() {

            const searchInput =
                document.getElementById('history-search');

            const search =
                searchInput.value.toLowerCase().trim();

            const rows =
                document.querySelectorAll('.history-row');

            rows.forEach(row => {

                const data =
                    row.dataset.search;

                row.style.display =
                    data.includes(search) ? '' : 'none';

            });

        }
    </script>

</x-layouts.customer>
