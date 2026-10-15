document.addEventListener("DOMContentLoaded", () => {
    const addWorkButton = document.getElementById("add-work-button");
    const workItemsContainer = document.getElementById("work-items-container");
    const workItemTemplate = document.getElementById("work-item-template");

    // Halaman ini bukan halaman inspection.
    // Tidak perlu menjalankan script.
    if (!addWorkButton || !workItemsContainer || !workItemTemplate) {
        return;
    }

    function updateWorkNumbers() {
        const workItems = workItemsContainer.querySelectorAll(".work-item");

        workItems.forEach((item, index) => {
            const number = item.querySelector(".work-number");

            if (number) {
                number.textContent = `Pekerjaan #${index + 1}`;
            }
        });
    }

    function updateWorkNames() {
        const workItems = workItemsContainer.querySelectorAll(".work-item");

        workItems.forEach((item, index) => {
            const nameInput = item.querySelector(".work-name");
            const costInput = item.querySelector(".work-cost");
            const descriptionInput = item.querySelector(".work-description");

            if (nameInput) {
                nameInput.name = `works[${index}][name]`;
            }

            if (costInput) {
                costInput.name = `works[${index}][estimated_cost]`;
            }

            if (descriptionInput) {
                descriptionInput.name = `works[${index}][description]`;
            }
        });
    }

    function removeEmptyMessage() {
        const emptyMessage = document.getElementById("empty-work-message");

        if (emptyMessage) {
            emptyMessage.remove();
        }
    }

    addWorkButton.addEventListener("click", () => {
        removeEmptyMessage();

        const clone = workItemTemplate.content.cloneNode(true);

        workItemsContainer.appendChild(clone);

        updateWorkNames();
        updateWorkNumbers();
    });

    workItemsContainer.addEventListener("click", (event) => {
        const removeButton = event.target.closest(".remove-work-button");

        if (!removeButton) {
            return;
        }

        const workItem = removeButton.closest(".work-item");

        if (!workItem) {
            return;
        }

        workItem.remove();

        updateWorkNames();
        updateWorkNumbers();
    });

    // Sinkronisasi saat halaman pertama kali dibuka.
    updateWorkNames();
    updateWorkNumbers();
});
