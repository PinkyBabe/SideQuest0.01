document.addEventListener('DOMContentLoaded', function() {
    // Menu toggle functionality
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const box = document.querySelector('.box');
    let overlay;

    // Create overlay element
    function createOverlay() {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }
    createOverlay();

    // Toggle sidebar function
    function toggleSidebar() {
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('sidebar-hidden');
        box.classList.toggle('sidebar-hidden');
        
        if (!sidebar.classList.contains('hidden')) {
            overlay.classList.add('active');
        } else {
            overlay.classList.remove('active');
        }
        
        localStorage.setItem('sidebarHidden', sidebar.classList.contains('hidden'));
    }

    // Event listeners for sidebar
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleSidebar);
    }
    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // Tab navigation
    const tabs = document.querySelectorAll('.sidebar li[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');

    function switchTab(tabId) {
        tabContents.forEach(content => {
            content.style.display = 'none';
        });

        tabs.forEach(tab => {
            tab.classList.remove('active');
        });

        const selectedTab = document.querySelector(`[data-tab="${tabId}"]`);
        const selectedContent = document.getElementById(tabId);
        
        if (selectedTab && selectedContent) {
            selectedTab.classList.add('active');
            selectedContent.style.display = 'block';
        }

        // Load content based on tab
        switch(tabId) {
            case 'posts':
                loadPosts();
                break;
            case 'students':
                loadStudents();
                break;
            case 'tasks':
                loadTasks();
                break;
        }
    }

    // Add click handlers to tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');
            switchTab(tabId);
        });
    });

    // Logout functionality
    function showLogoutConfirmation() {
        const logoutModal = document.getElementById('logoutModal');
        if (logoutModal) {
            logoutModal.classList.add('show');
        }
    }

    function closeLogoutModal() {
        const logoutModal = document.getElementById('logoutModal');
        if (logoutModal) {
            logoutModal.classList.remove('show');
        }
    }

    function confirmLogout() {
        fetch('includes/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=logout'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'index.php';
            }
        })
        .catch(error => console.error('Logout error:', error));
    }

    // Posts Management
    async function loadPosts() {
        try {
            const response = await fetch('includes/get_posts.php');
            const data = await response.json();
            
            const postsList = document.getElementById('postsList');
            if (postsList && data.success) {
                postsList.innerHTML = data.posts.map(post => `
                    <div class="post-card">
                        <div class="post-header">
                            <h3 class="post-title">${post.title}</h3>
                            <span class="post-date">${post.created_at}</span>
                        </div>
                        <div class="post-content">${post.content}</div>
                        <div class="post-actions">
                            <button class="btn btn-primary" onclick="editPost(${post.id})">Edit</button>
                            <button class="btn btn-secondary" onclick="deletePost(${post.id})">Delete</button>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading posts:', error);
        }
    }

    // Students List
    async function loadStudents() {
        try {
            const response = await fetch('includes/get_students.php');
            const data = await response.json();
            
            const studentsList = document.getElementById('studentsList');
            if (studentsList && data.success) {
                studentsList.innerHTML = data.students.map(student => `
                    <div class="student-card">
                        <h3>${student.name}</h3>
                        <p>Email: ${student.email}</p>
                        <p>Progress: ${student.progress}%</p>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading students:', error);
        }
    }

    // Tasks Management
    async function loadTasks() {
        try {
            const response = await fetch('includes/get_tasks.php');
            const data = await response.json();
            
            const tasksList = document.getElementById('tasksList');
            if (tasksList && data.success) {
                tasksList.innerHTML = data.tasks.map(task => `
                    <div class="task-card">
                        <h3>${task.title}</h3>
                        <p>${task.description}</p>
                        <div class="task-status ${task.status}">${task.status}</div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading tasks:', error);
        }
    }

    // Add click handlers for logout
    const dpImage = document.getElementById('dp');
    const logoutMenuItem = document.querySelector('.sidebar ul li:last-child');
    
    if (dpImage) {
        dpImage.addEventListener('click', showLogoutConfirmation);
    }
    if (logoutMenuItem) {
        logoutMenuItem.addEventListener('click', showLogoutConfirmation);
    }

    // Add click handlers for logout modal buttons
    const confirmLogoutBtn = document.querySelector('#logoutModal .btn-primary');
    const cancelLogoutBtn = document.querySelector('#logoutModal .btn-secondary');
    
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', confirmLogout);
    }
    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', closeLogoutModal);
    }

    // Initialize
    switchTab('dashboard');
    
    // Check localStorage for sidebar state
    const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
    if (sidebarHidden) {
        sidebar.classList.add('hidden');
        mainContent.classList.add('sidebar-hidden');
        box.classList.add('sidebar-hidden');
    }
});