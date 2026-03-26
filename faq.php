<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusTools | Frequently Asked Questions</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-hover: rgba(45, 61, 86, 0.9);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-1: #3b82f6; 
            --accent-2: #8b5cf6; 
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

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        .animated-bg {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1;
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(59, 130, 246, 0.15), transparent 30%),
                radial-gradient(circle at 85% 30%, rgba(139, 92, 246, 0.15), transparent 30%),
                radial-gradient(circle at 50% 80%, rgba(16, 185, 129, 0.1), transparent 30%);
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
            text-decoration: none;
        }

        .logo i {
            color: var(--accent-1);
            -webkit-text-fill-color: var(--accent-1);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--text-main);
        }

        /* Hero */
        .hero {
            text-align: center;
            padding: 4rem 1rem 4rem;
            max-width: 800px;
            margin: 0 auto;
            z-index: 10;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #f8fafc, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .hero p {
            color: var(--text-muted);
            font-size: 1.2rem;
        }

        /* FAQ Section */
        .container {
            max-width: 900px;
            margin: 0 auto 5rem;
            padding: 0 5%;
            z-index: 10;
        }

        .faq-item {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(16px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .faq-item:hover {
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.4);
        }

        .faq-item.active {
            border-color: var(--accent-1);
            box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.3);
            background: rgba(45, 61, 86, 0.85);
        }

        .faq-question {
            padding: 1.8rem 2rem;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background: transparent;
            border: none;
            color: var(--text-main);
            font-size: 1.25rem;
            font-weight: 600;
            text-align: left;
            font-family: 'Outfit', sans-serif;
            transition: color 0.3s ease;
        }

        .faq-item:hover .faq-question {
            color: #fff;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .faq-question i {
            color: var(--accent-1);
            font-size: 1.1rem;
            transition: transform 0.4s ease;
        }

        .faq-item.active .icon-circle {
            background: var(--accent-1);
        }

        .faq-item.active .faq-question i {
            color: #fff;
            transform: rotate(180deg);
        }

        .faq-answer-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s cubic-bezier(0, 1, 0, 1);
        }

        .faq-item.active .faq-answer-wrapper {
            max-height: 1000px;
            transition: max-height 0.6s ease-in-out;
        }

        .faq-answer {
            padding: 0 2rem 2rem;
            color: var(--text-muted);
            line-height: 1.7;
            font-size: 1.05rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.4s ease;
        }

        .faq-item.active .faq-answer {
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0.1s;
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
            .hero h1 { font-size: 2.8rem; }
            .hero p { font-size: 1.1rem; }
            .nav-links { display: none; }
            .faq-question { font-size: 1.1rem; padding: 1.5rem; }
            .faq-answer { padding: 0 1.5rem 1.5rem; }
            .icon-circle { width: 35px; height: 35px; min-width: 35px; }
        }
    </style>
</head>
<body>
    <div class="animated-bg"></div>

    <header class="animate-fade-in">
        <a href="index.php" class="logo">
            <i class="fa-solid fa-layer-group"></i> NexusTools
        </a>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="faq.php" class="active">Q&A</a></li>
                <li><a href="contact.php">Feedback</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero animate-fade-in delay-1">
            <h1>Frequently Asked Questions</h1>
            <p>Got questions? We've got answers to help you get the most out of NexusTools.</p>
        </section>

        <section class="container">
            
            <div class="faq-item animate-fade-in delay-2">
                <button class="faq-question">
                    <span>Are the converters really free to use?</span>
                    <div class="icon-circle"><i class="fa-solid fa-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrapper">
                    <div class="faq-answer">
                        Yes! All tools on NexusTools are 100% free to use with no hidden fees, subscriptions, or intrusive watermarks on your exported documents. We believe in providing premium tools for everyone.
                    </div>
                </div>
            </div>

            <div class="faq-item animate-fade-in delay-3">
                <button class="faq-question">
                    <span>Is my data secure?</span>
                    <div class="icon-circle"><i class="fa-solid fa-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrapper">
                    <div class="faq-answer">
                        Absolutely. We value your privacy above all else. Files uploaded to our servers are processed instantly and are immediately queued for strict deletion after 1 hour. We do not store, view, or share your documents with any third parties under any circumstances.
                    </div>
                </div>
            </div>

            <div class="faq-item animate-fade-in delay-4">
                <button class="faq-question">
                    <span>Is there a file size limit?</span>
                    <div class="icon-circle"><i class="fa-solid fa-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrapper">
                    <div class="faq-answer">
                        Currently, our system handles files up to a reasonable size suitable for standard documents, PDFs, and spreadsheets. If you encounter errors with exceptionally large files, please let us know via the feedback page so we can optimize our servers.
                    </div>
                </div>
            </div>

            <div class="faq-item animate-fade-in delay-5">
                <button class="faq-question">
                    <span>Which formats are currently supported?</span>
                    <div class="icon-circle"><i class="fa-solid fa-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrapper">
                    <div class="faq-answer">
                        We currently support converting PDF to Word (.docx), Word to PDF, and Excel (.xlsx) back and forth with CSV. We also have a powerful Image to Text OCR tool, Image Format Converter, and an interactive QR Code Generator. We are constantly working on expanding our toolkit!
                    </div>
                </div>
            </div>

            <div class="faq-item animate-fade-in delay-5">
                <button class="faq-question">
                    <span>What happens if a conversion fails?</span>
                    <div class="icon-circle"><i class="fa-solid fa-chevron-down"></i></div>
                </button>
                <div class="faq-answer-wrapper">
                    <div class="faq-answer">
                        Occasionally, highly complex layouts or corrupted files may fail to convert. If you receive an error, you can try saving the file in a different format natively before using our tool, or send us feedback so we can immediately investigate and improve our conversion engine.
                    </div>
                </div>
            </div>

        </section>
    </main>

    <footer class="animate-fade-in delay-5">
        &copy; 2026 NexusTools. All rights reserved.
    </footer>

    <script>
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.closest('.faq-item');
                const wasActive = faqItem.classList.contains('active');
                
                // Close all other open items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Toggle current item
                if (!wasActive) {
                    faqItem.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
