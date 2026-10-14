<x-layouts.customer title="Tracking Servis">

    @php
        $booking = $currentService;
    @endphp

    <div class="space-y-6">

        {{-- Deskripsi --}}
        <div>
            <p class="text-xs text-brand-steel">
                Pantau progres servis kendaraan Anda.
            </p>
        </div>


        @if ($booking)

            @php
                $inspection = $booking->inspection;
                $workOrder = $booking->workOrder;

                $recommendedWorks = $inspection?->recommendedWorks ?? collect();
                $workItems = $workOrder?->workItems ?? collect();

                $approvalCompleted =
                    $recommendedWorks->isNotEmpty()
                    && !$recommendedWorks->contains(
                        'approval_status',
                        'PENDING'
                    );

                $hasInProgressWork = $workItems->contains(
                    'status',
                    'IN_PROGRESS'
                );
            @endphp


            {{-- INFORMASI BOOKING --}}
            <section class="rounded-lg border border-ui-border bg-ui-card p-5">

                <div class="mb-5 flex items-center justify-between gap-4">

                    <div>
                        <p class="text-xs text-brand-steel">
                            Booking Code
                        </p>

                        <h2 class="text-lg font-semibold text-brand-navy">
                            {{ $booking->booking_code }}
                        </h2>
                    </div>


                    {{-- Status Booking --}}
                    @if ($booking->status === 'CONFIRMED')

                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                            Booking Dikonfirmasi
                        </span>

                    @elseif ($booking->status === 'IN_PROGRESS')

                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                            Servis Berjalan
                        </span>

                    @elseif ($booking->status === 'COMPLETED')

                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Servis Selesai
                        </span>

                    @elseif ($booking->status === 'REJECTED')

                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                            Booking Ditolak
                        </span>

                    @elseif ($booking->status === 'CANCELLED')

                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                            Booking Dibatalkan
                        </span>

                    @else

                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                            Menunggu Konfirmasi
                        </span>

                    @endif

                </div>


                {{-- Informasi Kendaraan --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    {{-- Kendaraan --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Kendaraan
                        </p>

                        <p class="text-sm font-medium text-brand-navy">
                            {{ $booking->vehicle?->brand ?? '-' }}
                            {{ $booking->vehicle?->model ?? '' }}
                        </p>
                    </div>


                    {{-- Nomor Polisi --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Nomor Polisi
                        </p>

                        <p class="text-sm font-medium text-brand-navy">
                            {{ $booking->vehicle?->license_plate ?? '-' }}
                        </p>
                    </div>


                    {{-- Jenis Servis --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Jenis Servis
                        </p>

                        <p class="text-sm font-medium text-brand-navy">
                            {{ $booking->service_type ?? '-' }}
                        </p>
                    </div>


                    {{-- Jadwal Servis --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Jadwal Servis
                        </p>

                        <p class="text-sm font-medium text-brand-navy">

                            {{ $booking->booking_date
                                ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y')
                                : '-' }}

                            @if ($booking->booking_time)
                                -
                                {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                            @endif

                        </p>
                    </div>

                </div>

            </section>



            {{-- STATUS SAAT INI --}}
            <section class="rounded-lg border border-ui-border bg-ui-main p-5">

                <p class="text-xs text-brand-steel">
                    Status Saat Ini
                </p>


                <h2 class="mt-1 text-base font-semibold text-brand-navy">

                    @switch($booking->status)

                        @case('PENDING')
                            Menunggu Konfirmasi
                            @break

                        @case('CONFIRMED')
                            Booking Dikonfirmasi
                            @break

                        @case('IN_PROGRESS')

                            @if ($workOrder && $hasInProgressWork)
                                Kendaraan Sedang Dikerjakan
                            @elseif ($workOrder)
                                Work Order Telah Dibuat
                            @elseif ($inspection?->status === 'IN_PROGRESS')
                                Pemeriksaan Kendaraan
                            @else
                                Kendaraan Sedang Diproses
                            @endif

                            @break

                        @case('COMPLETED')
                            Servis Selesai
                            @break

                        @case('REJECTED')
                            Booking Ditolak
                            @break

                        @case('CANCELLED')
                            Booking Dibatalkan
                            @break

                        @default
                            -
                    @endswitch

                </h2>


                {{-- Work Order --}}
                @if ($workOrder)

                    <div class="mt-3 flex items-center gap-2">

                        <i
                            data-lucide="clipboard-check"
                            class="h-4 w-4 text-brand-steel"
                            stroke-width="2"
                        ></i>

                        <p class="text-xs text-brand-steel">
                            Work Order
                        </p>

                        <span class="text-gray-300">
                            •
                        </span>

                        <p class="text-sm font-medium text-brand-navy">
                            {{ $workOrder->work_order_code }}
                        </p>

                    </div>

                @endif

            </section>



            {{-- PROGRESS SERVIS --}}
            <section class="rounded-lg border border-ui-border bg-ui-card p-5">

                <div class="mb-6">

                    <h2 class="text-base font-semibold text-brand-navy">
                        Progres Servis Kendaraan
                    </h2>

                    <p class="mt-1 text-xs text-brand-steel">
                        Status proses servis kendaraan Anda.
                    </p>

                </div>


                <div class="space-y-6">


                    {{-- BOOKING DIKONFIRMASI --}}
                    <div class="flex items-start gap-4">

                        <div class="flex flex-col items-center">

                            @if (in_array($booking->status, [
                                'CONFIRMED',
                                'IN_PROGRESS',
                                'COMPLETED'
                            ]))

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-navy text-white">

                                    <i
                                        data-lucide="check"
                                        class="h-4 w-4"
                                        stroke-width="2.5"
                                    ></i>

                                </div>

                            @elseif ($booking->status === 'PENDING')

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="calendar-check-2"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @else

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-ui-border text-brand-silver">

                                    <i
                                        data-lucide="calendar-check-2"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @endif


                            <div class="mt-1 h-10 w-px bg-ui-border"></div>

                        </div>


                        <div class="pt-1">

                            <p class="text-sm font-medium text-brand-navy">
                                Booking Dikonfirmasi
                            </p>

                            <p class="mt-1 text-xs text-brand-steel">

                                @if ($booking->status === 'PENDING')

                                    Menunggu konfirmasi dari Service Advisor.

                                @elseif ($booking->status === 'REJECTED')

                                    Booking tidak dapat diproses.

                                @elseif ($booking->status === 'CANCELLED')

                                    Booking telah dibatalkan.

                                @else

                                    Booking servis telah dikonfirmasi oleh Service Advisor.

                                @endif

                            </p>

                        </div>

                    </div>



                    {{-- KENDARAAN DITERIMA --}}
                    <div class="flex items-start gap-4">

                        <div class="flex flex-col items-center">

                            @if (in_array($booking->status, [
                                'IN_PROGRESS',
                                'COMPLETED'
                            ]))

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-navy text-white">

                                    <i
                                        data-lucide="check"
                                        class="h-4 w-4"
                                        stroke-width="2.5"
                                    ></i>

                                </div>

                            @elseif ($booking->status === 'CONFIRMED')

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="car-front"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @else

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-ui-border text-brand-silver">

                                    <i
                                        data-lucide="car-front"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @endif


                            <div class="mt-1 h-10 w-px bg-ui-border"></div>

                        </div>


                        <div class="pt-1">

                            <p class="text-sm font-medium text-brand-navy">
                                Kendaraan Diterima
                            </p>

                            <p class="mt-1 text-xs text-brand-steel">

                                @if ($booking->status === 'CONFIRMED')

                                    Menunggu kendaraan masuk ke bengkel.

                                @elseif (in_array($booking->status, [
                                    'IN_PROGRESS',
                                    'COMPLETED'
                                ]))

                                    Kendaraan telah diterima oleh Service Advisor.

                                @else

                                    Kendaraan belum diterima.

                                @endif

                            </p>

                        </div>

                    </div>



                    {{-- PEMERIKSAAN KENDARAAN --}}
                    <div class="flex items-start gap-4">

                        <div class="flex flex-col items-center">

                            @if (
                                $inspection?->status === 'COMPLETED'
                                || $workOrder
                            )

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-navy text-white">

                                    <i
                                        data-lucide="check"
                                        class="h-4 w-4"
                                        stroke-width="2.5"
                                    ></i>

                                </div>

                            @elseif ($inspection?->status === 'IN_PROGRESS')

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="clipboard-search"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @else

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-ui-border text-brand-silver">

                                    <i
                                        data-lucide="clipboard-search"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @endif


                            <div class="mt-1 h-10 w-px bg-ui-border"></div>

                        </div>


                        <div class="pt-1">

                            <p class="text-sm font-medium text-brand-navy">
                                Pemeriksaan Kendaraan
                            </p>

                            <p class="mt-1 text-xs text-brand-steel">

                                @if ($inspection?->status === 'IN_PROGRESS')

                                    Kendaraan sedang diperiksa oleh mekanik.

                                @elseif ($inspection?->status === 'COMPLETED')

                                    Pemeriksaan kendaraan telah selesai.

                                @elseif ($workOrder)

                                    Pemeriksaan kendaraan telah selesai.

                                @else

                                    Pemeriksaan dilakukan setelah kendaraan diterima.

                                @endif

                            </p>

                        </div>

                    </div>



                    {{-- PERSETUJUAN PEKERJAAN --}}
                    <div class="flex items-start gap-4">

                        <div class="flex flex-col items-center">

                            @if ($approvalCompleted || $workOrder)

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-navy text-white">

                                    <i
                                        data-lucide="check"
                                        class="h-4 w-4"
                                        stroke-width="2.5"
                                    ></i>

                                </div>

                            @elseif ($recommendedWorks->contains(
                                'approval_status',
                                'PENDING'
                            ))

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="clock-3"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @elseif ($recommendedWorks->isNotEmpty())

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="clipboard-check"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @else

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-ui-border text-brand-silver">

                                    <i
                                        data-lucide="clipboard-check"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @endif


                            <div class="mt-1 h-10 w-px bg-ui-border"></div>

                        </div>


                        <div class="pt-1">

                            <p class="text-sm font-medium text-brand-navy">
                                Persetujuan Pekerjaan
                            </p>

                            <p class="mt-1 text-xs text-brand-steel">

                                @if ($recommendedWorks->contains(
                                    'approval_status',
                                    'PENDING'
                                ))

                                    Menunggu keputusan pelanggan terhadap rekomendasi pekerjaan.

                                @elseif ($approvalCompleted)

                                    Keputusan pelanggan telah diberikan.

                                @elseif ($workOrder)

                                    Persetujuan pekerjaan telah selesai.

                                @else

                                    Menunggu hasil pemeriksaan dan rekomendasi mekanik.

                                @endif

                            </p>

                        </div>

                    </div>



                    {{-- 5. PENGERJAAN --}}
                    <div class="flex items-start gap-4">

                        <div class="flex h-8 w-8 shrink-0 items-center justify-center">

                            @if ($booking->status === 'COMPLETED')

                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-navy text-white">

                                    <i
                                        data-lucide="check"
                                        class="h-4 w-4"
                                        stroke-width="2.5"
                                    ></i>

                                </div>

                            @elseif ($hasInProgressWork)

                                <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="wrench"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @elseif ($workOrder)

                                <div class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-brand-gold text-brand-gold">

                                    <i
                                        data-lucide="wrench"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @else

                                <div class="flex h-8 w-8 items-center justify-center rounded-full border border-ui-border text-brand-silver">

                                    <i
                                        data-lucide="wrench"
                                        class="h-4 w-4"
                                        stroke-width="2"
                                    ></i>

                                </div>

                            @endif

                        </div>


                        <div class="pt-1">

                            <p class="text-sm font-medium text-brand-navy">

                                @if ($booking->status === 'COMPLETED')

                                    Servis Selesai

                                @elseif ($hasInProgressWork)

                                    Sedang Dikerjakan

                                @elseif ($workOrder)

                                    Work Order Dibuat

                                @else

                                    Menunggu Pengerjaan

                                @endif

                            </p>


                            <p class="mt-1 text-xs text-brand-steel">

                                @if ($booking->status === 'COMPLETED')

                                    Seluruh pekerjaan servis telah selesai.

                                @elseif ($hasInProgressWork)

                                    Kendaraan sedang dalam proses pengerjaan.

                                @elseif ($workOrder)

                                    Work Order telah dibuat dan pekerjaan akan dilakukan oleh mekanik.

                                @else

                                    Pengerjaan dimulai setelah Work Order dibuat.

                                @endif

                            </p>

                        </div>

                    </div>

                </div>

            </section>



            {{-- PEKERJAAN --}}
            @if ($workOrder)

                <section class="rounded-lg border border-ui-border bg-ui-card p-5">

                    <div class="mb-4">

                        <h2 class="text-base font-semibold text-brand-navy">
                            Pekerjaan
                        </h2>

                        <p class="mt-1 text-xs text-brand-steel">
                            Daftar pekerjaan yang telah disetujui pelanggan.
                        </p>

                    </div>


                    <div class="overflow-hidden rounded-lg border border-ui-border">

                        @forelse ($workItems as $workItem)

                            <div class="flex items-center justify-between gap-4 border-b border-ui-border p-4 last:border-b-0">

                                <div class="min-w-0">

                                    <p class="text-sm font-medium text-brand-navy">
                                        {{ $workItem->name ?? 'Pekerjaan' }}
                                    </p>

                                    @if ($workItem->description)

                                        <p class="mt-1 text-xs text-brand-steel">
                                            {{ $workItem->description }}
                                        </p>

                                    @endif

                                </div>


                                <div class="flex shrink-0 items-center gap-2">

                                    @if ($workItem->status === 'COMPLETED')

                                        <span class="text-xs text-gray-500">
                                            Selesai
                                        </span>

                                        <i
                                            data-lucide="check"
                                            class="h-4 w-4 text-brand-navy"
                                            stroke-width="2.5"
                                        ></i>

                                    @elseif ($workItem->status === 'IN_PROGRESS')

                                        <span class="text-xs font-medium text-brand-navy">
                                            Dikerjakan
                                        </span>

                                        <i
                                            data-lucide="wrench"
                                            class="h-4 w-4 text-brand-navy"
                                            stroke-width="2"
                                        ></i>

                                    @else

                                        <span class="text-xs text-gray-500">
                                            Menunggu
                                        </span>

                                        <i
                                            data-lucide="clock-3"
                                            class="h-4 w-4 text-gray-400"
                                            stroke-width="2"
                                        ></i>

                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="p-5 text-center">

                                <p class="text-xs text-brand-steel">
                                    Belum ada pekerjaan yang tercatat.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </section>

            @endif


        @else


            {{-- EMPTY STATE --}}
            <section class="rounded-lg border border-ui-border bg-ui-card p-8 text-center">

                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-ui-main text-gray-400">

                    <i
                        data-lucide="wrench"
                        class="h-6 w-6"
                        stroke-width="2"
                    ></i>

                </div>


                <h2 class="text-base font-semibold text-brand-navy">
                    Belum Ada Servis
                </h2>

                <p class="mt-2 text-xs text-brand-steel">
                    Saat ini belum ada servis kendaraan yang dapat dilacak.
                </p>

            </section>

        @endif

    </div>
</x-layouts.customer>