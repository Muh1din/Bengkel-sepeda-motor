<header class="h-16 bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-4 md:px-6 flex items-center justify-between sticky top-0 z-20">
    <div class="flex items-center gap-3.5">
        
        <!-- Tombol Hamburger Mobile (Sedikit diperbesar) -->
        <button id="open-sidebar-btn" 
                type="button" 
                class="md:hidden p-2.5 text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 rounded-xl flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200 active:scale-95">
            
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>

        </button>

        <!-- Judul Halaman (Perubahan: text-lg sm:text-xl font-bold) -->
        <div class="flex items-center gap-2">
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-none">
               {{ $title }}
            </h1>
        </div>
    </div>

    <!-- Sisi Kanan Header -->
    <div class="flex items-center gap-3">
        <!-- Profil ringkas / Notifikasi -->
    </div>
</header>