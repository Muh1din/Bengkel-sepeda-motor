<x-layouts.admin title="Dashboard Portfolio">
    <div class="space-y-8">

        {{-- Page Header --}}
        <section>
            <p class="mt-2 text-sm text-gray-500">
                Ringkasan dan pengelolaan portfolio proyek Nirwana Garage.
            </p>
        </section>

        {{-- Statistics --}}
        <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Total Projects --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">
                    Total Proyek
                </p>

                <p class="mt-3 text-3xl font-bold text-gray-900">
                    {{ $totalProjects }}
                </p>
            </div>

            {{-- Published Projects --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">
                    Published
                </p>

                <p class="mt-3 text-3xl font-bold text-green-600">
                    {{ $publishedProjects }}
                </p>
            </div>

            {{-- Draft Projects --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">
                    Draft
                </p>

                <p class="mt-3 text-3xl font-bold text-amber-600">
                    {{ $draftProjects }}
                </p>
            </div>

            {{-- Categories --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">
                    Kategori Proyek
                </p>

                <p class="mt-3 text-3xl font-bold text-gray-900">
                    {{ $totalCategories }}
                </p>
            </div>

        </section>

        {{-- Recent Projects --}}
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div
                class="flex flex-col gap-4 border-b border-gray-200 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Proyek Terbaru
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Lima proyek terakhir yang tersimpan.
                    </p>
                </div>
                <a href="{{ route('admin.projects.index') }}"
                    class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                    Lihat Semua Proyek
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">
                                Judul Proyek
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Kategori
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Status
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Tanggal Dibuat
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($recentProjects as $project)
                            <tr class="transition hover:bg-gray-50">

                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $project->title }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $project->category->name }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($project->status === 'published')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $project->created_at->format('d M Y') }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    Belum ada proyek yang tersimpan.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </section>

    </div>
</x-layouts.admin>
