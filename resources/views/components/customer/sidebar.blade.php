<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-navy text-white shrink-0 no-print flex flex-col justify-between h-screen overflow-y-auto transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">

    <div>
        <!-- Brand Header -->
        <div
            class="h-16 flex items-center justify-between px-6 bg-brand-navy border-b border-slate-700/50 sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logo.jpeg') }}" alt="Nirwana Garage Logo"
                    class="w-8 h-8 rounded-lg object- shrink-0">
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
            <div class="px-3 py-2 text-xs font-semibold text-brand-silver uppercase tracking-wider">Main Menu</div>

            <!-- Dashboard -->
            <a href="{{ route('customer.dashboard') }}" id="nav-dashboard"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.dashboard') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Dashboard</span>
            </a>

            <!-- Profile Saya -->
            <a href="{{ route('customer.profile') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.profile') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Profile Saya</span>
            </a>

            <a href="{{ route('customer.kendaraan') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.kendaraan') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Kendaraan Saya</span>
            </a>

            <div class="pt-4 px-3 py-2 text-xs font-semibold text-brand-silver uppercase tracking-wider">Layanan Servis
            </div>

            <a href="{{ route('customer.bookingService') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.bookingService') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Booking Servis</span>
            </a>

            <a href="{{ route('customer.bookingAktif') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.bookingAktif') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Booking Aktif</span>
            </a>

            <a href="{{ route('customer.trackingService') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.trackingService') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Tracking Servis</span>
            </a>

             <a href="{{ route('customer.riwayatService') }}" id="nav-profile"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('customer.riwayatService') ? 'bg-brand-gold/15 text-brand-gold border-l-2 border-brand-gold' : 'text-brand-silver hover:text-white hover:bg-slate-800' }}">
                <i data-lucide="user" class="w-4 h-4 shrink-0"></i>
                <span class="truncate">Riwayat Servis</span>
            </a>
        </nav>
    </div>

    <!-- User Profile Bottom -->
    <div class="p-4 border-t border-slate-700/50 bg-brand-navy sticky bottom-0 z-10">
        <!-- Profile Dropdown Card -->
        <div id="profile-card"
            class="hidden absolute bottom-full left-4 right-4 mb-2 bg-slate-800 rounded-xl p-4 shadow-xl border border-slate-700 transition-all duration-200 z-50">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-700">
                <div
                    class="w-10 h-10 rounded-full bg-brand-gold flex items-center justify-center font-bold text-brand-navy text-sm shrink-0">
                    BS
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-sm font-bold text-white truncate">Budi Santoso</div>
                    <div class="text-xs text-brand-silver truncate">budi.santoso@example.com</div>
                </div>
            </div>
            <div class="pt-2">
                <button type="button" onclick="handleLogout()"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-slate-700/50 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>

        <!-- Profile Button Trigger -->
        <div id="profile-trigger"
            class="flex items-center gap-3 cursor-pointer p-1.5 rounded-lg hover:bg-slate-800 transition-colors select-none">
            <div
                class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center font-semibold text-sm shrink-0">
                BS
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-medium text-white truncate">Budi Santoso</div>
                <div class="text-xs text-brand-silver truncate">budi.santoso@example.com</div>
            </div>
        </div>
    </div>
</aside>
