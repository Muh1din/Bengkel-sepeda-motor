<x-layouts.service-advisor title="Hasil Inspeksi">

    <div class="space-y-6">

        {{-- Inspection List --}}
        <div class="rounded-xl border border-ui-border bg-white shadow-sm">

            {{-- Header --}}
            <div class="border-b border-ui-border px-5 py-4">
                <div class="flex items-center justify-between gap-4">

                    <div class="flex items-center gap-2">
                        <i data-lucide="clipboard-check" class="h-5 w-5 text-brand-gold"></i>

                        <div>
                            <h2 class="text-base font-semibold text-ui-text">
                                Daftar Hasil Inspeksi
                            </h2>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Hasil inspeksi yang telah diselesaikan oleh mekanik.
                            </p>
                        </div>
                    </div>

                    <span class="rounded-full bg-brand-navy/10 px-3 py-1 text-xs font-semibold text-brand-navy">
                        {{ $inspections->count() }} Inspeksi
                    </span>

                </div>
            </div>


            {{-- Table --}}
            @if ($inspections->isNotEmpty())

                <div class="overflow-x-auto">
                    <table class="w-full text-left">

                        <thead class="border-b border-ui-border bg-ui-main">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Booking
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Pelanggan
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Kendaraan
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Mekanik
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Pekerjaan
                                </th>

                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Status
                                </th>

                                <th
                                    class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>


                        <tbody class="divide-y divide-ui-border">

                            @foreach ($inspections as $inspection)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Booking --}}
                                    <td class="px-5 py-4">
                                        <div>
                                            <p class="text-sm font-semibold text-ui-text">
                                                {{ $inspection->booking->booking_code }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($inspection->booking->booking_date)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </td>


                                    {{-- Customer --}}
                                    <td class="px-5 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-ui-text">
                                                {{ $inspection->booking->customer->name }}
                                            </p>

                                            @if ($inspection->booking->customer->phone)
                                                <p class="mt-0.5 text-xs text-gray-500">
                                                    {{ $inspection->booking->customer->phone }}
                                                </p>
                                            @endif
                                        </div>
                                    </td>


                                    {{-- Vehicle --}}
                                    <td class="px-5 py-4">
                                        <div>
                                            <p class="text-sm font-medium text-ui-text">
                                                {{ $inspection->booking->vehicle->plate_number }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-500">
                                                {{ $inspection->booking->vehicle->brand }}
                                                {{ $inspection->booking->vehicle->model }}
                                            </p>
                                        </div>
                                    </td>


                                    {{-- Mechanic --}}
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-medium text-ui-text">
                                            {{ $inspection->mechanic->name }}
                                        </p>
                                    </td>


                                    {{-- Recommended Works --}}
                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-brand-navy/10 px-2.5 py-1 text-xs font-semibold text-brand-navy">
                                            <i data-lucide="wrench" class="h-3.5 w-3.5"></i>

                                            {{ $inspection->recommendedWorks->count() }}
                                        </span>
                                    </td>


                                    {{-- Status --}}
                                    <td class="px-5 py-4">

                                        @if ($inspection->status === 'COMPLETED')
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                                Selesai
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-semibold text-yellow-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                                                Dalam Pemeriksaan
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="px-5 py-4 text-right">

                                        <a href="{{ route('service-advisor.inspections.show', ['inspection' => $inspection->id]) }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-brand-navy px-3 py-2 text-xs font-semibold text-white hover:opacity-90 transition">
                                            <i data-lucide="eye" class="h-4 w-4"></i>

                                            Lihat Hasil
                                        </a>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center px-5 py-14 text-center">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-ui-main">
                        <i data-lucide="clipboard-x" class="h-5 w-5 text-gray-400"></i>
                    </div>

                    <h3 class="mt-3 text-sm font-semibold text-ui-text">
                        Belum Ada Hasil Inspeksi
                    </h3>

                    <p class="mt-1 max-w-md text-sm text-gray-500">
                        Belum ada inspeksi kendaraan yang telah diselesaikan oleh mekanik.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-layouts.service-advisor>
