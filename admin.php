<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/auth_middleware.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug session
error_log("Session data: " . print_r($_SESSION, true));

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

// Get counts
$stats['faculty_count'] = getFacultyCount();
$stats['student_count'] = getStudentCount();
$stats['active_posts'] = getActivePostsCount();
$stats['completed_tasks'] = getCompletedTasksCount();

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
        <img id="dp" src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" alt="Profile" onclick="showLogoutConfirmation()" style="width: 40px; height: 40px; border-radius: 50%; cursor: pointer;">
    </div>

    <div class="sidebar">
        <div class="sidebar-header">
            <h2>SIDEQUEST</h2>
        </div>
        <ul>
            <li data-tab="dashboard" class="active">Dashboard</li>
            <li data-tab="faculty">Manage Faculty</li>
            <li data-tab="students">Manage Students</li>
            <li onclick="showLogoutConfirmation()">Logout</li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content">
            <h2>Dashboard</h2>
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Faculty Members</h3>
                    <p><?php echo $stats['faculty_count']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Students</h3>
                    <p><?php echo $stats['student_count']; ?></p>
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
        <div id="faculty" class="tab-content" style="display: none;">
            <h2>Manage Faculty</h2>
            <button class="btn btn-primary" onclick="showModal('addFacultyModal')">Add Faculty</button>
            
            <div class="faculty-list">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Room Number</th>
                            <th>Office</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="facultyTableBody">
                        <!-- Faculty list will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Students Management Tab -->
        <div id="students" class="tab-content" style="display: none;">
            <h2>Manage Students</h2>
            <div class="student-list">
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

    <!-- Edit Faculty Modal -->
    <div id="editFacultyModal" class="modal">
        <div class="modal-content">
            <h2>Edit Faculty</h2>
            <form id="editFacultyForm">
                <input type="hidden" id="editFacultyId" name="facultyId">
                <div class="form-group">
                    <label for="editFirstName">First Name</label>
                    <input type="text" id="editFirstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="editLastName">Last Name</label>
                    <input type="text" id="editLastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" id="editEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="editRoomNumber">Room Number</label>
                    <input type="text" id="editRoomNumber" name="roomNumber" required>
                </div>
                <div class="form-group">
                    <label for="editOfficeName">Office Name</label>
                    <input type="text" id="editOfficeName" name="officeName" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" onclick="hideModal('editFacultyModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
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

    <!-- View Faculty Modal -->
    <div id="viewFacultyModal" class="modal">
        <div class="modal-content">
            <h2>Faculty Details</h2>
            <div class="faculty-details">
                <div class="detail-row">
                    <label>Name:</label>
                    <span id="viewName"></span>
                </div>
                <div class="detail-row">
                    <label>Email:</label>
                    <span id="viewEmail"></span>
                </div>
                <div class="detail-row">
                    <label>Room Number:</label>
                    <span id="viewRoomNumber"></span>
                </div>
                <div class="detail-row">
                    <label>Office:</label>
                    <span id="viewOfficeName"></span>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn btn-secondary" onclick="hideModal('viewFacultyModal')">Close</button>
            </div>
        </div>
    </div>

    <script src="js/admin.js"></script>
</body>
</html> 