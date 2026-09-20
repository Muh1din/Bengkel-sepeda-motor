<x-layouts.customer title="Edit Kendaraan">

    <div class="max-w-3xl space-y-6">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl font-bold text-brand-navy tracking-tight">
                Edit Kendaraan
            </h1>
            <p class="mt-1 text-sm text-brand-steel">
                Perbarui informasi data kendaraan Anda.
            </p>
        </div>

        <!-- Form Card Container -->
        <div class="bg-ui-card border border-ui-border rounded-xl p-5 sm:p-7 shadow-xs">
            <form action="{{ route('customer.vehicles.update', $vehicle->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Grid 2 Kolom di Desktop, 1 Kolom di Mobile -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                    <!-- Plat Nomor (Full width pada breakpoint kecil) -->
                    <div class="sm:col-span-2">
                        <label for="license_plate"
                            class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-brand-navy">
                            Plat Nomor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="license_plate" name="license_plate"
                            value="{{ old('license_plate', $vehicle->license_plate) }}"
                            class="w-full rounded-lg border border-ui-border bg-ui-main/50 px-3.5 py-2.5 text-sm text-ui-text font-mono uppercase placeholder:text-brand-silver placeholder:font-sans focus:border-brand-navy focus:bg-ui-card focus:outline-none focus:ring-1 focus:ring-brand-navy transition-all @error('license_plate') border-red-500 bg-red-50/20 @enderror"
                            placeholder="B 1234 XYZ" required>
                        @error('license_plate')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Merk / Brand -->
                    <div>
                        <label for="brand"
                            class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-brand-navy">
                            Merk Kendaraan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                            class="w-full rounded-lg border border-ui-border bg-ui-main/50 px-3.5 py-2.5 text-sm text-ui-text placeholder:text-brand-silver focus:border-brand-navy focus:bg-ui-card focus:outline-none focus:ring-1 focus:ring-brand-navy transition-all @error('brand') border-red-500 bg-red-50/20 @enderror"
                            placeholder="Contoh: Honda, Yamaha" required>
                        @error('brand')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model"
                            class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-brand-navy">
                            Model / Tipe <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="model" name="model" value="{{ old('model', $vehicle->model) }}"
                            class="w-full rounded-lg border border-ui-border bg-ui-main/50 px-3.5 py-2.5 text-sm text-ui-text placeholder:text-brand-silver focus:border-brand-navy focus:bg-ui-card focus:outline-none focus:ring-1 focus:ring-brand-navy transition-all @error('model') border-red-500 bg-red-50/20 @enderror"
                            placeholder="Contoh: Vario 160, NMAX" required>
                        @error('model')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Pembuatan -->
                    <div>
                        <label for="year"
                            class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-brand-navy">
                            Tahun Pembuatan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="year" name="year" value="{{ old('year', $vehicle->year) }}"
                            min="1900" max="{{ date('Y') }}"
                            class="w-full rounded-lg border border-ui-border bg-ui-main/50 px-3.5 py-2.5 text-sm text-ui-text placeholder:text-brand-silver focus:border-brand-navy focus:bg-ui-card focus:outline-none focus:ring-1 focus:ring-brand-navy transition-all @error('year') border-red-500 bg-red-50/20 @enderror"
                            placeholder="2024" required>
                        @error('year')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Warna Kendaraan -->
                    <div>
                        <label for="color"
                            class="block mb-1.5 text-xs font-semibold uppercase tracking-wider text-brand-navy">
                            Warna Kendaraan
                        </label>
                        <input type="text" id="color" name="color" value="{{ old('color', $vehicle->color) }}"
                            class="w-full rounded-lg border border-ui-border bg-ui-main/50 px-3.5 py-2.5 text-sm text-ui-text placeholder:text-brand-silver focus:border-brand-navy focus:bg-ui-card focus:outline-none focus:ring-1 focus:ring-brand-navy transition-all @error('color') border-red-500 bg-red-50/20 @enderror"
                            placeholder="Contoh: Hitam Doff, Merah">
                        @error('color')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-ui-border/60">
                    <a href="{{ route('customer.vehicles.index') }}"
                        class="px-4 py-2 border border-ui-border rounded-lg text-sm font-medium text-brand-navy hover:bg-ui-main transition-colors">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-5 py-2 bg-brand-navy hover:bg-slate-800 text-white rounded-lg text-sm font-medium transition-colors active:scale-[0.98]">
                        Perbarui Kendaraan
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-layouts.customer>
