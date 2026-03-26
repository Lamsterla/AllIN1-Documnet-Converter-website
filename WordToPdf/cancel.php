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
$inputDocxPath = $uploadDir . $jobId . '.docx';
$outputPdfPath = $uploadDir . $jobId . '.pdf';
$progressFile = $uploadDir . $jobId . '_progress.txt';

// Clean up space
@unlink($inputDocxPath);
@unlink($outputPdfPath);
@unlink($progressFile);

echo json_encode(['success' => true]);
?>
