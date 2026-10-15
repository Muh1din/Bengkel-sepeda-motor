function initTrackingLoading() {
    const form = document.querySelector("#tracking form");
    const loading = document.getElementById("tracking-loading");
    const result = document.getElementById("tracking-result");

    if (!form || !loading || !result) {
        return;
    }

    form.addEventListener("submit", function () {
        loading.classList.remove("hidden");
        result.classList.add("hidden");
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initTrackingLoading);
} else {
    initTrackingLoading();
}
