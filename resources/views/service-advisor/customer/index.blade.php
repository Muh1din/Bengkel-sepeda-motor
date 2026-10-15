<x-layouts.service-advisor title="Pelanggan">

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <p class="mt-1 text-sm text-gray-500">
                Kelola dan lihat informasi pelanggan Nirwana Garage.
            </p>
        </div>

        {{-- Summary --}}
        <div class="rounded-xl border border-ui-border bg-ui-card p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500">
                Total Pelanggan
            </p>

            <p class="mt-1 text-2xl font-bold text-brand-navy">
                {{ $customers->count() }}
            </p>
        </div>

        {{-- Customer List --}}
        <div class="rounded-xl border border-ui-border bg-ui-card shadow-sm">

            {{-- Table Header --}}
            <div class="flex items-center justify-between border-b border-ui-border p-5">

                <div>
                    <h2 class="font-semibold text-ui-text">
                        Daftar Pelanggan
                    </h2>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <input type="text" id="customerSearch" placeholder="Cari pelanggan..."
                        class="w-64 rounded-lg border border-ui-border bg-white px-4 py-2 text-sm
                               outline-none focus:border-brand-steel focus:ring-1 focus:ring-brand-steel">
                </div>

            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="border-b border-ui-border bg-gray-50">
                        <tr>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Pelanggan
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Telepon
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Email
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Alamat
                            </th>

                            <th
                                class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($customers as $customer)
                            <tr class="customer-row transition hover:bg-gray-50"
                                data-search="{{ strtolower(
                                    ($customer->name ?? '') .
                                        ' ' .
                                        ($customer->phone ?? '') .
                                        ' ' .
                                        ($customer->email ?? '') .
                                        ' ' .
                                        ($customer->address ?? ''),
                                ) }}">

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-navy text-white">
                                            <i data-lucide="user" class="h-4 w-4"></i>
                                        </div>

                                        <div>
                                            <p class="font-medium text-ui-text">
                                                {{ $customer->name }}
                                            </p>
                                        </div>

                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $customer->phone }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $customer->email ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $customer->address ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-right">

                                    <a href="{{ route('service-advisor.customer.show', $customer->id) }}"
                                        class="inline-flex items-center gap-2 rounded-lg
                                               border border-ui-border px-3 py-2 text-sm
                                               font-medium text-brand-navy
                                               transition hover:bg-gray-100">
                                        <i data-lucide="user" class="h-4 w-4"></i>
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">

                                    <div class="flex flex-col items-center">

                                        <i data-lucide="users" class="mb-3 h-10 w-10 text-gray-400"></i>

                                        <p class="font-medium text-ui-text">
                                            Belum ada pelanggan
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Data pelanggan akan muncul di sini.
                                        </p>

                                    </div>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts.service-advisor>
