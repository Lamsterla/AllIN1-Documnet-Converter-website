<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF to Word Converter | NexusTools</title>
    <!-- Google Fonts for Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-main: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-hover: rgba(45, 61, 86, 0.9);
            --logo-color: #3b82f6;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary-color: #ef4444; /* Red for PDF to Word */
            --primary-hover: #dc2626;
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
            color: var(--logo-color);
            -webkit-text-fill-color: var(--logo-color);
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

        /* Upload Zone */
        .upload-zone {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: rgba(0, 0, 0, 0.2);
        }

        .upload-zone:hover, .upload-zone.dragover {
            border-color: var(--primary-color);
            background: rgba(239, 68, 68, 0.05);
        }

        .upload-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .upload-text {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .upload-hint {
            color: var(--text-muted);
            font-size: 1rem;
        }

        #file-input { display: none; }

        /* Progress & States */
        .status-container {
            display: none;
            text-align: center;
            padding: 2rem 0;
        }

        .file-info {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
            background: rgba(0,0,0,0.3);
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            border: 1px solid var(--border-color);
        }

        .file-info i { color: var(--primary-color); font-size: 1.2rem; }

        /* Buttons */
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
        }

        .btn-primary {
            background: var(--primary-color);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(239, 68, 68, 0.5);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-main);
            border: 2px solid var(--border-color);
            margin-top: 1rem;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.05);
            border-color: var(--text-muted);
        }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            display: none;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
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
            background: rgba(239, 68, 68, 0.1);
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

        /* Make it responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.05rem; }
            .converter-card { padding: 2rem 1.5rem; }
            .upload-zone { padding: 3rem 1rem; }
            header { flex-direction: column; gap: 1rem; }
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
        <h1>PDF to Word Converter</h1>
        <p>Transform your non-editable PDF documents into beautifully formatted Word files (.docx) in seconds. High quality, 100% free, and completely secure.</p>
    </section>

    <!-- Converter Form -->
    <div class="converter-container">
        <div class="converter-card">
            
            <!-- Upload UI -->
            <div id="upload-ui">
                <div class="upload-zone" id="drop-zone">
                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                    <h3 class="upload-text">Choose PDF file</h3>
                    <p class="upload-hint">or drop PDF here</p>
                    <input type="file" id="file-input" accept="application/pdf">
                </div>
            </div>

            <!-- Processing UI -->
            <div class="status-container" id="processing-ui">
                <div class="file-info">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span id="filename-display">document.pdf</span>
                </div>
                
                <h3 id="progress-title" style="margin-bottom: 0.5rem; color: var(--text-main);">Uploading Document</h3>
                <p id="progress-desc" class="upload-hint">Sending your file securely...</p>
                
                <div style="width: 100%; height: 12px; background: rgba(0,0,0,0.3); border-radius: 10px; margin: 1.5rem 0; overflow: hidden; position: relative;">
                    <div id="progress-fill" style="width: 0%; height: 100%; background: var(--primary-color); transition: width 0.3s ease;"></div>
                </div>
                <div id="progress-percentage" style="font-weight: 600; font-size: 1.5rem; color: var(--primary-color);">0%</div>
                
                <button id="cancel-btn" class="btn btn-outline" style="margin-top: 1rem; padding: 8px 24px; font-size: 0.95rem; border-color: rgba(239,68,68,0.5); color: #fca5a5;">
                    <i class="fa-solid fa-xmark"></i> Cancel
                </button>
            </div>

            <!-- Success UI -->
            <div class="status-container" id="success-ui">
                <div style="font-size: 4rem; color: var(--success); margin-bottom: 1.5rem;">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
                <h3 style="margin-bottom: 2rem; color: var(--text-main); font-size: 1.8rem;">Your Word file is ready!</h3>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
                    <a href="#" id="download-btn" class="btn btn-primary" download>
                        <i class="fa-solid fa-download"></i> Download File
                    </a>
                    <button id="convert-another" class="btn btn-outline">
                        Start Over
                    </button>
                </div>
            </div>

            <!-- Error Alert -->
            <div class="alert alert-error" id="error-alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span id="error-message">An error occurred.</span>
            </div>
            
        </div>
    </div>

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

    <!-- Footer -->
    <footer>
        &copy; 2026 NexusTools. All rights reserved.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('file-input');
            const uploadUI = document.getElementById('upload-ui');
            const processingUI = document.getElementById('processing-ui');
            const successUI = document.getElementById('success-ui');
            const filenameDisplay = document.getElementById('filename-display');
            const errorAlert = document.getElementById('error-alert');
            const errorMessage = document.getElementById('error-message');
            const downloadBtn = document.getElementById('download-btn');
            const convertAnotherBtn = document.getElementById('convert-another');

            // Drag and Drop Events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('dragover');
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            });

            dropZone.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                if (files.length === 0) return;
                
                const file = files[0];
                if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
                    showError('Please upload a valid PDF file.');
                    return;
                }

                // Setup UI for processing
                errorAlert.style.display = 'none';
                uploadUI.style.display = 'none';
                successUI.style.display = 'none';
                processingUI.style.display = 'block';
                filenameDisplay.textContent = file.name;

                // Prepare FormData
                const formData = new FormData();
                formData.append('pdf_file', file);

                // Send AJAX Request
                uploadFile(formData);
            }

            function uploadFile(formData) {
                // Generate a random ID to track progress across multiple scripts safely
                const jobId = 'pdf_' + Math.random().toString(36).substr(2, 9);
                formData.append('job_id', jobId);
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'convert.php', true);
                
                let pollingInterval = null;
                const progressFill = document.getElementById('progress-fill');
                const progressPercentage = document.getElementById('progress-percentage');
                const progressTitle = document.getElementById('progress-title');
                const progressDesc = document.getElementById('progress-desc');
                const cancelBtn = document.getElementById('cancel-btn');

                let isCancelled = false;
                
                // Add Cancel listener
                const handleCancel = () => {
                    isCancelled = true;
                    xhr.abort();
                    if (pollingInterval) clearInterval(pollingInterval);
                    
                    // Notify backend to clean up this job
                    fetch('cancel.php?job_id=' + jobId, { method: 'POST' }).catch(e => console.log('Cancel notify error', e));
                    
                    resetUI();
                };
                cancelBtn.onclick = handleCancel;

                // Reset Progress UI
                progressTitle.textContent = "Uploading Document";
                progressDesc.textContent = "Sending your file securely...";
                progressFill.style.width = '0%';
                progressFill.style.background = 'var(--primary-color)';
                progressPercentage.style.color = 'var(--primary-color)';
                progressPercentage.textContent = '0%';

                // 1. Upload Progress Tracking (Genuine network progress)
                xhr.upload.onprogress = function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = Math.floor((e.loaded / e.total) * 100);
                        progressFill.style.width = percentComplete + '%';
                        progressPercentage.textContent = percentComplete + '%';
                        
                        if (percentComplete >= 100) {
                            progressTitle.textContent = "Converting to Word";
                            progressDesc.textContent = "Extracting text and formatting... Phase 2 of 2";
                            progressFill.style.width = '0%';
                            progressFill.style.background = '#10b981'; // green for conversion phase
                            progressPercentage.style.color = '#10b981';
                            progressPercentage.textContent = '0%';
                            
                            // 2. Start polling for genuine python conversion progress
                            startPolling(jobId);
                        }
                    }
                };

                const startPolling = (id) => {
                    pollingInterval = setInterval(() => {
                        fetch('progress.php?job_id=' + id)
                        .then(res => res.text())
                        .then(text => {
                            let prog = parseInt(text);
                            if (!isNaN(prog) && prog > 0) {
                                progressFill.style.width = prog + '%';
                                progressPercentage.textContent = prog + '%';
                            }
                        }).catch(e => console.log('Polling error', e));
                    }, 500); // Check progress every half second
                };

                xhr.onload = function() {
                    if (pollingInterval) clearInterval(pollingInterval);
                    
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                progressFill.style.width = '100%';
                                progressPercentage.textContent = '100%';
                                setTimeout(() => {
                                    if (!isCancelled) {
                                        showSuccess(data.download_url, data.original_name);
                                    }
                                }, 500);
                            } else {
                                showError(data.error || 'Conversion failed.');
                                resetUI();
                            }
                        } catch(e) {
                            showError('Invalid server response.');
                            resetUI();
                        }
                    } else {
                        showError('Server error: ' + xhr.status);
                        resetUI();
                    }
                };

                xhr.onerror = function() {
                    if (pollingInterval) clearInterval(pollingInterval);
                    showError('Network error occurred.');
                    resetUI();
                };

                xhr.send(formData);
            }

            function showSuccess(downloadUrl, dlName) {
                processingUI.style.display = 'none';
                successUI.style.display = 'block';
                downloadBtn.href = downloadUrl;
                if(dlName) {
                    downloadBtn.setAttribute('download', dlName);
                }
            }

            function showError(msg) {
                errorMessage.textContent = msg;
                errorAlert.style.display = 'block';
                
                // auto hide error
                setTimeout(() => {
                    errorAlert.style.display = 'none';
                }, 8000);
            }

            function resetUI() {
                processingUI.style.display = 'none';
                successUI.style.display = 'none';
                uploadUI.style.display = 'block';
                fileInput.value = '';
            }

            convertAnotherBtn.addEventListener('click', (e) => {
                e.preventDefault();
                resetUI();
            });
        });
    </script>
</body>
</html>
