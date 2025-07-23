<?php
// Get the path to session files
$sessionPath = session_save_path();
if (empty($sessionPath)) {
    $sessionPath = sys_get_temp_dir(); // fallback
}

// Delete all session files
$files = glob($sessionPath . '/sess_*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file); // delete session file
    }
}

echo "All sessions destroyed. All users logged out.";
?>
