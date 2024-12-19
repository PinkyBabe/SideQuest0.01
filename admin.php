<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth_middleware.php';

// Check if user is admin
checkUserRole(['admin']);

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

// Function to get student list
function getStudentList() {
    $conn = Database::getInstance();
    $query = "SELECT id, first_name, last_name, email FROM users WHERE role = 'student'";
    $result = $conn->query($query);
    
    $students = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    return $students;
}

// Get student list
$students = getStudentList();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SideQuest</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/admin.css">
    <script src="js/admin.js"></script>
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
            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAOxAAADsQBlSsOGwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAVNSURBVGiB7VlrTFNnGH7Oc3oKlLa0XBRUQJhXmE6MDxRwuEVNzJY4p8sm/th+bH+WLP7xz5Yt2Y9t2ZIl+7Ht17Yf27LEbNmSxWTGxDgnKGNeUARUvIBUoNwKlN6+5/1RKbTS0hZqq5I+SZOe873nfb/ned/vfb/3+w5wj7sdRK4gQUo9yxhZxxjNZ4QFgJBuQsgVRuhZt6w8VqKhq2IEZRmgKIrR6/VvMkZ3A8BdZYcQMsUYO+DxeN5XFMUnJQdJDFAUxWgymY4DOKyxiYsQ8oXb7X5NURSvFDwqBUQQBIPZbG4E8LKEXBgh5CO3272n1WYbloAnmQGKohiMRmMDgFel5AwAhJCPXS7XnlQNSWKAoigGg8FwHMA+qQQjQQj50OVy7U3FkIQGKIpC9Xr9MQCvp0IsFoSQD1wuV0rTKaEBer3+KID9qZKKB0LI+y6Xa18yfRMaYDQa3wHwRjqkYoEQ8p7L5UpqOsU1wGg0vgVAyHTxeDxwOp1wOBwYHx+H3W6H3W7H1NQUJicnMTMzg9nZWXg8HhBCYDAYYDQaYTKZYDabkZWVhezsbGRnZyM3Nxe5ubkoKChAfn4+8vLyoNfrF2AihLzrcrn2J9MjrgEZGRlvA3gzlR/2er2w2Wzo7u5GV1cXent70dfXh4GBAYyMjMDpdEJVVTDGAAA8z4PjOBBCwPM8eJ4HABACAECMsQVqjDGEQiGoqgq/3w+/3w9VVaP6ZmZmIi8vD4WFhSguLkZJSQlKS0tRVlaG8vJyFBUVgeO4xQZ96Ha79yqK4k+kS8JptJhUKBTC4OAg2tvb0dHRgc7OTvT09KC/vx8TExNQVTWlMZMFz/PIyspCQUEBiouLUVpaivLycqiqiubmZgwPD8cMPELIEbfbfSCRLnENMBgMRwC8BQButxtdXV24fv062traYLVa0d/fj2AwmMZPSx0cx6GwsBDl5eWorKxEdXU1ampqUFVVBZPJFN6PEHIYwMFEuGIaoCiKyWQynQAY+vr60NLSgubmZrS2tqK3txd3YsUlhCA/Px9VVVWora3FunXrUF9fj5KSkoVuhJCDLpfrUDy8mAYYjcaTAF7p6OjA2bNncf78ebS3t8Pn86X/S9IEz/MoKyvDxo0bsWXLFmzbtg2lpaXhZkLImy6X6+1YGLEMOEoI2d/Y2IiGhgZcunQJMzMzaf8AqTCZTKirq8P27duxc+dOVFRUhJsIIa87nc4jsZ6NaoDRaDxFCNl54sQJnDp1Cu3t7XeFeUvB8zwqKyuxe/du7Nu3DxUVFQAAQsjrDofjaLRnohngIoRkHzt2DEePHsXo6GhGiEuFxWLBgQMHcPjwYeTk5AAAGGOvuN3uk4v7RjPgNCFk+6FDh3Dy5Mm7zrylKCkpwZEjR3Do0CEYDAYwxl" 
            alt="Profile" 
            onclick="showLogoutConfirmation()" 
            style="cursor: pointer; width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
    </div>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>SIDEQUEST</h2>
        </div>
        <ul>
            <li data-tab="dashboard">Dashboard</li>
            <li data-tab="faculty">Manage Faculty</li>
            <li data-tab="students">Manage Students</li>
            <li data-tab="activity">Activity Log</li>
            <li onclick="showLogoutConfirmation()">Logout</li>
        </ul>
    </div>

    <div class="main-content">
        <div id="dashboard" class="tab-content">
            <h2>Dashboard</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Faculty Members</h3>
                    <p data-stat="faculty_count"><?php echo $stats['faculty_count']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Students</h3>
                    <p data-stat="student_count"><?php echo $stats['student_count']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Active Posts</h3>
                    <p data-stat="active_posts"><?php echo $stats['active_posts']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Completed Tasks</h3>
                    <p data-stat="completed_tasks"><?php echo $stats['completed_tasks']; ?></p>
                </div>
            </div>
        </div>

        <div id="faculty" class="tab-content">
            <h2>Manage Faculty</h2>
            <button class="btn btn-primary" onclick="showModal('addFacultyModal')">Add Faculty</button>
            
            <div id="facultyList" class="faculty-list">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Room Number</th>
                            <th>Office</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="facultyTableBody">
                        <!-- Faculty list will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="students" class="tab-content">
            <h2>Manage Students</h2>
            <div id="studentsList">
                <?php if (empty($students)): ?>
                    <p>No students found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td>
                                    <button class="btn btn-secondary" onclick="viewStudent(<?php echo $student['id']; ?>)">View</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div id="activity" class="tab-content">
            <h2>Activity Log</h2>
            <div id="activityList">
                <!-- Activity log will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to logout?</p>
            <div class="modal-buttons">
                <button onclick="logout()" class="btn btn-danger">Logout</button>
                <button onclick="closeLogoutModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Add Faculty Modal -->
    <div id="addFacultyModal" class="modal">
        <div class="modal-content">
            <h2>Add New Faculty</h2>
            <form id="addFacultyForm">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-group">
                    <label for="roomNumber">Room Number</label>
                    <input type="text" id="roomNumber" name="roomNumber" required>
                </div>
                <div class="form-group">
                    <label for="officeName">Office Name</label>
                    <input type="text" id="officeName" name="officeName" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn btn-primary">Add Faculty</button>
                    <button type="button" class="btn btn-secondary" onclick="hideModal('addFacultyModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- All your modals here -->

    <!-- Place this script tag right before closing body tag -->
    <script src="js/admin.js"></script>
</body>
</html> 