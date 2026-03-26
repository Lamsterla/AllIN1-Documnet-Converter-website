<?php
// index.php - Prevent directory listing for uploads folder
header("HTTP/1.0 403 Forbidden");
echo "Forbidden";
?>
