<x-layouts.admin title="Permintaan Material">

    <main class="w-full space-y-6">

        {{-- PAGE HEADER --}}
        <div class="border-b border-ui-border pb-4">
            <p class="mt-1 text-sm text-ui-steel">
                Kelola permintaan material dari mekanik dan proses pengeluaran sesuai Work Order.
            </p>
        </div>

        {{-- STATUS FILTER --}}
        <div class="flex items-center gap-2">

            <a href="{{ route('admin.material-requests.index') }}"
                class="rounded-lg border px-4 py-2 text-sm font-medium
            {{ !request('status')
                ? 'border-brand-navy bg-brand-navy text-white'
                : 'border-ui-border bg-ui-card text-ui-steel hover:bg-ui-main hover:text-brand-navy' }}">
                Semua
            </a>

            <a href="{{ route('admin.material-requests.index', ['status' => 'PENDING']) }}"
                class="rounded-lg border px-4 py-2 text-sm font-medium
            {{ request('status') === 'PENDING'
                ? 'border-brand-navy bg-brand-navy text-white'
                : 'border-ui-border bg-ui-card text-ui-steel hover:bg-ui-main hover:text-brand-navy' }}">
                Menunggu
            </a>

            <a href="{{ route('admin.material-requests.index', ['status' => 'FULFILLED']) }}"
                class="rounded-lg border px-4 py-2 text-sm font-medium
            {{ request('status') === 'FULFILLED'
                ? 'border-brand-navy bg-brand-navy text-white'
                : 'border-ui-border bg-ui-card text-ui-steel hover:bg-ui-main hover:text-brand-navy' }}">
                Dipenuhi
            </a>

        </div>

        {{-- REQUEST TABLE --}}
        <div class="overflow-hidden rounded-xl border border-ui-border bg-ui-card">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-ui-border">

                    <thead class="bg-ui-main">

                        <tr>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-ui-steel">
                                Work Order
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-ui-steel">
                                Mekanik
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-ui-steel">
                                Material
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-ui-steel">
                                Status
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-ui-steel">
                                Diajukan
                            </th>

                            <th
                                class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-ui-steel">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($materialRequests as $materialRequest)

                            <tr class="align-top hover:bg-ui-main/50">

                                {{-- WORK ORDER --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="font-medium text-brand-navy">
                                        {{ $materialRequest->workOrder->work_order_code }}
                                    </span>

                                </td>

                                {{-- MECHANIC --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <span class="text-sm text-ui-text">
                                        {{ $materialRequest->mechanic->name }}
                                    </span>

                                </td>

                                {{-- MATERIAL --}}
                                <td class="px-6 py-4">

                                    <div class="space-y-1">

                                        @foreach ($materialRequest->items as $item)
                                            <div class="flex items-center gap-2 text-sm">

                                                <span class="font-medium text-ui-text">
                                                    {{ $item->material->name }}
                                                </span>

                                                <span class="text-ui-steel">
                                                    {{ $item->quantity }}
                                                    {{ $item->material->unit }}
                                                </span>

                                            </div>
                                        @endforeach

                                    </div>

                                    <p class="mt-2 text-xs text-ui-steel">
                                        {{ $materialRequest->items->count() }}
                                        {{ $materialRequest->items->count() === 1 ? 'item' : 'item' }}
                                    </p>

                                </td>

                                {{-- STATUS --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @if ($materialRequest->status === 'PENDING')
                                        <span
                                            class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Menunggu
                                        </span>
                                    @elseif ($materialRequest->status === 'FULFILLED')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Dipenuhi
                                        </span>
                                    @endif

                                </td>

                                {{-- CREATED AT --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-ui-steel">

                                    {{ $materialRequest->created_at->format('d M Y, H:i') }}

                                </td>

                                {{-- ACTION --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="flex justify-end gap-2">

                                        {{-- DETAIL --}}
                                        <button type="button"
                                            class="material-request-detail inline-flex items-center rounded-lg border border-ui-border px-3 py-2 text-sm font-medium text-brand-navy hover:bg-ui-main"
                                            data-request-id="{{ $materialRequest->id }}">
                                            Lihat
                                        </button>

                                        {{-- PROCESS --}}
                                        @if ($materialRequest->status === 'PENDING')
                                            <form method="POST"
                                                action="{{ route('admin.material-requests.fulfill', $materialRequest) }}">

                                                @csrf

                                                <button type="submit"
                                                    onclick="return confirm('Proses pengeluaran semua material dari request ini?')"
                                                    class="inline-flex items-center rounded-lg bg-brand-navy px-3 py-2 text-sm font-medium text-white hover:opacity-90">
                                                    Proses Pengeluaran
                                                </button>

                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="px-6 py-12 text-center">

                                    <p class="text-sm font-medium text-ui-text">
                                        Belum ada permintaan material.
                                    </p>

                                    <p class="mt-1 text-sm text-ui-steel">
                                        Permintaan material dari mekanik akan muncul di sini.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            @if ($materialRequests->hasPages())
                <div class="border-t border-ui-border px-6 py-4">
                    {{ $materialRequests->links() }}
                </div>
            @endif

        </div>

        {{-- HIDDEN MATERIAL REQUEST DETAILS --}}

        @foreach ($materialRequests as $materialRequest)
            <div id="material-request-detail-{{ $materialRequest->id }}"
                data-work-order="{{ $materialRequest->workOrder->work_order_code }}" class="hidden">

                <div class="space-y-4">

                    {{-- MECHANIC --}}

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-ui-steel">
                            Mekanik
                        </p>

                        <p class="mt-1 text-sm font-medium text-ui-text">
                            {{ $materialRequest->mechanic->name }}
                        </p>
                    </div>

                    {{-- MATERIALS --}}

                    <div>

                        <p class="mb-3 text-sm font-semibold text-ui-text">
                            Material yang Diminta
                        </p>

                        <div class="space-y-3">

                            @foreach ($materialRequest->items as $item)
                                <div class="rounded-lg border border-ui-border bg-ui-main p-4">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>

                                            <p class="text-sm font-medium text-ui-text">
                                                {{ $item->material->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-ui-steel">
                                                Barcode:
                                                {{ $item->material->barcode ?: '-' }}
                                            </p>

                                        </div>

                                        <div class="text-right">

                                            <p class="text-sm font-semibold text-brand-navy">
                                                {{ $item->quantity }}
                                                {{ $item->material->unit }}
                                            </p>

                                            <p class="mt-1 text-xs text-ui-steel">
                                                Stock:
                                                {{ $item->material->stock }}
                                            </p>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                    {{-- NOTES --}}

                    @if ($materialRequest->notes)
                        <div class="border-t border-ui-border pt-4">

                            <p class="text-xs font-medium uppercase tracking-wide text-ui-steel">
                                Catatan
                            </p>

                            <p class="mt-1 text-sm text-ui-text">
                                {{ $materialRequest->notes }}
                            </p>

                        </div>
                    @endif

                </div>

            </div>
        @endforeach

        {{-- MATERIAL REQUEST DETAIL MODAL --}}
        <div id="material-request-modal" class="fixed inset-0 z-50 hidden">

            {{-- BACKDROP --}}
            <div id="material-request-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

            {{-- MODAL --}}
            ```blade
            <div class="relative flex min-h-full items-center justify-center p-4">

                <div class="relative w-full max-w-2xl rounded-xl border border-ui-border bg-ui-card shadow-xl">

                    {{-- HEADER --}}
                    <div class="flex items-center justify-between border-b border-ui-border px-6 py-4">

                        <div>
                            <h2 class="text-lg font-semibold text-brand-navy">
                                Detail Permintaan Material
                            </h2>

                            <p id="material-request-modal-work-order" class="mt-1 text-sm text-ui-steel"></p>
                        </div>

                        <button type="button" id="material-request-modal-close"
                            class="rounded-lg p-2 text-ui-steel hover:bg-ui-main hover:text-ui-text">
                            &times;
                        </button>

                    </div>

                    {{-- CONTENT --}}
                    <div id="material-request-modal-content" class="max-h-[70vh] overflow-y-auto px-6 py-5">
                    </div>

                    {{-- FOOTER --}}
                    <div class="flex justify-end border-t border-ui-border px-6 py-4">
                        <button type="button" id="material-request-modal-close-footer"
                            class="rounded-lg border border-ui-border px-4 py-2 text-sm font-medium text-ui-text hover:bg-ui-main">
                            Tutup
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </main>

    @push('scripts')
        @vite('resources/js/admin/material-requests.js')
    @endpush

</x-layouts.admin>
