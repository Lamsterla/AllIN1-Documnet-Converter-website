const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const imagePreview = document.getElementById('image-preview');
const extractBtn = document.getElementById('extract-btn');
const resetBtn = document.getElementById('reset-btn');
const progressContainer = document.getElementById('progress-container');
const progressBar = document.getElementById('progress-bar');
const statusText = document.getElementById('status-text');
const progressPercent = document.getElementById('progress-percent');
const resultContainer = document.getElementById('result-container');
const extractedTextArea = document.getElementById('extracted-text');
const copyBtn = document.getElementById('copy-btn');

let selectedFile = null;

// Click to upload
dropZone.addEventListener('click', () => fileInput.click());

// Drag and drop events
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
});

dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles(files);
});

fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

function handleFiles(files) {
    if (files.length > 0) {
        selectedFile = files[0];
        if (selectedFile.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                dropZone.querySelector('i').style.display = 'none';
                dropZone.querySelector('h3').style.display = 'none';
                dropZone.querySelector('p').style.display = 'none';
                extractBtn.disabled = false;
            };
            reader.readAsDataURL(selectedFile);
        } else {
            alert('Please upload an image file (PNG, JPG, etc.)');
        }
    }
}

extractBtn.addEventListener('click', async () => {
    if (!selectedFile) return;

    extractBtn.disabled = true;
    progressContainer.style.display = 'block';
    resultContainer.style.display = 'none';
    
    try {
        const worker = await Tesseract.createWorker('eng', 1, {
            logger: m => {
                if (m.status === 'recognizing text') {
                    const prog = Math.round(m.progress * 100);
                    progressBar.style.width = prog + '%';
                    progressPercent.innerText = prog + '%';
                    statusText.innerText = 'Extracting text...';
                } else {
                    statusText.innerText = m.status;
                }
            }
        });

        const { data: { text } } = await worker.recognize(selectedFile);
        
        extractedTextArea.value = text;
        resultContainer.style.display = 'block';
        statusText.innerText = 'Extraction complete!';
        
        await worker.terminate();
    } catch (error) {
        console.error(error);
        statusText.innerText = 'Error: ' + error.message;
    } finally {
        extractBtn.disabled = false;
    }
});

copyBtn.addEventListener('click', () => {
    extractedTextArea.select();
    document.execCommand('copy');
    copyBtn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
    setTimeout(() => {
        copyBtn.innerHTML = '<i class="fa-solid fa-copy"></i> Copy Text';
    }, 2000);
});

resetBtn.addEventListener('click', () => {
    selectedFile = null;
    fileInput.value = '';
    imagePreview.src = '';
    imagePreview.style.display = 'none';
    dropZone.querySelector('i').style.display = 'block';
    dropZone.querySelector('h3').style.display = 'block';
    dropZone.querySelector('p').style.display = 'block';
    extractBtn.disabled = true;
    progressContainer.style.display = 'none';
    resultContainer.style.display = 'none';
    progressBar.style.width = '0%';
    progressPercent.innerText = '0%';
    statusText.innerText = 'Ready to extract...';
    extractedTextArea.value = '';
});
