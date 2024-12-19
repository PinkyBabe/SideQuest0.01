// Navigation and UI functions for student interface
document.addEventListener('DOMContentLoaded', function() {
    // Set home as default visible section
    navigateTo('home');
    
    // Initialize rating stars
    initializeRatingStars();
});

function navigateTo(pageId) {
    // Hide all containers
    const containers = document.querySelectorAll('.container');
    containers.forEach(container => {
        container.style.display = 'none';
    });

    // Show selected container
    const selectedPage = document.getElementById(pageId);
    if (selectedPage) {
        selectedPage.style.display = 'block';
    }

    // Update navigation active state
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('onclick').includes(pageId)) {
            link.classList.add('active');
        }
    });
}

function showLogoutConfirmation() {
    const modal = document.getElementById('logout-confirmation');
    modal.style.display = 'block';
}

function hideLogoutConfirmation() {
    const modal = document.getElementById('logout-confirmation');
    modal.style.display = 'none';
}

function logout() {
    window.location.href = 'includes/logout.php';
}

function showTab(tabName) {
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.textContent.toLowerCase().includes(tabName)) {
            tab.classList.add('active');
        }
    });

    // Here you would typically load the corresponding content
    loadTabContent(tabName);
}

function loadTabContent(tabName) {
    const taskList = document.getElementById('task-list');
    
    // Example of dynamic content loading
    if (tabName === 'accepted') {
        // Load accepted jobs
        taskList.innerHTML = '<div class="empty-state"><p>No accepted jobs yet.</p></div>';
    } else if (tabName === 'completed') {
        // Load completed jobs
        taskList.innerHTML = '<div class="empty-state"><p>No completed jobs yet.</p></div>';
    }
}

function initializeRatingStars() {
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            updateStars(rating);
        });
    });
}

function updateStars(rating) {
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        const starRating = star.getAttribute('data-rating');
        star.classList.toggle('selected', starRating <= rating);
    });
}

function closeRatingModal() {
    const modal = document.getElementById('rating-modal');
    modal.style.display = 'none';
}

function submitRating() {
    const selectedStars = document.querySelectorAll('.star.selected').length;
    // Here you would typically send the rating to the server
    console.log('Submitted rating:', selectedStars);
    closeRatingModal();
}

// Make functions globally available
window.navigateTo = navigateTo;
window.showLogoutConfirmation = showLogoutConfirmation;
window.hideLogoutConfirmation = hideLogoutConfirmation;
window.logout = logout;
window.showTab = showTab;
window.closeRatingModal = closeRatingModal;
window.submitRating = submitRating;