<?php
session_start();
session_destroy();
// Clear any cookies if you're using them
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}
// Redirect to index page
header("Location: ../index.php");
exit();
?>