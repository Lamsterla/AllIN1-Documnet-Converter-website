<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error uploading the file.']);
    exit;
}

$file = $_FILES['pdf_file'];

// Validate file extension and MIME type
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($ext !== 'pdf') {
    echo json_encode(['success' => false, 'error' => 'Only PDF files are allowed.']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Cleanup old files
$files = glob($uploadDir . '*');
$now   = time();
foreach ($files as $f) {
  if (is_file($f)) {
    if ($now - filemtime($f) >= 3600 && pathinfo($f, PATHINFO_BASENAME) !== 'index.php') {
      unlink($f);
    }
  }
}

// Fetch JOB ID from POST to track progress
$jobId = $_POST['job_id'] ?? uniqid('pdf_');
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    $jobId = uniqid('pdf_');
}

$inputPdfPath = $uploadDir . $jobId . '.pdf';
$outputDocxPath = $uploadDir . $jobId . '.docx';
$progressFile = $uploadDir . $jobId . '_progress.txt';

if (!move_uploaded_file($file['tmp_name'], $inputPdfPath)) {
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
    exit;
}

$pythonCommand = 'python';

// Pass the progress file to the python script
$command = escapeshellcmd($pythonCommand) . ' pdf2word.py ' . escapeshellarg($inputPdfPath) . ' ' . escapeshellarg($outputDocxPath) . ' ' . escapeshellarg($progressFile) . ' 2>&1';

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    @unlink($inputPdfPath); // Clean up the uploaded PDF
    @unlink($progressFile); // Clean up progress file
    echo json_encode([
        'success' => false, 
        'error' => 'Conversion failed.',
        'details' => implode("\n", $output)
    ]);
    exit;
}

@unlink($progressFile); // cleanup on success

echo json_encode([
    'success' => true,
    'download_url' => 'uploads/' . basename($outputDocxPath),
    'original_name' => pathinfo($file['name'], PATHINFO_FILENAME) . '.docx'
]);
exit;
?>
