<?php
header('Content-Type: application/json');

if (!isset($_POST['job_id']) && !isset($_GET['job_id'])) {
    echo json_encode(['success' => false, 'error' => 'Job ID not specified.']);
    exit;
}

$jobId = $_POST['job_id'] ?? $_GET['job_id'];

if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    echo json_encode(['success' => false, 'error' => 'Invalid Job ID.']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
$progressFile = $uploadDir . $jobId . '_progress.txt';

if (file_exists($progressFile)) {
    @unlink($progressFile);
}

// Clean up any stray uploads for this job
$files = glob($uploadDir . $jobId . '.*');
foreach ($files as $f) {
    if (is_file($f)) {
        @unlink($f);
    }
}

echo json_encode(['success' => true]);
?>
