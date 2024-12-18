document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin JS loaded'); // Debug message

    // Hide all tabs except dashboard initially
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tab => {
        if (tab.id !== 'dashboard') {
            tab.style.display = 'none';
        }
    });

    // Add click handlers to sidebar items
    const sidebarItems = document.querySelectorAll('.sidebar li[data-tab]');
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            sidebarItems.forEach(i => i.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Hide all tab contents
            tabContents.forEach(tab => {
                tab.style.display = 'none';
            });
            
            // Show selected tab content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).style.display = 'block';
        });
    });

    // Add Faculty form submission handler
    const addFacultyForm = document.getElementById('addFacultyForm');
    if (addFacultyForm) {
        addFacultyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            // Collect form data
            const formData = new FormData(this);
            formData.append('role', 'faculty'); // Automatically set role to faculty
            
            // Submit form data to server
            fetch('includes/add_faculty.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Faculty member added successfully!');
                    hideModal('addFacultyModal');
                    addFacultyForm.reset();
                    updateFacultyCount(); // Update the count after successful addition
                } else {
                    alert(data.message || 'Error adding faculty member');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding faculty member');
            });
        });
    }
});

// Modal functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    }
}

// Logout modal functions
function showLogoutConfirmation() {
    const modal = document.getElementById('logoutModal');
    if (modal) {
        modal.classList.add('show');
    }
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// Update the faculty count display
function updateFacultyCount() {
    fetch('includes/get_stats.php')
        .then(response => response.json())
        .then(data => {
            const facultyCountElement = document.querySelector('[data-stat="faculty_count"]');
            if (facultyCountElement) {
                facultyCountElement.textContent = data.faculty_count;
            }
        })
        .catch(error => console.error('Error updating stats:', error));
}