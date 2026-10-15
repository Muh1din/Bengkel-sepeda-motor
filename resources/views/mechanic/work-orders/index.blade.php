<x-layouts.mechanic title="Work Orders">

    <div class="space-y-6">

        {{-- Page Caption --}}
        <div>
            <p class="text-sm text-brand-steel">
                Kelola pekerjaan Work Order yang ditugaskan kepada Anda.
            </p>
        </div>

        {{-- Work Orders --}}
        <section class="space-y-4">

            {{-- Section Header --}}
            <div
                class="flex flex-col gap-4 rounded-xl border border-ui-border bg-ui-card px-5 py-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-navy/10 text-brand-navy">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">

                            <h2 class="text-base font-semibold text-ui-text">
                                Work Order
                            </h2>

                            @if ($workOrders->count())
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-semibold text-brand-steel">
                                    {{ $workOrders->count() }}
                                </span>
                            @endif

                        </div>

                        <p class="mt-0.5 text-xs text-brand-steel">
                            Daftar pekerjaan yang sedang dan telah dikerjakan.
                        </p>
                    </div>

                </div>

                @if ($workOrders->count())
                    <div class="hidden items-center gap-2 text-xs text-brand-steel sm:flex">
                        <i data-lucide="wrench" class="h-4 w-4"></i>

                        <span>
                            {{ $workOrders->count() }} Work Order
                        </span>
                    </div>
                @endif

            </div>

            {{-- Work Order List --}}
            @forelse ($workOrders as $workOrder)

                <div class="rounded-xl border border-ui-border bg-ui-card shadow-sm transition hover:shadow-md">

                    <div class="px-5 py-4">

                        {{-- WO Header --}}
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <div>

                                <div class="flex items-center gap-2">

                                    <h3 class="text-sm font-semibold text-ui-text">
                                        {{ $workOrder->work_order_code }}
                                    </h3>

                                    @if ($workOrder->status === 'IN_PROGRESS')
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                                            Sedang Dikerjakan
                                        </span>
                                    @elseif ($workOrder->status === 'COMPLETED')
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-semibold text-green-700">
                                            Selesai
                                        </span>
                                    @endif

                                </div>

                                <p class="mt-1 text-xs text-brand-steel">
                                    Booking {{ $workOrder->booking->booking_code }}
                                </p>

                            </div>

                            {{-- WO Actions --}}
                            <div class="flex flex-wrap items-center gap-2">

                                {{-- Request Material --}}
                                {{-- @if ($workOrder->status === 'IN_PROGRESS')

                                <a
                                    href="{{ route('mechanic.request-item.index', ['work_order_id' => $workOrder->id]) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-navy px-3 py-2 text-xs font-semibold text-white transition hover:opacity-90"
                                >
                                    <i data-lucide="package-plus" class="h-4 w-4"></i>
                                    Request Material
                                </a>

                            @endif --}}

                                @if ($workOrder->status === 'IN_PROGRESS')
                                    @php
                                        $materialRequest = $workOrder->materialRequests
                                            ->sortByDesc('created_at')
                                            ->first();
                                    @endphp

                                    @if (!$materialRequest)
                                        {{-- Belum pernah request --}}
                                        <a href="{{ route('mechanic.request-item.index', ['work_order_id' => $workOrder->id]) }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-navy px-3 py-2 text-xs font-semibold text-white transition hover:opacity-90">
                                            <i data-lucide="package-plus" class="h-4 w-4"></i>
                                            Request Material
                                        </a>
                                    @elseif ($materialRequest->status === 'PENDING')
                                        {{-- Sudah request, masih menunggu Admin --}}
                                        <span
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-100 px-3 py-2 text-xs font-semibold text-yellow-700">
                                            <i data-lucide="clock-3" class="h-4 w-4"></i>
                                            Menunggu Disiapkan
                                        </span>
                                    @elseif ($materialRequest->status === 'FULFILLED')
                                        {{-- Admin sudah menyiapkan --}}
                                        <span
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-xs font-semibold text-green-700">
                                            <i data-lucide="package-check" class="h-4 w-4"></i>
                                            Sudah Disiapkan
                                        </span>
                                    @endif
                                @endif

                                {{-- Detail --}}
                                <a href="{{ route('mechanic.work-orders.show', $workOrder) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-ui-border px-3 py-2 text-xs font-semibold text-brand-navy transition hover:bg-gray-50">
                                    <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                                    Lihat Detail
                                </a>

                            </div>

                        </div>

                        <div class="my-4 border-t border-ui-border"></div>

                        {{-- Basic Information --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                                    Pelanggan
                                </p>

                                <p class="mt-1 text-sm font-medium text-ui-text">
                                    {{ $workOrder->booking->customer->name }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                                    Kendaraan
                                </p>

                                <p class="mt-1 text-sm font-medium text-ui-text">
                                    {{ $workOrder->booking->vehicle->license_plate }}
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                                    Diterima
                                </p>

                                <p class="mt-1 text-sm font-medium text-ui-text">
                                    {{ $workOrder->received_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>

                        </div>

                        {{-- Work Items --}}
                        @if ($workOrder->workItems->count())
                            <div class="mt-5">

                                <div class="flex items-center justify-between">

                                    <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                                        Pekerjaan
                                    </p>

                                    <span class="text-[11px] text-brand-steel">
                                        {{ $workOrder->workItems->count() }} pekerjaan
                                    </span>

                                </div>

                                <div class="mt-2 space-y-2">

                                    @foreach ($workOrder->workItems as $workItem)
                                        <div
                                            class="flex flex-col gap-3 rounded-lg border border-ui-border bg-gray-50 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">

                                            <div class="flex items-start gap-3">

                                                <div
                                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-ui-border bg-white text-brand-navy">
                                                    <i data-lucide="wrench" class="h-4 w-4"></i>
                                                </div>

                                                <div>

                                                    <p class="text-sm font-medium text-ui-text">
                                                        {{ $workItem->name }}
                                                    </p>

                                                    @if ($workItem->description)
                                                        <p class="mt-0.5 text-xs text-brand-steel">
                                                            {{ $workItem->description }}
                                                        </p>
                                                    @endif

                                                </div>

                                            </div>

                                            <div class="flex items-center gap-2">

                                                @if ($workItem->status === 'PENDING')
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-[10px] font-semibold text-amber-700">
                                                        Menunggu
                                                    </span>

                                                    <form method="POST"
                                                        action="{{ route('mechanic.work-items.start', $workItem) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-navy px-3 py-2 text-xs font-semibold text-white transition hover:opacity-90">
                                                            <i data-lucide="play" class="h-3.5 w-3.5"></i>
                                                            Mulai
                                                        </button>
                                                    </form>
                                                @elseif ($workItem->status === 'IN_PROGRESS')
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-[10px] font-semibold text-blue-700">
                                                        Sedang Dikerjakan
                                                    </span>

                                                    <form method="POST"
                                                        action="{{ route('mechanic.work-items.complete', $workItem) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                                                            Selesaikan
                                                        </button>
                                                    </form>
                                                @elseif ($workItem->status === 'COMPLETED')
                                                    <span
                                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2 py-1 text-[10px] font-semibold text-green-700">
                                                        <i data-lucide="check-circle" class="h-3.5 w-3.5"></i>
                                                        Selesai
                                                    </span>
                                                @endif

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        @else
                            <div class="mt-5 rounded-lg border border-dashed border-ui-border px-4 py-6 text-center">
                                <i data-lucide="clipboard-x" class="mx-auto h-5 w-5 text-brand-steel"></i>

                                <p class="mt-2 text-xs text-brand-steel">
                                    Belum ada pekerjaan pada Work Order ini.
                                </p>
                            </div>
                        @endif

                    </div>

                </div>

            @empty

                <div class="rounded-xl border border-ui-border bg-ui-card px-5 py-10 text-center shadow-sm">

                    <div
                        class="mx-auto flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100 text-brand-steel">
                        <i data-lucide="clipboard-list" class="h-5 w-5"></i>
                    </div>

                    <h3 class="mt-3 text-sm font-semibold text-ui-text">
                        Belum Ada Work Order
                    </h3>

                    <p class="mt-1 text-xs text-brand-steel">
                        Belum ada Work Order yang ditugaskan kepada Anda.
                    </p>

                </div>

            @endforelse

        </section>

    </div>

</x-layouts.mechanic>
