<x-layouts.customer title="Tracking Servis">
    <div class="space-y-6"> {{-- Deskripsi --}} <div>
            <p class="text-xs text-brand-steel"> Pantau progres servis kendaraan Anda. </p>
        </div>
        @if ($currentService) {{-- Informasi Booking --}} <div
                class="bg-white border border-ui-border rounded-lg p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <p class="text-xs text-brand-steel"> Booking Code </p>
                        <h2 class="text-lg font-semibold text-brand-navy"> {{ $currentService->booking_code }} </h2>
                    </div> {{-- Status --}} @if ($currentService->status === 'CONFIRMED')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"> Booking
                            Dikonfirmasi </span>
                    @elseif ($currentService->status === 'IN_PROGRESS')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700"> Servis
                            Berjalan </span>
                    @elseif ($currentService->status === 'COMPLETED')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700"> Servis
                            Selesai </span>
                        @endif
                </div> {{-- Informasi Kendaraan --}} <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-brand-steel"> Kendaraan </p>
                        <p class="text-sm font-medium text-brand-navy"> {{ $currentService->vehicle->brand }}
                            {{ $currentService->vehicle->model }} </p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-steel"> Nomor Polisi </p>
                        <p class="text-sm font-medium text-brand-navy"> {{ $currentService->vehicle->license_plate }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-steel"> Jenis Servis </p>
                        <p class="text-sm font-medium text-brand-navy"> {{ $currentService->service_type }} </p>
                    </div>
                    <div>
                        <p class="text-xs text-brand-steel"> Jadwal Servis </p>
                        <p class="text-sm font-medium text-brand-navy"> {{ $currentService->booking_date }} -
                            {{ \Carbon\Carbon::parse($currentService->booking_time)->format('H:i') }} </p>
                    </div>
                </div>
            </div> {{-- Progress Servis --}} <div class="bg-white border border-ui-border rounded-lg p-5">
                <div class="mb-6">
                    <h2 class="text-base font-semibold text-brand-navy"> Progres Servis Kendaraan </h2>
                    <p class="text-xs text-brand-steel mt-1"> Status servis kendaraan Anda saat ini. </p>
                </div>
                <div class="space-y-6"> {{-- Booking Dikonfirmasi --}} <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-full {{ in_array($currentService->status, ['CONFIRMED', 'IN_PROGRESS', 'COMPLETED']) ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center shrink-0">
                            <i data-lucide="check" class="w-4 h-4"></i> </div>
                        <div>
                            <p class="text-sm font-medium text-brand-navy"> Booking Dikonfirmasi </p>
                            <p class="text-xs text-brand-steel mt-1"> Booking servis telah dikonfirmasi oleh Service
                                Advisor. </p>
                        </div>
                    </div> {{-- Servis Sedang Berjalan --}} <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-full {{ in_array($currentService->status, ['IN_PROGRESS', 'COMPLETED']) ? 'bg-brand-gold text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center shrink-0">
                            @if (in_array($currentService->status, ['IN_PROGRESS', 'COMPLETED']))
                                <i data-lucide="wrench" class="w-4 h-4"></i>
                            @else
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-brand-navy"> Servis Sedang Berjalan </p>
                            <p class="text-xs text-brand-steel mt-1">
                                @if ($currentService->status === 'CONFIRMED')
                                    Menunggu kendaraan masuk ke proses servis.
                                @elseif ($currentService->status === 'IN_PROGRESS')
                                    Kendaraan sedang dalam proses pengerjaan.
                                @else
                                    Proses pengerjaan servis telah selesai.
                                    @endif
                            </p>
                        </div>
                    </div> {{-- Servis Selesai --}} <div class="flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-full {{ $currentService->status === 'COMPLETED' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center shrink-0">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> </div>
                        <div>
                            <p class="text-sm font-medium text-brand-navy"> Servis Selesai </p>
                            <p class="text-xs text-brand-steel mt-1">
                                @if ($currentService->status === 'COMPLETED')
                                    Seluruh pekerjaan servis telah selesai.
                                @else
                                    Servis akan ditandai selesai setelah seluruh pekerjaan selesai.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty State --}} <div class="bg-white border border-ui-border rounded-lg p-8 text-center">
                <div
                    class="w-12 h-12 mx-auto mb-4 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">
                    <i data-lucide="wrench" class="w-6 h-6"></i> </div>
                <h2 class="text-base font-semibold text-brand-navy"> Belum Ada Servis </h2>
                <p class="text-xs text-brand-steel mt-2"> Saat ini belum ada servis kendaraan yang dapat dilacak. </p>
            </div>
        @endif
    </div>
</x-layouts.customer>
