<?php
// Prevent any output before headers
ob_start();

// Start session first
session_start();

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Required files
require_once 'config.php';
require_once 'functions.php';

// Clear any output buffers
while (ob_get_level()) {
    ob_end_clean();
}

try {
    // Set headers
    header('Content-Type: application/json');
    
    // Log the incoming request
    error_log("Login attempt - POST data: " . print_r($_POST, true));

    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate action
    if (!isset($_POST['action'])) {
        throw new Exception('No action specified');
    }

    // Get database connection
    $conn = Database::getInstance();

    switch ($_POST['action']) {
        case 'login':
            // Get and validate input
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!$email || empty($password)) {
                throw new Exception('Invalid email or password format');
            }

            // Query user
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            if (!$stmt) {
                throw new Exception('Database prepare error: ' . $conn->error);
            }

            $stmt->bind_param('s', $email);
            if (!$stmt->execute()) {
                throw new Exception('Database execute error: ' . $stmt->error);
            }

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                throw new Exception('Invalid credentials');
            }

            // For the default admin account
            if ($user['password'] === $password) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];

                echo json_encode([
                    'success' => true,
                    'role' => $user['role']
                ]);
                exit;
            }

            throw new Exception('Invalid credentials');

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    // Log the error
    error_log('Login error: ' . $e->getMessage());
    
    // Send error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>