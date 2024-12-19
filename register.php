<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SideQuest - Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>REGISTER</h1>
            <form id="registerForm" action="includes/auth.php" method="POST">
                <input type="hidden" name="action" value="register">
                <input type="hidden" name="role" value="student">
                <div class="form-group">
                    <input type="text" name="student_id" required placeholder="Student ID">
                </div>
                <div class="form-group">
                    <input type="text" name="firstName" required placeholder="First Name">
                </div>
                <div class="form-group">
                    <input type="text" name="lastName" required placeholder="Last Name">
                </div>
                <div class="form-group">
                    <input type="email" name="email" required placeholder="School Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" required placeholder="Confirm Password">
                </div>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
    <script src="js/register.js"></script>
</body>
</html> 