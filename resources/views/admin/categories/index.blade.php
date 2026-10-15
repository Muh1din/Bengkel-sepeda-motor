<x-layouts.admin title="Kategori Material">

    <div class="w-full">

        <div class=" pb-4">
            <p class="mt-1 text-sm text-ui-steel"> Kelola kategori untuk mengelompokkan material dalam inventory. </p>
        </div>

        {{-- Action --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center gap-2 rounded-lg
                       bg-brand-navy px-4 py-2.5
                       text-sm font-semibold text-white
                       hover:bg-slate-800">
                <span class="text-base leading-none">+</span>
                Tambah Kategori
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-ui-border bg-ui-card">

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="border-b border-ui-border bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                Nama Kategori
                            </th>

                            <th class="px-5 py-3 text-left font-semibold text-gray-700">
                                Deskripsi
                            </th>

                            <th class="px-5 py-3 text-right font-semibold text-gray-700">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-ui-border">

                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50">

                                {{-- Name --}}
                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $category->name }}
                                </td>

                                {{-- Description --}}
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $category->description ?: '-' }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="rounded-lg border border-ui-border
                                                   px-3 py-1.5 text-xs font-medium
                                                   text-gray-600
                                                   hover:bg-gray-50">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="rounded-lg border border-red-200
                                                       px-3 py-1.5 text-xs font-medium
                                                       text-red-600
                                                       hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-500">
                                    Belum ada kategori material.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-layouts.admin>
