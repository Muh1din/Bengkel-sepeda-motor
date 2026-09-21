<x-layouts.customer title="Kendaraan Saya">

    <div class="space-y-6">

        {{-- PAGE HEADER --}}

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4">

            <div>

                <h3 class="text-lg font-semibold text-brand-navy">
                    Daftar Kendaraan
                </h3>

                <p class="text-xs text-brand-steel mt-1">
                    Kelola kendaraan sepeda motor yang terdaftar pada akun Anda.
                </p>

            </div>

            <a href="{{ route('customer.vehicles.create') }}"
                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-2 bg-brand-navy hover:bg-slate-800 text-white text-sm font-medium rounded-md transition-colors">
                <i data-lucide="plus" class="w-4 h-4 text-brand-gold"></i>

                <span>Tambah Kendaraan</span>
            </a>

        </div>


        {{-- VEHICLES --}}

        <div id="vehicles-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse ($vehicles as $vehicle)
                <div class="bg-ui-card rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">

                    {{-- CARD HEADER --}}

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0">

                                <p class="text-xs font-medium text-brand-steel uppercase tracking-wide">
                                    {{ $vehicle->brand }}
                                </p>

                                <h4 class="text-base font-semibold text-brand-navy mt-1 truncate">
                                    {{ $vehicle->model }}
                                </h4>

                            </div>

                            <span
                                class="shrink-0 font-mono text-xs font-medium text-brand-navy bg-ui-main rounded-md px-2.5 py-1">
                                {{ $vehicle->license_plate }}
                            </span>

                        </div>

                    </div>


                    {{-- CARD DETAIL --}}

                    <div class="px-4 py-3">

                        <div class="flex items-center justify-between text-sm">

                            <span class="text-xs text-brand-steel">
                                Tahun Pembuatan
                            </span>

                            <span class="text-xs font-medium text-brand-navy">
                                {{ $vehicle->year }}
                            </span>

                        </div>

                    </div>


                    {{-- CARD ACTION --}}

                    <div class="flex items-center justify-end gap-2 px-4 py-3 bg-ui-main/50">

                        <a href="{{ route('customer.vehicles.edit', $vehicle->id) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-navy text-white text-xs font-medium rounded-md hover:bg-brand-gold transition-colors">
                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>

                            <span>Edit</span>
                        </a>

                        <form action="{{ route('customer.vehicles.destroy', $vehicle->id) }}" method="POST"
                            class="m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-brand-silver/20 text-brand-steel text-xs font-medium rounded-md hover:bg-brand-silver/40 transition-colors">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>

                                <span>Hapus</span>
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                {{-- EMPTY STATE --}}

                <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-ui-card rounded-lg p-8 text-center shadow-sm">

                    <div class="w-10 h-10 mx-auto rounded-full bg-ui-main flex items-center justify-center">
                        <i data-lucide="bike" class="w-5 h-5 text-brand-steel"></i>
                    </div>

                    <h4 class="text-sm font-semibold text-brand-navy mt-4">
                        Belum Ada Kendaraan
                    </h4>

                    <p class="text-xs text-brand-steel mt-1 max-w-sm mx-auto">
                        Anda belum memiliki kendaraan yang terdaftar pada sistem.
                    </p>
                </div>
            @endforelse

        </div>

    </div>

</x-layouts.customer>
