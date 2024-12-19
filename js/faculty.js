// Navigation and Initialization
document.addEventListener('DOMContentLoaded', () => {
    // Set initial state
    navigateTo('profile');
});

function navigateTo(sectionId) {
    // Only toggle the main content sections
    document.querySelectorAll('main, section').forEach(section => {
        if (section.id === 'profile' || section.id === 'workspace') {
            section.style.display = section.id === sectionId ? 'block' : 'none';
        }
    });

    // Update active state in navigation
    document.querySelectorAll('nav a').forEach(link => {
        if (link.getAttribute('onclick').includes(sectionId)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

// Post Management
function expandPostArea() {
    document.getElementById('post_textarea').style.display = 'none';
    document.getElementById('expanded_post').classList.remove('hidden');
}

function collapsePostArea() {
    document.getElementById('post_textarea').style.display = 'block';
    document.getElementById('expanded_post').classList.add('hidden');
    resetPostForm();
}

function toggleSpecifyField() {
    const jobDescription = document.getElementById('job_description');
    const specifyField = document.getElementById('specify_job');
    specifyField.classList.toggle('hidden', jobDescription.value !== 'Others');
}

function resetPostForm() {
    document.getElementById('expanded_textarea').value = '';
    document.getElementById('job_description').value = '';
    document.getElementById('specify_job').value = '';
    document.getElementById('specify_job').classList.add('hidden');
}

function submitPost() {
    const description = document.getElementById('expanded_textarea').value;
    const jobType = document.getElementById('job_description').value;
    
    if (!description || !jobType) {
        alert('Please fill in all required fields');
        return;
    }

    const post = createPostElement({
        description,
        jobType,
        status: 'pending'
    });

    document.getElementById('posts-container').insertBefore(
        post, 
        document.getElementById('posts-container').firstChild
    );

    // Also add to workspace
    addTaskToWorkspace({
        description,
        jobType,
        status: 'pending'
    });

    collapsePostArea();
}

function createPostElement(postData) {
    const post = document.createElement('div');
    post.id = 'post_bar';
    post.innerHTML = `
        <div>
            <img src="https://tse2.mm.bing.net/th?id=OIP.yYUwl3GDU07Q5J5ttyW9fQHaHa&pid=Api&P=0&h=220" alt="profile picture">
        </div>
        <div id="post_content">
            <div id="post_name">Rey Sinabian</div>
            <br>
            ${postData.description}
            <div class="post-type">Job Type: ${postData.jobType}</div>
        </div>
    `;
    return post;
}

// Workspace Management
function addTaskToWorkspace(taskData) {
    const task = document.createElement('div');
    task.className = 'task-item';
    task.innerHTML = `
        <div class="task-content">
            <div class="task-header">
                <span class="status-badge ${taskData.status}">${taskData.status.toUpperCase()}</span>
            </div>
            <div class="task-description">${taskData.description}</div>
            <div class="task-type">
                ${taskData.jobType}
            </div>
        </div>
    `;
    document.getElementById('task-list').appendChild(task);
}

// Task Management
function createTaskElement(task) {
    const taskElement = document.createElement('div');
    taskElement.className = 'task-item';
    taskElement.dataset.taskId = task.id;
    
    const statusClass = task.status.toLowerCase();
    const actions = task.status === 'ACCEPTED' ? 
        `<button onclick="completeTask(${task.id})" class="complete-btn">Mark as Complete</button>` : '';

    taskElement.innerHTML = `
        <div class="task-content">
            <div class="task-header">
                <span class="status-badge ${statusClass}">${task.status}</span>
            </div>
            <div class="task-description">${task.description}</div>
            ${task.assignedTo ? `<div class="assigned-to">Accepted by: ${task.assignedTo}</div>` : ''}
            <div class="task-actions">
                ${actions}
            </div>
        </div>
    `;

    return taskElement;
}

function completeTask(taskId) {
    showRatingModal(taskId);
}

// Rating System
function showRatingModal(taskId) {
    const modal = document.getElementById('rating-modal');
    modal.style.display = 'block';
    modal.dataset.taskId = taskId;

    // Reset stars
    document.querySelectorAll('.star').forEach(star => {
        star.classList.remove('selected');
        star.onclick = () => selectRating(star.dataset.rating);
    });
}

function selectRating(rating) {
    document.querySelectorAll('.star').forEach(star => {
        star.classList.toggle('selected', star.dataset.rating <= rating);
    });
}

// Logout handling
function showLogoutConfirmation() {
    document.getElementById('logout-confirmation').style.display = 'block';
}

function hideLogoutConfirmation() {
    document.getElementById('logout-confirmation').style.display = 'none';
}

function logout() {
    // Show loading state
    const logoutBtn = document.querySelector('#logout-confirmation .btn-primary');
    logoutBtn.textContent = 'Logging out...';
    logoutBtn.disabled = true;

    // Redirect to logout handler
    window.location.href = 'includes/logout.php';
}

// Close modals when clicking outside
window.onclick = (event) => {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
};