<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-navy text-white shrink-0 no-print flex flex-col justify-between h-screen overflow-y-auto transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">

    <div>
        <!-- Brand Header -->
        <div
            class="h-16 flex items-center justify-between px-6 bg-brand-navy border-b border-slate-700/50 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logo.jpeg') }}" alt="Nirwana Garage Logo"
                    class="w-8 h-8 rounded-lg object-cover shrink-0">
                <h1 class="font-bold text-base leading-none text-white">
                    NIRWANA <span class="text-brand-gold">GARAGE</span>
                </h1>
            </div>
            <!-- Close Button Mobile -->
            <button id="close-sidebar-btn" type="button"
                class="md:hidden text-brand-silver hover:text-white p-1 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-1">
            <!-- Main Menu -->
            <div class="px-3 py-2 text-xs font-semibold text-brand-silver uppercase tracking-wider">Main Menu</div>

            {{-- Dashboard Portofolio --}}
            <a href="{{ route('admin.portfolio.dashboard') }}" id="nav-dashboard"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.portfolio.dashboard') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Dashboard Portfolio</span>
            </a>

            {{-- Semua Proyek  --}}
            <a href="{{ route('admin.projects.index') }}" id="nav-projects"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.projects.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.7 6.3a5 5 0 00-6.9 6.9L3.5 17.5a2.1 2.1 0 003 3l4.3-4.3a5 5 0 006.9-6.9l-3.1 3.1-3.5-3.5 3.6-2.6z">
                    </path>
                </svg>
                <span class="truncate">Semua Proyek</span>
            </a>

            {{-- Kategori Proyek --}}
            <a href="{{ route('admin.project-categories.index') }}" id="nav-project-categories"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.project-categories.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M3 11l8.586 8.586a2 2 0 002.828 0l6.172-6.172a2 2 0 000-2.828L12 3H5a2 2 0 00-2 2v6z">
                    </path>
                </svg>
                <span class="truncate">Kategori Proyek</span>
            </a>

            {{-- Ulasan Pelanggan --}}
            <a href="{{ route('admin.reviews.index') }}" id="nav-reviews"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.reviews.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h8m-8 4h4m-9 6l3.5-3.5A9 9 0 1121 12a8.96 8.96 0 01-1.5 5L21 21l-4-1.5A9 9 0 013 12a9 9 0 010-1">
                    </path>
                </svg>
                <span class="truncate">Ulasan Pelanggan</span>
            </a>

            {{-- Kelola Booking --}}
            <a href="{{ route('admin.booking-settings.edit') }}" id="nav-reviews"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.booking-settings.edit') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z">
                    </path>
                </svg>
                <span class="truncate">Kelola Booking</span>
            </a>

            <!-- Layanan Servis -->
            <div class="pt-4 px-3 py-2 text-xs font-semibold text-brand-silver uppercase tracking-wider">Inventory
                Management
            </div>

            <!-- Inventory Dashboard -->
            <a href="{{ route('admin.dashboard') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Dashboard Inventory</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.categories.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="tags" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Kategori Material</span>
            </a>

            <!-- Material -->
            <a href="{{ route('admin.materials.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.materials.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="calendar-plus" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Material</span>
            </a>



            {{-- Material Request --}}
            <a href="{{ route('admin.material-requests.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.material-requests.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Permintaan Material</span>
            </a>

            {{-- Stock-in --}}
            <a href="{{ route('admin.stock-in.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-in.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="package-plus" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Stok Masuk</span>
            </a>

            {{-- Stock-out --}}
            <a href="{{ route('admin.stock-out.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-out.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="package-minus" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Stok Keluar</span>
            </a>

            {{-- Stock-out --}}
            <a href="{{ route('admin.stock-adjustments.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-adjustments.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="clipboard-pen" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Penyesuaian Stok</span>
            </a>

            <!-- Laporan -->
            <div class="pt-4 px-3 py-2 text-xs font-semibold text-brand-silver uppercase tracking-wider">Riwayat
                Pergerakan Stok
            </div>

            {{-- Pergerakan Stok --}}
            <a href="{{ route('admin.stock-movements.index') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.stock-movements.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="arrow-left-right" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Pergerakan Stok</span>
            </a>

        </nav>
    </div>

    <!-- User Profile Bottom -->
    <div class="p-4 border-t border-slate-700/50 bg-brand-navy sticky bottom-0 z-10">
        @php
            $name = Auth::user()->name ?? 'Customer';
            $words = explode(' ', trim($name));
            $initials = '';

            if (count($words) >= 2) {
                $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
            } else {
                $initials = strtoupper(substr($name, 0, min(2, strlen($name))));
            }
        @endphp

        <!-- Profile Dropdown Card -->
        <div id="profile-card"
            class="hidden absolute bottom-full left-4 right-4 mb-25 md:mb-4 bg-slate-800 rounded-xl p-4 shadow-xl border border-slate-700 transition-all duration-200 z-50">

            <div class="flex items-center gap-3 pb-3 border-b border-slate-700">

                <div
                    class="w-10 h-10 rounded-full bg-brand-gold flex items-center justify-center font-bold text-brand-navy text-sm shrink-0">
                    {{ $initials }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-brand-silver truncate">{{ Auth::user()->email }}</div>
                </div>

            </div>

            <div class="pt-2">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0">
                    @csrf

                    <button type="button" id="logout-button"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-slate-700/50 transition-colors font-medium">

                        <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                        <span>Keluar</span>

                    </button>
                </form>
            </div>

        </div>


        <!-- Profile Button Trigger -->
        <div id="profile-trigger"
            class="flex items-center gap-3 cursor-pointer p-1.5 rounded-lg hover:bg-slate-800 transition-colors select-none -mt-20 md:mt-0">
            <div
                class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center font-semibold text-sm shrink-0 text-white">
                {{ $initials }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs text-brand-silver truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>
</aside>
