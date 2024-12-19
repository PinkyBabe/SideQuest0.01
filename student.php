<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SideQuest</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/student.css">
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
            <li data-tab="quests">Available Quests</li>
            <li data-tab="progress">My Progress</li>
            <li data-tab="achievements">Achievements</li>
            <li onclick="showLogoutConfirmation()">Logout</li>
        </ul>
    </div>

    <div class="main-content">
        <div id="dashboard" class="tab-content active">
            <h2>Dashboard</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Completed Quests</h3>
                    <p><?php echo $stats['completed_quests']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Current Level</h3>
                    <p><?php echo $stats['current_level']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Achievement Points</h3>
                    <p><?php echo $stats['achievement_points']; ?></p>
                </div>
            </div>
        </div>

        <div id="quests" class="tab-content">
            <h2>Available Quests</h2>
            <div class="quests-filter">
                <select id="questFilter">
                    <option value="all">All Quests</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div id="questsList"></div>
        </div>

        <div id="progress" class="tab-content">
            <h2>My Progress</h2>
            <div class="progress-overview">
                <div class="level-progress">
                    <h3>Level Progress</h3>
                    <div class="progress-bar">
                        <div class="progress" style="width: <?php echo $stats['level_progress']; ?>%"></div>
                    </div>
                    <p><?php echo $stats['level_progress']; ?>% to next level</p>
                </div>
            </div>
            <div id="progressList"></div>
        </div>

        <div id="achievements" class="tab-content">
            <h2>Achievements</h2>
            <div id="achievementsList"></div>
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

    <script src="js/student.js"></script>
</body>
</html> 