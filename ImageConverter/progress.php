<?php
header('Content-Type: text/plain');

if (!isset($_GET['job_id'])) {
    echo '0';
    exit;
}

$jobId = $_GET['job_id'];

// Prevent path traversal
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    echo '0';
    exit;
}

$progressFile = __DIR__ . '/uploads/' . $jobId . '_progress.txt';

if (file_exists($progressFile)) {
    $progress = file_get_contents($progressFile);
    echo intval(trim($progress));
} else {
    echo '0';
}
?>
