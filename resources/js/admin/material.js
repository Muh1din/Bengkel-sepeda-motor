import { BrowserMultiFormatReader } from "@zxing/browser";
import { BarcodeFormat, DecodeHintType } from "@zxing/library";

const button = document.getElementById("scanBarcode");
const closeButton = document.getElementById("stopBarcodeScanner");
const scanner = document.getElementById("barcodeScanner");
const status = document.getElementById("barcodeScannerStatus");
const video = document.getElementById("barcodeVideo");
const input = document.getElementById("barcode");

if (button && closeButton && scanner && status && video && input) {
    const hints = new Map();

    hints.set(DecodeHintType.POSSIBLE_FORMATS, [
        BarcodeFormat.EAN_13,
        BarcodeFormat.EAN_8,
        BarcodeFormat.UPC_A,
        BarcodeFormat.UPC_E,
        BarcodeFormat.CODE_128,
        BarcodeFormat.CODE_39,
        BarcodeFormat.ITF,
    ]);
    hints.set(DecodeHintType.TRY_HARDER, true);

    const codeReader = new BrowserMultiFormatReader(hints);
    let controls = null;
    let isStarting = false;
    let scannerSession = 0;

    button.addEventListener("click", startScanner);
    closeButton.addEventListener("click", stopScanner);

    async function startScanner() {
        if (controls || isStarting) {
            return;
        }

        if (!navigator.mediaDevices?.getUserMedia) {
            showError("Browser ini tidak mendukung kamera. Coba gunakan Chrome atau Safari terbaru.");
            return;
        }

        if (!window.isSecureContext) {
            showError("Kamera hanya dapat digunakan melalui HTTPS atau localhost.");
            return;
        }

        isStarting = true;
        button.disabled = true;
        scanner.classList.remove("hidden");
        status.textContent = "Meminta akses kamera…";
        const currentSession = ++scannerSession;

        try {
            controls = await codeReader.decodeFromConstraints(
                {
                    audio: false,
                    video: {
                        facingMode: { ideal: "environment" },
                    },
                },
                video,
                (result) => {
                    if (!result || currentSession !== scannerSession) {
                        return;
                    }

                    input.value = result.getText();
                    input.dispatchEvent(new Event("input", { bubbles: true }));
                    input.dispatchEvent(new Event("change", { bubbles: true }));
                    input.focus();
                    stopScanner();
                },
            );

            if (currentSession !== scannerSession) {
                controls.stop();
                controls = null;
                stopVideoTracks();
                return;
            }

            status.textContent = "Arahkan kamera belakang ke barcode.";
        } catch (error) {
            stopScanner();
            showError(getCameraErrorMessage(error));
        } finally {
            isStarting = false;
            button.disabled = false;
        }
    }

    function stopScanner() {
        scannerSession++;
        controls?.stop();
        controls = null;
        stopVideoTracks();
        scanner.classList.add("hidden");
        button.disabled = isStarting;
    }

    function stopVideoTracks() {
        video.srcObject?.getTracks().forEach((track) => track.stop());
        video.pause();
        video.srcObject = null;
    }

    function showError(message) {
        scanner.classList.remove("hidden");
        status.textContent = message;
    }

    function getCameraErrorMessage(error) {
        switch (error?.name) {
            case "NotAllowedError":
            case "PermissionDeniedError":
                return "Izin kamera ditolak. Izinkan akses kamera di pengaturan browser, lalu coba lagi.";
            case "NotFoundError":
            case "DevicesNotFoundError":
                return "Kamera tidak ditemukan pada perangkat ini.";
            case "NotReadableError":
            case "TrackStartError":
                return "Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.";
            default:
                return "Kamera gagal dibuka. Periksa izin kamera dan coba lagi.";
        }
    }
}
