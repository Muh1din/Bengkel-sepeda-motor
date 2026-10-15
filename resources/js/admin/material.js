import { BrowserMultiFormatReader } from "@zxing/browser";

import { BarcodeFormat, DecodeHintType } from "@zxing/library";

const button = document.getElementById("scanBarcode");
const scanner = document.getElementById("barcodeScanner");
const video = document.getElementById("barcodeVideo");
const input = document.getElementById("barcode");

let codeReader = null;
let controls = null;

if (button && scanner && video && input) {
    button.addEventListener("click", startScanner);
}

async function startScanner() {
    if (codeReader) return;

    console.log("BARCODE SCANNER START");

    try {
        scanner.classList.remove("hidden");

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

        codeReader = new BrowserMultiFormatReader(hints);

        const devices = await BrowserMultiFormatReader.listVideoInputDevices();

        console.log("CAMERAS:", devices);

        if (!devices.length) {
            throw new Error("Camera tidak ditemukan.");
        }

        const deviceId = devices[0].deviceId;

        console.log("USING CAMERA:", devices[0].label);

        controls = await codeReader.decodeFromVideoDevice(
            deviceId,
            video,
            (result, error) => {
                if (result) {
                    const value = result.getText();

                    console.log("================================");
                    console.log("BARCODE BERHASIL:", value);
                    console.log("FORMAT:", result.getBarcodeFormat());
                    console.log("================================");

                    input.value = value;

                    stopScanner();
                }

                // Jangan console.error error di setiap frame.
            },
        );

        console.log("ZXING CAMERA SCANNER READY");
    } catch (error) {
        console.error("BARCODE SCANNER ERROR:", error);

        stopScanner();
    }
}

function stopScanner() {
    console.log("STOP BARCODE SCANNER");

    if (controls) {
        controls.stop();
        controls = null;
    }

    if (codeReader) {
        codeReader.reset();
        codeReader = null;
    }

    if (video) {
        video.pause();
        video.srcObject = null;
    }

    if (scanner) {
        scanner.classList.add("hidden");
    }
}
