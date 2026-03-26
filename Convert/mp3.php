<?php
// API Handler Endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['mode'])) {
    
    // Disable error reporting to screen (logs only)
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

    // Increase time limit
    set_time_limit(300);

    // Clean buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $file = $_FILES['file'];
    $mode = $_POST['mode']; 
    
    $fileName = basename($file['name']); 
    $uniqueId = uniqid();
    $targetPath = $uploadDir . $uniqueId . '_' . $fileName; 

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        
        $pythonScript = "logicapi.py";
        $cmdArgs = "";
        $contentType = "";
        $outputExt = "";

        if ($mode === 'mp4-to-mp3') {
            $cmdArgs = "--to-mp3";
            $contentType = "audio/mpeg";
            $outputExt = "mp3";
        } elseif ($mode === 'mp3-to-mp4') {
            $cmdArgs = "--to-mp4";
            $contentType = "video/mp4";
            $outputExt = "mp4";
        } else {
            http_response_code(400); 
            echo json_encode(['error' => 'Invalid mode selected']);
            unlink($targetPath);
            exit;
        }

        // Generate a Temporary Output Path
        $tempOutputName = $uniqueId . '_out.' . $outputExt;
        $tempOutputPath = $uploadDir . $tempOutputName;

        // Prepare output filename for the user
        $downloadFilename = pathinfo($fileName, PATHINFO_FILENAME) . '.' . $outputExt;

        // Construct command: python script.py input output --mode
        // Note: logicapi.py now expects: input_file output_file [flags]
        $command = "python \"$pythonScript\" \"$targetPath\" \"$tempOutputPath\" $cmdArgs";
        
        // Execute blockingly
        exec($command . " 2>&1", $output, $returnCode);

        if ($returnCode === 0 && file_exists($tempOutputPath)) {
            // Success! Serve the file.
            
            $fileSize = filesize($tempOutputPath);

            header('Content-Description: File Transfer');
            header('Content-Type: ' . $contentType);
            header('Content-Disposition: attachment; filename="' . $downloadFilename . '"');
            header('Content-Length: ' . $fileSize); // CRITICAL: Prevents "corruption" / truncation
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Access-Control-Expose-Headers: Content-Disposition, Content-Length');

            // --- JSON Database Logging ---
            $dbFile = 'database.json';
            $dbData = [];
            
            if (file_exists($dbFile)) {
                $jsonContent = file_get_contents($dbFile);
                $dbData = json_decode($jsonContent, true) ?? [];
            }

            $newRecord = [
                'id' => $uniqueId,
                'original_name' => $fileName,
                'converted_name' => $downloadFilename,
                'mode' => $mode,
                'size_bytes' => $fileSize,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            $dbData[] = $newRecord;
            
            // Keep only last 100 records to prevent infinite growth
            if (count($dbData) > 100) {
                $dbData = array_slice($dbData, -100);
            }

            file_put_contents($dbFile, json_encode($dbData, JSON_PRETTY_PRINT));
            // -----------------------------

            readfile($tempOutputPath);

            // Cleanup Output
            unlink($tempOutputPath);
        } else {
             // Error Handling
             http_response_code(500);
             // Return JSON error if possible, but headers might be messed up if we aren't careful.
             // Since we cleaned buffers, we can send JSON.
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Conversion failed', 'details' => $output]);
        }

        // Cleanup Input
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
        exit;

    } else {
        http_response_code(500);
        echo json_encode(['error' => 'File upload failed']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal Media Converter</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-hover: #7c3aed;
            --secondary: #ec4899;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f8fafc;
            --text-gray: #94a3b8;
            --border: #334155;
            --success: #10b981;
            --error: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(236, 72, 153, 0.15) 0%, transparent 20%);
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .card {
            background-color: rgba(30, 41, 59, 0.7);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            backdrop-filter: blur(20px);
            transition: transform 0.3s ease;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        p.subtitle {
            color: var(--text-gray);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .mode-switch {
            display: flex;
            background: rgba(0,0,0,0.2);
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .mode-option {
            flex: 1;
            padding: 0.75rem;
            text-align: center;
            cursor: pointer;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .mode-option.active {
            background: var(--card-bg);
            color: var(--text-light);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }

        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 16px;
            padding: 3rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.02);
            position: relative;
        }

        .upload-area:hover, .upload-area.dragover {
            border-color: var(--primary);
            background: rgba(139, 92, 246, 0.05);
            transform: scale(1.02);
        }

        .icon-box {
            width: 56px;
            height: 56px;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary);
            transition: transform 0.3s ease;
        }

        .upload-area:hover .icon-box {
            transform: rotate(10deg);
        }

        .btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            width: 100%;
            display: none; /* Hidden initially */
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.3);
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .file-info {
            margin-top: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 12px;
            display: none;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .remove-file {
            color: #ef4444;
            cursor: pointer;
            padding: 4px;
        }

        .loading-state {
            display: none;
            margin-top: 2rem;
        }

        .spinner {
            width: 30px;
            height: 30px;
            border: 3px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
            margin: 0 auto 0.5rem;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .error-message {
            color: var(--error);
            margin-top: 1rem;
            font-size: 0.9rem;
            display: none;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <h1>Media Converter</h1>
            <p class="subtitle">Convert between Audio and Video instantly</p>

            <div class="mode-switch">
                <div class="mode-option active" data-mode="mp4-to-mp3" onclick="setMode('mp4-to-mp3')">MP4 to MP3</div>
                <div class="mode-option" data-mode="mp3-to-mp4" onclick="setMode('mp3-to-mp4')">MP3 to MP4</div>
            </div>

            <form id="convertForm">
                <input type="file" id="fileInput" name="file" style="display: none" required>

                <div class="upload-area" id="dropZone">
                    <div class="icon-box">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <div style="font-weight: 500; font-size: 1.1rem; margin-bottom: 0.25rem;">Select File</div>
                    <div style="font-size: 0.85rem; color: var(--text-gray);" id="formatText">MP4, MKV, AVI</div>
                </div>

                <div class="file-info" id="fileInfo">
                    <span id="fileName" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 80%;">filename.mp4</span>
                    <span class="remove-file" onclick="resetFile()">✕</span>
                </div>

                <div class="error-message" id="errorMessage"></div>

                <div class="loading-state" id="loadingState">
                    <div class="spinner"></div>
                    <div style="font-size: 0.9rem; color: var(--text-gray);">Converting & Downloading...</div>
                </div>

                <button type="submit" class="btn" id="convertBtn">Convert Now</button>
            </form>
        </div>
    </div>

    <script>
        let currentMode = 'mp4-to-mp3';
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const formatText = document.getElementById('formatText');
        const fileInfo = document.getElementById('fileInfo');
        const fileNameSpan = document.getElementById('fileName');
        const convertBtn = document.getElementById('convertBtn');
        const loadingState = document.getElementById('loadingState');
        const errorMessage = document.getElementById('errorMessage');

        function setMode(mode) {
            currentMode = mode;

            // Update Tabs
            document.querySelectorAll('.mode-option').forEach(el => {
                el.classList.toggle('active', el.dataset.mode === mode);
            });

            // Update UI Hint
            if (mode === 'mp4-to-mp3') {
                formatText.textContent = 'MP4, MKV, AVI, MOV';
                fileInput.accept = '.mp4,.mkv,.avi,.mov';
            } else {
                formatText.textContent = 'MP3, WAV, AAC';
                fileInput.accept = '.mp3,.wav,.aac';
            }

            resetFile();
        }

        function resetFile() {
            fileInput.value = '';
            fileInfo.style.display = 'none';
            dropZone.style.display = 'block';
            convertBtn.style.display = 'none';
            errorMessage.style.display = 'none';
        }

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                showFile(this.files[0]);
            }
        });

        // Drag & Drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                showFile(e.dataTransfer.files[0]);
            }
        });

        function showFile(file) {
            fileNameSpan.textContent = file.name;
            dropZone.style.display = 'none';
            fileInfo.style.display = 'flex';
            convertBtn.style.display = 'block';
            errorMessage.style.display = 'none';
        }

        // Handle Form Submit via Fetch API
        document.getElementById('convertForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const file = fileInput.files[0];
            if (!file) return;

            // UI State: Loading
            convertBtn.style.display = 'none';
            loadingState.style.display = 'block';
            errorMessage.style.display = 'none';

            // Prepare Data
            const formData = new FormData();
            formData.append('file', file);
            formData.append('mode', currentMode);

            try {
                // Call PHP API
                const response = await fetch('mp3.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    const errJson = await response.json();
                    throw new Error(errJson.error || 'Conversion failed. Server returned ' + response.status);
                }

                // Get filename from header or default
                let filename = 'converted_file';
                const disposition = response.headers.get('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) { 
                        filename = matches[1].replace(/['"]/g, '');
                    }
                }
                
                if (filename === 'converted_file') {
                    const ext = currentMode === 'mp4-to-mp3' ? '.mp3' : '.mp4';
                    filename = file.name.split('.')[0] + '_converted' + ext;
                }

                // Connect to Stream/Blob
                const blob = await response.blob();
                
                // Create Download Link
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a); 
                a.click();
                
                // Cleanup
                window.URL.revokeObjectURL(url);
                a.remove();
                
                // Reset UI
                loadingState.style.display = 'none';
                resetFile();

            } catch (error) {
                console.error('Error:', error);
                loadingState.style.display = 'none';
                convertBtn.style.display = 'block';
                errorMessage.textContent = 'Error: ' + error.message;
                errorMessage.style.display = 'block';
            }
        });

    </script>
</body>
</html>