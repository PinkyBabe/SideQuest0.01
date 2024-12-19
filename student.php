<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SideQuest - Student Dashboard</title>
    <link rel="stylesheet" href="css/student.css">
</head>

<body>
    <!-- Top Area -->
    <div class="box">
        <h1>SIDEQUEST</h1>
        <input id="searchbar" type="text" placeholder="Search for a job type">
        <img id="dp" 
            src="images/default-profile.png" 
            alt="Profile" 
            onclick="showLogoutConfirmation()" 
            style="cursor: pointer;">
    </div>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="#" onclick="navigateTo('home')" class="active">HOME</a></li>
            <li><a href="#" onclick="navigateTo('profile')">PROFILE</a></li>
            <li><a href="#" onclick="navigateTo('workspace')">WORKSPACE</a></li>
        </ul>
    </nav>

    <!-- Home Section -->
    <main id="home" class="container">
        <div class="content-wrapper">
            <div class="left_container">
                <!-- Job types will go here -->
            </div>
            <div class="center_container">
                <div id="post_bar">
                    <img src="images/default-profile.png" alt="Profile">
                    <div id="post_content">
                        <textarea placeholder="Looking for a job?"></textarea>
                        <button id="post_button">Post</button>
                    </div>
                </div>
                <div id="posts_area">
                    <!-- Posts will be loaded here -->
                </div>
            </div>
        </div>
    </main>

    <!-- Profile Section -->
    <section id="profile" class="container" style="display: none;">
        <div class="student-profile">
            <img src="images/default-profile.png" alt="Profile Picture">
            <h2>Rey Sinabiananan</h2>
            <div class="student-details">
                <p><strong>Course:</strong> Bachelor of Science in Information Technology</p>
                <p><strong>Year Level:</strong> 3rd Year</p>
                <p><strong>Age:</strong> 21</p>
                <div class="ratings">★★★★☆ (4.5/5)</div>
            </div>
        </div>
    </section>

    <!-- Workspace Section -->
    <section id="workspace" class="container" style="display: none;">
        <h2>Workspace</h2>
        <div class="workspace-tabs">
            <button class="tab-btn active" onclick="showTab('accepted')">Accepted Jobs</button>
            <button class="tab-btn" onclick="showTab('completed')">Completed Jobs</button>
        </div>
        <div id="task-list">
            <!-- Tasks will be loaded here -->
        </div>
    </section>

    <!-- Modals -->
    <div id="logout-confirmation" class="modal">
        <div class="modal-content">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to logout?</p>
            <div class="modal-buttons">
                <button class="btn-secondary" onclick="hideLogoutConfirmation()">Cancel</button>
                <button class="btn-primary" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>

    <div id="rating-modal" class="modal">
        <div class="modal-content">
            <h3>Rate this Job</h3>
            <div class="rating-stars">
                <span class="star" data-rating="1">★</span>
                <span class="star" data-rating="2">★</span>
                <span class="star" data-rating="3">★</span>
                <span class="star" data-rating="4">★</span>
                <span class="star" data-rating="5">★</span>
            </div>
            <div class="modal-buttons">
                <button class="btn-secondary" onclick="closeRatingModal()">Cancel</button>
                <button class="btn-primary" onclick="submitRating()">Submit</button>
            </div>
        </div>
    </div>

    <script src="js/student.js"></script>
</body>

</html>