<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex h-dvh w-64 shrink-0 -translate-x-full flex-col overflow-hidden bg-brand-navy text-white transition-transform duration-300 ease-in-out no-print md:translate-x-0">
    
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
        <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain p-4">
            {{-- Main Menu --}}
            <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-brand-silver">
                Main Menu
            </div>

            {{-- Dashboard --}}
            <a href="{{ route('mechanic.dashboard') }}" id="nav-dashboard"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('mechanic.dashboard') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Dashboard</span>
            </a>

            {{-- Inspections --}}
            <a href="{{ route('mechanic.inspections.index') }}" id="nav-inspections"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('mechanic.inspections.*') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="clipboard-check" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Pemeriksaan</span>
            </a>

            {{-- Work Orders --}}
            <a href="{{ route('mechanic.work-orders.index') }}" id="nav-work-orders"
                class="nav-item flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('mechanic.work-orders.*') ? 'border-l-2 border-brand-gold bg-brand-gold/15 text-brand-gold' : 'text-brand-silver hover:bg-slate-800 hover:text-white' }}">
                <i data-lucide="clipboard-list" class="h-4 w-4 shrink-0"></i>
                <span class="truncate">Work Order</span>
            </a>
        </nav>
    </div>

    {{-- User Profile Bottom --}}
    <div class="relative z-20 shrink-0 border-t border-slate-700/50 bg-brand-navy p-4">
        @php
            $name = Auth::user()->name ?? 'Mechanic';
            $email = Auth::user()->email ?? '';
            $words = explode(' ', trim($name));

            if (count($words) >= 2) {
                $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
            } else {
                $initials = strtoupper(substr($name, 0, 2));
            }
        @endphp

        {{-- Profile Dropdown Card --}}
        <div id="profile-card"
            class="absolute bottom-full left-4 right-4 z-50 mb-4 hidden rounded-xl border border-slate-700 bg-slate-800 p-4 shadow-xl transition-all duration-200">
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
        <button id="profile-trigger" type="button" aria-label="Buka menu profil" aria-haspopup="true"
            aria-controls="profile-card" aria-expanded="false"
            class="flex w-full min-w-0 cursor-pointer items-center gap-3 rounded-lg p-1.5 text-left transition-colors select-none hover:bg-slate-800">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold text-white">
                {{ $initials }}
            </div>

            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-medium text-white">
                    {{ $name }}
                </div>

                <div class="truncate text-xs text-brand-silver">
                    {{ $email }}
                </div>
            </div>

        </button>
    </div>
</aside>
