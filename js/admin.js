document.addEventListener('DOMContentLoaded', function() {
    // Initialize active tab
    const activeTab = document.querySelector('.sidebar li[data-tab="dashboard"]');
    if (activeTab) {
        activeTab.click();
    }

    // Load faculty list if on faculty tab
    if (document.getElementById('faculty').style.display !== 'none') {
        loadFacultyList();
    }

    // Add form submission handlers
    const addFacultyForm = document.getElementById('addFacultyForm');
    if (addFacultyForm) {
        addFacultyForm.addEventListener('submit', handleAddFacultySubmit);
    }

    const editFacultyForm = document.getElementById('editFacultyForm');
    if (editFacultyForm) {
        editFacultyForm.addEventListener('submit', handleEditFacultySubmit);
    }

    // Add tab switching handlers
    document.querySelectorAll('.sidebar li[data-tab]').forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
});

// Tab switching function
function switchTab(tabId) {
    // Update sidebar active state
    document.querySelectorAll('.sidebar li').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(`.sidebar li[data-tab="${tabId}"]`).classList.add('active');

    // Hide all tabs and show selected
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    document.getElementById(tabId).style.display = 'block';

    // Load data if needed
    if (tabId === 'faculty') {
        loadFacultyList();
    }
}

// Faculty management functions
function loadFacultyList() {
    fetch('includes/get_faculty_list.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.getElementById('facultyTableBody');
                tbody.innerHTML = '';
                
                data.data.forEach(faculty => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${faculty.first_name} ${faculty.last_name}</td>
                        <td>${faculty.email}</td>
                        <td>${faculty.room_number || 'Not set'}</td>
                        <td>${faculty.office_name || 'Not set'}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" onclick="toggleDropdown(${faculty.id})">
                                    Actions
                                </button>
                                <div id="dropdown-${faculty.id}" class="dropdown-menu">
                                    <a href="#" onclick="editFaculty(${faculty.id}); return false;">Edit</a>
                                    <a href="#" onclick="viewFaculty(${faculty.id}); return false;">View</a>
                                    <a href="#" onclick="confirmDelete(${faculty.id}); return false;" class="text-danger">Delete</a>
                                </div>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                console.error('Error loading faculty list:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function handleAddFacultySubmit(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('includes/add_faculty.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideModal('addFacultyModal');
            loadFacultyList();
            this.reset();
        } else {
            alert(data.message || 'Error adding faculty');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding faculty');
    });
}

function editFaculty(id) {
    fetch(`includes/get_faculty.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const faculty = data.data;
                document.getElementById('editFacultyId').value = faculty.id;
                document.getElementById('editFirstName').value = faculty.first_name;
                document.getElementById('editLastName').value = faculty.last_name;
                document.getElementById('editEmail').value = faculty.email;
                document.getElementById('editRoomNumber').value = faculty.room_number || '';
                document.getElementById('editOfficeName').value = faculty.office_name || '';
                showModal('editFacultyModal');
            } else {
                alert(data.message || 'Error loading faculty data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading faculty data');
        });
}

function handleEditFacultySubmit(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('includes/update_faculty.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideModal('editFacultyModal');
            loadFacultyList();
        } else {
            alert(data.message || 'Error updating faculty');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating faculty');
    });
}

function deleteFaculty(id) {
    fetch('includes/delete_faculty.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ faculty_id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadFacultyList();
            alert('Faculty member deleted successfully');
        } else {
            alert(data.message || 'Error deleting faculty');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting faculty');
    });
}

// Modal functions
function showModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

function showLogoutConfirmation() {
    showModal('logoutModal');
}

function closeLogoutModal() {
    hideModal('logoutModal');
}

function logout() {
    window.location.href = 'includes/logout.php';
}

// Add dropdown toggle function
function toggleDropdown(facultyId) {
    const dropdown = document.getElementById(`dropdown-${facultyId}`);
    // Close all other dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu.id !== `dropdown-${facultyId}`) {
            menu.classList.remove('show');
        }
    });
    dropdown.classList.toggle('show');
}

// Add confirmation for delete
function confirmDelete(facultyId) {
    if (confirm('Are you sure you want to delete this faculty member?')) {
        deleteFaculty(facultyId);
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.matches('.dropdown-toggle')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

function viewFaculty(id) {
    fetch(`includes/get_faculty.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const faculty = data.data;
                document.getElementById('viewName').textContent = `${faculty.first_name} ${faculty.last_name}`;
                document.getElementById('viewEmail').textContent = faculty.email;
                document.getElementById('viewRoomNumber').textContent = faculty.room_number || 'Not set';
                document.getElementById('viewOfficeName').textContent = faculty.office_name || 'Not set';
                showModal('viewFacultyModal');
            } else {
                alert(data.message || 'Error loading faculty data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading faculty data');
        });
}