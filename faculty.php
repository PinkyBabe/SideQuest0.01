<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard - SideQuest</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/faculty.css">
</head>
<body>
    <div class="box">
        <div class="menu-toggle" id="menuToggle">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <h1>SIDEQUEST</h1>
        <img id="dp" 
            src="https://tse2.mm.bing.net/th?id=OIP.yYUwl3GDU07Q5J5ttyW9fQHaHa&pid=Api&P=0&h=220" 
            alt="User Icon" 
            onclick="showLogoutConfirmation()" 
            style="cursor: pointer;">
    </div>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>SIDEQUEST</h2>
        </div>
        <ul>
            <li data-tab="dashboard">Dashboard</li>
            <li data-tab="posts">Manage Posts</li>
            <li data-tab="students">View Students</li>
            <li data-tab="tasks">Tasks</li>
            <li onclick="showLogoutConfirmation()">Logout</li>
        </ul>
    </div>

    <div class="main-content">
        <div id="dashboard" class="tab-content active">
            <h2>Dashboard</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Active Posts</h3>
                    <p><?php echo $stats['active_posts']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Students Enrolled</h3>
                    <p><?php echo $stats['enrolled_students']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pending Tasks</h3>
                    <p><?php echo $stats['pending_tasks']; ?></p>
                </div>
            </div>
        </div>

        <div id="posts" class="tab-content">
            <h2>Manage Posts</h2>
            <button class="btn btn-primary" onclick="showAddPostModal()">Create New Post</button>
            <div id="postsList"></div>
        </div>

        <div id="students" class="tab-content">
            <h2>View Students</h2>
            <div id="studentsList"></div>
        </div>

        <div id="tasks" class="tab-content">
            <h2>Tasks</h2>
            <div id="tasksList"></div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to logout?</p>
            <div class="modal-buttons">
                <button class="btn btn-primary" onclick="confirmLogout()">Yes, Logout</button>
                <button class="btn btn-secondary" onclick="closeLogoutModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/faculty.js"></script>
</body>
</html> 