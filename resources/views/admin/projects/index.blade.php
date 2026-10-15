<x-layouts.admin title="Semua Proyek">
    <div class="space-y-6">

        {{-- Header --}}
        <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola portfolio hasil pengerjaan Nirwana Garage.
                </p>
            </div>

            <a href="{{ route('admin.projects.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                + Tambah Proyek
            </a>
        </section>

        {{-- Search and Filter --}}
        <section class="rounded-xl border border-gray-200 bg-white p-5">
            <form action="{{ route('admin.projects.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul atau kendaraan..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:outline-none">

                <select name="status"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:outline-none">
                    <option value="">Semua Status</option>

                    <option value="draft" @selected(request('status') === 'draft')>
                        Draft
                    </option>

                    <option value="published" @selected(request('status') === 'published')>
                        Published
                    </option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                    Cari
                </button>

                @if (request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.projects.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>
        </section>

        {{-- Projects Table --}}
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Proyek</th>
                            <th class="px-6 py-4 font-semibold">Kendaraan</th>
                            <th class="px-6 py-4 font-semibold">Kategori</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($projects as $project)
                            <tr class="transition hover:bg-gray-50">

                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900">
                                        {{ $project->title }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $project->slug }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $project->vehicle_name }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $project->category->name }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($project->status === 'published')
                                        <span
                                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                    {{ $project->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.projects.edit', $project) }}"
                                            class="font-semibold text-blue-600 hover:text-blue-800">
                                            Edit
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus proyek ini beserta semua gambarnya?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="font-semibold text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <p class="font-semibold text-gray-900">
                                        Belum Ada Proyek
                                    </p>

                                    <p class="mt-2 text-sm text-gray-500">
                                        Proyek yang dibuat akan ditampilkan di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($projects->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $projects->links() }}
                </div>
            @endif

        </section>

    </div>
</x-layouts.admin>
