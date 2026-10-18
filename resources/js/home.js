// SCROLL ANIMATION
const scrollObserver = new IntersectionObserver(
    (entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                observer.unobserve(entry.target);
            }
        });
    },
    {
        threshold: 0.12,
        rootMargin: "0px 0px -60px 0px",
    },
);

// Jangan biarkan elemen navigasi ikut menunggu scroll animation.
document.querySelectorAll("[data-scroll]").forEach((el) => {
    const isNavigationElement = el.closest("#navbar");
    const isWhatsAppButton = el.matches('a[href*="wa.me"]');

    if (isNavigationElement || isWhatsAppButton) {
        el.classList.add("is-visible");
        return;
    }

    scrollObserver.observe(el);
});

// Stagger animation untuk elemen anak.
document.querySelectorAll(".stagger-children").forEach((parent) => {
    const children = parent.querySelectorAll("[data-scroll]");

    children.forEach((child, index) => {
        child.style.transitionDelay = `${index * 0.12}s`;
    });
});

// MOBILE MENU (UPDATE FIX)
const menuBtn = document.getElementById("menu-btn");
const mobileMenu = document.getElementById("mobile-menu");

const bar1 = document.getElementById("bar1");
const bar2 = document.getElementById("bar2");
const bar3 = document.getElementById("bar3");

let menuOpen = false;

function closeMobileMenu() {
    menuOpen = false;

    if (mobileMenu) {
        mobileMenu.classList.add("hidden");
        mobileMenu.classList.remove("open");
    }

    if (menuBtn) {
        menuBtn.setAttribute("aria-expanded", "false");
    }

    if (bar1) bar1.style.transform = "";
    if (bar2) bar2.style.opacity = "";
    if (bar3) bar3.style.transform = "";
}

if (menuBtn && mobileMenu) {
    menuBtn.setAttribute("aria-expanded", "false");

    menuBtn.addEventListener("click", () => {
        menuOpen = !menuOpen;

        // Toggle hidden class Tailwind
        mobileMenu.classList.toggle("hidden", !menuOpen);
        mobileMenu.classList.toggle("open", menuOpen);

        menuBtn.setAttribute("aria-expanded", String(menuOpen));

        if (bar1) {
            bar1.style.transform = menuOpen
                ? "rotate(45deg) translate(5px, 5px)"
                : "";
        }

        if (bar2) {
            bar2.style.opacity = menuOpen ? "0" : "";
        }

        if (bar3) {
            bar3.style.transform = menuOpen
                ? "rotate(-45deg) translate(5px, -5px)"
                : "";
        }
    });

    mobileMenu.querySelectorAll(".mobile-link").forEach((link) => {
        link.addEventListener("click", closeMobileMenu);
    });
}

// NAVBAR SCROLL EFFECT
const navbar = document.getElementById("navbar");

function updateNavbar() {
    if (!navbar) return;

    navbar.style.borderBottomColor =
        window.scrollY > 80
            ? "rgba(197, 142, 75, 0.15)"
            : "rgba(255,255,255,0.1)";
}

window.addEventListener("scroll", updateNavbar, {
    passive: true,
});

updateNavbar();

// FAQ ACCORDION
document.querySelectorAll(".faq-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        const content = btn.nextElementSibling;
        const chevron = btn.querySelector(".faq-chevron");
        const faqItem = btn.closest(".faq-item");

        if (!content) return;

        const isOpen = content.classList.contains("open");

        // Tutup semua FAQ terlebih dahulu.
        document.querySelectorAll(".faq-content").forEach((item) => {
            item.classList.remove("open");
        });

        document.querySelectorAll(".faq-chevron").forEach((item) => {
            item.classList.remove("rotated");
        });

        document.querySelectorAll(".faq-item").forEach((item) => {
            item.style.borderColor = "";
        });

        // Buka FAQ yang dipilih jika sebelumnya tertutup.
        if (!isOpen) {
            content.classList.add("open");
            chevron?.classList.add("rotated");

            if (faqItem) {
                faqItem.style.borderColor = "rgba(197,142,75,0.4)";
            }
        }
    });
});

