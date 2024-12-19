<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Quest</title>
    <link rel="stylesheet" href="css/faculty.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="box">
        <h1>SIDEQUEST <input id="searchbar" type="text" placeholder="Search for a job type"></h1>
        <img id="dp" src="https://tse2.mm.bing.net/th?id=OIP.yYUwl3GDU07Q5J5ttyW9fQHaHa&pid=Api&P=0&h=220" alt="User Icon" 
        onclick="showLogoutConfirmation()" style="cursor: pointer;">
    </div>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="#" onclick="navigateTo('profile')" class="active">PROFILE</a></li>
            <li><a href="#" onclick="navigateTo('workspace')">WORKSPACE</a></li>
        </ul>
    </nav>

    <!-- Profile Section -->
    <main id="profile" class="container">
        <div class="cover_area">
            <div class="cover_page">
                <img id="prof_pic" src="https://tse2.mm.bing.net/th?id=OIP.yYUwl3GDU07Q5J5ttyW9fQHaHa&pid=Api&P=0&h=220" alt="prof_pic">
                <div class="faculty-info">
                    <div id="name">Rey Sinabiananan</div>
                    <div class="details">
                        <p>Room: 301</p>
                        <p>Office: Computer Studies Department</p>
                    </div>
                </div>
            </div>

            <!-- Post Creation Area -->
            <div class="post_box">
                <textarea id="post_textarea" placeholder="Post a project." onclick="expandPostArea()"></textarea>
                <div id="expanded_post" class="hidden">
                    <textarea id="expanded_textarea" placeholder="Describe your project here."></textarea>
                    <div class="form-group">
                        <label for="job_description">What job do you want them to do?</label>
                        <select id="job_description" onchange="toggleSpecifyField()">
                            <option value="" disabled selected>Select job type</option>
                            <option value="Office Work">Office Work</option>
                            <option value="Cleaning">Cleaning</option>
                            <option value="Printing">Printing</option>
                            <option value="Others">Others (Please Specify)</option>
                        </select>
                        <input type="text" id="specify_job" class="hidden" placeholder="Please specify the job">
                    </div>
                    <div class="post-actions">
                        <button onclick="submitPost()" class="post-btn">Post</button>
                        <button onclick="collapsePostArea()" class="cancel-btn">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Posted Tasks -->
            <div id="posts-container">
                <!-- Posts will be added here dynamically -->
            </div>
        </div>
    </main>

    <!-- Workspace Section -->
    <section id="workspace" class="container" style="display: none;">
        <h2>Task Status</h2>
        <div id="task-list">
            <!-- Tasks will be displayed here with their status -->
        </div>
    </section>

    <!-- Rating Modal -->
    <div id="rating-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Rate Student's Work</h2>
            <div class="rating-stars">
                <span class="star" data-rating="1">★</span>
                <span class="star" data-rating="2">★</span>
                <span class="star" data-rating="3">★</span>
                <span class="star" data-rating="4">★</span>
                <span class="star" data-rating="5">★</span>
            </div>
            <textarea id="rating-feedback" placeholder="Leave your feedback (optional)"></textarea>
            <button id="submit-rating">Submit Rating</button>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logout-confirmation" class="modal">
        <div class="modal-content">
            <h3>Logout Confirmation</h3>
            <p>Are you sure you want to log out?</p>
            <div class="modal-buttons">
                <button onclick="logout()" class="btn-primary">Yes, Logout</button>
                <button onclick="hideLogoutConfirmation()" class="btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/faculty.js"></script>
</body>
</html>