document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("work-order-search");
    const statusFilter = document.getElementById("work-order-status-filter");
    const rows = document.querySelectorAll(".work-order-row");
    const noResults = document.getElementById("work-order-no-results");

    function filterWorkOrders() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;
        let visibleCount = 0;

        rows.forEach(function (row) {
            const searchData = row.dataset.search || "";
            const status = row.dataset.status || "";

            const matchesSearch = searchData.includes(searchTerm);
            const matchesStatus = !selectedStatus || status === selectedStatus;
            const isVisible = matchesSearch && matchesStatus;

            row.classList.toggle("hidden", !isVisible);

            if (isVisible) {
                visibleCount++;
            }
        });

        noResults.classList.toggle(
            "hidden",
            visibleCount > 0 || rows.length === 0,
        );
    }

    searchInput.addEventListener("input", filterWorkOrders);
    statusFilter.addEventListener("change", filterWorkOrders);
});
