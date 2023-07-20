<?php
$filename = "data.txt";
if (file_exists($filename)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filename).'"');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
    unlink($filename); // Delete the file after it has been downloaded
    exit;
} else {
    echo "File not found.";
}
?>
