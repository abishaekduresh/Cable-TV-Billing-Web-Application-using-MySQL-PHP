<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../vendor/autoload.php';
include "../dbconfig.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

header('Content-Type: application/json');

// Read and decode the incoming JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($data['pin']) || empty($data['pin'])) {
    echo json_encode(['status' => false, 'message' => 'PIN is required.']);
    exit;
}

$pin = trim($data['pin']);
$username = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($data['username']) ? $data['username'] : null);

if (!$username) {
    echo json_encode(['status' => false, 'message' => 'Session expired or username not set.']);
    exit;
}

$g = new GoogleAuthenticator();

if(isset($data['username']) && !empty($data['username'])){
    // Step 1: Try to fetch the user's TOTP secret
    $stmt = $con->prepare("SELECT google_totp_auth_secret FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $storedSecret = $row['google_totp_auth_secret'] ?? null;
    
    // Case A: Secret exists in DB, verify directly
    if ($storedSecret) {
        if ($g->checkCode($storedSecret, $pin)) {
            echo json_encode(['status' => true, 'message' => 'PIN verified.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid PIN.!']);
        }
        exit;
    }

}

// Case B: Secret not in DB — check session for secret
$sessionSecret = isset($storedSecret) ? $storedSecret : $data['google_totp_secret'];

if (!$sessionSecret) {
    echo json_encode(['status' => false, 'message' => 'No TOTP secret found for verification.']);
    exit;
}

// If PIN is valid with session secret, update DB
if ($g->checkCode($sessionSecret, $pin)) {
    $update = $con->prepare("UPDATE user SET google_totp_auth_secret = ? WHERE username = ?");
    $update->bind_param("ss", $sessionSecret, $username);
    if ($update->execute()) {
        echo json_encode(['status' => true, 'message' => 'PIN verified and TOTP secret saved.']);
    } else {
        echo json_encode(['status' => false, 'message' => 'PIN verified but failed to save secret.']);
    }
    exit;
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid PIN.!!']);
    exit;
}
