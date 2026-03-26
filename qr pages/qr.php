<?php
// Start output buffering to prevent headers being sent before HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator | NexusTools</title>
    <!-- Google Fonts for Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        :root {
            --bg-main: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-hover: rgba(45, 61, 86, 0.9);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary-color: #8b5cf6; /* Purple for QR Generator */
            --primary-hover: #7c3aed;
            --success: #10b981;
            --error: #ef4444;
            --border-color: rgba(255, 255, 255, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* --- Navbar --- */
        header {
            padding: 2rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #f8fafc, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo i {
            color: var(--text-main);
            -webkit-text-fill-color: var(--text-main);
        }

        nav ul {
            display: flex; gap: 2rem; list-style: none;
        }
        
        nav a {
            color: var(--text-muted); text-decoration: none; font-weight: 500; transition: color 0.3s ease;
        }

        nav a:hover, nav a.active {
            color: var(--text-main);
        }

        /* --- Hero Section --- */
        .hero {
            text-align: center;
            padding: 3rem 1rem 2rem;
            max-width: 800px;
            margin: 0 auto;
            z-index: 10;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        /* --- Converter Section --- */
        .converter-container {
            max-width: 800px;
            margin: 0 auto 4rem auto;
            position: relative;
            z-index: 10;
            padding: 0 5%;
            width: 100%;
        }

        .converter-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 3rem;
            backdrop-filter: blur(16px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
        }

        .input-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .input-section input {
            padding: 1.25rem;
            border-radius: 12px;
            border: 2px solid var(--border-color);
            background: rgba(0, 0, 0, 0.2);
            color: var(--text-main);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .input-section input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: rgba(139, 92, 246, 0.05);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        .input-section input::placeholder {
            color: var(--text-muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 32px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            width: 100%;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(139, 92, 246, 0.5);
        }

        .qr-display {
            margin-top: 2rem;
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        .qr-display.show-img {
            display: flex;
        }

        .qr-container {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            display: inline-block;
        }

        .qr-display img {
            display: block;
            width: 100%;
            height: auto;
            max-width: 250px;
            max-height: 250px;
            border-radius: 8px;
        }

        .download-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        .download-btn {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            flex: 1;
            min-width: 110px;
            justify-content: center;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--text-muted);
        }

        .download-btn.png:hover { border-color: #3b82f6; color: #60a5fa; }
        .download-btn.jpg:hover { border-color: #ef4444; color: #f87171; }
        .download-btn.pdf:hover { border-color: #10b981; color: #34d399; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .loading {
            display: none;
            text-align: center;
            padding: 1.5rem;
            color: var(--primary-color);
        }

        .loading.show {
            display: block;
        }

        .loading i {
            font-size: 2rem;
            margin-bottom: 1rem;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .qr-info {
            color: var(--text-muted);
            font-size: 0.95rem;
            word-break: break-word;
            max-width: 100%;
        }

        .download-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            font-weight: 500;
            align-items: center;
            gap: 10px;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* --- Features Section --- */
        .features {
            padding: 4rem 5%;
            margin-bottom: 4rem;
            position: relative;
            z-index: 10;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 3rem;
            color: var(--text-main);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }

        .feature-card {
            padding: 2.5rem 2rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            text-align: center;
            transition: var(--transition);
            backdrop-filter: blur(16px);
        }

        .feature-card:hover {
            background: var(--card-hover);
            transform: translateY(-5px);
            border-color: rgba(255,255,255,0.15);
        }

        .feature-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            background: rgba(139, 92, 246, 0.1);
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* --- Footer --- */
        footer {
            margin-top: auto;
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            border-top: 1px solid var(--border-color);
            position: relative;
            z-index: 10;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.05rem; }
            .converter-card { padding: 2rem 1.5rem; }
            header { flex-direction: column; gap: 1rem; }
            .download-options { flex-direction: column; gap: 0.75rem; }
            .download-btn { width: 100%; }
        }
    </style>
</head>
<body>

    <header>
        <a href="../index.php" class="logo">
            <i class="fa-solid fa-layer-group"></i> ALLIN1 
        </a>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../faq.php">Q&A</a></li>
                <li><a href="../contact.php">Feedback</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Generate QR Codes Instantly</h1>
        <p>Convert any text or URL into a beautiful, high-quality, scannable QR code. Fast, free, and secure.</p>
    </section>

    <!-- Converter Form -->
    <div class="converter-container">
        <div class="converter-card">
            
            <div class="input-section">
                <input type="text" id="qrtext" placeholder="Enter text or URL (e.g., https://example.com)">
                <button class="btn btn-primary" onclick="GenerateQR()">
                    <i class="fas fa-qrcode"></i> Generate QR Code
                </button>
            </div>

            <div class="loading" id="loading">
                <i class="fas fa-circle-notch"></i>
                <p>Generating Code...</p>
            </div>

            <div class="qr-display" id="imgbox">
                <div class="qr-container">
                    <img id="qrimage" src="" alt="QR Code">
                </div>
                <div class="qr-info" id="qrInfo"></div>
                <div class="download-options">
                    <button id="downloadPng" class="download-btn png">
                        <i class="fas fa-file-image"></i> PNG
                    </button>
                    <button id="downloadJpg" class="download-btn jpg">
                        <i class="fas fa-file-image"></i> JPG
                    </button>
                    <button id="downloadPdf" class="download-btn pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">Why use our QR Generator?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                <h3>Lightning Fast</h3>
                <p>Instantly generate and download your codes with zero latency.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-download"></i></div>
                <h3>Multiple Formats</h3>
                <p>Export your QR code in high-resolution PNG, JPG, or even vector PDF.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>Fully Secure</h3>
                <p>Your data stays completely private. Operations run client-side where possible.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; 2026 NexusTools. All rights reserved.
    </footer>

    <!-- Download success notification -->
    <div class="download-success" id="downloadSuccess">
        <i class="fas fa-check-circle"></i> <span id="successMessage">Download started!</span>
    </div>

    <script>
    // Global variables
    const imgbox = document.getElementById("imgbox");
    const qrimage = document.getElementById("qrimage");
    const qrtext = document.getElementById("qrtext");
    const loading = document.getElementById("loading");
    const downloadPng = document.getElementById("downloadPng");
    const downloadJpg = document.getElementById("downloadJpg");
    const downloadPdf = document.getElementById("downloadPdf");
    const qrInfo = document.getElementById("qrInfo");
    const downloadSuccess = document.getElementById("downloadSuccess");
    const successMessage = document.getElementById("successMessage");

    let qrData = "";
    let qrGenerated = false;
    let currentQRUrl = "";

    // Generate QR Code function
    async function GenerateQR() {
        const inputText = qrtext.value.trim();
        
        if (!inputText) {
            alert("Please enter text or URL to generate QR code");
            qrtext.focus();
            return;
        }
        
        qrData = inputText;
        
        // Show loading, hide previous QR
        loading.classList.add("show");
        imgbox.classList.remove("show-img");
        qrGenerated = false;
        
        try {
            // Generate QR code using an API
            const apiUrl = "https://api.qrserver.com/v1/create-qr-code/";
            const size = "300x300";
            const data = encodeURIComponent(inputText);
            currentQRUrl = `${apiUrl}?size=${size}&data=${data}&format=png&color=0-0-0&bgcolor=255-255-255&qzone=1`;
            
            // Create a promise to handle image loading
            await new Promise((resolve, reject) => {
                qrimage.onload = resolve;
                qrimage.onerror = reject;
                qrimage.src = currentQRUrl;
            });
            
            // Wait a bit to ensure image is fully loaded
            await new Promise(resolve => setTimeout(resolve, 300));
            
            // Hide loading, show QR
            loading.classList.remove("show");
            imgbox.classList.add("show-img");
            qrGenerated = true;
            
            // Show QR info
            const textPreview = inputText.length > 50 ? inputText.substring(0, 50) + "..." : inputText;
            qrInfo.innerHTML = `<strong>Data:</strong> "${textPreview}"`;
            
        } catch (error) {
            loading.classList.remove("show");
            alert("Failed to generate QR code. Please try again.");
            console.error("QR Generation Error:", error);
        }
    }

    // Show download success notification
    function showDownloadNotification(format) {
        successMessage.textContent = `QR Code downloaded as ${format}!`;
        downloadSuccess.style.display = 'flex';
        
        setTimeout(() => {
            downloadSuccess.style.display = 'none';
        }, 3000);
    }

    // Create and download image
    function downloadImage(format, quality = 1.0) {
        if (!qrGenerated || !qrimage.src) {
            alert("Please generate a QR code first");
            return;
        }
        
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            canvas.width = 350;
            canvas.height = 350;
            
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            const qrSize = 300;
            const offset = (canvas.width - qrSize) / 2;
            
            const tempImg = new Image();
            tempImg.crossOrigin = 'Anonymous';
            
            tempImg.onload = function() {
                ctx.drawImage(tempImg, offset, offset, qrSize, qrSize);
                
                ctx.strokeStyle = '#333';
                ctx.lineWidth = 1;
                ctx.strokeRect(offset - 2, offset - 2, qrSize + 4, qrSize + 4);
                
                const link = document.createElement('a');
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                const filename = `qr-code-${timestamp}.${format}`;
                
                if (format === 'png') {
                    link.href = canvas.toDataURL('image/png');
                } else if (format === 'jpg' || format === 'jpeg') {
                    link.href = canvas.toDataURL('image/jpeg', quality);
                }
                
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                showDownloadNotification(format.toUpperCase());
            };
            
            tempImg.onerror = function() {
                alert("Error loading QR image. Please try generating again.");
            };
            
            tempImg.src = currentQRUrl + '&t=' + Date.now();
            
        } catch (error) {
            console.error("Download error:", error);
            alert(`Failed to download ${format}. Please try again.`);
        }
    }

    // Download as PNG
    function downloadAsPNG() {
        downloadImage('png', 1.0);
    }

    // Download as JPG
    function downloadAsJPG() {
        downloadImage('jpg', 0.95);
    }

    // Download as PDF
    async function downloadAsPDF() {
        if (!qrGenerated) {
            alert("Please generate a QR code first");
            return;
        }
        
        try {
            const pdfContent = document.createElement('div');
            pdfContent.style.cssText = `
                position: fixed;
                left: -9999px;
                top: -9999px;
                width: 210mm;
                padding: 20mm;
                background: white;
                color: black;
                font-family: Arial, sans-serif;
                text-align: center;
            `;
            
            const date = new Date().toLocaleDateString();
            const time = new Date().toLocaleTimeString();
            
            pdfContent.innerHTML = `
                <h1 style="color: #333; margin-bottom: 10px; font-size: 24px;">QR Code</h1>
                <p style="color: #666; margin-bottom: 5px;">Generated on: ${date}</p>
                <p style="color: #666; margin-bottom: 20px;">Time: ${time}</p>
                <div style="margin: 20px 0; display: flex; justify-content: center;">
                    <img src="${currentQRUrl}" width="200" height="200" style="border: 1px solid #ddd;" />
                </div>
                <p style="color: #444; margin-top: 20px; font-size: 14px;"><strong>Encoded Data:</strong></p>
                <p style="color: #333; background: #f5f5f5; padding: 10px; border-radius: 5px; word-break: break-all; font-size: 12px;">
                    ${qrData}
                </p>
                <p style="color: #777; font-size: 12px; margin-top: 30px;">
                    Generated via NexusTools
                </p>
            `;
            
            document.body.appendChild(pdfContent);
            
            const canvas = await html2canvas(pdfContent, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false
            });
            
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            
            const imgData = canvas.toDataURL('image/png');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            const filename = `qr-code-${timestamp}.pdf`;
            pdf.save(filename);
            
            document.body.removeChild(pdfContent);
            
            showDownloadNotification("PDF");
            
        } catch (error) {
            console.error("PDF Generation Error:", error);
            alert("Failed to generate PDF. Please try downloading as PNG or JPG instead.");
        }
    }

    qrtext.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            GenerateQR();
        }
    });

    downloadPng.addEventListener("click", downloadAsPNG);
    downloadJpg.addEventListener("click", downloadAsJPG);
    downloadPdf.addEventListener("click", downloadAsPDF);

    document.addEventListener('DOMContentLoaded', function() {
        qrtext.focus();
    });
    </script>
</body>
</html>