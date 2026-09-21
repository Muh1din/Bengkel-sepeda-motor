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

                                    <div class="flex flex-col items-start gap-2">

                                        {{-- DETAIL --}}
                                        <button type="button" onclick="openDetailModal(@js([
    'bookingCode' => $booking->booking_code,
    'date' => \Carbon\Carbon::parse($booking->booking_date)->format('d M Y'),
    'time' => \Carbon\Carbon::parse($booking->booking_time)->format('H:i'),
    'vehicle' => $booking->vehicle->brand . ' ' . $booking->vehicle->model,
    'licensePlate' => $booking->vehicle->license_plate,
    'serviceType' => $booking->service_type,
    'complaint' => $booking->complaint ?? '-',
]))"
                                            class="text-xs font-medium text-brand-navy hover:text-brand-gold transition-colors">
                                            Detail
                                        </button>

                                        {{-- REVIEW --}}
                                        @if ($booking->review)
                                            <span class="text-xs text-green-600 font-medium">
                                                Sudah Direview
                                            </span>
                                        @else
                                            <button type="button" onclick="openReviewModal({{ $booking->id }})"
                                                class="text-xs font-medium text-brand-gold hover:text-brand-navy transition-colors">
                                                Beri Review
                                            </button>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="py-10 px-4 text-center">

                                    <div
                                        class="w-12 h-12 mx-auto mb-4 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">

                                        <i data-lucide="history" class="w-6 h-6"></i>

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

        {{-- DETAIL MODAL --}}
        <div id="detail-modal" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center px-4">

            <div class="w-full max-w-lg bg-ui-card rounded-lg shadow-lg">

                <div class="flex items-center justify-between px-5 py-4 border-b border-ui-border">

                    <h2 class="text-base font-semibold text-brand-navy">
                        Detail Service
                    </h2>

                    <button type="button" onclick="closeDetailModal()" class="text-brand-steel hover:text-brand-navy">
                        ✕
                    </button>

                </div>

                <div id="detail-content" class="p-5 space-y-4">

                    {{-- BOOKING CODE --}}
                    <div>

                        <p class="text-xs text-brand-steel">
                            Kode Booking
                        </p>

                        <p id="detail-booking-code" class="text-sm font-medium text-brand-navy"></p>

                    </div>

                    {{-- DATE & TIME --}}
                    <div class="grid grid-cols-2 gap-4">

                        <div>

                            <p class="text-xs text-brand-steel">
                                Tanggal
                            </p>

                            <p id="detail-date" class="text-sm text-brand-navy"></p>

                        </div>

                        <div>

                            <p class="text-xs text-brand-steel">
                                Jam
                            </p>

                            <p id="detail-time" class="text-sm text-brand-navy"></p>

                        </div>

                    </div>

                    {{-- VEHICLE --}}
                    <div>

                        <p class="text-xs text-brand-steel">
                            Kendaraan
                        </p>

                        <p id="detail-vehicle" class="text-sm text-brand-navy"></p>

                    </div>

                    {{-- LICENSE PLATE --}}
                    <div>

                        <p class="text-xs text-brand-steel">
                            Nomor Polisi
                        </p>

                        <p id="detail-license-plate" class="text-sm text-brand-navy"></p>

                    </div>

                    {{-- SERVICE TYPE --}}
                    <div>

                        <p class="text-xs text-brand-steel">
                            Jenis Servis
                        </p>

                        <p id="detail-service-type" class="text-sm text-brand-navy"></p>

                    </div>

                    {{-- COMPLAINT --}}
                    <div>

                        <p class="text-xs text-brand-steel">
                            Keluhan
                        </p>

                        <p id="detail-complaint" class="text-sm text-brand-navy"></p>

                    </div>

                    {{-- STATUS --}}
                    <div>

                        <p class="text-xs text-brand-steel">
                            Status
                        </p>

                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Selesai
                        </span>

                    </div>

                </div>

                <div class="flex justify-end px-5 py-4 border-t border-ui-border">

                    <button type="button" onclick="closeDetailModal()"
                        class="px-4 py-2 text-sm border border-ui-border rounded-md text-brand-steel hover:bg-ui-main">
                        Tutup
                    </button>

                </div>

            </div>

        </div>

        {{-- REVIEW MODAL --}}
        <div id="review-modal" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center px-4">

            <div class="w-full max-w-md bg-ui-card rounded-lg shadow-lg">

                <div class="flex items-center justify-between px-5 py-4 border-b border-ui-border">

                    <h2 class="text-base font-semibold text-brand-navy">
                        Berikan Review
                    </h2>

                    <button type="button" onclick="closeReviewModal()" class="text-brand-steel hover:text-brand-navy">
                        ✕
                    </button>

                </div>

                <form id="review-form" method="POST" class="p-5 space-y-5">

                    @csrf

                    {{-- RATING --}}
                    <div>

                        <label class="block text-sm font-medium text-brand-navy mb-2">
                            Rating
                        </label>

                        <div class="flex items-center gap-4">

                            @for ($rating = 1; $rating <= 5; $rating++)
                                <label class="flex items-center gap-1 text-sm text-brand-steel cursor-pointer">

                                    <input type="radio" name="rating" value="{{ $rating }}"
                                        class="text-brand-navy focus:ring-brand-navy" required>

                                    {{ $rating }}

                                </label>
                            @endfor

                        </div>

                    </div>

                    {{-- COMMENT --}}
                    <div>

                        <label for="comment" class="block text-sm font-medium text-brand-navy mb-2">
                            Komentar
                        </label>

                        <textarea id="comment" name="comment" rows="4" maxlength="1000" placeholder="Tulis pengalaman Anda..."
                            class="w-full border border-ui-border rounded-md px-3 py-2 text-sm focus:outline-none focus:border-brand-navy"></textarea>

                    </div>

                    {{-- ACTION --}}
                    <div class="flex justify-end gap-3">

                        <button type="button" onclick="closeReviewModal()"
                            class="px-4 py-2 text-sm border border-ui-border rounded-md text-brand-steel hover:bg-ui-main">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-md bg-brand-navy text-white hover:opacity-90">
                            Kirim Review
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </section>
</x-layouts.customer>
