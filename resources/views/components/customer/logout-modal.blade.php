<div id="logout-modal"
    class="fixed inset-0 z-100 hidden items-center justify-center bg-brand-navy/50 backdrop-blur-sm px-4">
    <div class="w-full max-w-sm bg-ui-card rounded-lg shadow-xl border border-ui-border">

        <div class="p-5">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-5">

                <div
                    class="w-10 h-10 rounded-full bg-ui-main text-brand-gold border border-ui-border flex items-center justify-center shrink-0">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </div>

                <div>

                    <h2 class="text-base font-semibold text-brand-navy">
                        Konfirmasi Keluar
                    </h2>

                    <p class="text-xs text-brand-steel mt-1">
                        Apakah Anda yakin ingin keluar dari akun?
                    </p>

                </div>

            </div>

            {{-- Action --}}
            <div class="flex justify-end gap-2">

                <button type="button" id="logout-cancel"
                    class="px-4 py-2 rounded-md text-sm font-medium text-brand-steel border border-ui-border hover:bg-ui-main transition-colors">
                    Batal
                </button>

                <button type="button" id="logout-confirm"
                    class="px-4 py-2 rounded-md text-sm font-medium text-white bg-brand-navy hover:bg-brand-gold transition-colors duration-200">
                    Keluar
                </button>

            </div>

        </div>

    </div>
</div>
