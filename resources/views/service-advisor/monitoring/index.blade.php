<x-layouts.service-advisor title="Monitoring">

    <div class="space-y-6">

        <div>
            <p class="text-sm text-ui-steel mt-1">
                Pantau progres pekerjaan kendaraan yang sedang ditangani mekanik.
            </p>
        </div>

        

        {{-- Table --}}
        <div class="bg-ui-card border border-ui-border rounded-lg overflow-hidden">

            <table class="w-full">

                <thead class="bg-ui-main border-b border-ui-border">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold">
                            Work Order
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold">
                            Customer
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold">
                            Kendaraan
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold">
                            Mekanik
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold">
                            Status
                        </th>

                        <th class="px-4 py-3 text-right text-xs font-semibold">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody id="monitoring-table-body">

                    @forelse ($workOrders as $workOrder)
                        <tr data-work-order-code="{{ $workOrder->work_order_code }}"
                            data-customer-name="{{ $workOrder->booking->customer->name }}"
                            data-license-plate="{{ $workOrder->booking->vehicle->license_plate }}"
                            data-status="{{ $workOrder->status }}" class="border-b border-ui-border last:border-0">

                            <td class="px-4 py-3 text-sm font-semibold text-brand-navy">
                                {{ $workOrder->work_order_code }}
                            </td>

                            <td class="px-4 py-3 text-sm text-ui-text">
                                {{ $workOrder->booking->customer->name }}
                            </td>

                            <td class="px-4 py-3 text-sm text-ui-text">
                                {{ $workOrder->booking->vehicle->license_plate }}
                            </td>

                            <td class="px-4 py-3 text-sm text-ui-text">
                                {{ $workOrder->mechanic->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="text-xs font-semibold">
                                    {{ $workOrder->status }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('service-advisor.monitoring.show', ['workOrder' => $workOrder->id]) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-brand-navy bg-white border border-ui-border rounded-md hover:bg-gray-50 transition-colors">
                                    Monitoring
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-ui-steel">
                                Tidak ada Work Order yang sedang berjalan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-layouts.service-advisor>
