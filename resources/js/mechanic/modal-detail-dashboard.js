document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("work-order-detail-modal");
    const overlay = document.getElementById("work-order-detail-overlay");
    const closeButton = document.getElementById("close-work-order-detail");
    const closeFooterButton = document.getElementById(
        "close-work-order-detail-footer",
    );

    const detailButtons = document.querySelectorAll("[data-work-order-detail]");

    // Jika modal tidak ada di halaman ini, hentikan script
    if (!modal) {
        return;
    }

    function openModal(button) {
        document.getElementById("modal-work-order-code").textContent =
            button.dataset.workOrderCode;

        document.getElementById("modal-booking-code").textContent =
            button.dataset.bookingCode;

        document.getElementById("modal-customer").textContent =
            button.dataset.customer;

        document.getElementById("modal-vehicle").textContent =
            button.dataset.vehicle;

        document.getElementById("modal-service").textContent =
            button.dataset.service;

        document.getElementById("modal-complaint").textContent =
            button.dataset.complaint;

        document.getElementById("modal-received-at").textContent =
            button.dataset.receivedAt || "-";

        const status = button.dataset.status;

        const statusElement = document.getElementById("modal-status");

        statusElement.textContent =
            status === "IN_PROGRESS"
                ? "Sedang Dikerjakan"
                : status === "COMPLETED"
                  ? "Selesai"
                  : status;

        statusElement.className =
            "inline-flex px-2.5 py-1 mt-1 rounded-full text-xs font-semibold " +
            (status === "IN_PROGRESS"
                ? "bg-indigo-50 text-indigo-700"
                : status === "COMPLETED"
                  ? "bg-green-50 text-green-700"
                  : "bg-gray-50 text-gray-600");

        modal.classList.remove("hidden");
    }

    function closeModal() {
        modal.classList.add("hidden");
    }

    detailButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            openModal(button);
        });
    });

    // Pasang event hanya kalau elemennya memang ada
    if (closeButton) {
        closeButton.addEventListener("click", closeModal);
    }

    if (closeFooterButton) {
        closeFooterButton.addEventListener("click", closeModal);
    }

    if (overlay) {
        overlay.addEventListener("click", closeModal);
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
});
