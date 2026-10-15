const materialSelect = document.getElementById("material_id");
const currentStockInput = document.getElementById("current_stock");
const physicalStockInput = document.getElementById("physical_stock");

const stockDifference = document.getElementById("stock-difference");
const submitButton = document.getElementById("submit-adjustment");

const projectionCurrentStock = document.getElementById(
    "projection-current-stock",
);

const projectionPhysicalStock = document.getElementById(
    "projection-physical-stock",
);

const projectionDifference = document.getElementById("projection-difference");

const projectionFinalStock = document.getElementById("projection-final-stock");

const adjustmentStatus = document.getElementById("adjustment-status");

function updateAdjustment() {
    const selectedOption = materialSelect.options[materialSelect.selectedIndex];

    const currentStock = Number(selectedOption?.dataset.stock ?? 0);

    const physicalStockValue = physicalStockInput.value;

    const physicalStock =
        physicalStockValue === "" ? 0 : Number(physicalStockValue);

    const difference = physicalStock - currentStock;

    currentStockInput.value = currentStock;

    stockDifference.textContent = difference;

    projectionCurrentStock.textContent = currentStock;
    projectionPhysicalStock.textContent = physicalStock;
    projectionDifference.textContent = difference;
    projectionFinalStock.textContent = physicalStock;

    if (!materialSelect.value) {
        stockDifference.textContent = "0";

        projectionCurrentStock.textContent = "0";
        projectionPhysicalStock.textContent = "0";
        projectionDifference.textContent = "0";
        projectionFinalStock.textContent = "0";

        adjustmentStatus.textContent =
            "Pilih material dan masukkan stok fisik untuk melihat perubahan.";

        submitButton.disabled = true;

        return;
    }

    if (physicalStock === currentStock) {
        adjustmentStatus.textContent =
            "Tidak ada penyesuaian stok yang diperlukan.";

        submitButton.disabled = true;

        return;
    }

    if (difference > 0) {
        adjustmentStatus.textContent = `Stok akan bertambah ${difference} dari stok sistem.`;
    } else {
        adjustmentStatus.textContent = `Stok akan berkurang ${Math.abs(difference)} dari stok sistem.`;
    }

    submitButton.disabled = false;
}

materialSelect.addEventListener("change", updateAdjustment);

physicalStockInput.addEventListener("input", updateAdjustment);

updateAdjustment();
