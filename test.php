<?php
// Basic PHP functionality test file

// Test PHP configuration
echo "<h2>PHP Configuration Test</h2>";
if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
    echo "PHP Version: " . PHP_VERSION . " ✓<br>";
} else {
    echo "PHP Version: " . PHP_VERSION . " (Upgrade recommended)<br>";
}

// Test database connection
echo "<h2>Database Connection Test</h2>";
require_once 'includes/config.php';

try {
    $conn = Database::getInstance();
    echo "Database connection successful ✓<br>";
    
    // Test if the database exists and has tables
    $result = $conn->query("SHOW TABLES");
    $tables = array();
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        $tables[] = $row[0];
    }
    echo "Found " . count($tables) . " tables in database<br>";
    echo "Tables: " . implode(", ", $tables) . "<br>";
    
} catch(Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "<br>";
}

// Add this to your test.php to see if the users/admin table exists
try {
    $conn = Database::getInstance();
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        echo "Table: " . $row[0] . "<br>";
    }
    
    // Try to check users table structure
    $result = $conn->query("DESCRIBE users");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "Column: " . $row['Field'] . " - Type: " . $row['Type'] . "<br>";
        }
    }
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Test session functionality
echo "<h2>Session Test</h2>";
session_start();
$_SESSION['test'] = "Session is working";
if (isset($_SESSION['test'])) {
    echo "Session functionality working ✓<br>";
} else {
    echo "Session functionality failed ✗<br>";
}

// Test file system permissions
echo "<h2>File System Test</h2>";
$testDir = "./";
if (is_writable($testDir)) {
    echo "Directory is writable ✓<br>";
} else {
    echo "Directory is not writable ✗<br>";
}

// Display some server information
echo "<h2>Server Information</h2>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Server Protocol: " . $_SERVER['SERVER_PROTOCOL'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// Memory usage
echo "<h2>Memory Usage</h2>";
echo "Current Memory Usage: " . memory_get_usage(true) / 1024 / 1024 . " MB<br>";
echo "Peak Memory Usage: " . memory_get_peak_usage(true) / 1024 / 1024 . " MB<br>";

// Display full phpinfo in a collapsible section
echo "<h2>Full PHP Information</h2>";
echo "<details>";
echo "<summary>Click to view full PHP information</summary>";
phpinfo();
echo "</details>";
?> 