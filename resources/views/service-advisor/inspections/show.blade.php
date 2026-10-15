<x-layouts.service-advisor title="Hasil Inspeksi">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500">
                    Hasil pemeriksaan, diagnosis, dan pekerjaan yang direkomendasikan oleh mekanik.
                </p>
            </div>

        </div>

        {{-- Inspection Status --}}
        <div class="rounded-xl border border-ui-border bg-white shadow-sm">

            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-navy/10">
                        <i data-lucide="clipboard-check" class="h-5 w-5 text-brand-navy"></i>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-ui-text">
                            Hasil Inspeksi Mekanik
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ $inspection->mechanic->name }}
                        </p>
                    </div>

                </div>


                @if ($inspection->status === 'COMPLETED')
                    <span
                        class="inline-flex w-fit items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                        Selesai
                    </span>
                @else
                    <span
                        class="inline-flex w-fit items-center gap-1.5 rounded-full bg-yellow-100 px-3 py-1.5 text-xs font-semibold text-yellow-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                        Dalam Pemeriksaan
                    </span>
                @endif

            </div>

        </div>


        {{-- Booking Information --}}
        <div class="rounded-xl border border-ui-border bg-white shadow-sm">

            <div class="border-b border-ui-border px-5 py-4">

                <div class="flex items-center gap-2">

                    <i data-lucide="calendar-check" class="h-5 w-5 text-brand-gold"></i>

                    <h2 class="text-base font-semibold text-ui-text">
                        Informasi Booking
                    </h2>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <p class="text-xs text-gray-500">
                        Kode Booking
                    </p>

                    <p class="mt-1 text-sm font-semibold text-ui-text">
                        {{ $inspection->booking->booking_code }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Tanggal Booking
                    </p>

                    <p class="mt-1 text-sm font-semibold text-ui-text">
                        {{ \Carbon\Carbon::parse($inspection->booking->booking_date)->format('d M Y') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Waktu Booking
                    </p>

                    <p class="mt-1 text-sm font-semibold text-ui-text">
                        {{ \Carbon\Carbon::parse($inspection->booking->booking_time)->format('H:i') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Jenis Layanan
                    </p>

                    <p class="mt-1 text-sm font-semibold text-ui-text">
                        {{ $inspection->booking->service_type }}
                    </p>
                </div>

            </div>

        </div>


        {{-- Customer & Vehicle --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Customer --}}
            <div class="rounded-xl border border-ui-border bg-white shadow-sm">

                <div class="border-b border-ui-border px-5 py-4">

                    <div class="flex items-center gap-2">

                        <i data-lucide="user" class="h-5 w-5 text-brand-gold"></i>

                        <h2 class="text-base font-semibold text-ui-text">
                            Pelanggan
                        </h2>

                    </div>

                </div>


                <div class="space-y-4 p-5">

                    <div>
                        <p class="text-xs text-gray-500">
                            Nama
                        </p>

                        <p class="mt-1 text-sm font-semibold text-ui-text">
                            {{ $inspection->booking->customer->name }}
                        </p>
                    </div>


                    @if ($inspection->booking->customer->phone)
                        <div>
                            <p class="text-xs text-gray-500">
                                Nomor Telepon
                            </p>

                            <p class="mt-1 text-sm font-semibold text-ui-text">
                                {{ $inspection->booking->customer->phone }}
                            </p>
                        </div>
                    @endif

                </div>

            </div>


            {{-- Vehicle --}}
            <div class="rounded-xl border border-ui-border bg-white shadow-sm">

                <div class="border-b border-ui-border px-5 py-4">

                    <div class="flex items-center gap-2">

                        <i data-lucide="bike" class="h-5 w-5 text-brand-gold"></i>

                        <h2 class="text-base font-semibold text-ui-text">
                            Kendaraan
                        </h2>

                    </div>

                </div>


                <div class="grid grid-cols-2 gap-4 p-5">

                    <div>
                        <p class="text-xs text-gray-500">
                            Nomor Polisi
                        </p>

                        <p class="mt-1 text-sm font-semibold text-ui-text">
                            {{ $inspection->booking->vehicle->plate_number }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-500">
                            Merek
                        </p>

                        <p class="mt-1 text-sm font-semibold text-ui-text">
                            {{ $inspection->booking->vehicle->brand }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-500">
                            Model
                        </p>

                        <p class="mt-1 text-sm font-semibold text-ui-text">
                            {{ $inspection->booking->vehicle->model }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-500">
                            Tahun
                        </p>

                        <p class="mt-1 text-sm font-semibold text-ui-text">
                            {{ $inspection->booking->vehicle->year }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- Inspection Result --}}
        <div class="rounded-xl border border-ui-border bg-white shadow-sm">

            <div class="border-b border-ui-border px-5 py-4">

                <div class="flex items-center gap-2">

                    <i data-lucide="clipboard-list" class="h-5 w-5 text-brand-gold"></i>

                    <div>

                        <h2 class="text-base font-semibold text-ui-text">
                            Hasil Inspeksi
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Hasil pemeriksaan dan diagnosis yang dikirim oleh mekanik.
                        </p>

                    </div>

                </div>

            </div>


            <div class="space-y-6 p-5">

                {{-- Inspection --}}
                <div>

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Hasil Pemeriksaan
                    </p>

                    <div class="rounded-lg bg-ui-main p-4">

                        <p class="whitespace-pre-line text-sm leading-6 text-ui-text">
                            {{ $inspection->inspection ?: 'Tidak ada hasil pemeriksaan.' }}
                        </p>

                    </div>

                </div>


                {{-- Diagnosis --}}
                <div>

                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Diagnosis
                    </p>

                    <div class="rounded-lg bg-ui-main p-4">

                        <p class="whitespace-pre-line text-sm leading-6 text-ui-text">
                            {{ $inspection->diagnosis ?: 'Tidak ada diagnosis.' }}
                        </p>

                    </div>

                </div>


                {{-- Recommendation --}}
                @if ($inspection->recommendation)
                    <div>

                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Rekomendasi Mekanik
                        </p>

                        <div class="rounded-lg border border-brand-gold/30 bg-brand-gold/5 p-4">

                            <div class="flex gap-3">

                                <i data-lucide="lightbulb" class="mt-0.5 h-5 w-5 shrink-0 text-brand-gold"></i>

                                <p class="whitespace-pre-line text-sm leading-6 text-ui-text">
                                    {{ $inspection->recommendation }}
                                </p>

                            </div>

                        </div>

                    </div>
                @endif

            </div>

        </div>


        {{-- Recommended Works --}}
        <div class="rounded-xl border border-ui-border bg-white shadow-sm">

            <div class="border-b border-ui-border px-5 py-4">

                <div class="flex items-center justify-between gap-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-gold/10">
                            <i data-lucide="wrench" class="h-5 w-5 text-brand-gold"></i>
                        </div>

                        <div>
                            <h2 class="text-base font-semibold text-ui-text">
                                Pekerjaan yang Direkomendasikan
                            </h2>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Konfirmasi pekerjaan berdasarkan persetujuan pelanggan.
                            </p>
                        </div>

                    </div>

                    <span class="rounded-full bg-brand-navy/10 px-3 py-1 text-xs font-semibold text-brand-navy">
                        {{ $inspection->recommendedWorks->count() }} Pekerjaan
                    </span>

                </div>

            </div>


            @if ($inspection->recommendedWorks->isNotEmpty())

                <div class="divide-y divide-ui-border">

                    @foreach ($inspection->recommendedWorks as $index => $work)
                        <div class="p-5">

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                                {{-- Work Information --}}
                                <div class="flex gap-3">

                                    <div
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-navy text-xs font-bold text-white">
                                        {{ $index + 1 }}
                                    </div>

                                    <div>

                                        <h3 class="text-sm font-semibold text-ui-text">
                                            {{ $work->name }}
                                        </h3>

                                        @if ($work->description)
                                            <p class="mt-1 text-sm leading-6 text-gray-500">
                                                {{ $work->description }}
                                            </p>
                                        @endif

                                        @if ($work->estimated_cost !== null)
                                            <p class="mt-2 text-sm font-bold text-ui-text">
                                                Rp {{ number_format($work->estimated_cost, 0, ',', '.') }}
                                            </p>
                                        @endif

                                    </div>

                                </div>


                                {{-- Customer Approval --}}
                                <div class="flex flex-col gap-2 lg:items-end">

                                    @if ($work->approval_status === 'PENDING')
                                        <p class="text-xs text-gray-500 lg:text-right">
                                            Hasil komunikasi dengan pelanggan
                                        </p>

                                        <div class="flex flex-wrap gap-2">

                                            {{-- Approve --}}
                                            <form
                                                action="{{ route('service-advisor.recommended-works.approve', $work) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white hover:bg-green-700 transition">
                                                    <i data-lucide="circle-check" class="h-4 w-4"></i>

                                                    Disetujui
                                                </button>

                                            </form>


                                            {{-- Reject --}}
                                            <form
                                                action="{{ route('service-advisor.recommended-works.reject', $work) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                                    <i data-lucide="circle-x" class="h-4 w-4"></i>

                                                    Ditolak
                                                </button>

                                            </form>

                                        </div>
                                    @elseif ($work->approval_status === 'ACCEPTED')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">

                                            <i data-lucide="circle-check" class="h-4 w-4"></i>

                                            Disetujui Pelanggan

                                        </span>
                                    @elseif ($work->approval_status === 'REJECTED')
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700">

                                            <i data-lucide="circle-x" class="h-4 w-4"></i>

                                            Ditolak Pelanggan

                                        </span>
                                    @endif

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                <div class="flex flex-col items-center justify-center px-5 py-12 text-center">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-ui-main">

                        <i data-lucide="wrench" class="h-5 w-5 text-gray-400"></i>

                    </div>

                    <h3 class="mt-3 text-sm font-semibold text-ui-text">
                        Tidak Ada Pekerjaan yang Direkomendasikan
                    </h3>

                    <p class="mt-1 max-w-md text-sm text-gray-500">
                        Mekanik tidak menambahkan pekerjaan tambahan berdasarkan hasil inspeksi.
                    </p>

                </div>

            @endif

        </div>

</x-layouts.service-advisor>
