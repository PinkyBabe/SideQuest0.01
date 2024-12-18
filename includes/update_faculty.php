<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'auth_middleware.php';

// Check if user is admin
checkUserRole(['admin']);

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
    $required_fields = ['id', 'firstName', 'lastName', 'email'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("$field is required");
        }
    }

    $conn = Database::getInstance();

    // Check if email exists for other faculty members
    $stmt = $conn->prepare("
        SELECT id FROM users 
        WHERE email = ? AND id != ? AND role = 'faculty'
    ");
    $stmt->bind_param("si", $_POST['email'], $_POST['id']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception('Email already exists for another faculty member');
    }

    // Update faculty member
    $stmt = $conn->prepare("
        UPDATE users 
        SET first_name = ?,
            last_name = ?,
            email = ?,
            room_number = ?,
            office_name = ?
        WHERE id = ? AND role = 'faculty'
    ");

    $stmt->bind_param(
        "sssssi",
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['email'],
        $_POST['roomNumber'],
        $_POST['officeName'],
        $_POST['id']
    );

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Faculty member updated successfully';
        } else {
            throw new Exception('No changes made or faculty member not found');
        }
    } else {
        throw new Exception('Error updating faculty member');
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response); 