// SMOOTH SCROLL
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (event) {
        const href = this.getAttribute("href");

        // Abaikan anchor kosong.
        if (!href || href === "#") return;

        const target = document.querySelector(href);

        // Jangan mencegah perilaku default jika target tidak ditemukan.
        if (!target) return;

        event.preventDefault();

        const offset = 72;

        const top =
            target.getBoundingClientRect().top + window.scrollY - offset;

        window.scrollTo({
            top,
            behavior: "smooth",
        });
    });
});

// ACTIVE NAV LINK
const sections = document.querySelectorAll("section[id]");
const navLinks = document.querySelectorAll(".nav-link");

function updateActiveNavLink() {
    let current = "";

    sections.forEach((section) => {
        if (window.scrollY >= section.offsetTop - 100) {
            current = section.id;
        }
    });

    navLinks.forEach((link) => {
        const isActive = link.getAttribute("href") === `#${current}`;

        link.style.color = isActive ? "#c58e4b" : "";
    });
}

window.addEventListener("scroll", updateActiveNavLink, {
    passive: true,
});

updateActiveNavLink();

// KONSULTASI VIA WHATSAPP
document.addEventListener("DOMContentLoaded", function () {
    const section = document.getElementById("kontak");

    if (!section) {
        return;
    }

    const serviceType = section.querySelector("#service_type");
    const otherServiceWrapper = section.querySelector("#other_service_wrapper");
    const otherServiceInput = section.querySelector("#other_service");

    if (!serviceType || !otherServiceWrapper || !otherServiceInput) {
        return;
    }

    function toggleOtherService() {
        const isOther = serviceType.value === "Lainnya";

        otherServiceWrapper.classList.toggle("hidden", !isOther);
        otherServiceInput.required = isOther;

        if (!isOther) {
            otherServiceInput.value = "";
        }
    }

    serviceType.addEventListener("change", toggleOtherService);

    toggleOtherService();
});


// Mengirim konsultasi melalui WhatsApp
window.handleKonsultasiSubmit = function () {
    const section = document.getElementById("kontak");

    if (!section) {
        console.error("Section kontak tidak ditemukan.");
        return;
    }

    const nama =
        section.querySelector('input[type="text"]:not(#other_service)')
            ?.value.trim() ?? "";

    const serviceType =
        section.querySelector("#service_type");

    const otherService =
        section.querySelector("#other_service");

    const detail =
        section.querySelector("textarea")?.value.trim() ?? "";

    if (!nama) {
        alert("Nama wajib diisi.");
        return;
    }

    if (!serviceType || !serviceType.value) {
        alert("Silakan pilih jenis layanan.");
        serviceType?.focus();
        return;
    }

    if (
        serviceType.value === "Lainnya" &&
        !otherService?.value.trim()
    ) {
        alert("Silakan tuliskan layanan yang Anda inginkan.");
        otherService?.focus();
        return;
    }

    if (!detail) {
        alert("Silakan isi detail rencana motor Anda.");
        section.querySelector("textarea")?.focus();
        return;
    }

    const layanan = serviceType.value === "Lainnya"
        ? `Lainnya - ${otherService.value.trim()}`
        : serviceType.value;

    const nomorWA = "6285717676292";

    const pesan =
        `Halo Admin, saya ${nama} ingin konsultasi gratis.\n\n` +
        `Layanan: ${layanan}\n` +
        `Detail: ${detail}\n\n` +
        `Mohon informasi lebih lanjut. Terima kasih.`;

    const url =
        `https://wa.me/${nomorWA}?text=${encodeURIComponent(pesan)}`;

    window.open(url, "_blank", "noopener,noreferrer");
};

