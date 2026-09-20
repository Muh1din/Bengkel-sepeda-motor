document.addEventListener("DOMContentLoaded", function () {
    const logoutButton = document.getElementById("logout-button");
    const logoutModal = document.getElementById("logout-modal");
    const logoutCancel = document.getElementById("logout-cancel");
    const logoutConfirm = document.getElementById("logout-confirm");
    const logoutForm = document.getElementById("logout-form");

    // Jika element logout tidak tersedia, hentikan script.
    if (
        !logoutButton ||
        !logoutModal ||
        !logoutCancel ||
        !logoutConfirm ||
        !logoutForm
    ) {
        return;
    }

    // Buka modal
    logoutButton.addEventListener("click", function () {
        logoutModal.classList.remove("hidden");
        logoutModal.classList.add("flex");
    });

    // Tombol Batal
    logoutCancel.addEventListener("click", function () {
        logoutModal.classList.remove("flex");
        logoutModal.classList.add("hidden");
    });

    // Tombol Keluar
    logoutConfirm.addEventListener("click", function () {
        logoutForm.submit();
    });

    // Klik backdrop
    logoutModal.addEventListener("click", function (event) {
        if (event.target === logoutModal) {
            logoutModal.classList.remove("flex");
            logoutModal.classList.add("hidden");
        }
    });
});
