<x-layouts.admin title="Material">

    <div class="w-full">

        {{-- Toolbar --}}
        <div class="flex items-center justify-between mb-5">

            <div>
                <p class="text-md text-gray-500">
                    Daftar material dan kondisi stok saat ini.
                </p>
            </div>

            <a href="{{ route('admin.materials.create') }}"
                class="inline-flex items-center gap-2
                       px-4 py-2.5
                       rounded-lg
                       bg-brand-navy
                       text-white
                       text-sm font-semibold
                       hover:bg-slate-800
                       transition-colors">
                <span class="text-base leading-none">+</span>
                Tambah Material
            </a>

        </div>


        {{-- Table --}}
        <div class="bg-white rounded-xl border border-ui-border shadow-sm overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    {{-- Table Header --}}
                    <thead class="bg-gray-50 border-b border-ui-border">

                        <tr>

                            <th
                                class="px-5 py-3.5 text-left
                                       text-xs font-semibold
                                       text-gray-600">
                                Nama Material
                            </th>

                            <th
                                class="px-5 py-3.5 text-left
                                       text-xs font-semibold
                                       text-gray-600">
                                Kategori
                            </th>

                            <th
                                class="px-5 py-3.5 text-left
                                       text-xs font-semibold
                                       text-gray-600">
                                Barcode
                            </th>

                            <th
                                class="px-5 py-3.5 text-left
                                       text-xs font-semibold
                                       text-gray-600">
                                Satuan
                            </th>

                            <th
                                class="px-5 py-3.5 text-center
                                       text-xs font-semibold
                                       text-gray-600">
                                Stok
                            </th>

                            <th
                                class="px-5 py-3.5 text-center
                                       text-xs font-semibold
                                       text-gray-600">
                                Minimum
                            </th>

                            <th
                                class="px-5 py-3.5 text-center
                                       text-xs font-semibold
                                       text-gray-600">
                                Status
                            </th>

                            <th
                                class="px-5 py-3.5 text-center 
                                text-xs font-semibold
                                text-gray-600">
                                Action
                            </th>

                        </tr>

                    </thead>


                    {{-- Table Body --}}
                    <tbody class="divide-y divide-ui-border">

                        @forelse ($materials as $material)
                            @php
                                $isOutOfStock = $material->stock <= 0;

                                $isLowStock = $material->stock > 0 && $material->stock <= $material->minimum_stock;
                            @endphp

                            <tr class="hover:bg-gray-50/70 transition-colors">

                                {{-- Nama --}}
                                <td class="px-5 py-4">

                                    <span class="font-semibold text-brand-navy">
                                        {{ $material->name }}
                                    </span>

                                </td>


                                {{-- Kategori --}}
                                <td class="px-5 py-4 text-gray-600">

                                    {{ $material->category?->name ?? '-' }}

                                </td>


                                {{-- Barcode --}}
                                <td class="px-5 py-4">

                                    @if ($material->barcode)
                                        <span class="font-mono text-xs text-gray-600">
                                            {{ $material->barcode }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- Satuan --}}
                                <td class="px-5 py-4 text-gray-600">

                                    {{ $material->unit }}

                                </td>


                                {{-- Stok --}}
                                <td class="px-5 py-4 text-center">

                                    <span
                                        class="font-bold
                                        {{ $isOutOfStock ? 'text-rose-600' : ($isLowStock ? 'text-amber-600' : 'text-brand-navy') }}">
                                        {{ $material->stock }}
                                    </span>

                                </td>


                                {{-- Minimum --}}
                                <td class="px-5 py-4 text-center text-gray-600">

                                    {{ $material->minimum_stock }}

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4 text-center">

                                    @if ($isOutOfStock)
                                        <span
                                            class="inline-flex items-center
                                                   px-2.5 py-1
                                                   rounded-full
                                                   text-[11px]
                                                   font-semibold
                                                   bg-rose-100
                                                   text-rose-700
                                                   border border-rose-200">
                                            Stok Habis
                                        </span>
                                    @elseif ($isLowStock)
                                        <span
                                            class="inline-flex items-center
                                                   px-2.5 py-1
                                                   rounded-full
                                                   text-[11px]
                                                   font-semibold
                                                   bg-amber-100
                                                   text-amber-800
                                                   border border-amber-200">
                                            Stok Rendah
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center
                                                   px-2.5 py-1
                                                   rounded-full
                                                   text-[11px]
                                                   font-semibold
                                                   bg-emerald-100
                                                   text-emerald-800
                                                   border border-emerald-200">
                                            Aman
                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-3">

                                        <a href="{{ route('admin.materials.edit', $material) }}"
                                            class="text-sm font-medium text-brand-navy hover:underline">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.materials.destroy', $material) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus material ini?')"
                                                class="text-sm font-medium text-rose-600 hover:underline">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>


                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-5 py-12 text-center">

                                    <p class="text-sm font-medium text-gray-600">
                                        Belum ada material.
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        Tambahkan material untuk mulai mengelola persediaan.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts.admin>
