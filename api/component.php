<?php

function generate_pos_billing_token() {

    unset($_SESSION['generate_pos_billing_token']);
    
    // Check if the session variable is set
    if (!isset($_SESSION['generate_pos_billing_token'])) {
        // Generate a random token
        $token = bin2hex(random_bytes(16));
        
        // Set the session variable
        $_SESSION['generate_pos_billing_token'] = 'DUR43' . $token . '3A5';
    } else {
        // Token already exists, unset it
        unset($_SESSION['generate_pos_billing_token']);
    }
    
    // Return the token
    return $_SESSION['generate_pos_billing_token'];
}
?>

