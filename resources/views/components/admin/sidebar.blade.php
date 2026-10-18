<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex h-dvh w-64 shrink-0 -translate-x-full flex-col overflow-hidden bg-brand-navy text-white shadow-xl transition-transform duration-300 ease-in-out no-print md:translate-x-0 md:shadow-none">
    
    {{-- Brand Header --}}
    <div class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-slate-700/50 bg-brand-navy px-4">

        {{-- Logo --}}
        <div class="flex min-w-0 flex-1 items-center gap-2">
            <img src="{{ asset('logo/logo.jpeg') }}" alt="Nirwana Garage Logo"
                class="h-8 w-8 shrink-0 rounded-lg object-cover">

            <h1 class="min-w-0 whitespace-nowrap text-sm font-bold leading-none text-white">
                NIRWANA <span class="text-brand-gold">GARAGE</span>
            </h1>
        </div>

        {{-- Close Button Mobile --}}
        <button id="close-sidebar-btn" type="button" aria-label="Tutup menu navigasi"
            class="ml-auto flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-brand-silver transition-colors hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-slate-600 md:hidden">
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>

    </div>

    {{-- Navigation Container --}}
    <div class="flex min-h-0 flex-1 flex-col overflow-hidden">

        {{-- Navigation Links --}}
        <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-4" aria-label="Navigasi admin">
            {{-- Main Menu --}}
            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-brand-silver">
                Main Menu
            </div>

            {{-- Dashboard Portfolio --}}
            <a href="{{ route('admin.portfolio.dashboard') }}" id="nav-dashboard"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.portfolio.dashboard') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Dashboard Portfolio</span>
            </a>

            {{-- Semua Proyek --}}
            <a href="{{ route('admin.projects.index') }}" id="nav-projects"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.projects.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.7 6.3a5 5 0 00-6.9 6.9L3.5 17.5a2.1 2.1 0 003 3l4.3-4.3a5 5 0 006.9-6.9l-3.1 3.1-3.5-3.5 3.6-2.6z">
                    </path>
                </svg>

                <span class="truncate">Semua Proyek</span>
            </a>

            {{-- Kategori Proyek --}}
            <a href="{{ route('admin.project-categories.index') }}" id="nav-project-categories"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.project-categories.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M3 11l8.586 8.586a2 2 0 002.828 0l6.172-6.172a2 2 0 000-2.828L12 3H5a2 2 0 00-2 2v6z">
                    </path>
                </svg>

                <span class="truncate">Kategori Proyek</span>
            </a>

            {{-- Ulasan Pelanggan --}}
            <a href="{{ route('admin.reviews.index') }}" id="nav-reviews"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.reviews.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h8m-8 4h4m-9 6l3.5-3.5A9 9 0 1121 12a8.96 8.96 0 01-1.5 5L21 21l-4-1.5A9 9 0 013 12a9 9 0 010-1">
                    </path>
                </svg>

                <span class="truncate">Ulasan Pelanggan</span>
            </a>

            {{-- Kelola Booking --}}
            <a href="{{ route('admin.booking-settings.edit') }}" id="nav-booking-settings"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.booking-settings.edit') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"></path>
                </svg>

                <span class="truncate">Kelola Booking</span>
            </a>

            {{-- Inventory Management --}}
            <div class="px-3 pb-2 pt-4 text-xs font-semibold uppercase tracking-wider text-brand-silver">
                Inventory Management
            </div>

            {{-- Inventory Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" id="nav-inventory-dashboard"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Dashboard Inventory</span>
            </a>

            {{-- Categories --}}
            <a href="{{ route('admin.categories.index') }}" id="nav-categories"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.categories.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="tags" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Kategori Material</span>
            </a>

            {{-- Material --}}
            <a href="{{ route('admin.materials.index') }}" id="nav-materials"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.materials.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="package" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Material</span>
            </a>

            {{-- Material Request --}}
            <a href="{{ route('admin.material-requests.index') }}" id="nav-material-requests"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.material-requests.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="clipboard-list" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Permintaan Material</span>
            </a>

            {{-- Stock In --}}
            <a href="{{ route('admin.stock-in.index') }}" id="nav-stock-in"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-in.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="package-plus" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Stok Masuk</span>
            </a>

            {{-- Stock Out --}}
            <a href="{{ route('admin.stock-out.index') }}" id="nav-stock-out"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-out.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="package-minus" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Stok Keluar</span>
            </a>

            {{-- Stock Adjustment --}}
            <a href="{{ route('admin.stock-adjustments.index') }}" id="nav-stock-adjustments"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-adjustments.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="clipboard-pen" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Penyesuaian Stok</span>
            </a>

            {{-- Stock Movement History --}}
            <div class="px-3 pb-2 pt-4 text-xs font-semibold uppercase tracking-wider text-brand-silver">
                Riwayat Pergerakan Stok
            </div>

            {{-- Stock Movements --}}
            <a href="{{ route('admin.stock-movements.index') }}" id="nav-stock-movements"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-movements.index') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="arrow-left-right" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Pergerakan Stok</span>
            </a>

        </nav>
    </div>

    {{-- User Profile Bottom --}}
    <div class="relative z-20 shrink-0 border-t border-slate-700/50 bg-brand-navy p-4">

        @php
            $name = Auth::user()->name ?? 'Customer';
            $email = Auth::user()->email ?? '';

            $words = explode(' ', trim($name));

            if (count($words) >= 2) {
                $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
            } else {
                $initials = strtoupper(substr($name, 0, min(2, strlen($name))));
            }
        @endphp

        {{-- Profile Dropdown Card --}}
        <div id="profile-card"
            class="absolute bottom-full left-4 right-4 z-50 mb-4 hidden rounded-xl border border-slate-700 bg-slate-800 p-4 shadow-xl transition-all duration-200">
            {{-- Profile Information --}}
            <div class="flex items-center gap-3 border-b border-slate-700 pb-3">

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-gold text-sm font-bold text-brand-navy">
                    {{ $initials }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="truncate text-sm font-bold text-white">
                        {{ $name }}
                    </div>

                    <div class="truncate text-xs text-brand-silver">
                        {{ $email }}
                    </div>
                </div>

            </div>

            {{-- Logout --}}
            <div class="pt-2">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0">
                    @csrf

                    <button type="button" id="logout-button"
                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-400 transition-colors hover:bg-slate-700/50 hover:text-red-300">
                        <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>

        </div>

        {{-- Profile Button Trigger --}}
        <button type="button" id="profile-trigger" aria-expanded="false" aria-controls="profile-card"
            class="flex w-full min-w-0 cursor-pointer items-center gap-3 rounded-lg p-1.5 text-left transition-colors select-none hover:bg-slate-800">
            <span
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold text-white">
                {{ $initials }}
            </span>

            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-medium text-white">
                    {{ $name }}
                </span>

                <span class="block truncate text-xs text-brand-silver">
                    {{ $email }}
                </span>
            </span>
        </button>

    </div>
</aside>
