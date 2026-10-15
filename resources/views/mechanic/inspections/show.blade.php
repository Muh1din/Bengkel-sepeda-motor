<x-layouts.mechanic title="Inspeksi Kendaraan">

    @php
        $booking = $inspection->booking;
        $customer = $booking->customer;
        $vehicle = $booking->vehicle;
    @endphp

    <section class="space-y-6">

        {{-- HEADER --}}
        <div>
            <h2 class="text-xl font-bold text-brand-navy">
                Inspeksi Kendaraan
            </h2>

            <p class="text-sm text-brand-steel">
                Lakukan pemeriksaan kendaraan berdasarkan booking yang telah diterima.
            </p>
        </div>

        {{-- simpan draft  --}}
        @if (session('success') || session('error'))
            <div id="flash-message"
                class="fixed top-5 right-5 z-50 flex items-center gap-3 max-w-sm rounded-lg px-4 py-3 text-sm font-medium shadow-lg
            {{ session('success')
                ? 'bg-green-100 text-green-800 border border-green-200'
                : 'bg-red-100 text-red-800 border border-red-200' }}">
                @if (session('success'))
                    <i data-lucide="circle-check" class="w-5 h-5 shrink-0"></i>
                @else
                    <i data-lucide="circle-alert" class="w-5 h-5 shrink-0"></i>
                @endif

                <span>
                    {{ session('success') ?? session('error') }}
                </span>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    setTimeout(() => {
                        const flashMessage = document.getElementById('flash-message');

                        if (flashMessage) {
                            flashMessage.remove();
                        }
                    }, 3000);
                });
            </script>
        @endif

        {{-- Sinpan & kirim ke SA --}}
        @if (session('success') || session('error') || $errors->any())
            @php
                $isSuccess = session('success');
                $message = session('success') ?? (session('error') ?? $errors->first());
            @endphp

            <div id="flash-message"
                class="fixed top-5 right-5 z-50 flex items-center gap-3 max-w-sm rounded-lg px-4 py-3 text-sm font-medium shadow-lg
            {{ $isSuccess
                ? 'bg-green-100 text-green-800 border border-green-200'
                : 'bg-red-100 text-red-800 border border-red-200' }}">
                @if ($isSuccess)
                    <i data-lucide="circle-check" class="w-5 h-5 shrink-0"></i>
                @else
                    <i data-lucide="circle-alert" class="w-5 h-5 shrink-0"></i>
                @endif

                <span>{{ $message }}</span>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    setTimeout(() => {
                        const flashMessage = document.getElementById('flash-message');

                        if (flashMessage) {
                            flashMessage.remove();
                        }
                    }, 3000);
                });
            </script>
        @endif


        {{-- INSPECTION FORM --}}
        <div class="bg-ui-card rounded-lg border border-ui-border shadow-sm">

            {{-- HEADER --}}
            <div class="p-5 border-b border-ui-border">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <p class="text-xs font-semibold text-brand-steel uppercase tracking-wider">
                            Pemeriksaan Kendaraan
                        </p>

                        <h3 class="mt-1 text-lg font-bold text-brand-navy">
                            {{ $booking->booking_code }}
                        </h3>
                    </div>

                    <span
                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Sedang Diinspeksi
                    </span>

                </div>

            </div>

            {{-- VEHICLE INFORMATION --}}
            <div class="p-5 border-b border-ui-border">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    {{-- CUSTOMER --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Pelanggan
                        </p>

                        <p class="mt-1 text-sm font-semibold text-brand-navy">
                            {{ $customer->name }}
                        </p>
                    </div>

                    {{-- VEHICLE --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Kendaraan
                        </p>

                        <p class="mt-1 text-sm font-semibold text-brand-navy">
                            {{ $vehicle->brand }}
                            {{ $vehicle->model }}
                        </p>
                    </div>

                    {{-- LICENSE PLATE --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Nomor Polisi
                        </p>

                        <p class="mt-1 text-sm font-semibold text-brand-navy">
                            {{ $vehicle->license_plate }}
                        </p>
                    </div>

                    {{-- SERVICE TYPE --}}
                    <div>
                        <p class="text-xs text-brand-steel">
                            Jenis Servis
                        </p>

                        <p class="mt-1 text-sm font-semibold text-brand-navy">
                            {{ $booking->service_type }}
                        </p>
                    </div>

                </div>

                {{-- CUSTOMER COMPLAINT --}}
                <div class="mt-5">

                    <p class="text-xs font-semibold text-brand-steel uppercase tracking-wider">
                        Keluhan Pelanggan
                    </p>

                    <div class="mt-2 rounded-lg bg-ui-main border border-ui-border px-4 py-3">

                        <p class="text-sm leading-relaxed text-ui-text">
                            {{ $booking->complaint ?? 'Tidak ada keluhan yang dicatat.' }}
                        </p>

                    </div>

                </div>

            </div>

            {{-- <div class="text-red-500">
                DEBUG URL:
                {{ route('mechanic.inspections.save-draft', ['inspection' => $inspection->id]) }}
            </div> --}}

            {{-- INSPECTION FORM --}}
            <form id="inspection-form" method="POST"
                action="{{ route('mechanic.inspections.save-draft', ['inspection' => $inspection->id]) }}">
                @csrf
                @method('PATCH')

                <div class="p-5 space-y-6">

                    {{-- INSPECTION --}}
                    <div>

                        <label for="inspection" class="block text-sm font-semibold text-brand-navy">
                            Hasil Pemeriksaan
                        </label>

                        <p class="mt-1 text-xs text-brand-steel">
                            Catat kondisi dan temuan kendaraan selama pemeriksaan.
                        </p>

                        <textarea id="inspection" name="inspection" rows="5"
                            class="mt-3 w-full rounded-lg border border-ui-border bg-white px-4 py-3 text-sm text-ui-text outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            placeholder="Contoh: Pemeriksaan bagian mesin menunjukkan adanya getaran pada area CVT...">{{ old('inspection', $inspection->inspection) }}</textarea>

                    </div>

                    {{-- DIAGNOSIS --}}
                    <div>

                        <label for="diagnosis" class="block text-sm font-semibold text-brand-navy">
                            Diagnosis
                        </label>

                        <p class="mt-1 text-xs text-brand-steel">
                            Jelaskan penyebab atau masalah yang ditemukan berdasarkan hasil pemeriksaan.
                        </p>

                        <textarea id="diagnosis" name="diagnosis" rows="5"
                            class="mt-3 w-full rounded-lg border border-ui-border bg-white px-4 py-3 text-sm text-ui-text outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            placeholder="Contoh: Kampas kopling CVT mengalami keausan sehingga menyebabkan getaran...">{{ old('diagnosis', $inspection->diagnosis) }}</textarea>

                    </div>

                    {{-- RECOMMENDATION --}}
                    <div>

                        <label for="recommendation" class="block text-sm font-semibold text-brand-navy">
                            Rekomendasi Umum
                        </label>

                        <p class="mt-1 text-xs text-brand-steel">
                            Tambahkan catatan atau rekomendasi umum berdasarkan hasil diagnosis.
                        </p>

                        <textarea id="recommendation" name="recommendation" rows="4"
                            class="mt-3 w-full rounded-lg border border-ui-border bg-white px-4 py-3 text-sm text-ui-text outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                            placeholder="Contoh: Disarankan melakukan penggantian kampas kopling CVT...">{{ old('recommendation', $inspection->recommendation) }}</textarea>

                    </div>

                    {{-- RECOMMENDED WORKS --}}
                    <div>

                        <div class="flex items-center justify-between gap-4">

                            <div>
                                <h4 class="text-sm font-semibold text-brand-navy">
                                    Pekerjaan yang Direkomendasikan
                                </h4>

                                <p class="mt-1 text-xs text-brand-steel">
                                    Tambahkan pekerjaan yang perlu mendapat persetujuan pelanggan.
                                </p>
                            </div>

                            <button type="button" id="add-work-button"
                                class="px-3 py-2 text-xs font-semibold rounded-lg border border-brand-navy text-brand-navy hover:bg-brand-navy hover:text-white transition">
                                + Tambah Pekerjaan
                            </button>

                        </div>

                        {{-- WORK ITEMS --}}
                        <div id="work-items-container" class="mt-4 space-y-4">

                            {{-- EXISTING WORKS --}}
                            @forelse ($inspection->recommendedWorks as $index => $work)
                                <div class="work-item rounded-xl border border-ui-border p-4">

                                    <div class="flex items-start justify-between gap-4 mb-4">

                                        <h5 class="work-number text-sm font-semibold text-brand-navy">
                                            Pekerjaan #{{ $index + 1 }}
                                        </h5>

                                        <button type="button"
                                            class="remove-work-button text-xs font-semibold text-red-600 hover:text-red-700">
                                            Hapus
                                        </button>

                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        {{-- WORK NAME --}}
                                        <div>

                                            <label class="block text-xs font-semibold text-brand-steel">
                                                Nama Pekerjaan
                                            </label>

                                            <input type="text" name="works[{{ $index }}][name]"
                                                value="{{ old("works.$index.name", $work->name) }}"
                                                class="work-name mt-2 w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                                                placeholder="Contoh: Ganti Kampas Kopling CVT">

                                        </div>

                                        {{-- ESTIMATED COST --}}
                                        <div>

                                            <label class="block text-xs font-semibold text-brand-steel">
                                                Estimasi Biaya
                                            </label>

                                            <input type="number" name="works[{{ $index }}][estimated_cost]"
                                                value="{{ old("works.$index.estimated_cost", $work->estimated_cost) }}"
                                                min="0"
                                                class="work-cost mt-2 w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                                                placeholder="0">

                                        </div>

                                    </div>

                                    {{-- DESCRIPTION --}}
                                    <div class="mt-4">

                                        <label class="block text-xs font-semibold text-brand-steel">
                                            Deskripsi
                                        </label>

                                        <textarea name="works[{{ $index }}][description]" rows="3"
                                            class="work-description mt-2 w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                                            placeholder="Jelaskan pekerjaan yang direkomendasikan...">{{ old("works.$index.description", $work->description) }}</textarea>

                                    </div>

                                </div>

                            @empty

                                <div id="empty-work-message"
                                    class="rounded-xl border border-dashed border-ui-border p-6 text-center">

                                    <p class="text-sm text-brand-steel">
                                        Belum ada pekerjaan yang direkomendasikan.
                                    </p>

                                </div>
                            @endforelse

                        </div>

                        {{-- TEMPLATE --}}
                        <template id="work-item-template">

                            <div class="work-item rounded-xl border border-ui-border p-4">

                                <div class="flex items-start justify-between gap-4 mb-4">

                                    <h5 class="work-number text-sm font-semibold text-brand-navy">
                                        Pekerjaan #1
                                    </h5>

                                    <button type="button"
                                        class="remove-work-button text-xs font-semibold text-red-600 hover:text-red-700">
                                        Hapus
                                    </button>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    {{-- WORK NAME --}}
                                    <div>

                                        <label class="block text-xs font-semibold text-brand-steel">
                                            Nama Pekerjaan
                                        </label>

                                        <input type="text"
                                            class="work-name mt-2 w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                                            placeholder="Contoh: Ganti Kampas Kopling CVT">

                                    </div>

                                    {{-- ESTIMATED COST --}}
                                    <div>

                                        <label class="block text-xs font-semibold text-brand-steel">
                                            Estimasi Biaya
                                        </label>

                                        <input type="number" min="0"
                                            class="work-cost mt-2 w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                                            placeholder="0">

                                    </div>

                                </div>

                                {{-- DESCRIPTION --}}
                                <div class="mt-4">

                                    <label class="block text-xs font-semibold text-brand-steel">
                                        Deskripsi
                                    </label>

                                    <textarea rows="3"
                                        class="work-description mt-2 w-full rounded-lg border border-ui-border px-3 py-2.5 text-sm outline-none focus:border-brand-navy focus:ring-1 focus:ring-brand-navy"
                                        placeholder="Jelaskan pekerjaan yang direkomendasikan..."></textarea>

                                </div>

                            </div>

                        </template>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-4 bg-ui-main border-t border-ui-border flex items-center justify-end gap-3">

                    {{-- Simpan Draft --}}
                    <button type="submit"
                        class="rounded-lg border border-brand-navy px-4 py-2 text-sm font-semibold text-brand-navy hover:bg-gray-50 transition">
                        Simpan Draft
                    </button>

                    {{-- Selesaikan & Kirim ke SA --}}
                    <button type="submit"
                        formaction="{{ route('mechanic.inspections.complete', ['inspection' => $inspection->id]) }}"
                        class="rounded-lg bg-brand-navy px-4 py-2 text-sm font-semibold text-white hover:opacity-90 transition"
                        onclick="return confirm('Yakin ingin menyelesaikan inspeksi dan mengirim hasilnya ke Service Advisor?')">
                        Selesaikan & Kirim ke SA
                    </button>

                </div>

            </form>

        </div>

    </section>

</x-layouts.mechanic>
