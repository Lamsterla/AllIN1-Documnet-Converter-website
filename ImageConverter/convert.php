<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error uploading the file.']);
    exit;
}

if (!isset($_POST['format'])) {
    echo json_encode(['success' => false, 'error' => 'Output format not specified.']);
    exit;
}

$file = $_FILES['image_file'];
$targetFormat = strtolower($_POST['format']); // jpg, png, webp, gif, bmp

$allowedFormats = ['jpg', 'png', 'webp', 'gif', 'bmp', 'jpeg'];
if (!in_array($targetFormat, $allowedFormats)) {
    echo json_encode(['success' => false, 'error' => 'Invalid output format.']);
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
      @unlink($f);
    }
  }
}

$paramsId = $_POST['job_id'] ?? '';
$jobPrefix = 'img_';

$jobId = empty($paramsId) ? uniqid($jobPrefix) : $paramsId;
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    $jobId = uniqid($jobPrefix);
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!$ext) $ext = 'tmp';

// Avoid processing non-image extensions simply based on generic logic
$inputPath = $uploadDir . $jobId . '_in.' . $ext;
$outputPath = $uploadDir . $jobId . '_out.' . ($targetFormat === 'jpeg' ? 'jpg' : $targetFormat);

$progressFile = $uploadDir . $jobId . '_progress.txt';

if (!move_uploaded_file($file['tmp_name'], $inputPath)) {
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
    exit;
}

$pythonCommand = 'python'; // or python3 depending on setup
$flag = '--to-' . $targetFormat;
$command = escapeshellcmd($pythonCommand) . ' image_convert.py ' . escapeshellarg($inputPath) . ' ' . escapeshellarg($outputPath) . ' ' . escapeshellarg($flag) . ' ' . escapeshellarg($progressFile) . ' 2>&1';

exec($command, $output, $returnVar);

if ($returnVar !== 0) {
    @unlink($inputPath);
    @unlink($progressFile);
    echo json_encode([
        'success' => false, 
        'error' => 'Conversion failed.',
        'details' => implode("\n", $output)
    ]);
    exit;
}

@unlink($progressFile);

$originalBaseName = pathinfo($file['name'], PATHINFO_FILENAME);
$newExt = '.' . ($targetFormat === 'jpeg' ? 'jpg' : $targetFormat);

echo json_encode([
    'success' => true,
    'download_url' => 'uploads/' . basename($outputPath),
    'original_name' => $originalBaseName . $newExt
]);
exit;
?>
