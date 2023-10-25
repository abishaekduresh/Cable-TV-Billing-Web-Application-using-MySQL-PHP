<?php
include_once('Mysqldump/Mysqldump.php');
require 'smtp/PHPMailerAutoload.php';

// Delete the backup file after sending
// unlink($backupFile);
$timezone = new DateTimeZone('Asia/Kolkata');
$datetime = new DateTime('now', $timezone);
$currentDateTime = $datetime->format('Y-m-d/h:i');

// Specify the folder path to save the backup file
$backupFolderPath = 'sql_backup_files/';

// Create a backup file with the current timestamp
$backupFile = $backupFolderPath . 'ctv' . $datetime->format('Y-m-d_h:i') . '.sql';

$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=pdpcabletv', 'pdpcabletv', 'aiyxicDwaBktmh2e');
$dump->start($backupFile);


// Instantiate PHPMailer
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';  // Specify your SMTP server
$mail->Port = 587;  // Specify the SMTP port
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'pdpcabletv@gmail.com'; // Your SMTP email address
$mail->Password = 'rjtucqoxcxgblpbc'; // Your SMTP password
$mail->SMTPSecure = 'tls'; // Enable encryption, 'ssl' also accepted

// Set email details
$mail->setFrom('pdpcabletv@gmail.com', 'CTV DB Backup' . $currentDateTime); // Set the sender's email address and name
$mail->addAddress('pdpcabletv@gmail.com', 'Duresh'); // Set the recipient's email address and name
$mail->Subject = 'pdpcabletv.in-sql-backup'; // Set the email subject
$mail->Body = $currentDateTime; // Set the email body


// Attach a file
$attachmentPath = $backupFile; // Replace with the actual path to your attachment file

// Read the attachment file
$attachmentContent = file_get_contents($attachmentPath);

// Check if the attachment content was successfully loaded
if ($attachmentContent !== false) {
    $mail->addStringAttachment($attachmentContent, $backupFile); // Add the attachment to the email
} else {
    echo 'Failed to load attachment file.';
    exit;
}

// Send the email
if ($mail->send()) {
    echo 'Email sent successfully.';
    closeTab();
    
} else {
    echo 'Error sending email: ' . $mail->ErrorInfo;
    closeTab();
}

    // Tab Close function
    function closeTab() {
        echo "<script>
        setTimeout(function(){
          window.close();
        }, 100);
    </script>";
    }

?>



<!-- rjtucqoxcxgblpbc -->