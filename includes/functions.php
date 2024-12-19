<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function logAction($action, $description) {
    $conn = Database::getInstance();
    $userId = $_SESSION['user_id'] ?? 0;
    
    $query = "INSERT INTO activity_log (user_id, action, description, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss', $userId, $action, $description);
    $stmt->execute();
    $stmt->close();
}

// Add any other helper functions you need
?>