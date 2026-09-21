<x-layouts.customer title="Profil Saya">

    <section id="profile" class="content-section space-y-6">

        <!-- Card Wrapper -->
        <div class="max-w-3xl bg-ui-card border border-ui-border rounded-xl p-5 sm:p-7 shadow-xs space-y-6">

            <!-- Card Header -->
            <div class="flex items-center justify-between pb-4 border-b border-ui-border/60">
                <div>
                    <h3 class="text-xl font-bold text-brand-navy tracking-tight">
                        Informasi Pelanggan
                    </h3>
                    <p class="text-xs text-brand-steel mt-0.5">
                        Detail informasi akun dan data diri Anda.
                    </p>
                </div>

                <!-- Avatar / Badge Inisial -->
                <div
                    class="w-10 h-10 rounded-full bg-brand-navy/10 text-brand-navy font-bold text-base flex items-center justify-center shrink-0 border border-brand-navy/20">
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
                    
                    {{ $initials }}
                </div>
            </div>

            <!-- Profile Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                {{-- Nama Lengkap --}}
                <div class="p-3 bg-ui-main/50 border border-ui-border/60 rounded-lg">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-brand-steel block">
                        Nama Lengkap
                    </label>
                    <div id="profile-display-name" class="text-sm font-semibold text-brand-navy mt-1 truncate">
                        {{ Auth::user()->customer->name }}
                    </div>
                </div>

                {{-- Email --}}
                <div class="p-3 bg-ui-main/50 border border-ui-border/60 rounded-lg">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-brand-steel block">
                        Email
                    </label>
                    <div id="profile-display-email" class="text-sm font-semibold text-brand-navy mt-1 truncate">
                        {{ Auth::user()->customer->email }}
                    </div>
                </div>

                {{-- Nomor Telepon (Full width di mobile, 1 kolom di desktop) --}}
                <div class="sm:col-span-2 p-3 bg-ui-main/50 border border-ui-border/60 rounded-lg">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-brand-steel block">
                        Nomor Telepon
                    </label>
                    <div id="profile-display-phone" class="text-sm font-semibold text-brand-navy mt-1 font-mono">
                        {{ Auth::user()->customer->phone }}
                    </div>
                </div>

                {{-- Alamat (Dibuat Full Width sm:col-span-2 & break-words agar fleksibel untuk teks panjang) --}}
                <div class="sm:col-span-2 p-3.5 bg-ui-main/50 border border-ui-border/60 rounded-lg">
                    <label class="text-[11px] font-semibold uppercase tracking-wider text-brand-steel block">
                        Alamat Lengkap
                    </label>
                    <div id="profile-display-address"
                        class="text-sm font-medium text-ui-text mt-1.5 leading-relaxed wrap-break-words">
                        {{ Auth::user()->customer->address ?? '-' }}
                    </div>
                </div>

            </div>

            <!-- Action Button -->
            <div class="flex justify-end pt-4 border-t border-ui-border/60">
                <a href="{{ route('customer.profile.edit') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-brand-navy hover:bg-slate-800 text-white font-medium text-sm rounded-lg shadow-sm hover:shadow active:scale-[0.98] transition-all duration-200">
                    <i data-lucide="pencil" class="w-4 h-4 text-brand-gold"></i>
                    <span>Edit Profil</span>
                </a>
            </div>

        </div>

    </section>

</x-layouts.customer>
