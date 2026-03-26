<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusTools | Universal Converter Toolkit</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-hover: rgba(45, 61, 86, 0.9);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-1: #3b82f6; /* Blue for Word to PDF */
            --accent-2: #8b5cf6; /* Purple for Audio/Video */
            --accent-3: #10b981; /* Green for Excel */
            --accent-4: #ef4444; /* Red for PDF to Word */
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
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.15), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.15), transparent 25%),
                radial-gradient(circle at 50% 80%, rgba(16, 185, 129, 0.1), transparent 25%);
        }

        /* Navbar */
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
        }

        .logo i {
            color: var(--accent-1);
            -webkit-text-fill-color: var(--accent-1);
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

        /* Hero */
        .hero {
            text-align: center;
            padding: 4rem 1rem 3rem;
            max-width: 800px;
            margin: 0 auto;
            z-index: 10;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
        }

        /* Grid */
        .container {
            max-width: 1200px;
            margin: 0 auto 5rem;
            padding: 0 5%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            z-index: 10;
        }

        /* Card */
        .card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-decoration: none;
            color: var(--text-main);
            backdrop-filter: blur(16px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: var(--accent-1);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            background: var(--card-hover);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        /* Specific Card Accents */
        .card.pdf-word::before { background: var(--accent-4); }
        .card.word-pdf::before { background: var(--accent-1); }
        .card.media::before { background: var(--accent-2); }
        .card.excel::before { background: var(--accent-3); }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .card.pdf-word .icon-wrapper { background: rgba(239, 68, 68, 0.1); color: var(--accent-4); }
        .card.word-pdf .icon-wrapper { background: rgba(59, 130, 246, 0.1); color: var(--accent-1); }
        .card.media .icon-wrapper { background: rgba(139, 92, 246, 0.1); color: var(--accent-2); }
        .card.excel .icon-wrapper { background: rgba(16, 185, 129, 0.1); color: var(--accent-3); }

        .card:hover .icon-wrapper {
            transform: scale(1.1) rotate(5deg);
        }

        .card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .card p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .card:hover .card-footer {
            opacity: 1;
        }

        .card-footer i {
            transition: transform 0.3s ease;
        }

        .card:hover .card-footer i {
            transform: translateX(5px);
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 1.5rem;
            }
            nav ul {
                gap: 1.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            .hero { padding: 3rem 1rem 2rem; }
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.05rem; }
            .container { grid-template-columns: 1fr; gap: 1.5rem; }
            .card { padding: 2rem 1.5rem; }
        }

        @media (max-width: 480px) {
            .hero h1 { font-size: 2rem; }
            nav ul { flex-direction: column; text-align: center; gap: 1rem; }
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <i class="fa-solid fa-layer-group"></i> ALLIN1 
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="faq.php">Q&A</a></li>
                <li><a href="contact.php">Feedback</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>All Your Converters in One Place.</h1>
            <p>Select a tool below to instantly transform your documents, spreadsheets, and media files securely and beautifully.</p>
        </section>

        <section class="container">
            <!-- PDF to Word -->
            <a href="PdfToWord/index.php" class="card pdf-word">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <h2>PDF to Word</h2>
                <p>Extract text, layouts, and formatting from PDF documents into editable Microsoft Word (.docx) files.</p>
                <div class="card-footer">
                    <span>Open Tool</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Word to PDF -->
            <a href="WordToPdf/index.php" class="card word-pdf">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-file-word"></i>
                </div>
                <h2>Word to PDF</h2>
                <p>Convert your Word documents (.docx) into professional, highly-compatible PDF files in seconds.</p>
                <div class="card-footer">
                    <span>Open Tool</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- QR Generator -->
            <a href="qr pages/qr.php" class="card media">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
                <h2>QR Generator</h2>
                <p>Instantly create customizable QR codes for your links, text, and contact information with ease.</p>
                <div class="card-footer">
                    <span>Open Tool</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Excel / CSV -->
            <a href="ExcelConverter/index.php" class="card excel">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-file-excel"></i>
                </div>
                <h2>Spreadsheets</h2>
                <p>Easily transform your data back and forth between Microsoft Excel (.xlsx) and generic CSV formats.</p>
                <div class="card-footer">
                    <span>Open Tool</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Image Converter -->
            <a href="ImageConverter/index.php" class="card media" style="--accent-2: #cfe52bff;">
                <div class="icon-wrapper">
                    <i class="fa-regular fa-image"></i>
                </div>
                <h2>Image Converter</h2>
                <p>Seamlessly transform your images into PNG, JPG, WEBP, GIF, or BMP formats instantly.</p>
                <div class="card-footer">
                    <span>Open Tool</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            <!-- Image to Text -->
            <a href="ImageToText/index.php" class="card media" style="--accent-2: #f1a501ff;">
                <div class="icon-wrapper">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <h2>Image to Text</h2>
                <p>Extract text from images (OCR) instantly and copy it to your clipboard with high precision.</p>
                <div class="card-footer">
                    <span>Open Tool</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
        </section>
    </main>

    <footer>
        &copy; 2026 NexusTools. All rights reserved.
    </footer>

</body>
</html>
