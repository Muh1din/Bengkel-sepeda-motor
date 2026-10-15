document.addEventListener("DOMContentLoaded", function () {
    const detailButtons = document.querySelectorAll(".work-order-detail-btn");

    const closeButtons = document.querySelectorAll(".work-order-detail-close");

    detailButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const workOrderId = this.dataset.workOrderId;

            const modal = document.getElementById(
                `work-order-detail-modal-${workOrderId}`,
            );

            if (!modal) {
                return;
            }

            modal.classList.remove("hidden");

            document.body.classList.add("overflow-hidden");

            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }
        });
    });

    closeButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const workOrderId = this.dataset.workOrderId;

            const modal = document.getElementById(
                `work-order-detail-modal-${workOrderId}`,
            );

            if (!modal) {
                return;
            }

            modal.classList.add("hidden");

            document.body.classList.remove("overflow-hidden");
        });
    });
});
