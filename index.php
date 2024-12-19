<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SideQuest - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>SIDEQUEST</h1>
            <div id="error-message" style="color: red; margin-bottom: 10px; display: none;"></div>
            <form id="loginForm" action="includes/auth.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <input type="email" name="email" required placeholder="Email">
                </div>
                <div class="form-group">
                    <input type="password" name="password" required placeholder="Password">
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const errorDiv = document.getElementById('error-message');
        
        // Get the form's action URL
        const actionUrl = this.getAttribute('action');
        
        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin' // Include cookies
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error occurred');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.location.href = data.role + '.php';
            } else {
                errorDiv.style.display = 'block';
                errorDiv.textContent = data.error || 'Login failed';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'An error occurred during login. Please try again.';
        });
    });
    </script>
</body>
</html> 