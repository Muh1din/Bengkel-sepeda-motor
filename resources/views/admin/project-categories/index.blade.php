<x-layouts.admin title="Kategori Proyek">
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-brand-steel">
                    Kelola kategori untuk mengelompokkan proyek portofolio bengkel.
                </p>
            </div>

            <a
                href="{{ route('admin.project-categories.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-brand-navy px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-steel"
            >
                + Tambah Kategori
            </a>
        </div>

        {{-- Notifikasi Berhasil --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi Gagal --}}
        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validasi Error --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Terjadi kesalahan:</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel Kategori --}}
        <div class="overflow-hidden rounded-xl border border-ui-border bg-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-ui-border bg-gray-50 text-xs uppercase text-brand-steel">
                        <tr>
                            <th class="px-6 py-4 font-semibold">No.</th>
                            <th class="px-6 py-4 font-semibold">Nama Kategori</th>
                            <th class="px-6 py-4 font-semibold">Slug</th>
                            <th class="px-6 py-4 text-center font-semibold">Jumlah Proyek</th>
                            <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-ui-border">
                        @forelse ($categories as $category)
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-brand-steel">
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 font-medium text-ui-text">
                                    {{ $category->name }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-brand-steel">
                                    {{ $category->slug }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                        {{ $category->projects_count }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a
                                            href="{{ route('admin.project-categories.edit', $category) }}"
                                            class="font-semibold text-brand-steel transition hover:text-brand-navy"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.project-categories.destroy', $category) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                @disabled($category->projects_count > 0)
                                                class="font-semibold text-red-600 transition hover:text-red-800 disabled:cursor-not-allowed disabled:opacity-40"
                                                title="{{ $category->projects_count > 0 ? 'Kategori masih digunakan oleh proyek' : 'Hapus kategori' }}"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="font-medium text-ui-text">
                                        Belum ada kategori proyek.
                                    </p>

                                    <p class="mt-1 text-sm text-brand-steel">
                                        Tambahkan kategori untuk mulai mengelompokkan portofolio.
                                    </p>

                                    <a
                                        href="{{ route('admin.project-categories.create') }}"
                                        class="mt-4 inline-flex font-semibold text-brand-navy hover:text-brand-steel"
                                    >
                                        + Tambah Kategori
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($categories->hasPages())
                <div class="border-t border-ui-border px-6 py-4">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>