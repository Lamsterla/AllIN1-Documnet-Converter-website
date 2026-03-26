<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusTools | Image to Text (OCR)</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <link rel="stylesheet" href="style.css?v=2">
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

    <main class="container">
        <section class="hero">
            <h1>Image to Text</h1>
            <p>High-precision OCR to instantly extract text from any image.</p>
        </section>

        <div class="glass-card">
            <div class="upload-area" id="drop-zone">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <h3>Choose an image</h3>
                <p>or drag and drop here</p>
                <img id="image-preview" alt="Preview">
                <input type="file" id="file-input" accept="image/*">
            </div>

            <div class="controls">
                <button class="btn btn-primary" id="extract-btn" disabled>
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Extract Text
                </button>
                <button class="btn btn-secondary" id="reset-btn">
                    <i class="fa-solid fa-rotate"></i> Reset
                </button>
            </div>

            <div class="progress-container" id="progress-container">
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" id="progress-bar"></div>
                </div>
                <div class="progress-status">
                    <span id="status-text">Ready to extract...</span>
                    <span id="progress-percent">0%</span>
                </div>
            </div>

            <div class="result-container" id="result-container">
                <div class="result-header">
                    <h2>Extracted Text</h2>
                    <button class="btn btn-secondary" id="copy-btn">
                        <i class="fa-solid fa-copy"></i> Copy Text
                    </button>
                </div>
                <textarea id="extracted-text" readonly></textarea>
            </div>
        </div>
    </main>

       <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">Why choose DocConvert?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                <h3>Fast & Easy</h3>
                <p>Simply drag and drop your PDF and download your Word document in seconds. No registration required.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h3>100% Secure</h3>
                <p>Your files are processed on our secure servers and automatically deleted after 1 hour to ensure privacy.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <h3>High Quality</h3>
                <p>Our advanced conversion engine accurately extracts text, layouts, and tables into editable Word formats.</p>
            </div>
        </div>
    </section>

    <footer>
        &copy; 2026 NexusTools. All rights reserved.
    </footer>

    <script src="script.js"></script>
</body>
</html>
