const modal = document.getElementById("material-request-modal");

const closeButton = document.getElementById("material-request-modal-close");

const footerCloseButton = document.getElementById(
    "material-request-modal-close-footer",
);

const backdrop = document.getElementById("material-request-modal-backdrop");

const detailButtons = document.querySelectorAll(".material-request-detail");

const workOrderElement = document.getElementById(
    "material-request-modal-work-order",
);

const modalContent = document.getElementById("material-request-modal-content");

function openModal(requestId) {
    const requestDetail = document.getElementById(
        `material-request-detail-${requestId}`,
    );

    if (!requestDetail) {
        return;
    }

    workOrderElement.textContent = requestDetail.dataset.workOrder;

    modalContent.innerHTML = requestDetail.innerHTML;

    modal.classList.remove("hidden");

    document.body.classList.add("overflow-hidden");
}

function closeModal() {
    modal.classList.add("hidden");

    modalContent.innerHTML = "";
    workOrderElement.textContent = "";

    document.body.classList.remove("overflow-hidden");
}

detailButtons.forEach((button) => {
    button.addEventListener("click", () => {
        openModal(button.dataset.requestId);
    });
});

closeButton.addEventListener("click", closeModal);

footerCloseButton.addEventListener("click", closeModal);

backdrop.addEventListener("click", closeModal);

document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && !modal.classList.contains("hidden")) {
        closeModal();
    }
});
