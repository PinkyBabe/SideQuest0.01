<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only define these functions if they don't already exist
if (!function_exists('checkUserLogin')) {
    function checkUserLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
    }
}

if (!function_exists('checkUserRole')) {
    function checkUserRole($allowed_roles) {
        checkUserLogin();
        
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
            header("Location: index.php");
            exit();
        }
    }
}

// Check if user is logged in
checkUserLogin();
?> 