<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusTools | Feedback & Contact</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-1: #3b82f6; 
            --accent-2: #8b5cf6; 
            --success: #10b981;
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

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 5%;
            z-index: 10;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .contact-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #f8fafc, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .contact-header p {
            color: var(--text-muted);
            font-size: 1.2rem;
        }

        .contact-card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 3.5rem;
            width: 100%;
            max-width: 650px;
            backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: all 0.4s ease;
        }
        
        .contact-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px -10px rgba(0, 0, 0, 0.6);
        }

        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: var(--text-main);
            font-size: 1rem;
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            padding: 1.2rem;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.5);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-1);
            background: rgba(15, 23, 42, 0.9);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--accent-1), var(--accent-2));
            color: white;
            border: none;
            padding: 1.2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 5px 10px -5px rgba(59, 130, 246, 0.4);
        }

        .success-msg {
            display: none;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            font-weight: 500;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease forwards;
        }

        .success-msg i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
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
            .contact-header h1 { font-size: 2.8rem; }
            .contact-card { padding: 2.5rem 2rem; }
            .nav-links { display: none; }
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
                <li><a href="faq.php">Q&A</a></li>
                <li><a href="contact.php" class="active">Feedback</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="contact-header animate-fade-in delay-1">
            <h1>We value your feedback</h1>
            <p>Have a suggestion, found a bug, or just want to say hi? Send us a message.</p>
        </div>

        <div class="contact-card animate-fade-in delay-2">
            <div class="success-msg" id="successMsg">
                <i class="fa-solid fa-circle-check"></i> 
                Thank you! Your feedback has been sent successfully.
            </div>

            <form id="feedbackForm">
                <div class="form-group">
                    <label for="name">Name (Optional)</label>
                    <input type="text" id="name" class="form-control" placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" class="form-control" placeholder="john@example.com" required>
                </div>
                
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" class="form-control" placeholder="How can we improve NexusTools?" required></textarea>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    Send Message <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <footer class="animate-fade-in delay-3">
        &copy; 2026 NexusTools. All rights reserved.
    </footer>

    <script>
        document.getElementById('feedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In a real application, you would send this via AJAX to a PHP backend script.
            // For now, we simulate a successful submission with a loading state.
            
            const btn = document.getElementById('submitBtn');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Sending...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
            
            setTimeout(() => {
                this.reset();
                this.style.display = 'none';
                document.getElementById('successMsg').style.display = 'block';
            }, 1200);
        });
    </script>
</body>
</html>
