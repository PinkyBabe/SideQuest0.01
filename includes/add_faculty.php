<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'auth_middleware.php';

// Check if user is admin
checkUserRole(['admin']);

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $required_fields = ['firstName', 'lastName', 'email', 'password', 'roomNumber', 'officeName'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("$field is required");
        }
    }

    // Get database connection
    $conn = Database::getInstance();

    // Check if email already exists
    $email = trim($_POST['email']);
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    if ($check_email->get_result()->num_rows > 0) {
        throw new Exception('Email already exists');
    }

    // Hash password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert new faculty member
    $stmt = $conn->prepare("
        INSERT INTO users (first_name, last_name, email, password, role, room_number, office_name) 
        VALUES (?, ?, ?, ?, 'faculty', ?, ?)
    ");

    $stmt->bind_param(
        "ssssss",
        $_POST['firstName'],
        $_POST['lastName'],
        $email,
        $password,
        $_POST['roomNumber'],
        $_POST['officeName']
    );

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Faculty member added successfully';
    } else {
        throw new Exception('Error adding faculty member');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response); 