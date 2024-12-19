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
            <form action="includes/auth.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <input type="text" name="firstName" required placeholder="First Name">
                </div>
                <div class="form-group">
                    <input type="text" name="lastName" required placeholder="Last Name">
                </div>
                <div class="form-group">
                    <input type="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <select name="role" required>
                        <option value="">Select Role</option>
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                    </select>
                </div>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html> 