document.addEventListener("DOMContentLoaded", function () {
    const serviceType = document.getElementById("service_type");
    const customServiceWrapper = document.getElementById(
        "custom_service_wrapper",
    );
    const customServiceInput = document.getElementById("custom_service");

    function toggleCustomService() {
        const isOther = serviceType.value === "Lainnya";

        customServiceWrapper.classList.toggle("hidden", !isOther);

        customServiceInput.required = isOther;

        if (!isOther) {
            customServiceInput.value = "";
        }
    }

    serviceType.addEventListener("change", toggleCustomService);

    toggleCustomService();
});
