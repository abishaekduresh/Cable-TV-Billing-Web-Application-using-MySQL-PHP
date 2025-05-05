<?php 
session_start();

require_once '../vendor/autoload.php';
include "../dbconfig.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// Generate the secret
$g = new GoogleAuthenticator();
$secret = $g->generateSecret();

// Optionally, store the secret in the database for later verification
$username = $_SESSION['username'];

// Create the QR code content
$appName = 'PDP CTV - Billing Soft';
// $qrContent = GoogleQrUrl::generate($username, $secret, $appName);
$qrContent = "otpauth://totp/" . rawurlencode($appName . ':' . $username) . "?secret=" . $secret . "&issuer=" . rawurlencode($appName);

// Build QR code image in memory
$result = Builder::create()
    ->writer(new PngWriter())
    ->data($qrContent)
    ->size(200)
    ->margin(0)
    ->build();

// Convert image to base64
$base64Image = base64_encode($result->getString());

// Return JSON with both secret and base64-encoded image data
header('Content-Type: application/json');
echo json_encode([
    'secret' => $secret,
    'image'  => 'data:image/png;base64,' . $base64Image
]);
exit;

// Convert image to base64 string
// $base64 = base64_encode($result->getString());

// Output image directly
// echo $result->getString();
// exit;
?>

<!-- <!DOCTYPE html>
<html>
<head><title>Google Authenticator</title></head>
<body>
    <h2>Scan the QR Code</h2>
    <img src="data:image/png;base64,<?= $base64 ?>" alt="Google Authenticator QR Code" />
    <p><strong>Secret:</strong> <?= htmlspecialchars($secret) ?></p>
</body>
</html> -->

<!-- $stmt = $con->prepare("UPDATE user SET google_totp_auth_secret = ? WHERE username = ?");
$stmt->bind_param("ss", $secret, $username);

if ($stmt->execute()) {
    // echo "Secret updated statusfully.";

} else {
    echo "Error updating secret: " . $stmt->error;
    exit;
}

$stmt->close();
$mysqli->close(); -->