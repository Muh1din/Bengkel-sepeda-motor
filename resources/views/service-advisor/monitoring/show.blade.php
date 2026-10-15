<x-layouts.service-advisor title="Monitoring Work Order">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-semibold text-ui-text">
                Monitoring Work Order
            </h1>

            <p class="text-sm text-ui-steel mt-1">
                Pantau progres pekerjaan kendaraan yang sedang ditangani mekanik.
            </p>
        </div>

        {{-- Work Order Information --}}
        <div class="bg-ui-card border border-ui-border rounded-lg p-5">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-xs text-ui-steel">
                        Work Order
                    </p>

                    <h2 class="text-lg font-semibold text-brand-navy">
                        {{ $workOrder->work_order_code }}
                    </h2>
                </div>

                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-ui-text">
                    {{ $workOrder->status }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                {{-- Booking --}}
                <div>
                    <p class="text-xs text-ui-steel mb-1">
                        Booking
                    </p>

                    <p class="text-sm font-medium text-ui-text">
                        {{ $workOrder->booking->booking_code }}
                    </p>
                </div>

                {{-- Customer --}}
                <div>
                    <p class="text-xs text-ui-steel mb-1">
                        Customer
                    </p>

                    <p class="text-sm font-medium text-ui-text">
                        {{ $workOrder->booking->customer->name }}
                    </p>
                </div>

                {{-- Vehicle --}}
                <div>
                    <p class="text-xs text-ui-steel mb-1">
                        Kendaraan
                    </p>

                    <p class="text-sm font-medium text-ui-text">
                        {{ $workOrder->booking->vehicle->license_plate }}
                    </p>
                </div>

                {{-- Mechanic --}}
                <div>
                    <p class="text-xs text-ui-steel mb-1">
                        Mekanik
                    </p>

                    <p class="text-sm font-medium text-ui-text">
                        {{ $workOrder->mechanic->name ?? '-' }}
                    </p>
                </div>

            </div>
        </div>


        {{-- Service Progress --}}
        <div class="bg-ui-card border border-ui-border rounded-lg p-5">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-6">

                {{-- Title --}}
                <div>
                    <h2 class="text-base font-semibold text-ui-text">
                        Progres Servis
                    </h2>

                    <p class="text-xs text-ui-steel mt-1">
                        Progres pekerjaan berdasarkan status Work Order.
                    </p>
                </div>

                {{-- Back --}}
                <div>
                    <a href="{{ route('service-advisor.monitoring.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-ui-text bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                        Kembali
                    </a>
                </div>

            </div>

            @php
                $statuses = [
                    'RECEIVED' => 'Kendaraan Diterima',
                    'INSPECTION' => 'Pemeriksaan',
                    'DISASSEMBLY' => 'Pembongkaran',
                    'IN_PROGRESS' => 'Proses Servis',
                    'ASSEMBLY' => 'Pemasangan',
                    'FINAL_CHECK' => 'Pemeriksaan Akhir',
                    'COMPLETED' => 'Selesai',
                ];

                $statusKeys = array_keys($statuses);
                $currentIndex = array_search($workOrder->status, $statusKeys);
            @endphp

            <div class="space-y-5">

                @foreach ($statuses as $status => $label)
                    @php
                        $index = array_search($status, $statusKeys);

                        $isCompleted = $currentIndex !== false && $index < $currentIndex;

                        $isCurrent = $currentIndex !== false && $index === $currentIndex;
                    @endphp

                    <div class="flex items-start gap-4">

                        {{-- Indicator --}}
                        <div class="flex flex-col items-center">

                            <div
                                class="
                            w-8 h-8 rounded-full
                            flex items-center justify-center
                            text-xs font-semibold
                            border

                            {{ $isCompleted
                                ? 'bg-brand-navy text-white border-brand-navy'
                                : ($isCurrent
                                    ? 'bg-brand-gold text-white border-brand-gold'
                                    : 'bg-white text-ui-steel border-ui-border') }}
                        ">
                                @if ($isCompleted)
                                    ✓
                                @elseif ($isCurrent)
                                    ●
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>

                            @if (!$loop->last)
                                <div class="w-px h-8 bg-ui-border"></div>
                            @endif

                        </div>

                        {{-- Information --}}
                        <div class="pt-1">

                            <p
                                class="
                            text-sm font-semibold
                            {{ $isCurrent ? 'text-brand-gold' : 'text-ui-text' }}
                        ">
                                {{ $label }}
                            </p>

                            <p class="text-xs text-ui-steel mt-1">

                                @if ($isCompleted)
                                    Tahap telah selesai.
                                @elseif ($isCurrent)
                                    Tahap sedang dikerjakan.
                                @else
                                    Tahap belum dimulai.
                                @endif

                            </p>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>


        {{-- Work Order Notes --}}
        <div class="bg-ui-card border border-ui-border rounded-lg p-5">

            <div class="mb-4">
                <h2 class="text-base font-semibold text-ui-text">
                    Catatan Work Order
                </h2>

                <p class="text-xs text-ui-steel mt-1">
                    Catatan yang berkaitan dengan pengerjaan kendaraan.
                </p>
            </div>

            <div class="bg-ui-main border border-ui-border rounded-md p-4">

                <p class="text-sm text-ui-text whitespace-pre-line">
                    {{ $workOrder->notes ?? 'Belum ada catatan.' }}
                </p>

            </div>

        </div>
    </div>

</x-layouts.service-advisor>
