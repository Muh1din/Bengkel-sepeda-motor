document.addEventListener("DOMContentLoaded", function () {
    
    // 1. AMBIL ELEMEN HTML
    const roleSelect = document.getElementById("role");
    const identityInput = document.getElementById("identity");
    const passwordInput = document.getElementById("password");
    const loginForm = document.getElementById("loginForm");

    const notification = document.getElementById("notification");
    const notificationMessage = document.getElementById("notificationMessage");

    const togglePasswordButton = document.getElementById("togglePassword");
    const eyeOpenIcon = document.getElementById("eyeOpenIcon");
    const eyeClosedIcon = document.getElementById("eyeClosedIcon");

    const forgotPasswordLink = document.getElementById("forgotPassword");
    const registerLink = document.getElementById("registerLink");

    // 2. KONFIGURASI AKUN DEMO
    const demoUsers = {
        owner: {
            email: "owner@nirwanagarage.test",
            password: "rahasia",
            dashboard: "/design/dashboard/owner/index.html",
            name: "Owner",
        },

        admin: {
            email: "admin@nirwanagarage.test",
            password: "rahasia",
            dashboard: "design/dashboard/admin/index.html",
            name: "Admin",
        },

        "service-advisor": {
            email: "sa@nirwanagarage.test",
            password: "rahasia",
            dashboard: "design/dashboard/service-advisor/index.html",
            name: "Service Advisor",
        },

        mechanic: {
            email: "mekanik@nirwanagarage.test",
            password: "rahasia",
            dashboard: "design/dashboard/mechanic/index.html",
            name: "Mekanik",
        },

        customer: {
            email: "customer@nirwanagarage.test",
            password: "rahasia",
            dashboard: "design/dashboard/customer/index.html",
            name: "Customer",
        },
    };

    // 3. VALIDASI ELEMEN HTML
    if (!roleSelect || !identityInput || !passwordInput || !loginForm) {
        console.error("Login gagal diinisialisasi. Periksa ID elemen HTML.");

        return;
    }

    console.log("Login JavaScript berhasil dimuat.");

    // 4. FUNGSI NOTIFIKASI
    function showNotification(message, type = "info") {
        if (!notification || !notificationMessage) {
            alert(message);
            return;
        }

        notification.hidden = false;
        notificationMessage.textContent = message;

        notification.classList.remove(
            "border-blue-200",
            "bg-blue-50",
            "text-blue-800",
            "border-green-200",
            "bg-green-50",
            "text-green-800",
            "border-red-200",
            "bg-red-50",
            "text-red-800",
        );

        if (type === "success") {
            notification.classList.add(
                "border-green-200",
                "bg-green-50",
                "text-green-800",
            );
        } else if (type === "error") {
            notification.classList.add(
                "border-red-200",
                "bg-red-50",
                "text-red-800",
            );
        } else {
            notification.classList.add(
                "border-blue-200",
                "bg-blue-50",
                "text-blue-800",
            );
        }
    }

    // 5. AUTOFILL EMAIL DAN PASSWORD
    function fillCredentials() {
        const selectedRole = roleSelect.value;
        const user = demoUsers[selectedRole];

        console.log("Role dipilih:", selectedRole);

        if (!user) {
            console.error(
                "Credential untuk role tidak ditemukan:",
                selectedRole,
            );

            return;
        }

        // Isi email dan password berdasarkan role.
        identityInput.value = user.email;
        passwordInput.value = user.password;

        // Sembunyikan password setelah role berubah.
        passwordInput.type = "password";

        if (eyeOpenIcon) {
            eyeOpenIcon.classList.remove("hidden");
        }

        if (eyeClosedIcon) {
            eyeClosedIcon.classList.add("hidden");
        }

        if (togglePasswordButton) {
            togglePasswordButton.setAttribute(
                "aria-label",
                "Tampilkan password",
            );

            togglePasswordButton.setAttribute("aria-pressed", "false");
        }

        if (notification) {
            notification.hidden = true;
        }

        console.log("Email berhasil diisi:", user.email);
        console.log("Dashboard tujuan:", user.dashboard);
    }

    // Autofill ketika halaman pertama kali dibuka.
    fillCredentials();

    // Autofill ketika role diganti.
    roleSelect.addEventListener("change", fillCredentials);

    // 6. TOGGLE PASSWORD
    if (togglePasswordButton) {
        togglePasswordButton.addEventListener("click", function () {
            const isHidden = passwordInput.type === "password";

            passwordInput.type = isHidden ? "text" : "password";

            if (eyeOpenIcon) {
                eyeOpenIcon.classList.toggle("hidden", isHidden);
            }

            if (eyeClosedIcon) {
                eyeClosedIcon.classList.toggle("hidden", !isHidden);
            }

            togglePasswordButton.setAttribute(
                "aria-label",
                isHidden ? "Sembunyikan password" : "Tampilkan password",
            );

            togglePasswordButton.setAttribute("aria-pressed", String(isHidden));
        });
    }

    // 7. PROSES LOGIN DEMO
    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const selectedRole = roleSelect.value;
        const user = demoUsers[selectedRole];

        if (!user) {
            showNotification("Role tidak ditemukan.", "error");
            return;
        }

        const email = identityInput.value.trim().toLowerCase();
        const password = passwordInput.value;

        if (email !== user.email.toLowerCase() || password !== user.password) {
            showNotification(
                "Email atau password tidak sesuai dengan role yang dipilih.",
                "error",
            );
            return;
        }

        // Tentukan tujuan berdasarkan role yang dipilih.
        const targetUrl = new URL(user.dashboard, window.location.origin);

        console.clear();
        console.log("Role:", selectedRole);
        console.log("URL tujuan:", targetUrl.href);

        // Langsung redirect tanpa setTimeout / konfirmasi
        window.location.assign(targetUrl.href);
    });

    // 8. SIMULASI LUPA PASSWORD
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener("click", function (event) {
            event.preventDefault();

            showNotification(
                "Fitur lupa kata sandi belum tersedia pada versi demo.",
                "info",
            );
        });
    }

    // ==================================================
    // 9. SIMULASI PENDAFTARAN
    // ==================================================

    if (registerLink) {
        registerLink.addEventListener("click", function (event) {
            event.preventDefault();

            showNotification(
                "Fitur pendaftaran akun belum tersedia pada versi demo.",
                "info",
            );
        });
    }
});
