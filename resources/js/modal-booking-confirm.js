function openBookingModal() {
    const modal = document.getElementById("bookingModal");

    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    document.body.classList.add("overflow-hidden");
}

function closeBookingModal(event) {
    if (event && event.target !== event.currentTarget) {
        return;
    }

    const modal = document.getElementById("bookingModal");

    if (!modal) return;

    modal.classList.add("hidden");
    modal.classList.remove("flex");

    document.body.classList.remove("overflow-hidden");
}

document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeBookingModal();
    }
});

// Membuat fungsi dapat dipanggil dari atribut onclick di Blade.
window.openBookingModal = openBookingModal;
window.closeBookingModal = closeBookingModal;

// booking notification
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('booking-success-modal');
    const closeButton = document.getElementById('close-booking-modal');
    const copyButton = document.getElementById('copy-booking-code');
    const bookingCode = document.getElementById('booking-code');

    if (!modal) {
        return;
    }

    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
    }

    closeButton.addEventListener('click', closeModal);

    // Close when clicking the backdrop
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close using Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Copy booking code
    copyButton.addEventListener('click', async function () {
        const code = bookingCode.textContent.trim();

        try {
            await navigator.clipboard.writeText(code);

            copyButton.textContent = 'Kode Berhasil Disalin!';

            setTimeout(function () {
                copyButton.textContent = 'Salin Kode Booking';
            }, 2000);
        } catch (error) {
            // Fallback for browsers that block clipboard access
            const textarea = document.createElement('textarea');

            textarea.value = code;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';

            document.body.appendChild(textarea);
            textarea.select();

            const copied = document.execCommand('copy');
            textarea.remove();

            copyButton.textContent = copied
                ? 'Kode Berhasil Disalin!'
                : 'Gagal Menyalin Kode';

            setTimeout(function () {
                copyButton.textContent = 'Salin Kode Booking';
            }, 2000);
        }
    });

    // Focus the primary action when the modal appears
    closeButton.focus();
});
