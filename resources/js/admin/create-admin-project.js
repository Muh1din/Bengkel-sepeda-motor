document.addEventListener("DOMContentLoaded", function () {
    const addButton = document.getElementById("add-gallery");
    const galleryContainer = document.getElementById("gallery-container");

    addButton.addEventListener("click", function () {
        const galleryItem = document.createElement("div");

        galleryItem.className = "gallery-item flex items-center gap-3";

        galleryItem.innerHTML = `
                <input
                    type="file"
                    name="gallery[]"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full rounded-lg border border-ui-border text-sm
                           file:mr-4 file:border-0 file:bg-gray-100
                           file:px-4 file:py-2.5 file:font-medium
                           hover:file:bg-gray-200"
                >

                <button
                    type="button"
                    class="remove-gallery shrink-0 rounded-lg px-3 py-2 text-sm
                           font-medium text-red-600 transition hover:bg-red-50"
                >
                    Hapus
                </button>
            `;

        galleryContainer.appendChild(galleryItem);
    });

    galleryContainer.addEventListener("click", function (event) {
        const removeButton = event.target.closest(".remove-gallery");

        if (removeButton) {
            removeButton.closest(".gallery-item").remove();
        }
    });
});
