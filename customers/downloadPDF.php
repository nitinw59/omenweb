<?php
$filename = $_GET['file'];
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="'"' . $filename . '"'"');
readfile($filename);
?>