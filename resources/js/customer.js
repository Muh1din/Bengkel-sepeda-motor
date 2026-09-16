document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    const openBtn = document.getElementById("open-sidebar-btn"); // Tombol hamburger di header
    const closeBtn = document.getElementById("close-sidebar-btn");
    const profileTrigger = document.getElementById("profile-trigger");
    const profileCard = document.getElementById("profile-card");

    // Re-render Lucide Icons jika library-nya tersedia
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }

    // Fungsi Buka Sidebar
    function openSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
        }
    }

    // Fungsi Tutup Sidebar
    function closeSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        }
    }

    // Expose ke global window (opsional, jika ingin dipanggil dari inline onclick)
    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;

    // Event Listener Tombol Mobile
    if (openBtn) openBtn.addEventListener("click", openSidebar);
    if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
    if (overlay) overlay.addEventListener("click", closeSidebar);

    // Toggle Profile Dropdown
    if (profileTrigger && profileCard) {
        profileTrigger.addEventListener("click", function (e) {
            e.stopPropagation();
            profileCard.classList.toggle("hidden");
        });

        // Klik di luar profile menu untuk menutupnya
        document.addEventListener("click", function (e) {
            if (
                !profileCard.contains(e.target) &&
                !profileTrigger.contains(e.target)
            ) {
                profileCard.classList.add("hidden");
            }
        });
    }
});
