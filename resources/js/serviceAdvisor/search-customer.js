const searchInput = document.getElementById("customerSearch");
const rows = document.querySelectorAll(".customer-row");

if (searchInput) {
    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase().trim();

        rows.forEach((row) => {
            const data = row.dataset.search;

            row.style.display = data.includes(keyword) ? "" : "none";
        });
    });
}
