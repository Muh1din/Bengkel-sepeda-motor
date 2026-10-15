window.openBookingDetail = function (id) {
    const booking = window.bookings.find((booking) => booking.id === id);

    if (!booking) {
        return;
    }

    const time = booking.booking_time.slice(0, 5);

    // Booking Information
    document.getElementById("detail-booking-code").textContent =
        booking.booking_code;

    document.getElementById("detail-customer").textContent =
        booking.customer.name;

    document.getElementById("detail-vehicle").textContent =
        booking.vehicle.license_plate;

    document.getElementById("detail-service-type").textContent =
        booking.service_type;

    document.getElementById("detail-date-time").textContent =
        `${booking.booking_date} ${time}`;

    document.getElementById("detail-complaint").textContent =
        booking.complaint ?? "-";

    document.getElementById("detail-status").textContent = booking.status;

    // Rejection Reason
    const rejectionSection = document.getElementById(
        "rejection-reason-section",
    );

    const rejectionReason = document.getElementById("detail-rejection-reason");

    if (booking.status === "REJECTED") {
        rejectionReason.textContent = booking.rejection_reason ?? "-";

        rejectionSection.classList.remove("hidden");
    } else {
        rejectionReason.textContent = "";

        rejectionSection.classList.add("hidden");
    }

    // Cancellation Reason
    const cancellationSection = document.getElementById(
        "cancellation-reason-section",
    );

    const cancellationReason = document.getElementById(
        "detail-cancellation-reason",
    );

    if (booking.status === "CANCELLED") {
        cancellationReason.textContent = booking.cancellation_reason ?? "-";

        cancellationSection.classList.remove("hidden");
    } else {
        cancellationReason.textContent = "";

        cancellationSection.classList.add("hidden");
    }

    // Open Modal
    const modal = document.getElementById("booking-detail-modal");

    modal.classList.remove("hidden");
    modal.classList.add("flex");
};

// Close Booking Detail
window.closeBookingDetail = function () {
    const modal = document.getElementById("booking-detail-modal");

    modal.classList.add("hidden");
    modal.classList.remove("flex");
};

// Reject Booking
window.openRejectBooking = function (id) {
    const modal = document.getElementById("reject-booking-modal");

    const form = document.getElementById("reject-booking-form");

    form.action = `/service-advisor/bookings/${id}/reject`;

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    document.getElementById("rejection_reason").focus();
};

// Close Reject Booking
window.closeRejectBooking = function () {
    const modal = document.getElementById("reject-booking-modal");

    const form = document.getElementById("reject-booking-form");

    modal.classList.add("hidden");
    modal.classList.remove("flex");

    form.reset();
};

const mechanicModal = document.getElementById("mechanic-modal");
const mechanicForm = document.getElementById("mechanic-form");

const closeMechanicModalBtn = document.getElementById("close-mechanic-modal");

const cancelMechanicModalBtn = document.getElementById("cancel-mechanic-modal");

window.openReceiveVehicle = function (bookingId) {
    if (!mechanicModal || !mechanicForm) {
        return;
    }

    mechanicForm.action = `/service-advisor/bookings/${bookingId}/receive`;

    mechanicModal.classList.remove("hidden");
    mechanicModal.classList.add("flex");
};

function closeMechanicModal() {
    if (!mechanicModal || !mechanicForm) {
        return;
    }

    mechanicModal.classList.add("hidden");
    mechanicModal.classList.remove("flex");

    mechanicForm.reset();
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

// Search, Status & Date Filter
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("booking-search");
    const statusFilter = document.getElementById("booking-filter-status");
    const dateFilter = document.getElementById("booking-filter-date");
    const tableBody = document.getElementById("booking-table-body");

    if (!tableBody) {
        return;
    }

    const filterBookings = () => {
        const keyword = searchInput
            ? searchInput.value.trim().toLowerCase()
            : "";

        const selectedStatus = statusFilter ? statusFilter.value : "";

        const selectedDate = dateFilter ? dateFilter.value : "";

        const rows = tableBody.querySelectorAll("tr[data-booking-code]");

        rows.forEach((row) => {
            const bookingCode = (row.dataset.bookingCode || "")
                .trim()
                .toLowerCase();

            const customerName = (row.dataset.customerName || "")
                .trim()
                .toLowerCase();

            const licensePlate = (row.dataset.licensePlate || "")
                .trim()
                .toLowerCase();

            const serviceType = (row.dataset.serviceType || "")
                .trim()
                .toLowerCase();

            const status = (row.dataset.status || "").trim();

            const bookingDate = (row.dataset.bookingDate || "").trim();

            // Search
            const matchesSearch =
                bookingCode.includes(keyword) ||
                customerName.includes(keyword) ||
                licensePlate.includes(keyword) ||
                serviceType.includes(keyword);

            // Status
            const matchesStatus =
                selectedStatus === "" || status === selectedStatus;

            // Date
            const matchesDate =
                selectedDate === "" || bookingDate === selectedDate;

            // Semua filter harus terpenuhi
            const isMatch = matchesSearch && matchesStatus && matchesDate;

            row.classList.toggle("hidden", !isMatch);
        });
    };

    // Search
    if (searchInput) {
        searchInput.addEventListener("input", filterBookings);
    }

    // Status
    if (statusFilter) {
        statusFilter.addEventListener("change", filterBookings);
    }

    // Date
    if (dateFilter) {
        dateFilter.addEventListener("change", filterBookings);
    }
});
