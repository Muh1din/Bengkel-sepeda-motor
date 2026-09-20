document.addEventListener("DOMContentLoaded", function () {

    // 1. Re-render Lucide Icons paling awal
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }

    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    const openBtn = document.getElementById("open-sidebar-btn");
    const closeBtn = document.getElementById("close-sidebar-btn");
    const profileTrigger = document.getElementById("profile-trigger");
    const profileCard = document.getElementById("profile-card");

    // 2. Sidebar Handling
    function openSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
        }
    }

    function closeSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        }
    }

    window.openSidebar = openSidebar;
    window.closeSidebar = closeSidebar;

    if (openBtn) {
        openBtn.addEventListener("click", openSidebar);
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", closeSidebar);
    }

    if (overlay) {
        overlay.addEventListener("click", closeSidebar);
    }

    // 3. Profile Menu Handling
    if (profileTrigger && profileCard) {
        profileTrigger.addEventListener("click", function (e) {
            e.stopPropagation();
            profileCard.classList.toggle("hidden");
        });

        document.addEventListener("click", function (e) {
            if (
                !profileCard?.contains(e.target) &&
                !profileTrigger?.contains(e.target)
            ) {
                profileCard.classList.add("hidden");
            }
        });
    }

});
