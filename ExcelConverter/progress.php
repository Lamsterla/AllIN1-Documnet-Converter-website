<?php
// progress.php
$jobId = $_GET['job_id'] ?? '';
if (!preg_match('/^[a-zA-Z0-9_]+$/', $jobId)) {
    echo "0";
    exit;
}

$file = __DIR__ . '/uploads/' . $jobId . '_progress.txt';
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo "0";
}
?>
