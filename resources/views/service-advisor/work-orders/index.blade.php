<x-layouts.service-advisor title="Work Order">

    <section id="sec-work-orders" class="space-y-8">

        <div>
            <p class="text-sm text-brand-steel">
                Kelola Work Order berdasarkan hasil inspeksi dan keputusan pelanggan.
            </p>
        </div>



        {{-- MENUNGGU DIBUAT --}}
        <div class="space-y-4">

            {{-- Section Header --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4
                       rounded-xl border border-ui-border bg-ui-card px-5 py-4 shadow-sm">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center
                               rounded-lg bg-amber-50 text-amber-600">
                        <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <div class="flex items-center gap-2">

                            <h2 class="text-base font-semibold text-ui-text">
                                Menunggu Dibuat
                            </h2>

                            @if ($readyInspections->count())
                                <span
                                    class="inline-flex items-center rounded-full
                                           bg-amber-50 px-2 py-0.5 text-[10px]
                                           font-semibold text-amber-700">
                                    {{ $readyInspections->count() }}
                                </span>
                            @endif

                        </div>

                        <p class="mt-0.5 text-xs text-brand-steel">
                            Inspeksi yang siap dibuat menjadi Work Order.
                        </p>

                    </div>

                </div>

                @if ($readyInspections->count())
                    <div class="hidden sm:flex items-center gap-2 text-xs text-brand-steel">
                        <i data-lucide="clock-3" class="h-4 w-4"></i>

                        <span>
                            {{ $readyInspections->count() }} inspeksi menunggu
                        </span>
                    </div>
                @endif

            </div>


            {{-- Ready Inspections --}}
            @forelse ($readyInspections as $inspection)
                <div class="bg-ui-card rounded-xl border border-ui-border shadow-sm">

                    {{-- Card Header --}}
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                               gap-3 px-5 py-4 border-b border-ui-border">

                        <div>

                            <div class="flex items-center gap-2">

                                <span class="text-sm font-semibold text-ui-text">
                                    {{ $inspection->booking->booking_code }}
                                </span>

                                <span
                                    class="inline-flex items-center rounded-full
                                           bg-amber-50 px-2.5 py-1 text-[11px]
                                           font-semibold text-amber-700">
                                    Siap Dibuat
                                </span>

                            </div>

                            <p class="mt-1 text-xs text-brand-steel">
                                Inspeksi selesai dan keputusan pelanggan telah lengkap.
                            </p>

                        </div>


                        {{-- Create Work Order --}}
                        <form action="{{ route('service-advisor.work-orders.store', $inspection) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin membuat Work Order untuk booking ini?')">
                            @csrf

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2
                                       rounded-lg bg-brand-navy px-4 py-2 text-xs
                                       font-semibold text-white hover:opacity-90 transition">
                                <i data-lucide="clipboard-plus" class="h-4 w-4"></i>

                                Buat Work Order
                            </button>

                        </form>

                    </div>


                    {{-- Information --}}
                    <div
                        class="grid grid-cols-1 sm:grid-cols-3
                               divide-y sm:divide-y-0 sm:divide-x
                               divide-ui-border">

                        {{-- Customer --}}
                        <div class="px-5 py-4">

                            <p
                                class="text-[11px] font-medium uppercase
                                       tracking-wide text-brand-steel">
                                Pelanggan
                            </p>

                            <div class="mt-2 flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 shrink-0 items-center
                                           justify-center rounded-lg bg-gray-100
                                           text-brand-steel">
                                    <i data-lucide="user-round" class="h-4 w-4"></i>
                                </div>

                                <div class="min-w-0">

                                    <p
                                        class="truncate text-sm font-semibold
                                               text-ui-text">
                                        {{ $inspection->booking->customer->name }}
                                    </p>

                                    <p class="text-xs text-brand-steel">
                                        Pelanggan
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Vehicle --}}
                        <div class="px-5 py-4">

                            <p
                                class="text-[11px] font-medium uppercase
                                       tracking-wide text-brand-steel">
                                Kendaraan
                            </p>

                            <div class="mt-2 flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 shrink-0 items-center
                                           justify-center rounded-lg bg-gray-100
                                           text-brand-steel">
                                    <i data-lucide="bike" class="h-4 w-4"></i>
                                </div>

                                <div class="min-w-0">

                                    <p
                                        class="truncate text-sm font-semibold
                                               text-ui-text">
                                        {{ $inspection->booking->vehicle->license_plate }}
                                    </p>

                                    <p class="text-xs text-brand-steel">
                                        Kendaraan
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Mechanic --}}
                        <div class="px-5 py-4">

                            <p
                                class="text-[11px] font-medium uppercase
                                       tracking-wide text-brand-steel">
                                Mekanik Inspeksi
                            </p>

                            <div class="mt-2 flex items-center gap-3">

                                <div
                                    class="flex h-9 w-9 shrink-0 items-center
                                           justify-center rounded-lg bg-gray-100
                                           text-brand-steel">
                                    <i data-lucide="wrench" class="h-4 w-4"></i>
                                </div>

                                <div class="min-w-0">

                                    <p
                                        class="truncate text-sm font-semibold
                                               text-ui-text">
                                        {{ $inspection->mechanic->name }}
                                    </p>

                                    <p class="text-xs text-brand-steel">
                                        Mekanik
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                {{-- Empty State --}}
                <div
                    class="rounded-xl border border-dashed border-ui-border
                           bg-ui-card px-6 py-12 text-center">

                    <div
                        class="mx-auto flex h-10 w-10 items-center justify-center
                               rounded-full bg-gray-100 text-brand-steel">
                        <i data-lucide="clipboard-check" class="h-5 w-5"></i>
                    </div>

                    <p class="mt-3 text-sm font-medium text-ui-text">
                        Tidak ada Work Order yang menunggu dibuat
                    </p>

                    <p class="mt-1 text-xs text-brand-steel">
                        Inspeksi yang sudah siap akan muncul di sini.
                    </p>

                </div>
            @endforelse

        </div>


        {{-- WORK ORDER --}}
        <div class="space-y-4">

            {{-- Section Header --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4
                       rounded-xl border border-ui-border bg-ui-card px-5 py-4 shadow-sm">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center
                               rounded-lg bg-brand-navy/10 text-brand-navy">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>

                    <div>

                        <div class="flex items-center gap-2">

                            <h2 class="text-base font-semibold text-ui-text">
                                Work Order
                            </h2>

                            @if ($workOrders->count())
                                <span
                                    class="inline-flex items-center rounded-full
                                           bg-gray-100 px-2 py-0.5 text-[10px]
                                           font-semibold text-brand-steel">
                                    {{ $workOrders->count() }}
                                </span>
                            @endif

                        </div>

                        <p class="mt-0.5 text-xs text-brand-steel">
                            Work Order yang sudah dibuat dan sedang diproses.
                        </p>

                    </div>

                </div>

                @if ($workOrders->count())
                    <div class="hidden sm:flex items-center gap-2 text-xs text-brand-steel">

                        <i data-lucide="files" class="h-4 w-4"></i>

                        <span>
                            {{ $workOrders->count() }} Work Order
                        </span>

                    </div>
                @endif

            </div>


            {{-- Work Orders --}}
            @forelse ($workOrders as $workOrder)

                <div
                    class="bg-ui-card rounded-xl border border-ui-border
               shadow-sm hover:shadow-md transition">
                    <div class="px-5 py-4">

                        {{-- Top --}}
                        <div
                            class="flex flex-col sm:flex-row sm:items-center
                       sm:justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">

                                    <h3 class="text-sm font-semibold text-ui-text">
                                        {{ $workOrder->work_order_code }}
                                    </h3>

                                    @if ($workOrder->status === 'IN_PROGRESS')
                                        <span
                                            class="inline-flex items-center rounded-full
                                       bg-blue-50 px-2.5 py-1 text-[11px]
                                       font-semibold text-blue-700">
                                            Sedang Dikerjakan
                                        </span>
                                    @elseif ($workOrder->status === 'COMPLETED')
                                        <span
                                            class="inline-flex items-center rounded-full
                                       bg-green-50 px-2.5 py-1 text-[11px]
                                       font-semibold text-green-700">
                                            Selesai
                                        </span>
                                    @endif

                                </div>

                                <p class="mt-1 text-xs text-brand-steel">
                                    Booking {{ $workOrder->booking->booking_code }}
                                </p>
                            </div>

                            {{-- Detail --}}
                            <button type="button" data-work-order-id="{{ $workOrder->id }}"
                                class="work-order-detail-btn
                           inline-flex items-center justify-center gap-2
                           rounded-lg border border-ui-border px-3 py-2
                           text-xs font-semibold text-brand-navy
                           hover:bg-gray-50 transition">
                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                Lihat Detail
                            </button>
                        </div>

                        {{-- Divider --}}
                        <div class="my-4 border-t border-ui-border"></div>

                        {{-- Info --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                            {{-- Customer --}}
                            <div>
                                <p
                                    class="text-[11px] uppercase tracking-wide
                               text-brand-steel">
                                    Pelanggan
                                </p>

                                <p class="mt-1 text-sm font-medium text-ui-text">
                                    {{ $workOrder->booking->customer->name }}
                                </p>
                            </div>

                            {{-- Vehicle --}}
                            <div>
                                <p
                                    class="text-[11px] uppercase tracking-wide
                               text-brand-steel">
                                    Kendaraan
                                </p>

                                <p class="mt-1 text-sm font-medium text-ui-text">
                                    {{ $workOrder->booking->vehicle->license_plate }}
                                </p>
                            </div>

                            {{-- Mechanic --}}
                            <div>
                                <p
                                    class="text-[11px] uppercase tracking-wide
                               text-brand-steel">
                                    Mekanik
                                </p>

                                <p class="mt-1 text-sm font-medium text-ui-text">
                                    {{ $workOrder->mechanic->name }}
                                </p>
                            </div>

                        </div>

                        {{-- Work Items --}}
                        @if ($workOrder->workItems->count())
                            <div class="mt-5">

                                <p
                                    class="text-[11px] uppercase tracking-wide
                               text-brand-steel">
                                    Pekerjaan
                                </p>

                                <div class="mt-2 flex flex-wrap gap-2">

                                    @foreach ($workOrder->workItems as $workItem)
                                        <span
                                            class="inline-flex items-center gap-1.5
                                       rounded-md bg-gray-100 px-2.5 py-1.5
                                       text-xs text-gray-700">
                                            <i data-lucide="wrench" class="h-3.5 w-3.5"></i>

                                            {{ $workItem->name }}
                                        </span>
                                    @endforeach

                                </div>

                            </div>
                        @endif

                    </div>
                </div>


                {{-- WORK ORDER DETAIL MODAL --}}
                <div id="work-order-detail-modal-{{ $workOrder->id }}"
                    class="work-order-detail-modal fixed inset-0 z-50 hidden">

                    {{-- Overlay --}}
                    <div class="work-order-detail-close absolute inset-0 bg-black/40"
                        data-work-order-id="{{ $workOrder->id }}"></div>


                    {{-- Modal Wrapper --}}
                    <div class="relative flex min-h-full items-center
                   justify-center p-4">

                        {{-- Modal --}}
                        <div
                            class="relative w-full max-w-2xl max-h-[90vh]
                       overflow-hidden rounded-xl bg-ui-card shadow-xl">

                            {{-- Header --}}
                            <div
                                class="flex items-center justify-between
                           border-b border-ui-border px-5 py-4">

                                <div>

                                    <div class="flex items-center gap-2">

                                        <h2 class="text-base font-semibold text-ui-text">
                                            {{ $workOrder->work_order_code }}
                                        </h2>

                                        @if ($workOrder->status === 'IN_PROGRESS')
                                            <span
                                                class="inline-flex items-center rounded-full
                                           bg-blue-50 px-2.5 py-1 text-[11px]
                                           font-semibold text-blue-700">
                                                Sedang Dikerjakan
                                            </span>
                                        @elseif ($workOrder->status === 'COMPLETED')
                                            <span
                                                class="inline-flex items-center rounded-full
                                           bg-green-50 px-2.5 py-1 text-[11px]
                                           font-semibold text-green-700">
                                                Selesai
                                            </span>
                                        @endif

                                    </div>

                                    <p class="mt-1 text-xs text-brand-steel">
                                        Booking {{ $workOrder->booking->booking_code }}
                                    </p>

                                </div>


                                {{-- Close --}}
                                <button type="button" data-work-order-id="{{ $workOrder->id }}"
                                    class="work-order-detail-close
                               flex h-8 w-8 items-center justify-center
                               rounded-lg text-brand-steel
                               hover:bg-gray-100
                               hover:text-ui-text transition">
                                    <i data-lucide="x" class="h-5 w-5"></i>
                                </button>

                            </div>


                            {{-- Content --}}
                            <div class="max-h-[calc(90vh-130px)]
                           overflow-y-auto">

                                {{-- General Information --}}
                                <div class="px-5 py-5">

                                    <h3
                                        class="text-xs font-semibold uppercase
                                   tracking-wide text-brand-steel">
                                        Informasi Work Order
                                    </h3>


                                    <div
                                        class="mt-4 grid grid-cols-1
                                            sm:grid-cols-2 gap-4">

                                        {{-- Customer --}}
                                        <div
                                            class="rounded-lg border border-ui-border
                                       bg-gray-50 px-4 py-3">
                                            <p class="text-[11px] text-brand-steel">
                                                Pelanggan
                                            </p>

                                            <p
                                                class="mt-1 text-sm font-semibold
                                           text-ui-text">
                                                {{ $workOrder->booking->customer->name }}
                                            </p>
                                        </div>


                                        {{-- Vehicle --}}
                                        <div
                                            class="rounded-lg border border-ui-border
                                       bg-gray-50 px-4 py-3">
                                            <p class="text-[11px] text-brand-steel">
                                                Kendaraan
                                            </p>

                                            <p
                                                class="mt-1 text-sm font-semibold
                                           text-ui-text">
                                                {{ $workOrder->booking->vehicle->license_plate }}
                                            </p>
                                        </div>


                                        {{-- Mechanic --}}
                                        <div
                                            class="rounded-lg border border-ui-border
                                       bg-gray-50 px-4 py-3">
                                            <p class="text-[11px] text-brand-steel">
                                                Mekanik
                                            </p>

                                            <p
                                                class="mt-1 text-sm font-semibold
                                           text-ui-text">
                                                {{ $workOrder->mechanic->name }}
                                            </p>
                                        </div>


                                        {{-- Received At --}}
                                        <div
                                            class="rounded-lg border border-ui-border
                                       bg-gray-50 px-4 py-3">
                                            <p class="text-[11px] text-brand-steel">
                                                Kendaraan Diterima
                                            </p>

                                            <p
                                                class="mt-1 text-sm font-semibold
                                           text-ui-text">
                                                {{ $workOrder->received_at?->format('d M Y, H:i') }}
                                            </p>
                                        </div>

                                    </div>

                                </div>


                                {{-- Work Items --}}
                                <div class="border-t border-ui-border
                               px-5 py-5">

                                    <div class="flex items-center
                                   justify-between">

                                        <div>

                                            <h3
                                                class="text-xs font-semibold uppercase
                                           tracking-wide text-brand-steel">
                                                Daftar Pekerjaan
                                            </h3>

                                            <p class="mt-1 text-xs text-brand-steel">
                                                Pekerjaan yang termasuk dalam Work Order.
                                            </p>

                                        </div>

                                        <span
                                            class="text-xs font-medium
                                       text-brand-steel">
                                            {{ $workOrder->workItems->count() }}
                                            pekerjaan
                                        </span>

                                    </div>


                                    <div class="mt-4 space-y-2">

                                        @forelse ($workOrder->workItems as $workItem)
                                            <div
                                                class="flex items-start gap-3
                                           rounded-lg border border-ui-border
                                           bg-gray-50 px-4 py-3">

                                                <div
                                                    class="mt-0.5 flex h-8 w-8
                                               shrink-0 items-center
                                               justify-center rounded-lg
                                               bg-brand-navy/10
                                               text-brand-navy">
                                                    <i data-lucide="wrench" class="h-4 w-4"></i>
                                                </div>


                                                <div class="min-w-0">

                                                    <p
                                                        class="text-sm font-semibold
                                                   text-ui-text">
                                                        {{ $workItem->name }}
                                                    </p>

                                                    @if ($workItem->description)
                                                        <p
                                                            class="mt-1 text-xs
                                                       leading-relaxed
                                                       text-brand-steel">
                                                            {{ $workItem->description }}
                                                        </p>
                                                    @endif


                                                    <div class="mt-2">

                                                        @if ($workItem->status === 'PENDING')
                                                            <span
                                                                class="inline-flex items-center
                                                           rounded-full bg-gray-100
                                                           px-2 py-0.5 text-[10px]
                                                           font-semibold text-gray-600">
                                                                Menunggu
                                                            </span>
                                                        @elseif ($workItem->status === 'IN_PROGRESS')
                                                            <span
                                                                class="inline-flex items-center
                                                           rounded-full bg-blue-50
                                                           px-2 py-0.5 text-[10px]
                                                           font-semibold text-blue-700">
                                                                Sedang Dikerjakan
                                                            </span>
                                                        @elseif ($workItem->status === 'COMPLETED')
                                                            <span
                                                                class="inline-flex items-center
                                                           rounded-full bg-green-50
                                                           px-2 py-0.5 text-[10px]
                                                           font-semibold text-green-700">
                                                                Selesai
                                                            </span>
                                                        @endif

                                                    </div>

                                                </div>

                                            </div>

                                        @empty

                                            <div
                                                class="rounded-lg border border-dashed
                                           border-ui-border px-4 py-6
                                           text-center">
                                                <p class="text-xs text-brand-steel">
                                                    Tidak ada pekerjaan dalam Work Order.
                                                </p>
                                            </div>
                                        @endforelse

                                    </div>

                                </div>

                            </div>


                            {{-- Footer --}}
                            <div
                                class="flex justify-end
                           border-t border-ui-border
                           bg-gray-50 px-5 py-3">

                                <button type="button" data-work-order-id="{{ $workOrder->id }}"
                                    class="work-order-detail-close
                               rounded-lg border border-ui-border
                               bg-white px-4 py-2 text-xs font-semibold
                               text-ui-text hover:bg-gray-50 transition">
                                    Tutup
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                {{-- Empty State --}}
                <div
                    class="rounded-xl border border-dashed border-ui-border
               bg-ui-card px-6 py-12 text-center">
                    <div
                        class="mx-auto flex h-10 w-10 items-center justify-center
                   rounded-full bg-gray-100 text-brand-steel">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>

                    <p class="mt-3 text-sm font-medium text-ui-text">
                        Belum ada Work Order
                    </p>

                    <p class="mt-1 text-xs text-brand-steel">
                        Work Order yang sudah dibuat akan muncul di sini.
                    </p>
                </div>

            @endforelse

        </div>

        {{-- WORK ORDER DETAIL MODAL --}}
        <div id="work-order-detail-modal" class="fixed inset-0 z-50 hidden">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/40" onclick="closeWorkOrderDetail()"></div>


            {{-- Modal --}}
            <div class="relative flex min-h-full items-center justify-center p-4">

                <div
                    class="relative w-full max-w-2xl max-h-[90vh]
                   overflow-hidden rounded-xl bg-ui-card shadow-xl">

                    {{-- Modal Header --}}
                    <div
                        class="flex items-center justify-between
                       border-b border-ui-border px-5 py-4">

                        <div>

                            <div class="flex items-center gap-2">

                                <h2 id="modal-work-order-code" class="text-base font-semibold text-ui-text">
                                    Work Order
                                </h2>

                                <span id="modal-work-order-status"
                                    class="inline-flex items-center rounded-full
                                   px-2.5 py-1 text-[11px] font-semibold"></span>

                            </div>

                            <p id="modal-booking-code" class="mt-1 text-xs text-brand-steel"></p>

                        </div>


                        {{-- Close --}}
                        <button type="button"
                            class="work-order-detail-close
                                flex h-8 w-8 items-center justify-center
                                rounded-lg text-brand-steel
                                hover:bg-gray-100 hover:text-ui-text transition">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>

                    </div>


                    {{-- Modal Content --}}
                    <div class="max-h-[calc(90vh-73px)] overflow-y-auto">

                        {{-- General Information --}}
                        <div class="px-5 py-5">

                            <h3
                                class="text-xs font-semibold uppercase
                               tracking-wide text-brand-steel">
                                Informasi Work Order
                            </h3>


                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

                                {{-- Customer --}}
                                <div
                                    class="rounded-lg border border-ui-border
                                   bg-gray-50 px-4 py-3">

                                    <p class="text-[11px] text-brand-steel">
                                        Pelanggan
                                    </p>

                                    <p id="modal-customer" class="mt-1 text-sm font-semibold text-ui-text">
                                        -
                                    </p>

                                </div>


                                {{-- Vehicle --}}
                                <div
                                    class="rounded-lg border border-ui-border
                                   bg-gray-50 px-4 py-3">

                                    <p class="text-[11px] text-brand-steel">
                                        Kendaraan
                                    </p>

                                    <p id="modal-vehicle" class="mt-1 text-sm font-semibold text-ui-text">
                                        -
                                    </p>

                                </div>


                                {{-- Mechanic --}}
                                <div
                                    class="rounded-lg border border-ui-border
                                   bg-gray-50 px-4 py-3">

                                    <p class="text-[11px] text-brand-steel">
                                        Mekanik
                                    </p>

                                    <p id="modal-mechanic" class="mt-1 text-sm font-semibold text-ui-text">
                                        -
                                    </p>

                                </div>


                                {{-- Received At --}}
                                <div
                                    class="rounded-lg border border-ui-border
                                   bg-gray-50 px-4 py-3">

                                    <p class="text-[11px] text-brand-steel">
                                        Kendaraan Diterima
                                    </p>

                                    <p id="modal-received-at" class="mt-1 text-sm font-semibold text-ui-text">
                                        -
                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- Work Items --}}
                        <div class="border-t border-ui-border px-5 py-5">

                            <div class="flex items-center justify-between">

                                <div>

                                    <h3
                                        class="text-xs font-semibold uppercase
                                       tracking-wide text-brand-steel">
                                        Daftar Pekerjaan
                                    </h3>

                                    <p class="mt-1 text-xs text-brand-steel">
                                        Pekerjaan yang termasuk dalam Work Order.
                                    </p>

                                </div>

                                <span id="modal-work-item-count" class="text-xs font-medium text-brand-steel">
                                    0 pekerjaan
                                </span>

                            </div>


                            <div id="modal-work-items" class="mt-4 space-y-2">
                            </div>

                        </div>

                    </div>


                    {{-- Modal Footer --}}
                    <div
                        class="flex justify-end border-t border-ui-border
                       bg-gray-50 px-5 py-3">

                        <button type="button"
                            class="work-order-detail-close
                                rounded-lg border border-ui-border
                                bg-white px-4 py-2 text-xs font-semibold
                                text-ui-text hover:bg-gray-50 transition">
                            Tutup
                        </button>

                    </div>

                </div>

            </div>

        </div>


    </section>
</x-layouts.service-advisor>
