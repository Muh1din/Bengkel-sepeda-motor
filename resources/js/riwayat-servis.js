window.filterHistory = function () {
    const searchInput = document.getElementById("history-search");

    const search = searchInput.value.toLowerCase().trim();

    const rows = document.querySelectorAll(".history-row");

    rows.forEach((row) => {
        const data = row.dataset.search;

        row.style.display = data.includes(search) ? "" : "none";
    });
};

window.openDetailModal = function (booking) {
    document.getElementById("detail-booking-code").textContent =
        booking.bookingCode;

    document.getElementById("detail-date").textContent = booking.date;

    document.getElementById("detail-time").textContent = booking.time;

    document.getElementById("detail-vehicle").textContent = booking.vehicle;

    document.getElementById("detail-license-plate").textContent =
        booking.licensePlate;

    document.getElementById("detail-service-type").textContent =
        booking.serviceType;

    document.getElementById("detail-complaint").textContent = booking.complaint;

    const modal = document.getElementById("detail-modal");

    modal.classList.remove("hidden");
    modal.classList.add("flex");
};

window.closeDetailModal = function () {
    const modal = document.getElementById("detail-modal");

    modal.classList.add("hidden");
    modal.classList.remove("flex");
};

window.openReviewModal = function (bookingId) {
    const modal = document.getElementById("review-modal");
    const form = document.getElementById("review-form");

    form.action = `/customer/bookings/${bookingId}/review`;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
};

window.closeReviewModal = function () {
    const modal = document.getElementById("review-modal");
    const form = document.getElementById("review-form");

    modal.classList.add("hidden");
    modal.classList.remove("flex");

    form.reset();
};
