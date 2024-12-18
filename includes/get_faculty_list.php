<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'auth_middleware.php';

// Check if user is admin
checkUserRole(['admin']);

$response = [
    'success' => false,
    'data' => [],
    'message' => ''
];

try {
    $conn = Database::getInstance();
    
    // Simpler query that works with basic user fields
    $query = "SELECT id, first_name, last_name, email, 
              CASE WHEN status IS NULL OR status = 1 THEN 'active' ELSE 'inactive' END as status 
              FROM users 
              WHERE role = 'faculty' 
              ORDER BY first_name, last_name";
              
    $result = $conn->query($query);
    
    if ($result) {
        $faculty_list = [];
        while ($row = $result->fetch_assoc()) {
            // Add default values for room_number and office_name
            $row['room_number'] = '-';
            $row['office_name'] = '-';
            $faculty_list[] = $row;
        }
        $response['success'] = true;
        $response['data'] = $faculty_list;
    } else {
        throw new Exception('Error fetching faculty list');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response); 