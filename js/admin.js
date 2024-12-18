document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if elements exist
    console.log('Menu Toggle:', document.getElementById('menuToggle'));
    console.log('Sidebar:', document.querySelector('.sidebar'));
    console.log('Main Content:', document.querySelector('.main-content'));
    console.log('Box:', document.querySelector('.box'));

    // Sidebar toggle functionality
    const menuToggle = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const box = document.querySelector('.box');
    let isSidebarHidden = false;

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            isSidebarHidden = !isSidebarHidden;
            
            if (isSidebarHidden) {
                sidebar.style.left = '-280px';
                mainContent.style.marginLeft = '0';
                box.style.left = '0';
            } else {
                sidebar.style.left = '0';
                mainContent.style.marginLeft = '280px';
                box.style.left = '280px';
            }
        });
    }

    // Tab switching functionality
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
                    updateFacultyCount();
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

    // Load faculty list when the faculty tab is clicked
    const facultyTab = document.querySelector('li[data-tab="faculty"]');
    if (facultyTab) {
        facultyTab.addEventListener('click', loadFacultyList);
    }

    // Add edit faculty form submission handler
    const editFacultyForm = document.getElementById('editFacultyForm');
    if (editFacultyForm) {
        editFacultyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('includes/update_faculty.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Faculty member updated successfully!');
                    hideModal('editFacultyModal');
                    loadFacultyList(); // Refresh the list
                } else {
                    alert(data.message || 'Error updating faculty member');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating faculty member');
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

// Stats update function
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

function loadFacultyList() {
    fetch('includes/get_faculty_list.php')
        .then(response => response.json())
        .then(response => {
            if (!response.success) {
                throw new Error(response.message || 'Error loading faculty list');
            }

            const tableBody = document.getElementById('facultyTableBody');
            if (!tableBody) return;

            tableBody.innerHTML = '';
            
            if (response.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No faculty members found</td></tr>';
                return;
            }

            response.data.forEach(faculty => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${faculty.first_name} ${faculty.last_name}</td>
                    <td>${faculty.email}</td>
                    <td>${faculty.room_number || '-'}</td>
                    <td>${faculty.office_name || '-'}</td>
                    <td>
                        <span class="status-badge ${faculty.status === 'active' ? 'active' : 'inactive'}">
                            ${faculty.status}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-secondary btn-sm" onclick="editFaculty(${faculty.id})">Edit</button>
                        <button class="btn ${faculty.status === 'active' ? 'btn-danger' : 'btn-success'} btn-sm" 
                            onclick="toggleFacultyStatus(${faculty.id}, '${faculty.status}')">
                            ${faculty.status === 'active' ? 'Deactivate' : 'Activate'}
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading faculty list:', error);
            const tableBody = document.getElementById('facultyTableBody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center;">Error loading faculty list</td></tr>';
            }
        });
}

function toggleFacultyStatus(facultyId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const confirmMessage = `Are you sure you want to ${currentStatus === 'active' ? 'deactivate' : 'activate'} this faculty member?`;
    
    if (confirm(confirmMessage)) {
        fetch('includes/toggle_faculty_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                faculty_id: facultyId,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadFacultyList();
                alert(data.message);
            } else {
                alert(data.message || 'Error updating faculty status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating faculty status');
        });
    }
}

function editFaculty(facultyId) {
    fetch(`includes/get_faculty.php?id=${facultyId}`)
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                const faculty = response.faculty;
                document.getElementById('editFacultyId').value = faculty.id;
                document.getElementById('editFirstName').value = faculty.first_name;
                document.getElementById('editLastName').value = faculty.last_name;
                document.getElementById('editEmail').value = faculty.email;
                document.getElementById('editRoomNumber').value = faculty.room_number || '';
                document.getElementById('editOfficeName').value = faculty.office_name || '';
                showModal('editFacultyModal');
            } else {
                throw new Error(response.message || 'Error loading faculty data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading faculty data');
        });
}