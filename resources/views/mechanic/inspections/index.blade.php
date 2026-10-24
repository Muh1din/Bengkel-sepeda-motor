<x-layouts.mechanic title="Inspeksi Kendaraan">
    <section class="space-y-6">

        {{-- HEADER --}}
        <div>
            <p class="text-sm text-brand-steel">
                Lakukan pemeriksaan kendaraan berdasarkan booking yang telah diterima.
            </p>
        </div>

        {{-- INSPECTION LIST --}}
        <div class="bg-ui-card rounded-lg border border-ui-border shadow-sm overflow-hidden">

            <div class="p-4 border-b border-ui-border">
                <h3 class="font-bold text-brand-navy">
                    Kendaraan Menunggu Inspeksi
                </h3>
                <p class="mt-1 text-xs text-brand-steel">
                    Daftar kendaraan yang ditugaskan kepada Anda untuk dilakukan pemeriksaan.
                </p>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead
                        class="bg-ui-main text-xs uppercase text-brand-steel font-semibold border-b border-ui-border">
                        <tr>
                            <th class="px-4 py-3">Kode Booking</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Kendaraan</th>
                            <th class="px-4 py-3">Jenis Servis</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($inspections as $inspection)
                            <tr class="hover:bg-gray-50/50">

                                {{-- BOOKING CODE --}}
                                <td class="px-4 py-3 font-medium text-brand-navy">
                                    {{ $inspection->booking->booking_code }}
                                </td>

                                {{-- CUSTOMER --}}
                                <td class="px-4 py-3 text-ui-text">
                                    {{ $inspection->booking->customer->name }}
                                </td>

                                {{-- VEHICLE --}}
                                <td class="px-4 py-3 text-ui-text">
                                    {{ $inspection->booking->vehicle->brand }}
                                    {{ $inspection->booking->vehicle->model }}

                                    <span class="block text-xs text-brand-steel">
                                        {{ $inspection->booking->vehicle->license_plate }}
                                    </span>
                                </td>

                                {{-- SERVICE TYPE --}}
                                <td class="px-4 py-3 text-ui-text">
                                    {{ $inspection->booking->service_type }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3">

                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border bg-amber-50 text-amber-700 border-amber-200">
                                        Menunggu Inspeksi
                                    </span>

                                </td>

                                {{-- ACTION --}}
                                <td class="px-4 py-3 text-right">

                                    <a href="{{ route('mechanic.inspections.show', $inspection) }}"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-brand-navy text-white hover:opacity-90 transition">
                                        Mulai Inspeksi
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-brand-steel">
                                    Tidak ada kendaraan yang perlu diperiksa.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>
</x-layouts.mechanic>
