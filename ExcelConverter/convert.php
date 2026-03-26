<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_FILES['spreadsheet_file']) || $_FILES['spreadsheet_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error uploading the file.']);
    exit;
}

if (!isset($_POST['mode'])) {
    echo json_encode(['success' => false, 'error' => 'Mode not specified.']);
    exit;
}

$file = $_FILES['spreadsheet_file'];
$mode = $_POST['mode']; // 'excel-to-csv' or 'csv-to-excel'

// Validate file extension
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($mode === 'excel-to-csv' && $ext !== 'xlsx') {
    echo json_encode(['success' => false, 'error' => 'Only XLSX files are allowed for Excel to CSV mode.']);
    exit;
} elseif ($mode === 'csv-to-excel' && $ext !== 'csv') {
    echo json_encode(['success' => false, 'error' => 'Only CSV files are allowed for CSV to Excel mode.']);
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
$paramsId = $_POST['job_id'] ?? '';
$jobPrefix = ($mode === 'excel-to-csv') ? 'e2c_' : 'c2e_';

$jobId = empty($paramsId) ? uniqid($jobPrefix) : $paramsId;
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    $jobId = uniqid($jobPrefix);
}

$inputPath = '';
$outputPath = '';

if ($mode === 'excel-to-csv') {
    $inputPath = $uploadDir . $jobId . '.xlsx';
    $outputPath = $uploadDir . $jobId . '.csv';
} else {
    $inputPath = $uploadDir . $jobId . '.csv';
    $outputPath = $uploadDir . $jobId . '.xlsx';
}

$progressFile = $uploadDir . $jobId . '_progress.txt';

if (!move_uploaded_file($file['tmp_name'], $inputPath)) {
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
    exit;
}

$pythonCommand = 'python';

// Pass the files to python script with mode flag
$flag = ($mode === 'excel-to-csv') ? '--to-csv' : '--to-excel';
$command = escapeshellcmd($pythonCommand) . ' excel_convert.py ' . escapeshellarg($inputPath) . ' ' . escapeshellarg($outputPath) . ' ' . escapeshellarg($flag) . ' ' . escapeshellarg($progressFile) . ' 2>&1';

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    @unlink($inputPath); // Clean up uploaded file
    @unlink($progressFile);  // Clean up progress file
    echo json_encode([
        'success' => false, 
        'error' => 'Conversion failed.',
        'details' => implode("\n", $output)
    ]);
    exit;
}

@unlink($progressFile); // cleanup on success

$originalBaseName = pathinfo($file['name'], PATHINFO_FILENAME);
$newExt = ($mode === 'excel-to-csv') ? '.csv' : '.xlsx';

echo json_encode([
    'success' => true,
    'download_url' => 'uploads/' . basename($outputPath),
    'original_name' => $originalBaseName . $newExt
]);
exit;
?>
