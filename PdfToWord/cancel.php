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
$inputPdfPath = $uploadDir . $jobId . '.pdf';
$outputDocxPath = $uploadDir . $jobId . '.docx';
$progressFile = $uploadDir . $jobId . '_progress.txt';

// Deleting these files will clean up space. 
// If the python script is running, it might crash or cleanly finish but the user won't get the file.
@unlink($inputPdfPath);
@unlink($outputDocxPath);
@unlink($progressFile);

echo json_encode(['success' => true]);
?>
