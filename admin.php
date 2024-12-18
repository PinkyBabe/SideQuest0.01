<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get database connection
$conn = Database::getInstance();

// Initialize stats array
$stats = [
    'faculty_count' => 0,
    'student_count' => 0,
    'active_posts' => 0,
    'completed_tasks' => 0
];

// Get student count
$student_query = "SELECT COUNT(*) as count FROM users WHERE role = 'student'";
$result = $conn->query($student_query);
if ($result) {
    $stats['student_count'] = $result->fetch_assoc()['count'];
}

// Get faculty count
$faculty_query = "SELECT COUNT(*) as count FROM users WHERE role = 'faculty'";
$result = $conn->query($faculty_query);
if ($result) {
    $stats['faculty_count'] = $result->fetch_assoc()['count'];
}

// Get active posts count
$posts_query = "SELECT COUNT(*) as count FROM posts WHERE status = 'active'";
$result = $conn->query($posts_query);
if ($result) {
    $stats['active_posts'] = $result->fetch_assoc()['count'];
}

// Get completed tasks count
$tasks_query = "SELECT COUNT(*) as count FROM tasks WHERE status = 'completed'";
$result = $conn->query($tasks_query);
if ($result) {
    $stats['completed_tasks'] = $result->fetch_assoc()['count'];
}

require_once 'includes/auth_middleware.php';

// Check if user is admin
checkRole(['admin']);

$functions = new Functions();
$faculty_list = $functions->getFacultyList();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SideQuest</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body>
    <div class="box">
        <h1>SIDEQUEST</h1>
        <img id="dp" 
            src="https://tse2.mm.bing.net/th?id=OIP.yYUwl3GDU07Q5J5ttyW9fQHaHa&pid=Api&P=0&h=220" 
            alt="User Icon" 
            onclick="showLogoutConfirmation()" 
            style="cursor: pointer;">
    </div>

    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>SIDEQUEST</h2>
            </div>
            <ul>
                <li data-tab="dashboard" class="active">Dashboard</li>
                <li data-tab="faculty">Manage Faculty</li>
                <li data-tab="students">Manage Students</li>
                <li data-tab="activity">Activity Log</li>
                <li><a href="#" onclick="handleLogout(event)">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-content active">
                <h2>Dashboard</h2>
                <div class="stats-container">
                    <div class="stat-card">
                        <h3>Faculty Members</h3>
                        <p><?php echo $stats['faculty_count']; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Active Posts</h3>
                        <p><?php echo $stats['active_posts']; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Completed Tasks</h3>
                        <p><?php echo $stats['completed_tasks']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Faculty Management Tab -->
            <div id="faculty" class="tab-content">
                <h2>Manage Faculty</h2>
                <button onclick="showAddFacultyModal()" class="btn btn-primary">Add Faculty</button>
                <div class="faculty-list">
                    <?php if (!empty($faculty_list)): ?>
                        <?php foreach ($faculty_list as $faculty): ?>
                            <div class="faculty-card">
                                <h3><?php echo htmlspecialchars($faculty['first_name'] . ' ' . $faculty['last_name']); ?></h3>
                                <p>Department: <?php echo htmlspecialchars($faculty['department']); ?></p>
                                <button onclick="toggleFacultyStatus(<?php echo $faculty['id']; ?>)">
                                    <?php echo $faculty['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No faculty members found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add Student Management Tab -->
            <div id="students" class="tab-content">
                <h2>Manage Students</h2>
                <div class="student-list">
                    <?php 
                    $student_list = $functions->getStudentList();
                    if (!empty($student_list)): 
                    ?>
                        <?php foreach ($student_list as $student): ?>
                            <div class="student-card">
                                <h3><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h3>
                                <p>Department: <?php echo htmlspecialchars($student['department']); ?></p>
                                <button onclick="toggleStudentStatus(<?php echo $student['id']; ?>)">
                                    <?php echo $student['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No students found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Replace Reports with Activity Log -->
            <div id="activity" class="tab-content">
                <h2>Activity Log</h2>
                <div class="activity-log">
                    <?php 
                    $activities = $functions->getActivityLog();
                    if (!empty($activities)): 
                    ?>
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-item">
                                <p class="activity-time"><?php echo htmlspecialchars($activity['created_at']); ?></p>
                                <p class="activity-desc"><?php echo htmlspecialchars($activity['description']); ?></p>
                                <p class="activity-user">By: <?php echo htmlspecialchars($activity['user_name']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No activities recorded.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Faculty Modal -->
    <div id="addFacultyModal" class="modal hidden">
        <div class="modal-content">
            <h2>Add Faculty Member</h2>
            <form id="addFacultyForm" onsubmit="handleAddFaculty(event)">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSED">BSED</option>
                        <option value="BSBA">BSBA</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Faculty</button>
                <button type="button" onclick="closeModal('addFacultyModal')" class="btn">Cancel</button>
            </form>
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

    <script src="js/admin.js"></script>
</body>
</html> 