window.toggleField = function (inputId, closedIconId, openIconId) {
    const input = document.getElementById(inputId);
    const closedIcon = document.getElementById(closedIconId);
    const openIcon = document.getElementById(openIconId);

    if (!input) return;

    if (input.type === "password") {
        input.type = "text";
        closedIcon?.classList.add("hidden");
        openIcon?.classList.remove("hidden");
    } else {
        input.type = "password";
        closedIcon?.classList.remove("hidden");
        openIcon?.classList.add("hidden");
    }
};
