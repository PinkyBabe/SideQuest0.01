<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

function checkUserRole($allowed_roles) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        if (isAjaxRequest()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        } else {
            header('Location: ../index.php');
            exit;
        }
    }
    
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        if (isAjaxRequest()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit;
        } else {
            header('Location: ../index.php');
            exit;
        }
    }
}

function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Stats functions for admin dashboard
function getFacultyCount() {
    try {
        $conn = Database::getInstance();
        $query = "SELECT COUNT(*) as count FROM users WHERE role = 'faculty'";
        $result = $conn->query($query);
        if ($result) {
            return $result->fetch_assoc()['count'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Error getting faculty count: " . $e->getMessage());
        return 0;
    }
}

function getStudentCount() {
    try {
        $conn = Database::getInstance();
        $query = "SELECT COUNT(*) as count FROM users WHERE role = 'student'";
        $result = $conn->query($query);
        if ($result) {
            return $result->fetch_assoc()['count'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Error getting student count: " . $e->getMessage());
        return 0;
    }
}

function getActivePostsCount() {
    try {
        $conn = Database::getInstance();
        $query = "SELECT COUNT(*) as count FROM posts WHERE status = 'active'";
        $result = $conn->query($query);
        if ($result) {
            return $result->fetch_assoc()['count'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Error getting active posts count: " . $e->getMessage());
        return 0;
    }
}

function getCompletedTasksCount() {
    try {
        $conn = Database::getInstance();
        $query = "SELECT COUNT(*) as count FROM tasks WHERE status = 'completed'";
        $result = $conn->query($query);
        if ($result) {
            return $result->fetch_assoc()['count'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Error getting completed tasks count: " . $e->getMessage());
        return 0;
    }
}

function getStudentList() {
    try {
        $conn = Database::getInstance();
        $query = "SELECT id, first_name, last_name, email FROM users WHERE role = 'student'";
        $result = $conn->query($query);
        
        $students = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        return $students;
    } catch (Exception $e) {
        error_log("Error getting student list: " . $e->getMessage());
        return [];
    }
}

// Validation functions
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Error handling function
function handleError($message, $error_code = 500) {
    error_log($message);
    http_response_code($error_code);
    if (!headers_sent()) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $message]);
    }
    exit;
}

// Check if tables exist
function checkRequiredTables() {
    try {
        $conn = Database::getInstance();
        $required_tables = ['users', 'posts', 'tasks'];
        
        foreach ($required_tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows === 0) {
                throw new Exception("Required table '$table' does not exist");
            }
        }
        return true;
    } catch (Exception $e) {
        error_log("Database table check failed: " . $e->getMessage());
        return false;
    }
}

// Initialize database if needed
function initializeDatabase() {
    try {
        if (!checkRequiredTables()) {
            $sql_file = file_get_contents(__DIR__ . '/../sidequest_db.sql');
            if ($sql_file === false) {
                throw new Exception("Could not read SQL file");
            }
            
            $conn = Database::getInstance();
            if ($conn->multi_query($sql_file)) {
                do {
                    // Process all results
                    while ($conn->more_results() && $conn->next_result()) {;}
                } while ($conn->more_results());
            }
            
            if ($conn->error) {
                throw new Exception("Error initializing database: " . $conn->error);
            }
        }
        return true;
    } catch (Exception $e) {
        error_log("Database initialization failed: " . $e->getMessage());
        return false;
    }
}
?>