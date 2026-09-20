<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-navy text-white shrink-0 no-print flex flex-col justify-between h-screen overflow-y-auto transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">

    <div>
        <!-- Brand Header -->
        <div
            class="h-16 flex items-center justify-between px-6 bg-brand-navy border-b border-slate-700/50 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logo.jpeg') }}" alt="Nirwana Garage Logo"
                    class="w-8 h-8 rounded-lg object-cover shrink-0">
                <div>
                    <h1 class="font-bold text-base leading-none text-white">NIRWANA GARAGE</h1>
                </div>
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

            <!-- Dashboard -->
            <a href="{{ route('customer.dashboard') }}" id="nav-dashboard"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.dashboard') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Dashboard</span>
            </a>

            <!-- Profile Saya -->
            <a href="{{ route('customer.profile') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.profile*') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Profile Saya</span>
            </a>

            <!-- Kendaraan Saya -->
            <a href="{{ route('customer.vehicles.index') }}" id="nav-vehicles"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.vehicles.*') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="bike" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Kendaraan Saya</span>
            </a>

            <!-- Layanan Servis -->
            <div class="pt-4 px-3 py-2 text-xs font-semibold text-brand-silver uppercase tracking-wider">Layanan Servis
            </div>

            <!-- Booking Servis -->
            <a href="{{ route('customer.bookingService') }}" id="nav-booking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.bookingService') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="calendar-plus" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Booking Servis</span>
            </a>

            <!-- Booking Aktif -->
            <a href="{{ route('customer.bookings.index') }}" id="nav-booking-active"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.bookings.index') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Booking Aktif</span>
            </a>

            <!-- Tracking Servis -->
            <a href="{{ route('customer.trackingService') }}" id="nav-tracking-service"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.trackingService*') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">

                <i data-lucide="gauge" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Tracking Servis</span>
            </a>

            <!-- Riwayat Servis -->
            <a href="{{ route('customer.riwayatService') }}" id="nav-service-history"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.riwayatService*') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="history" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Riwayat Servis</span>
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
            class="hidden absolute bottom-full left-4 right-4 mb-2 bg-slate-800 rounded-xl p-4 shadow-xl border border-slate-700 transition-all duration-200 z-50">
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
            class="flex items-center gap-3 cursor-pointer p-1.5 rounded-lg hover:bg-slate-800 transition-colors select-none">
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
