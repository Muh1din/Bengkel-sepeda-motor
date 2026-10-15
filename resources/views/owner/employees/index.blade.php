<x-layouts.owner title="Manajemen Akun Pegawai">

    <div class="space-y-5">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola akun Service Advisor, Mekanik, dan Admin.
                </p>
            </div>

            <a href="{{ route('owner.employees.create') }}"
                class="inline-flex items-center justify-center rounded-md bg-[#1b263b] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#2d3c55]">
                + Tambah Pegawai
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Ringkasan --}}
        <div>
            <h2 class="mb-3 text-sm font-semibold text-[#1b263b]">
                Ringkasan Pegawai
            </h2>

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

                {{-- Total Pegawai --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Total Pegawai
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-[#1b263b]">
                        {{ number_format($statistics['total']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-[#1b263b]"></div>
                </div>

                {{-- Service Advisor --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Service Advisor
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-blue-700">
                        {{ number_format($statistics['service_advisor']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-blue-600"></div>
                </div>

                {{-- Mekanik --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Mekanik
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-green-700">
                        {{ number_format($statistics['mechanic']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-green-600"></div>
                </div>

                {{-- Admin --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-sm text-gray-500">
                        Admin
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-amber-700">
                        {{ number_format($statistics['admin']) }}
                    </p>

                    <div class="mt-3 h-1 w-10 rounded-full bg-amber-500"></div>
                </div>

            </div>
        </div>

        {{-- Daftar Pegawai --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">

            {{-- Header Tabel --}}
            <div class="border-b border-gray-200 px-4 py-3">
                <h2 class="text-sm font-semibold text-[#1b263b]">
                    Daftar Pegawai
                </h2>
            </div>

            {{-- Pencarian dan Filter --}}
            <div class="flex flex-col gap-3 border-b border-gray-200 p-4 sm:flex-row">

                <div class="flex-1">
                    <label for="searchEmployee" class="sr-only">
                        Cari pegawai
                    </label>

                    <input type="search" id="searchEmployee" placeholder="Cari nama, email, atau nomor telepon..."
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-[#1b263b] focus:outline-none focus:ring-1 focus:ring-[#1b263b]">
                </div>

                <div class="sm:w-52">
                    <label for="filterRole" class="sr-only">
                        Filter role
                    </label>

                    <select id="filterRole"
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-[#1b263b] focus:outline-none focus:ring-1 focus:ring-[#1b263b]">
                        <option value="">Semua Role</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">
                                @switch($role->name)
                                    @case('ServiceAdvisor')
                                        Service Advisor
                                    @break

                                    @case('Mechanic')
                                        Mekanik
                                    @break

                                    @case('Admin')
                                        Admin
                                    @break

                                    @default
                                        {{ $role->name }}
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Pegawai
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Nomor Telepon
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Role
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Terdaftar
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 font-medium">
                                Status
                            </th>

                            <th class="whitespace-nowrap px-4 py-3 text-right font-medium">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody id="employeeTableBody" class="divide-y divide-gray-100">

                        @forelse ($employees as $employee)
                            <tr class="employee-row transition hover:bg-gray-50"
                                data-name="{{ strtolower($employee->name) }}"
                                data-email="{{ strtolower($employee->email) }}"
                                data-phone="{{ strtolower($employee->phone ?? '') }}"
                                data-role="{{ $employee->role?->name }}">
                                {{-- Informasi Pegawai --}}
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">
                                        {{ $employee->name }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $employee->email }}
                                    </p>
                                </td>

                                {{-- Nomor Telepon --}}
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                    {{ $employee->phone ?: '-' }}
                                </td>

                                {{-- Role --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    @switch($employee->role?->name)
                                        @case('ServiceAdvisor')
                                            <span
                                                class="inline-flex rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                                Service Advisor
                                            </span>
                                        @break

                                        @case('Mechanic')
                                            <span
                                                class="inline-flex rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                                Mekanik
                                            </span>
                                        @break

                                        @case('Admin')
                                            <span
                                                class="inline-flex rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700">
                                                Admin
                                            </span>
                                        @break

                                        @default
                                            <span class="text-gray-500">
                                                {{ $employee->role?->name ?? '-' }}
                                            </span>
                                    @endswitch
                                </td>

                                {{-- Tanggal Terdaftar --}}
                                <td class="whitespace-nowrap px-4 py-3 text-gray-600">
                                    {{ $employee->created_at?->format('d/m/Y') ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @if ($employee->is_active)
                                        <span
                                            class="rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Reset Password --}}
                                        <a href="{{ route('owner.employees.reset-password', $employee) }}"
                                            class="inline-flex items-center rounded-lg border border-gray-300
                   bg-white px-3 py-2 text-xs font-semibold text-gray-700
                   transition-colors duration-200 hover:bg-gray-100">
                                            Reset Password
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('owner.employees.edit', $employee) }}"
                                            class="inline-flex items-center rounded-lg bg-[#1b263b]
                                                px-3 py-2 text-xs font-semibold text-white
                                                transition-colors duration-200 hover:bg-[#2d3b54]">
                                            Edit
                                        </a>

                                        {{-- Toggle Status --}}
                                        <form action="{{ route('owner.employees.toggle-status', $employee) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin {{ $employee->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun pegawai ini?')">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                class="inline-flex items-center rounded-lg px-3 py-2
                                                    text-xs font-semibold transition-colors duration-200
                                                    {{ $employee->is_active
                                                        ? 'bg-red-50 text-red-700 hover:bg-red-100'
                                                        : 'bg-green-50 text-green-700 hover:bg-green-100' }}">

                                                {{ $employee->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                        Belum ada data pegawai.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="noSearchResults" class="hidden">
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                    Tidak ada pegawai yang sesuai dengan pencarian.
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($employees->hasPages())
                    <div id="employeePagination" class="border-t border-gray-200 px-4 py-3">
                        {{ $employees->links() }}
                    </div>
                @endif

                {{-- Informasi Jumlah Data --}}
                <div class="border-t border-gray-100 px-4 py-3 text-xs text-gray-500">
                    Menampilkan {{ $employees->firstItem() ?? 0 }}
                    –{{ $employees->lastItem() ?? 0 }}
                    dari {{ $employees->total() }} pegawai.
                </div>

            </div>

        </div>

        {{-- Pencarian dan Filter Client-side --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchEmployee');
                const roleFilter = document.getElementById('filterRole');
                const rows = document.querySelectorAll('.employee-row');
                const noResults = document.getElementById('noSearchResults');
                const pagination = document.getElementById('employeePagination');

                function filterEmployees() {
                    const keyword = searchInput.value.trim().toLowerCase();
                    const selectedRole = roleFilter.value;
                    let visibleCount = 0;

                    rows.forEach(function(row) {
                        const name = row.dataset.name || '';
                        const email = row.dataset.email || '';
                        const phone = row.dataset.phone || '';
                        const role = row.dataset.role || '';

                        const matchesSearch =
                            name.includes(keyword) ||
                            email.includes(keyword) ||
                            phone.includes(keyword);

                        const matchesRole =
                            selectedRole === '' || role === selectedRole;

                        const visible = matchesSearch && matchesRole;

                        row.classList.toggle('hidden', !visible);

                        if (visible) {
                            visibleCount++;
                        }
                    });

                    noResults.classList.toggle('hidden', visibleCount > 0);

                    // Pagination disembunyikan ketika filter client-side aktif.
                    const filterActive = keyword !== '' || selectedRole !== '';

                    if (pagination) {
                        pagination.classList.toggle('hidden', filterActive);
                    }
                }

                searchInput.addEventListener('input', filterEmployees);
                roleFilter.addEventListener('change', filterEmployees);
            });
        </script>

    </x-layouts.owner>
