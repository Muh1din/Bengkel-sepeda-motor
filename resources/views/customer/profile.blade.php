<x-layouts.customer title="Profile">
    <section id="profile" class="content-section space-y-6">
        <div class="max-w-2xl bg-ui-card border border-ui-border rounded-lg shadow-sm p-6 space-y-6">
            <h3 class="text-lg font-bold text-brand-navy border-b border-ui-border pb-3">Informasi Pelanggan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-brand-steel block">Nama Lengkap</label>
                    <div id="profile-display-name" class="text-base font-medium text-ui-text mt-1">Budi Santoso
                    </div>
                </div>
                <div>
                    <label class="text-xs text-brand-steel block">Email</label>
                    <div id="profile-display-email" class="text-base font-medium text-ui-text mt-1">
                        budi.santoso@example.com</div>
                </div>
                <div>
                    <label class="text-xs text-brand-steel block">Nomor Telepon</label>
                    <div id="profile-display-phone" class="text-base font-medium text-ui-text mt-1">081234567890
                    </div>
                </div>
                <div>
                    <label class="text-xs text-brand-steel block">Alamat</label>
                    <div id="profile-display-address" class="text-base font-medium text-ui-text mt-1">Jl.
                        Merdeka No. 45, Jakarta Selatan</div>
                </div>
            </div>

            <div class="pt-4 border-t border-ui-border">
                <button onclick="openModal('modal-edit-profile')"
                    class="px-4 py-2 bg-brand-navy text-white font-medium text-sm rounded-md hover:bg-opacity-90">
                    Edit Profil
                </button>
            </div>
        </div>
    </section>

</x-layouts.customer>
