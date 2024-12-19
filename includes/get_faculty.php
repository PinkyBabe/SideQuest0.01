<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'auth_middleware.php';

// Check if user is admin
checkUserRole(['admin']);

$response = [
    'success' => false,
    'faculty' => null,
    'message' => ''
];

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Faculty ID is required');
    }

    $conn = Database::getInstance();
    
    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, email, room_number, office_name,
        CASE WHEN status IS NULL OR status = 1 THEN 'active' ELSE 'inactive' END as status
        FROM users 
        WHERE id = ? AND role = 'faculty'
    ");
    
    $stmt->bind_param('i', $_GET['id']);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($faculty = $result->fetch_assoc()) {
            $response['success'] = true;
            $response['faculty'] = $faculty;
        } else {
            throw new Exception('Faculty member not found');
        }
    } else {
        throw new Exception('Error fetching faculty data');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response); 