// RESERVATION VIA WHATSAPP
window.handleReservationSubmit = function () {
    const btn = document.querySelector(
        'button[onclick="handleReservationSubmit()"]',
    );

    if (!btn) {
        console.error("Tombol submit tidak ditemukan.");
        return;
    }

    const formContainer = btn.closest(".space-y-5");

    if (!formContainer) {
        console.error("Form container tidak ditemukan.");
        return;
    }

    const tgl = formContainer.querySelector('input[type="date"]')?.value ?? "";

    const allInputs = formContainer.querySelectorAll('input[type="text"]');

    const merk = allInputs[0]?.value.trim() ?? "";
    const model = allInputs[1]?.value.trim() ?? "";
    const nama = allInputs[2]?.value.trim() ?? "";
    const wa = allInputs[3]?.value.trim() ?? "";

    const rencana = formContainer.querySelector("select")?.value ?? "";

    const catatan = formContainer.querySelector("textarea")?.value.trim() ?? "";

    if (!tgl || !nama || !wa) {
        alert("Mohon isi Tanggal, Nama, dan Nomor WhatsApp Anda!");
        return;
    }

    const nomorWA = "6285717676292";

    const pesan =
        `Halo Admin, saya mau booking slot antrian bengkel.\n\n` +
        `*Detail Booking:*\n` +
        `Tanggal: ${tgl}\n` +
        `Nama: ${nama}\n` +
        `WA: ${wa}\n\n` +
        `*Detail Kendaraan:*\n` +
        `Merk Motor: ${merk || "-"}\n` +
        `Model Unit: ${model || "-"}\n\n` +
        `*Rencana Pengerjaan:*\n` +
        `Layanan: ${rencana || "-"}\n` +
        `Catatan: ${catatan || "-"}`;

    const url = `https://wa.me/${nomorWA}?text=${encodeURIComponent(pesan)}`;

    window.open(url, "_blank", "noopener,noreferrer");
};

// TESTIMONIAL SLIDER
document.addEventListener("DOMContentLoaded", () => {
    const track = document.getElementById("testimonialTrack");
    const cards = document.querySelectorAll(".testi-card");
    const dotsContainer = document.getElementById("testimonialDots");

    if (!track || !dotsContainer || cards.length === 0) {
        return;
    }

    let currentIndex = 0;
    const gap = 16;

    function getVisibleCards() {
        if (!cards[0] || !track.parentElement) return 1;

        return Math.max(
            1,
            Math.floor(
                track.parentElement.offsetWidth / (cards[0].offsetWidth + gap),
            ),
        );
    }

    function updateSlider() {
        const visibleCards = getVisibleCards();
        const cardWidth = cards[0].offsetWidth;

        const offset = currentIndex * visibleCards * (cardWidth + gap);

        track.style.transform = `translateX(-${offset}px)`;

        const dots = dotsContainer.querySelectorAll("button");

        dots.forEach((dot, index) => {
            dot.className = `h-2 rounded-full transition-all duration-300 ${
                index === currentIndex ? "bg-brand-gold w-6" : "bg-white/20 w-2"
            }`;
        });
    }

    function setupSlider() {
        const visibleCards = getVisibleCards();
        const totalGroups = Math.ceil(cards.length / visibleCards);

        currentIndex = Math.min(currentIndex, Math.max(0, totalGroups - 1));

        dotsContainer.innerHTML = "";

        for (let i = 0; i < totalGroups; i++) {
            const dot = document.createElement("button");

            dot.type = "button";
            dot.setAttribute("aria-label", `Testimoni grup ${i + 1}`);

            dot.addEventListener("click", () => {
                currentIndex = i;
                updateSlider();
            });

            dotsContainer.appendChild(dot);
        }

        updateSlider();
    }

    window.moveTestimonial = function (direction) {
        const visibleCards = getVisibleCards();
        const totalGroups = Math.ceil(cards.length / visibleCards);

        currentIndex = Math.max(
            0,
            Math.min(currentIndex + direction, totalGroups - 1),
        );

        updateSlider();
    };

    setupSlider();

    window.addEventListener("resize", setupSlider);
});

// mengosongkan form konsultasi
document.addEventListener("DOMContentLoaded", function () {
    const section = document.getElementById("kontak");

    if (!section) return;

    const formInputs = section.querySelectorAll("input, select, textarea");

    formInputs.forEach((input) => {
        if (input.tagName === "SELECT") {
            input.selectedIndex = 0;
            input.dispatchEvent(new Event("change"));
        } else {
            input.value = "";
        }
    });
});

// FIX: AUTO-TRIGGER RENDER ENGINE (LOAD EVENT)
window.addEventListener("load", () => {
    updateNavbar();
    updateActiveNavLink();

    // Membangunkan rendering engine browser agar elemen fixed & blur langsung normal tanpa perlu di-scroll manual
    setTimeout(() => {
        const currentScroll = window.scrollY;
        window.scrollTo(window.scrollX, currentScroll + 1);
        window.scrollTo(window.scrollX, currentScroll);
    }, 50);
});
