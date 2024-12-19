<?php
require_once 'includes/config.php';

$conn = Database::getInstance();

// First, delete existing admin user
$delete_stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
$email = 'admin@example.com';
$delete_stmt->bind_param("s", $email);
$delete_stmt->execute();

// Now create new admin user
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
$first_name = 'Admin';
$last_name = 'User';
$role = 'admin';

$stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo "Admin user created successfully!\n";
    echo "Email: admin@example.com\n";
    echo "Password: admin123\n";
} else {
    echo "Error creating admin user: " . $stmt->error;
}
?> 