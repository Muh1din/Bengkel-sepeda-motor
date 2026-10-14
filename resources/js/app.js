import { createIcons, icons } from "lucide";import Chart from "chart.js/auto";


import "./login";
import "./customer";
import "./logoutModal";
import "./register";
import "./riwayat-servis";

import "./serviceAdvisor/modal-dashboard";
import "./serviceAdvisor/modal-booking";
import "./serviceAdvisor/modal-work-order";
import "./serviceAdvisor/search-customer";

import "./rejected-reson";

import "./mechanic/modal-detail-dashboard";
import "./mechanic/inspection";
import "./home";
import "./modal-booking-confirm";


function initializeIcons() {
    createIcons({
        icons,
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeIcons);
} else {
    initializeIcons();
}

window.Chart = Chart;
