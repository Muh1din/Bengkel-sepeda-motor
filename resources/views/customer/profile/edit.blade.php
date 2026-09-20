<x-layouts.customer title="Edit Profile">

    <section class="content-section space-y-6">

        <div class="max-w-2xl bg-ui-card border border-ui-border rounded-lg shadow-sm p-6">

            <div class="border-b border-ui-border pb-4 mb-6">
                <h3 class="text-lg font-bold text-brand-navy">
                    Edit Profil
                </h3>

                <p class="text-sm text-brand-steel mt-1">
                    Perbarui informasi profil Anda.
                </p>
            </div>

            <form 
            action="{{ route('customer.profile.update') }}" 
            method="POST" class="space-y-5">

                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-ui-text mb-1">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $customer->name) }}"
                        class="w-full rounded-md border border-ui-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-navy"
                    >

                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-ui-text mb-1">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $customer->email) }}"
                        class="w-full rounded-md border border-ui-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-navy"
                    >

                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-ui-text mb-1">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $customer->phone) }}"
                        class="w-full rounded-md border border-ui-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-navy"
                    >

                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-ui-text mb-1">
                        Alamat
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        class="w-full rounded-md border border-ui-border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-navy"
                    >{{ old('address', $customer->address) }}</textarea>

                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4 border-t border-ui-border">

                    <a
                        href="{{ route('customer.profile') }}"
                        class="px-4 py-2 border border-ui-border text-ui-text font-medium text-sm rounded-md hover:bg-gray-50"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-brand-navy text-white font-medium text-sm rounded-md hover:bg-opacity-90"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </section>

</x-layouts.customer>
