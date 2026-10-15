const materialSelect = document.getElementById("material_id");
const quantityInput = document.getElementById("quantity");

const unitLabel = document.getElementById("unitLabel");

const previewMaterialName = document.getElementById("previewMaterialName");

const previewMaterialBarcode = document.getElementById(
    "previewMaterialBarcode",
);

const previewStockBefore = document.getElementById("previewStockBefore");

const previewQuantity = document.getElementById("previewQuantity");

const previewStockAfter = document.getElementById("previewStockAfter");

const previewStatus = document.getElementById("previewStatus");

if (
    materialSelect &&
    quantityInput &&
    unitLabel &&
    previewMaterialName &&
    previewMaterialBarcode &&
    previewStockBefore &&
    previewQuantity &&
    previewStockAfter &&
    previewStatus
) {
    function updateStockPreview() {
        const selectedOption =
            materialSelect.options[materialSelect.selectedIndex];

        if (!selectedOption || selectedOption.value === "") {
            unitLabel.textContent = "-";
            previewMaterialName.textContent = "-";
            previewMaterialBarcode.textContent = "Barcode: -";
            previewStockBefore.textContent = "0";
            previewQuantity.textContent = "0";
            previewStockAfter.textContent = "0";
            previewStatus.textContent = "-";

            previewStatus.className = "font-bold text-slate-400";

            return;
        }

        const name = selectedOption.dataset.name;
        const barcode = selectedOption.dataset.barcode;
        const stock = Number(selectedOption.dataset.stock) || 0;
        const unit = selectedOption.dataset.unit || "";
        const minimumStock = Number(selectedOption.dataset.minimumStock) || 0;

        const quantity = Number(quantityInput.value) || 0;

        const stockAfter = stock + quantity;

        unitLabel.textContent = unit;

        previewMaterialName.textContent = name;

        previewMaterialBarcode.textContent = "Barcode: " + barcode;

        previewStockBefore.textContent = stock + " " + unit;

        previewQuantity.textContent = "+" + quantity + " " + unit;

        previewStockAfter.textContent = stockAfter + " " + unit;

        if (stockAfter <= minimumStock) {
            previewStatus.textContent = "STOK HABIS";

            previewStatus.className = "font-bold text-amber-600";
        } else {
            previewStatus.textContent = "AMAN";

            previewStatus.className = "font-bold text-emerald-600";
        }
    }

    materialSelect.addEventListener("change", updateStockPreview);

    quantityInput.addEventListener("input", updateStockPreview);

    updateStockPreview();
}
