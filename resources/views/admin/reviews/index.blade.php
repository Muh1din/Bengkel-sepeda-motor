<x-layouts.admin title="Ulasan Pelanggan">
    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <p class="mt-1 text-sm text-gray-500">
                Kelola dan moderasi ulasan pelanggan Nirwana Garage.
            </p>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Total Ulasan</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $totalReviews }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Menunggu Moderasi</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">
                    {{ $pendingReviews }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Dipublikasikan</p>
                <p class="mt-2 text-3xl font-bold text-green-600">
                    {{ $publishedReviews }}
                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <p class="text-sm text-gray-500">Rating Rata-rata</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $averageRating !== null ? number_format($averageRating, 1) : '0.0' }}
                    <span class="text-lg text-amber-500">★</span>
                </p>
            </div>

        </div>

        {{-- Reviews Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            {{-- Search and Filter --}}
            <form action="{{ route('admin.reviews.index') }}" method="GET"
                class="flex flex-col gap-3 border-b border-gray-200 p-4 md:flex-row">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama pelanggan atau kode booking..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-200">

                <select name="rating"
                    class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-gray-500 focus:outline-none">
                    <option value="">Semua Rating</option>
                    @foreach ([5, 4, 3, 2, 1] as $rating)
                        <option value="{{ $rating }}" @selected(request('rating') == $rating && request('rating') !== null)>
                            {{ $rating }} Bintang
                        </option>
                    @endforeach
                </select>

                <select name="status"
                    class="rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-gray-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>
                        Pending
                    </option>
                    <option value="published" @selected(request('status') === 'published')>
                        Published
                    </option>
                    <option value="hidden" @selected(request('status') === 'hidden')>
                        Hidden
                    </option>
                </select>

                <button type="submit"
                    class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700">
                    Filter
                </button>

                @if (request()->hasAny(['search', 'rating', 'status']))
                    <a href="{{ route('admin.reviews.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">Pelanggan</th>
                            <th class="px-5 py-4 font-semibold">Kode Booking</th>
                            <th class="px-5 py-4 font-semibold">Rating</th>
                            <th class="px-5 py-4 font-semibold">Ulasan</th>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($reviews as $review)
                            <tr class="transition hover:bg-gray-50">

                                {{-- Customer --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-semibold text-gray-900">
                                        {{ $review->customer?->name ?? 'Pelanggan tidak ditemukan' }}
                                    </p>
                                </td>

                                {{-- Booking --}}
                                <td class="whitespace-nowrap px-5 py-4 text-gray-600">
                                    {{ $review->booking?->booking_code ?? '-' }}
                                </td>

                                {{-- Rating --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="text-amber-500">
                                        {{ str_repeat('★', (int) $review->rating) }}
                                    </span>
                                    <span class="text-gray-300">
                                        {{ str_repeat('★', max(0, 5 - (int) $review->rating)) }}
                                    </span>
                                </td>

                                {{-- Comment --}}
                                <td class="max-w-xs px-5 py-4">
                                    <p class="line-clamp-2 text-gray-600">
                                        {{ $review->comment ?: 'Tidak ada komentar.' }}
                                    </p>
                                </td>

                                {{-- Date --}}
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $review->created_at?->format('d M Y') }}
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'published' => 'bg-green-50 text-green-700',
                                            'hidden' => 'bg-gray-100 text-gray-600',
                                        ];

                                        $statusClass = $statusClasses[$review->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex justify-end gap-2">

                                        @if ($review->status !== 'published')
                                            <form action="{{ route('admin.reviews.publish', $review) }}" method="POST"
                                                onsubmit="return confirm('Publikasikan ulasan ini?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                                    Publish
                                                </button>
                                            </form>
                                        @endif

                                        @if ($review->status === 'published')
                                            <form action="{{ route('admin.reviews.hide', $review) }}" method="POST"
                                                onsubmit="return confirm('Sembunyikan ulasan ini?')">
                                                @csrf
                                                @method('PATCH')

                                                <button type="submit"
                                                    class="rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-100">
                                                    Hide
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-700">
                                        Belum ada ulasan pelanggan.
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Ulasan akan muncul di sini setelah pelanggan mengirimkannya.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($reviews->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $reviews->links() }}
                </div>
            @endif

        </div>

    </div>
</x-layouts.admin>
