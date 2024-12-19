<?php
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'login':
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Get database connection
            $conn = Database::getInstance();
            
            // Prepare statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                // Verify password
                if (password_verify($password, $user['password'])) {
                    if (!session_id()) {
                        session_start(); // Only start session if not already started
                    }
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    echo json_encode([
                        'success' => true,
                        'role' => $user['role']
                    ]);
                    exit;
                }
            }
            
            // If we get here, login failed
            echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
            exit;
            
        case 'logout':
            if (!session_id()) {
                session_start();
            }
            session_destroy();
            echo json_encode(['success' => true]);
            exit;
            
        case 'register':
            $student_id = $_POST['student_id'];
            
            // Format: "20230123" (YearNumber)
            if (!preg_match('/^\d{8}$/', $student_id)) {
                echo json_encode(['success' => false, 'error' => 'Invalid student ID format. Please use YYYYXXXX format (e.g., 20230123)']);
                exit;
            }
            
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = 'student';
            
            try {
                $conn = Database::getInstance();
                $stmt = $conn->prepare("INSERT INTO users (student_id, first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $student_id, $firstName, $lastName, $email, $password, $role);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Registration failed']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => 'Registration failed: ' . $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>