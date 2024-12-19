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
            case 'quests':
                loadQuests();
                break;
            case 'progress':
                loadProgress();
                break;
            case 'achievements':
                loadAchievements();
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

    // Quests functionality
    async function loadQuests() {
        try {
            const filter = document.getElementById('questFilter').value;
            const response = await fetch(`includes/get_quests.php?filter=${filter}`);
            const data = await response.json();
            
            const questsList = document.getElementById('questsList');
            if (questsList && data.success) {
                questsList.innerHTML = data.quests.map(quest => `
                    <div class="quest-card">
                        <div class="quest-header">
                            <h3 class="quest-title">${quest.title}</h3>
                            <span class="quest-points">${quest.points} points</span>
                        </div>
                        <p>${quest.description}</p>
                        <div class="quest-status ${quest.status}">${quest.status}</div>
                        ${quest.status === 'available' ? 
                            `<button class="btn btn-primary" onclick="startQuest(${quest.id})">Start Quest</button>` : 
                            ''}
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading quests:', error);
        }
    }

    // Progress functionality
    async function loadProgress() {
        try {
            const response = await fetch('includes/get_progress.php');
            const data = await response.json();
            
            const progressList = document.getElementById('progressList');
            if (progressList && data.success) {
                progressList.innerHTML = data.progress.map(item => `
                    <div class="progress-card">
                        <h3>${item.title}</h3>
                        <div class="progress-bar">
                            <div class="progress" style="width: ${item.progress}%"></div>
                        </div>
                        <p>${item.progress}% Complete</p>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading progress:', error);
        }
    }

    // Achievements functionality
    async function loadAchievements() {
        try {
            const response = await fetch('includes/get_achievements.php');
            const data = await response.json();
            
            const achievementsList = document.getElementById('achievementsList');
            if (achievementsList && data.success) {
                achievementsList.innerHTML = data.achievements.map(achievement => `
                    <div class="achievement-card ${achievement.unlocked ? 'unlocked' : 'locked'}">
                        <div class="achievement-icon">🏆</div>
                        <div class="achievement-info">
                            <h3>${achievement.title}</h3>
                            <p>${achievement.description}</p>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading achievements:', error);
        }
    }

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

    // Quest filter change handler
    const questFilter = document.getElementById('questFilter');
    if (questFilter) {
        questFilter.addEventListener('change', loadQuests);
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