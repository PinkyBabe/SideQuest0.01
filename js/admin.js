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
    }

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

    // Add click handlers for both logout triggers
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

    // Load dashboard stats
    async function loadDashboardStats() {
        try {
            const response = await fetch('includes/get_stats.php');
            const data = await response.json();
            
            if (data.success) {
                const stats = data.stats;
                Object.keys(stats).forEach(key => {
                    const element = document.querySelector(`.stat-card p[data-stat="${key}"]`);
                    if (element) {
                        element.textContent = stats[key];
                    }
                });
            }
        } catch (error) {
            console.error('Error loading dashboard stats:', error);
        }
    }

    // Initialize
    loadDashboardStats();
    switchTab('dashboard');
    
    // Check localStorage for sidebar state
    const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
    if (sidebarHidden) {
        sidebar.classList.add('hidden');
        mainContent.classList.add('sidebar-hidden');
        box.classList.add('sidebar-hidden');
    }
});