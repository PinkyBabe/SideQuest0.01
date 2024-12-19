<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/auth_middleware.php';

// Check if user is admin
checkUserRole(['admin']);

// Get database connection
$conn = Database::getInstance();

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$studentId = isset($data['studentId']) ? intval($data['studentId']) : 0;

if (!$studentId) {
    echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
    exit;
}

// Update student status to rejected
$query = "UPDATE users SET status = 'rejected' WHERE id = ? AND role = 'student'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $studentId);

if ($stmt->execute()) {
    // Log the action
    logAction('Student rejected', 'Admin rejected student ID: ' . $studentId);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close(); 