<x-layouts.home>
    
    <x-home.hero-section :remainingBookingSlots="$remainingBookingSlots" :activeVehicle="$activeVehicle" />

    <x-home.service-section></x-home.service-section>

    <x-home.galery-section :projects="$projects"></x-home.galery-section>

    <x-home.pricelist-section></x-home.pricelist-section>

    <x-home.testimoni-section :reviews="$reviews"></x-home.testimoni-section>

    <x-home.tracking-section :booking="$booking" :tracking-searched="session('tracking_section', false)"></x-home.tracking-section>

    <x-home.about-section></x-home.about-section>

    <x-home.faq-section></x-home.faq-section>

    <x-home.contact-section></x-home.contact-section>

    <a href="https://wa.me/62812xxxxxxxx" target="_blank"
        class="fixed bottom-6 right-4 sm:bottom-8 sm:right-8 z-50 bg-brand-navy w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center shadow-2xl hover:bg-green-600 transition-all duration-300 group">
        <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl text-white group-hover:scale-110 transition-transform"></i>
    </a>

</x-layouts.home>
