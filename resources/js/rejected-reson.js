// REJECTION REASON MODAL
// ========================================

window.openRejectionReason = function (bookingCode, reason) {
    const modal = document.getElementById("rejection-reason-modal");
    const modalBox = document.getElementById("rejection-reason-modal-box");

    const bookingCodeElement = document.getElementById(
        "rejection-booking-code"
    );

    const reasonElement = document.getElementById(
        "rejection-reason-message"
    );

    // Pastikan modal ditemukan
    if (!modal || !modalBox) {
        console.error("Rejection reason modal tidak ditemukan.");
        return;
    }

    // Isi data booking
    if (bookingCodeElement) {
        bookingCodeElement.textContent = `Booking ${bookingCode}`;
    }

    // Isi alasan penolakan
    if (reasonElement) {
        reasonElement.textContent = reason || "-";
    }

    // Tampilkan modal
    modal.classList.remove("hidden");
    modal.classList.add("flex");

    // Animasi modal
    requestAnimationFrame(() => {
        modalBox.classList.remove("scale-95", "opacity-0");
        modalBox.classList.add("scale-100", "opacity-100");
    });
};


// ========================================
// CLOSE REJECTION REASON MODAL
// ========================================

window.closeRejectionReason = function () {
    const modal = document.getElementById("rejection-reason-modal");
    const modalBox = document.getElementById("rejection-reason-modal-box");

    if (!modal || !modalBox) {
        return;
    }

    // Animasi keluar
    modalBox.classList.remove("scale-100", "opacity-100");
    modalBox.classList.add("scale-95", "opacity-0");

    // Tunggu animasi selesai
    setTimeout(() => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }, 200);
};
