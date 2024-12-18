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
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['faculty_id']) || !isset($data['status'])) {
        throw new Exception('Missing required parameters');
    }
    
    $conn = Database::getInstance();
    
    $status = $data['status'] === 'active' ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'faculty'");
    $stmt->bind_param('ii', $status, $data['faculty_id']);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Faculty status updated successfully';
    } else {
        throw new Exception('Error updating faculty status');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response); 