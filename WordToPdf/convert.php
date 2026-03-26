<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_FILES['word_file']) || $_FILES['word_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error uploading the file.']);
    exit;
}

$file = $_FILES['word_file'];

// Validate file extension
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($ext !== 'docx') {
    echo json_encode(['success' => false, 'error' => 'Only DOCX files are allowed.']);
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
$jobId = $_POST['job_id'] ?? uniqid('word_');
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    $jobId = uniqid('word_');
}

$inputDocxPath = $uploadDir . $jobId . '.docx';
$outputPdfPath = $uploadDir . $jobId . '.pdf';
$progressFile = $uploadDir . $jobId . '_progress.txt';

if (!move_uploaded_file($file['tmp_name'], $inputDocxPath)) {
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
    exit;
}

$pythonCommand = 'python';

// Pass the files to python script
$command = escapeshellcmd($pythonCommand) . ' word2pdf.py ' . escapeshellarg($inputDocxPath) . ' ' . escapeshellarg($outputPdfPath) . ' ' . escapeshellarg($progressFile) . ' 2>&1';

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    @unlink($inputDocxPath); // Clean up uploaded file
    @unlink($progressFile);  // Clean up progress file
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
    'download_url' => 'uploads/' . basename($outputPdfPath),
    'original_name' => pathinfo($file['name'], PATHINFO_FILENAME) . '.pdf'
]);
exit;
?>
