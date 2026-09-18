<?php
/* =========================================================
   SCAN QR PAGE
   Phone-only QR scanner
   ========================================================= */

/*
   Basic mobile device detection.
   Note: User-agent detection is not a security boundary.
*/

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

$isMobile = preg_match(
    '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile/i',
    $userAgent
);

if (!$isMobile) {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mobile Access Only</title>

        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;

                font-family: Arial, Helvetica, sans-serif;
                background: #f5f7fb;
                color: #17213d;
            }

            .blocked-card {
                width: 100%;
                max-width: 380px;
                padding: 32px 24px;
                text-align: center;

                background: #ffffff;
                border: 1px solid #e1e5ef;
                border-radius: 20px;

                box-shadow: 0 12px 35px rgba(30, 45, 90, .06);
            }

            .blocked-icon {
                width: 64px;
                height: 64px;
                margin: 0 auto 20px;

                display: flex;
                align-items: center;
                justify-content: center;

                border-radius: 18px;
                background: #eef0ff;
                color: #4b50c5;

                font-size: 30px;
            }

            h1 {
                margin: 0 0 12px;
                font-size: 22px;
            }

            p {
                margin: 0;
                color: #66718e;
                font-size: 14px;
                line-height: 1.6;
            }
        </style>
    </head>
    <body>

        <div class="blocked-card">
            <div class="blocked-icon">📱</div>

            <h1>Mobile Access Only</h1>

            <p>
                The QR Scanner is available only on mobile devices.
                Please open this page using your phone.
            </p>
        </div>

    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Scan QR | Organization / Asset Management</title>

    <style>
        /* =====================================================
           GLOBAL
           ===================================================== */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #17213d;
            overflow-x: hidden;
        }

        button {
            font-family: inherit;
        }


        /* =====================================================
           APP HEADER
           ===================================================== */

        .scanner-header {
            position: sticky;
            top: 0;
            z-index: 20;

            display: flex;
            align-items: center;
            gap: 12px;

            width: 100%;
            min-height: 66px;
            padding: 12px 16px;

            background: #ffffff;
            border-bottom: 1px solid #e1e5ef;
        }

        .back-button {
            display: flex;
            align-items: center;
            justify-content: center;

            flex: 0 0 38px;

            width: 38px;
            height: 38px;

            border: 1px solid #e1e5ef;
            border-radius: 10px;

            background: #ffffff;
            color: #4b50c5;

            font-size: 20px;
            text-decoration: none;

            cursor: pointer;
        }

        .back-button:active {
            background: #eef0ff;
        }

        .header-logo {
            display: flex;
            align-items: center;
            justify-content: center;

            flex: 0 0 38px;

            width: 38px;
            height: 38px;

            border-radius: 11px;

            background: #4b50c5;
            color: #ffffff;

            font-size: 14px;
            font-weight: 800;
        }

        .header-text {
            min-width: 0;
        }

        .header-text h1 {
            margin: 0;

            font-size: 15px;
            font-weight: 800;
            line-height: 1.3;
        }

        .header-text p {
            margin: 3px 0 0;

            color: #66718e;
            font-size: 10px;
        }


        /* =====================================================
           MAIN SCANNER CONTENT
           ===================================================== */

        .scanner-page {
            width: 100%;
            max-width: 520px;

            margin: 0 auto;
            padding: 22px 16px 36px;
        }

        .scanner-intro {
            margin-bottom: 18px;
        }

        .scanner-intro .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            margin-bottom: 9px;

            color: #4b50c5;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .scanner-intro .eyebrow::before {
            content: "";

            width: 7px;
            height: 7px;

            border-radius: 50%;
            background: #4b50c5;
        }

        .scanner-intro h2 {
            margin: 0 0 8px;

            color: #17213d;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.15;
        }

        .scanner-intro p {
            margin: 0;

            color: #66718e;
            font-size: 13px;
            line-height: 1.6;
        }


        /* =====================================================
           CAMERA CARD
           ===================================================== */

        .scanner-card {
            padding: 12px;

            background: #ffffff;
            border: 1px solid #e1e5ef;
            border-radius: 20px;

            box-shadow: 0 10px 30px rgba(30, 45, 90, .05);
        }

        .camera-container {
            position: relative;

            width: 100%;
            aspect-ratio: 1 / 1;

            min-height: 260px;
            max-height: 430px;

            overflow: hidden;

            background: #101522;
            border-radius: 14px;
        }

        #cameraVideo {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: cover;
        }

        /* Camera scanning overlay */

        .scan-overlay {
            position: absolute;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            pointer-events: none;
        }

        .scan-frame {
            position: relative;

            width: 62%;
            aspect-ratio: 1 / 1;

            border: 2px solid rgba(255, 255, 255, .9);
            border-radius: 18px;

            box-shadow:
                0 0 0 999px rgba(0, 0, 0, .28);
        }

        .scan-frame::before,
        .scan-frame::after {
            content: "";

            position: absolute;

            width: 25px;
            height: 25px;

            border-color: #8d93ff;
            border-style: solid;
        }

        .scan-frame::before {
            top: -2px;
            left: -2px;

            border-width: 4px 0 0 4px;
            border-radius: 12px 0 0 0;
        }

        .scan-frame::after {
            right: -2px;
            bottom: -2px;

            border-width: 0 4px 4px 0;
            border-radius: 0 0 12px 0;
        }

        .scan-line {
            position: absolute;
            left: 8%;
            right: 8%;
            top: 50%;

            height: 2px;

            background: #8d93ff;
            box-shadow: 0 0 10px rgba(141, 147, 255, .8);

            animation: scanLine 2s ease-in-out infinite;
        }

        @keyframes scanLine {
            0% {
                transform: translateY(-55px);
                opacity: .5;
            }

            50% {
                transform: translateY(55px);
                opacity: 1;
            }

            100% {
                transform: translateY(-55px);
                opacity: .5;
            }
        }

        .camera-status {
            padding: 12px 4px 2px;

            text-align: center;

            color: #66718e;
            font-size: 12px;
            line-height: 1.5;
        }

        .camera-status.success {
            color: #198754;
            font-weight: 700;
        }

        .camera-status.error {
            color: #dc3545;
            font-weight: 700;
        }


        /* =====================================================
           BUTTONS
           ===================================================== */

        .scanner-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;

            margin-top: 14px;
        }

        .scanner-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 46px;
            padding: 12px 14px;

            border: 0;
            border-radius: 11px;

            font-size: 13px;
            font-weight: 800;

            cursor: pointer;
            transition: .2s ease;
        }

        .scanner-button:active {
            transform: scale(.98);
        }

        .scanner-button.primary {
            background: #4b50c5;
            color: #ffffff;
        }

        .scanner-button.primary:hover {
            background: #303a9b;
        }

        .scanner-button.secondary {
            background: #eef0ff;
            color: #4b50c5;
        }

        .scanner-button.secondary:hover {
            background: #e1e4ff;
        }

        .scanner-button:disabled {
            opacity: .5;
            cursor: not-allowed;
        }


        /* =====================================================
           SCAN RESULT
           ===================================================== */

        .scan-result {
            display: none;

            margin-top: 18px;
            padding: 18px;

            background: #ffffff;
            border: 1px solid #dfe4ef;
            border-radius: 16px;

            box-shadow: 0 8px 24px rgba(30, 45, 90, .04);
        }

        .scan-result.show {
            display: block;
        }

        .result-heading {
            display: flex;
            align-items: center;
            gap: 10px;

            margin-bottom: 14px;
        }

        .result-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 36px;
            height: 36px;

            border-radius: 10px;

            background: #e8f7ee;
            color: #198754;

            font-size: 18px;
        }

        .result-heading h3 {
            margin: 0;

            font-size: 15px;
            font-weight: 800;
        }

        .result-heading p {
            margin: 3px 0 0;

            color: #66718e;
            font-size: 11px;
        }

        .result-value-box {
            padding: 12px;

            background: #f8f9ff;
            border: 1px solid #e8eaf5;
            border-radius: 10px;
        }

        .result-value-label {
            display: block;

            margin-bottom: 6px;

            color: #697492;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        #scanResultText {
            display: block;

            color: #17213d;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;

            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .result-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;

            margin-top: 12px;
        }

        .result-actions a,
        .result-actions button {
            display: flex;
            align-items: center;
            justify-content: center;

            min-height: 42px;
            padding: 10px;

            border-radius: 10px;

            font-size: 12px;
            font-weight: 800;
            text-decoration: none;

            cursor: pointer;
        }

        .result-actions .view-button {
            border: 0;
            background: #4b50c5;
            color: #ffffff;
        }

        .result-actions .clear-button {
            border: 1px solid #e1e5ef;
            background: #ffffff;
            color: #66718e;
        }


        /* =====================================================
           NO CAMERA / UNSUPPORTED STATE
           ===================================================== */

        .scanner-message {
            display: none;

            margin-top: 16px;
            padding: 14px;

            border: 1px solid #f0d9a8;
            border-radius: 12px;

            background: #fffaf0;
            color: #856404;

            font-size: 12px;
            line-height: 1.6;
        }

        .scanner-message.show {
            display: block;
        }


        /* =====================================================
           SMALL PHONES
           ===================================================== */

        @media (max-width: 380px) {
            .scanner-page {
                padding-left: 12px;
                padding-right: 12px;
            }

            .scanner-intro h2 {
                font-size: 25px;
            }

            .camera-container {
                min-height: 230px;
            }

            .scanner-actions,
            .result-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================================
         HEADER
         ===================================================== -->

    <header class="scanner-header">

        <!-- Palitan ang href kung iba ang actual dashboard route -->
        <a href="dashboard" class="back-button" aria-label="Back">
            ‹
        </a>

        <div class="header-logo">
            OA
        </div>

        <div class="header-text">
            <h1>Scan QR</h1>
            <p>Organization / Asset Management</p>
        </div>

    </header>


    <!-- =====================================================
         MAIN
         ===================================================== -->

    <main class="scanner-page">

        <section class="scanner-intro">
            <div class="eyebrow">
                Asset Scanner
            </div>

            <h2>Scan Asset QR</h2>

            <p>
                Point your phone camera at an asset QR code
                to view its assigned information.
            </p>
        </section>


        <!-- =================================================
             CAMERA CARD
             ================================================= -->

        <section class="scanner-card">

            <div class="camera-container">

                <video
                    id="cameraVideo"
                    autoplay
                    muted
                    playsinline
                ></video>

                <div class="scan-overlay">
                    <div class="scan-frame">
                        <div class="scan-line"></div>
                    </div>
                </div>

            </div>

            <div class="camera-status" id="cameraStatus">
                Press Start Camera to begin scanning.
            </div>

            <div class="scanner-actions">

                <button
                    type="button"
                    class="scanner-button primary"
                    id="startCameraBtn"
                >
                    📷 Start Camera
                </button>

                <button
                    type="button"
                    class="scanner-button secondary"
                    id="stopCameraBtn"
                    disabled
                >
                    ■ Stop
                </button>

            </div>

        </section>


        <!-- =================================================
             SCAN RESULT
             ================================================= -->

        <section class="scan-result" id="scanResult">

            <div class="result-heading">

                <div class="result-icon">
                    ✓
                </div>

                <div>
                    <h3>QR Code Detected</h3>
                    <p>The scanner successfully read the QR code.</p>
                </div>

            </div>

            <div class="result-value-box">

                <span class="result-value-label">
                    Scanned QR Value
                </span>

                <span id="scanResultText">
                    -
                </span>

            </div>

            <div class="result-actions">

                <!--
                    Palitan ang URL na ito kapag mayroon ka nang
                    asset details page.

                    Example:
                    asset-details.php?qr=...
                -->

                <a
                    href="#"
                    class="view-button"
                    id="viewAssetBtn"
                >
                    View Asset Details
                </a>

                <button
                    type="button"
                    class="clear-button"
                    id="clearResultBtn"
                >
                    Scan Again
                </button>

            </div>

        </section>


        <!-- =================================================
             MESSAGE
             ================================================= -->

        <div class="scanner-message" id="scannerMessage"></div>

    </main>


    <script>
        /* =====================================================
           QR SCANNER
           Native BarcodeDetector API
           ===================================================== */

        const video = document.getElementById("cameraVideo");

        const startCameraBtn = document.getElementById("startCameraBtn");
        const stopCameraBtn = document.getElementById("stopCameraBtn");

        const cameraStatus = document.getElementById("cameraStatus");

        const scanResult = document.getElementById("scanResult");
        const scanResultText = document.getElementById("scanResultText");

        const viewAssetBtn = document.getElementById("viewAssetBtn");
        const clearResultBtn = document.getElementById("clearResultBtn");

        const scannerMessage = document.getElementById("scannerMessage");

        let cameraStream = null;
        let scanning = false;
        let animationFrameId = null;

        let barcodeDetector = null;


        /* =====================================================
           STATUS HELPERS
           ===================================================== */

        function setStatus(message, type = "") {
            cameraStatus.textContent = message;
            cameraStatus.className = "camera-status";

            if (type) {
                cameraStatus.classList.add(type);
            }
        }

        function showMessage(message) {
            scannerMessage.textContent = message;
            scannerMessage.classList.add("show");
        }

        function hideMessage() {
            scannerMessage.textContent = "";
            scannerMessage.classList.remove("show");
        }


        /* =====================================================
           CHECK SUPPORT
           ===================================================== */

        function checkScannerSupport() {

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showMessage(
                    "Camera access is not supported by this browser. " +
                    "Please use a modern mobile browser."
                );

                startCameraBtn.disabled = true;
                return false;
            }

            if (!("BarcodeDetector" in window)) {
                showMessage(
                    "This browser does not support the built-in QR scanner. " +
                    "Please use a compatible browser or add a QR scanning library."
                );

                startCameraBtn.disabled = true;
                return false;
            }

            return true;
        }


        /* =====================================================
           START CAMERA
           ===================================================== */

        async function startCamera() {

            hideMessage();

            if (!checkScannerSupport()) {
                return;
            }

            try {

                barcodeDetector = new BarcodeDetector({
                    formats: ["qr_code"]
                });

                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: "environment"
                        },
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    },
                    audio: false
                });

                video.srcObject = cameraStream;

                await video.play();

                scanning = true;

                startCameraBtn.disabled = true;
                stopCameraBtn.disabled = false;

                setStatus("Camera active. Point it at a QR code.");

                scanFrame();

            } catch (error) {

                console.error("Camera error:", error);

                let message =
                    "Unable to access the camera. Please allow camera permission.";

                if (error.name === "NotAllowedError") {
                    message =
                        "Camera permission was denied. Please allow camera access in your browser settings.";
                } else if (error.name === "NotFoundError") {
                    message =
                        "No camera was found on this device.";
                } else if (error.name === "NotReadableError") {
                    message =
                        "The camera is currently being used by another application.";
                } else if (error.name === "SecurityError") {
                    message =
                        "Camera access requires HTTPS or localhost.";
                }

                showMessage(message);
                setStatus("Camera could not be started.", "error");

            }

        }


        /* =====================================================
           SCAN FRAME
           ===================================================== */

        async function scanFrame() {

            if (!scanning) {
                return;
            }

            if (video.readyState >= 2 && video.videoWidth > 0) {

                try {

                    const barcodes = await barcodeDetector.detect(video);

                    if (barcodes.length > 0) {

                        const qrValue = barcodes[0].rawValue;

                        if (qrValue) {
                            handleScanResult(qrValue);
                            return;
                        }

                    }

                } catch (error) {
                    console.warn("QR detection error:", error);
                }

            }

            animationFrameId = requestAnimationFrame(scanFrame);
        }


        /* =====================================================
           HANDLE RESULT
           ===================================================== */

        function handleScanResult(value) {

            scanning = false;

            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
                animationFrameId = null;
            }

            scanResultText.textContent = value;

            scanResult.classList.add("show");

            /*
               IMPORTANT:
               Ito ay example lamang.

               Kung ang QR value ay asset ID:
               asset-details.php?asset_id=VALUE

               Kung ang QR value ay serial number:
               asset-details.php?serial=VALUE

               Palitan ayon sa actual asset details route mo.
            */

            const encodedValue = encodeURIComponent(value);

            viewAssetBtn.href =
                "asset-details.php?qr=" + encodedValue;

            setStatus("QR code detected successfully.", "success");

            stopCamera(false);

            // Scroll to result
            setTimeout(() => {
                scanResult.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }, 100);

        }


        /* =====================================================
           STOP CAMERA
           ===================================================== */

        function stopCamera(updateStatus = true) {

            scanning = false;

            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
                animationFrameId = null;
            }

            if (cameraStream) {

                cameraStream.getTracks().forEach(track => {
                    track.stop();
                });

                cameraStream = null;
            }

            video.srcObject = null;

            startCameraBtn.disabled = false;
            stopCameraBtn.disabled = true;

            if (updateStatus) {
                setStatus("Camera stopped. Press Start Camera to scan again.");
            }

        }


        /* =====================================================
           CLEAR RESULT / SCAN AGAIN
           ===================================================== */

        function clearResult() {

            scanResult.classList.remove("show");
            scanResultText.textContent = "-";
            viewAssetBtn.href = "#";

            hideMessage();

            setStatus("Press Start Camera to begin scanning.");

        }


        /* =====================================================
           EVENTS
           ===================================================== */

        startCameraBtn.addEventListener("click", startCamera);

        stopCameraBtn.addEventListener("click", () => {
            stopCamera();
        });

        clearResultBtn.addEventListener("click", () => {

            clearResult();

            startCamera();

        });


        /* =====================================================
           CLEANUP
           ===================================================== */

        window.addEventListener("pagehide", () => {
            stopCamera(false);
        });

        window.addEventListener("beforeunload", () => {
            stopCamera(false);
        });

    </script>

</body>
</html>