import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { bunny } from "laravel-vite-plugin/fonts";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/option-booking-form.js",
                "resources/js/owner/chart-dashboard.js",
                "resources/js/owner/work-orders.js",
                "resources/js/admin/material-requests.js",
                "resources/js/admin/stock-preview.js",
                "resources/js/admin/stock-adjustments.js",
                "resources/js/admin/create-admin-project.js",
                "resources/js/admin/create-admin-project.js",
            ],
            refresh: true,
            fonts: [
                bunny("Instrument Sans", {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
