document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("bookingDetailModal");

    if (!modal) {
        return;
    }

    const modalBox = document.getElementById("modalBox");

    const closeModalBtn = document.getElementById("closeModalBtn");
    const closeModalFooterBtn = document.getElementById("closeModalFooterBtn");

    const modalBookingCode = document.getElementById("modalBookingCode");
    const modalStatus = document.getElementById("modalStatus");
    const modalCustomer = document.getElementById("modalCustomer");
    const modalPhone = document.getElementById("modalPhone");
    const modalVehicle = document.getElementById("modalVehicle");
    const modalService = document.getElementById("modalService");
    const modalDateTime = document.getElementById("modalDateTime");
    const modalComplaint = document.getElementById("modalComplaint");

    // Rejection Reason
    const rejectionSection = document.getElementById(
        "rejection-reason-section",
    );

    const rejectionReason = document.getElementById("detail-rejection-reason");

    // Cancellation Reason
    const cancellationSection = document.getElementById(
        "cancellation-reason-section",
    );

    const cancellationReason = document.getElementById(
        "detail-cancellation-reason",
    );

    // Open Modal
    function openModal(button) {
        const data = button.dataset;

        // debuging saja

        // console.log("STATUS:", data.status);
        // console.log("CANCELLATION REASON:", data.cancellationReason);

        // Booking Information
        modalBookingCode.textContent = data.bookingCode;
        modalCustomer.textContent = data.customer;
        modalPhone.textContent = data.phone;
        modalVehicle.textContent = data.vehicle;
        modalService.textContent = data.service;
        modalDateTime.textContent = data.datetime;
        modalComplaint.textContent = data.complaint || "-";
        modalStatus.textContent = data.status;

        // Rejection Reason
        if (data.status === "REJECTED") {
            rejectionReason.textContent = data.rejectionReason || "-";

            rejectionSection.classList.remove("hidden");
        } else {
            rejectionReason.textContent = "";
            rejectionSection.classList.add("hidden");
        }

        // Cancellation Reason
        if (data.status === "CANCELLED") {
            cancellationReason.textContent = data.cancellationReason || "-";

            cancellationSection.classList.remove("hidden");
        } else {
            cancellationReason.textContent = "";
            cancellationSection.classList.add("hidden");
        }

        // Status Styling
        modalStatus.className =
            "px-2.5 py-0.5 rounded-full text-xs font-semibold";

        if (data.status === "PENDING") {
            modalStatus.classList.add("bg-amber-100", "text-amber-800");
        } else if (data.status === "CONFIRMED") {
            modalStatus.classList.add("bg-blue-100", "text-blue-800");
        } else if (data.status === "IN_PROGRESS") {
            modalStatus.classList.add("bg-emerald-100", "text-emerald-800");
        } else if (data.status === "COMPLETED") {
            modalStatus.classList.add("bg-green-100", "text-green-800");
        } else if (data.status === "CANCELED") {
            modalStatus.classList.add("bg-red-100", "text-red-800");
        } else if (data.status === "REJECTED") {
            modalStatus.classList.add("bg-gray-100", "text-gray-800");
        }

        // Show Modal
        modal.classList.remove("hidden");

        // Animation
        requestAnimationFrame(() => {
            modalBox.classList.remove("scale-95", "opacity-0");

            modalBox.classList.add("scale-100", "opacity-100");
        });
    }

    // Close Modal
    function closeModal() {
        modalBox.classList.remove("scale-100", "opacity-100");

        modalBox.classList.add("scale-95", "opacity-0");

        setTimeout(() => {
            modal.classList.add("hidden");
        }, 200);
    }

    // Detail Booking Buttons
    document.querySelectorAll("[data-booking-detail]").forEach((button) => {
        button.addEventListener("click", () => {
            openModal(button);
        });
    });

    // Close Buttons
    closeModalBtn.addEventListener("click", closeModal);
    closeModalFooterBtn.addEventListener("click", closeModal);

    // Click Outside Modal
    modal.addEventListener("click", (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Escape Key
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !modal.classList.contains("hidden")) {
            closeModal();
        }
    });

    // Mechanic Modal
    const mechanicModal = document.getElementById("mechanic-modal");
    const closeMechanicModalBtn = document.getElementById(
        "close-mechanic-modal",
    );
    const cancelMechanicModalBtn = document.getElementById(
        "cancel-mechanic-modal",
    );

    window.openReceiveVehicle = function (bookingId) {
        if (!mechanicModal) {
            return;
        }

        mechanicModal.classList.remove("hidden");
        mechanicModal.classList.add("flex");
    };

    function closeMechanicModal() {
        if (!mechanicModal) {
            return;
        }

        mechanicModal.classList.add("hidden");
        mechanicModal.classList.remove("flex");
    }

    if (closeMechanicModalBtn) {
        closeMechanicModalBtn.addEventListener("click", closeMechanicModal);
    }

    if (cancelMechanicModalBtn) {
        cancelMechanicModalBtn.addEventListener("click", closeMechanicModal);
    }

    if (mechanicModal) {
        mechanicModal.addEventListener("click", function (event) {
            if (event.target === mechanicModal) {
                closeMechanicModal();
            }
        });
    }

});
