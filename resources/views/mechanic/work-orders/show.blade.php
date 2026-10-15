<x-layouts.mechanic title="Work Order Detail ">
    <div class="space-y-6">

        {{-- Page Caption --}}
        <div>
            <p class="text-sm text-brand-steel">
                Detail Work Order dan pekerjaan yang harus diselesaikan.
            </p>
        </div>

        {{-- Work Order Information --}}
        <section class="rounded-xl border border-ui-border bg-ui-card shadow-sm">
            <div class="px-5 py-4">

                {{-- Header --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center
                           sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">

                            <h1 class="text-base font-semibold text-ui-text">
                                {{ $workOrder->work_order_code }}
                            </h1>

                            @if ($workOrder->status === 'IN_PROGRESS')
                                <span
                                    class="inline-flex items-center rounded-full
                                           bg-blue-50 px-2 py-0.5 text-[10px]
                                           font-semibold text-blue-700">
                                    Sedang Dikerjakan
                                </span>
                            @elseif ($workOrder->status === 'COMPLETED')
                                <span
                                    class="inline-flex items-center gap-1
                                           rounded-full bg-green-50 px-2 py-0.5
                                           text-[10px] font-semibold text-green-700">
                                    <i data-lucide="check-circle" class="h-3.5 w-3.5"></i>

                                    Selesai
                                </span>
                            @endif

                        </div>

                        <p class="mb-2 text-xs text-brand-steel">
                            Booking {{ $workOrder->booking->booking_code }}
                        </p>
                    </div>

                    <a href="{{ route('mechanic.work-orders.index') }}"
                        class="inline-flex items-center justify-center gap-2
                               rounded-lg border border-ui-border px-3 py-2
                               text-xs font-semibold text-brand-navy
                               hover:bg-gray-50 transition">
                        <i data-lucide="arrow-left" class="h-4 w-4"></i>
                        Kembali
                    </a>
                </div>

                <div class="my-5 border-t border-ui-border"></div>

                {{-- Basic Information --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                    <div>
                        <p class="text-[11px] mt-2 uppercase tracking-wide text-brand-steel">
                            Pelanggan
                        </p>

                        <p class="mt-1 text-sm font-medium text-ui-text">
                            {{ $workOrder->booking->customer->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] mt-2 uppercase tracking-wide text-brand-steel">
                            Kendaraan
                        </p>

                        <p class="mt-1 text-sm font-medium text-ui-text">
                            {{ $workOrder->booking->vehicle->license_plate }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] mt-2 uppercase tracking-wide text-brand-steel">
                            Jenis Layanan
                        </p>

                        <p class="mt-1 text-sm font-medium text-ui-text">
                            {{ $workOrder->booking->service_type }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[11px] mt-2 uppercase tracking-wide text-brand-steel">
                            Kendaraan Diterima
                        </p>

                        <p class="mt-1 text-sm font-medium text-ui-text">
                            {{ $workOrder->received_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                </div>

                {{-- Complaint --}}
                @if ($workOrder->booking->complaint)
                    <div class="mt-5">

                        <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                            Keluhan
                        </p>

                        <div
                            class="mt-2 rounded-lg border border-ui-border
                                   bg-gray-50 px-4 py-3">
                            <p class="text-sm leading-6 text-ui-text">
                                {{ $workOrder->booking->complaint }}
                            </p>
                        </div>

                    </div>
                @endif

                {{-- Notes --}}
                @if ($workOrder->notes)
                    <div class="mt-5">

                        <p class="text-[11px] uppercase tracking-wide text-brand-steel">
                            Catatan Work Order
                        </p>

                        <div
                            class="mt-2 rounded-lg border border-ui-border
                                   bg-gray-50 px-4 py-3">
                            <p class="text-sm leading-6 text-ui-text">
                                {{ $workOrder->notes }}
                            </p>
                        </div>

                    </div>
                @endif

            </div>
        </section>

        {{-- Work Items --}}
        <section class="space-y-4">

            {{-- Section Header --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center
                       sm:justify-between gap-4 rounded-xl border
                       border-ui-border bg-ui-card px-5 py-4 shadow-sm">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center
                               justify-center rounded-lg bg-brand-navy/10
                               text-brand-navy">
                        <i data-lucide="wrench" class="h-5 w-5"></i>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">

                            <h2 class="text-base font-semibold text-ui-text">
                                Pekerjaan
                            </h2>

                            <span
                                class="inline-flex items-center rounded-full
                                       bg-gray-100 px-2 py-0.5 text-[10px]
                                       font-semibold text-brand-steel">
                                {{ $workOrder->workItems->count() }}
                            </span>

                        </div>

                        <p class="mt-0.5 text-xs text-brand-steel">
                            Daftar pekerjaan yang harus dikerjakan.
                        </p>
                    </div>

                </div>
            </div>

            {{-- Work Item List --}}
            <div class="space-y-3">

                @forelse ($workOrder->workItems as $workItem)
                    <div class="rounded-xl border border-ui-border
                               bg-ui-card shadow-sm">
                        <div
                            class="flex flex-col sm:flex-row sm:items-center
                                   sm:justify-between gap-4 px-5 py-4">

                            {{-- Work Information --}}
                            <div class="flex items-start gap-3">

                                <div
                                    class="mt-0.5 flex h-9 w-9 shrink-0
                                           items-center justify-center
                                           rounded-lg bg-gray-100
                                           text-brand-navy">
                                    <i data-lucide="wrench" class="h-4 w-4"></i>
                                </div>

                                <div>

                                    <div class="flex items-center gap-2 flex-wrap">

                                        <h3 class="text-sm font-semibold text-ui-text">
                                            {{ $workItem->name }}
                                        </h3>

                                        @if ($workItem->status === 'PENDING')
                                            <span
                                                class="inline-flex items-center
                                                       rounded-full bg-amber-50
                                                       px-2 py-0.5 text-[10px]
                                                       font-semibold text-amber-700">
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
                                                class="inline-flex items-center gap-1
                                                       rounded-full bg-green-50
                                                       px-2 py-0.5 text-[10px]
                                                       font-semibold text-green-700">
                                                <i data-lucide="check" class="h-3 w-3"></i>

                                                Selesai
                                            </span>
                                        @endif

                                    </div>

                                    <div class="mt-1 flex items-center gap-2">

                                        <span class="text-[11px] text-brand-steel">
                                            {{ $workItem->type }}
                                        </span>

                                    </div>

                                    @if ($workItem->description)
                                        <p
                                            class="mt-2 text-xs leading-5
                                                   text-brand-steel">
                                            {{ $workItem->description }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                            {{-- Action --}}
                            <div class="shrink-0">

                                @if ($workItem->status === 'PENDING')
                                    <form method="POST"
                                        action="{{ route('mechanic.work-items.start', $workItem) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="inline-flex w-full sm:w-auto
                                                   items-center justify-center
                                                   gap-2 rounded-lg
                                                   bg-brand-navy px-4 py-2
                                                   text-xs font-semibold
                                                   text-white
                                                   hover:opacity-90 transition">
                                            <i data-lucide="play" class="h-4 w-4"></i>

                                            Mulai Pekerjaan
                                        </button>
                                    </form>
                                @elseif ($workItem->status === 'IN_PROGRESS')
                                    <form method="POST"
                                        action="{{ route('mechanic.work-items.complete', $workItem) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="inline-flex w-full sm:w-auto
                                                   items-center justify-center
                                                   gap-2 rounded-lg
                                                   bg-green-600 px-4 py-2
                                                   text-xs font-semibold
                                                   text-white
                                                   hover:bg-green-700 transition">
                                            <i data-lucide="check" class="h-4 w-4"></i>

                                            Selesaikan Pekerjaan
                                        </button>
                                    </form>
                                @elseif ($workItem->status === 'COMPLETED')
                                    <span
                                        class="inline-flex items-center gap-2
                                               rounded-lg bg-green-50 px-4 py-2
                                               text-xs font-semibold
                                               text-green-700">
                                        <i data-lucide="check-circle" class="h-4 w-4"></i>

                                        Pekerjaan Selesai
                                    </span>
                                @endif

                            </div>

                        </div>
                    </div>

                @empty

                    <div
                        class="rounded-xl border border-dashed
                               border-ui-border bg-ui-card px-5 py-10
                               text-center">
                        <div
                            class="mx-auto flex h-11 w-11 items-center
                                   justify-center rounded-lg bg-gray-100
                                   text-brand-steel">
                            <i data-lucide="clipboard-x" class="h-5 w-5"></i>
                        </div>

                        <h3 class="mt-3 text-sm font-semibold text-ui-text">
                            Belum Ada Pekerjaan
                        </h3>

                        <p class="mt-1 text-xs text-brand-steel">
                            Work Order ini belum memiliki pekerjaan.
                        </p>
                    </div>
                @endforelse

            </div>

        </section>

    </div>
</x-layouts.mechanic>
