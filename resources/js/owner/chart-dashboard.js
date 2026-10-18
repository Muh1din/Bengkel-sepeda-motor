document.addEventListener("DOMContentLoaded", function () {
    const dashboard = document.getElementById("ownerDashboard");

    if (!dashboard) {
        return;
    }

    const Chart = window.Chart;

    if (!Chart) {
        console.error("Chart.js belum dimuat.");
        return;
    }

    // Ambil data dari atribut HTML yang disediakan Blade.
    const chartLabels = JSON.parse(dashboard.dataset.chartLabels);
    const bookingData = JSON.parse(dashboard.dataset.bookingData);
    const completedData = JSON.parse(dashboard.dataset.completedData);
    const statusData = JSON.parse(dashboard.dataset.statusData);

    const statusLabels = {
        PENDING: "Menunggu",
        IN_PROGRESS: "Dalam Proses",
        COMPLETED: "Selesai",
        CANCELLED: "Dibatalkan",
        INSPECTION: "Pemeriksaan",
        REJECTED: "Ditolak",
    };

    // Line chart: Tren Operasional.
    const operationalCanvas = document.getElementById("operationalChart");

    if (operationalCanvas) {
        new Chart(operationalCanvas, {
            type: "line",

            data: {
                labels: chartLabels,

                datasets: [
                    {
                        label: "Booking Masuk",
                        data: bookingData,
                        borderColor: "#6366f1",
                        backgroundColor: "rgba(99, 102, 241, 0.12)",
                        pointBackgroundColor: "#6366f1",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.35,
                    },
                    {
                        label: "Work Order Selesai",
                        data: completedData,
                        borderColor: "#10b981",
                        backgroundColor: "rgba(16, 185, 129, 0.12)",
                        pointBackgroundColor: "#10b981",
                        pointBorderColor: "#ffffff",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.35,
                    },
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: "index",
                },

                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    }

    // Doughnut chart: Status Work Order.
    const statusCanvas = document.getElementById("workOrderStatusChart");

    if (statusCanvas) {
        const entries = Object.entries(statusData);

        const statusColors = {
            PENDING: "#f59e0b", // Amber
            CONFIRMED: "#3b82f6", // Biru
            IN_PROGRESS: "#8b5cf6", // Ungu
            COMPLETED: "#10b981", // Hijau
            REJECTED: "#ef4444", // Merah
            CANCELLED: "#64748b", // Abu-abu
        };

        new Chart(statusCanvas, {
            type: "doughnut",

            data: {
                labels: entries.map(
                    ([status]) => statusLabels[status] ?? status,
                ),

                datasets: [
                    {
                        data: entries.map(([, total]) => Number(total)),

                        backgroundColor: entries.map(
                            ([status]) => statusColors[status] ?? "#94a3b8",
                        ),

                        borderWidth: 2,
                    },
                ],
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: "bottom",
                    },
                },
            },
        });
    }
});
