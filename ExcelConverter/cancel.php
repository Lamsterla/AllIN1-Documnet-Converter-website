<?php
// cancel.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$jobId = $_GET['job_id'] ?? '';
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    exit;
}

$uploadDir = __DIR__ . '/uploads/';

// We don't know the exact extensions the python script is working with 
// at the moment of cancel (could be xlsx->csv or csv->xlsx), so we try cleaning both
$exts = ['.xlsx', '.csv', '_progress.txt'];

foreach ($exts as $ext) {
    @unlink($uploadDir . $jobId . $ext);
}

echo json_encode(['success' => true]);
?>
