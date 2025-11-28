<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    throw new Exception("Unauthorized access. Please log in.");
    header("Location: ../index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body>

    <?php require '../includes/registrarHeader.php'; ?>

    <main class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">Course Management</h1>
            </div>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Action Buttons -->
            <div class="mb-6 flex gap-3">
                <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Course
                </button>
                <button onclick="loadCourses()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-6 py-4 text-left font-semibold">Course ID</th>
                            <th class="px-6 py-4 text-left font-semibold">Course Name</th>
                            <th class="px-6 py-4 text-left font-semibold">Created At</th>
                            <th class="px-6 py-4 text-left font-semibold">Updated At</th>
                            <th class="px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="coursesTableBody">
                        <tr class="border-b border-gray-200">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading courses...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- No Data Message -->
            <div id="noDataMessage" class="hidden mt-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                <p class="text-yellow-800 font-semibold">No courses found. Create your first course!</p>
            </div>
        </div>
    </main>

    <!-- Add/Edit Course Modal -->
    <div id="courseModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <!-- Modal Header -->
            <div class="bg-green-600 text-white px-6 py-4 border-b border-gray-200">
                <h2 id="modalTitle" class="text-xl font-bold">Add Course</h2>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
                <form id="courseForm">
                    <input type="hidden" id="modalAction" value="create">
                    <input type="hidden" id="course_id" value="">

                    <!-- Course Name Field -->
                    <div class="mb-4">
                        <label for="courseName" class="block text-gray-700 font-semibold mb-2">Course Name</label>
                        <input 
                            type="text" 
                            id="courseName" 
                            name="course"
                            placeholder="Enter course name"
                            maxlength="255"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            required
                        >
                        <p class="text-gray-500 text-sm mt-1" id="charCount">0 / 255 characters</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button 
                            type="button" 
                            onclick="closeAddModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            id="submitBtn"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4">
            <!-- Modal Header -->
            <div class="bg-red-600 text-white px-6 py-4">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Confirm Delete
                </h2>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4">
                <p class="text-gray-700 mb-2">Are you sure you want to delete this course?</p>
                <p id="deleteCourseName" class="font-semibold text-gray-900 p-3 bg-gray-100 rounded-lg mb-4"></p>
                <p class="text-sm text-gray-600">This action cannot be undone.</p>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                <button 
                    type="button" 
                    onclick="closeDeleteModal()"
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition"
                >
                    Cancel
                </button>
                <button 
                    type="button"
                    onclick="confirmDelete()"
                    id="confirmDeleteBtn"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let currentDeleteId = null;

        /**
         * Load all courses
         */
        function loadCourses() {
            fetch('../controllers/courseController.php?action=fetch')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        displayCourses(data.courses);
                    } else {
                        showAlert('Error loading courses: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Failed to load courses', 'error');
                });
        }

        /**
         * Display courses in table
         */
        function displayCourses(courses) {
            const tbody = document.getElementById('coursesTableBody');
            const noDataMsg = document.getElementById('noDataMessage');

            if (courses.length === 0) {
                tbody.innerHTML = '<tr class="border-b border-gray-200"><td colspan="5" class="px-6 py-4 text-center text-gray-500">No courses available</td></tr>';
                noDataMsg.classList.remove('hidden');
                return;
            }

            noDataMsg.classList.add('hidden');
            tbody.innerHTML = '';

            courses.forEach((course, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white border-b border-gray-200 hover:bg-gray-50' : 'bg-gray-50 border-b border-gray-200 hover:bg-gray-100';
                
                const createdAt = new Date(course.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const updatedAt = new Date(course.updated_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                row.innerHTML = `
                    <td class="px-6 py-4 font-semibold text-gray-800">${course.course_id}</td>
                    <td class="px-6 py-4 text-gray-700">${escapeHtml(course.course)}</td>
                    <td class="px-6 py-4 text-gray-700 text-sm">${createdAt}</td>
                    <td class="px-6 py-4 text-gray-700 text-sm">${updatedAt}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openEditModal(${course.course_id})" class="text-blue-600 hover:text-blue-800 hover:bg-blue-100 px-3 py-1 rounded-lg font-semibold transition mr-2">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="openDeleteModal(${course.course_id}, '${escapeHtml(course.course)}')" class="text-red-600 hover:text-red-800 hover:bg-red-100 px-3 py-1 rounded-lg font-semibold transition">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        /**
         * Open add modal
         */
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Course';
            document.getElementById('modalAction').value = 'create';
            document.getElementById('course_id').value = '';
            document.getElementById('courseForm').reset();
            document.getElementById('charCount').textContent = '0 / 255 characters';
            document.getElementById('submitBtn').innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Add Course';
            document.getElementById('courseModal').classList.remove('hidden');
        }

        /**
         * Open edit modal
         */
        function openEditModal(courseId) {
            fetch(`../controllers/courseController.php?action=get&course_id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const course = data.course;
                        document.getElementById('modalTitle').textContent = 'Edit Course';
                        document.getElementById('modalAction').value = 'update';
                        document.getElementById('course_id').value = course.course_id;
                        document.getElementById('courseName').value = course.course;
                        document.getElementById('charCount').textContent = course.course.length + ' / 255 characters';
                        document.getElementById('submitBtn').innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Update Course';
                        document.getElementById('courseModal').classList.remove('hidden');
                    } else {
                        showAlert('Error loading course: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Failed to load course', 'error');
                });
        }

        /**
         * Close add/edit modal
         */
        function closeAddModal() {
            document.getElementById('courseModal').classList.add('hidden');
            document.getElementById('courseForm').reset();
        }

        /**
         * Open delete confirmation modal
         */
        function openDeleteModal(courseId, courseName) {
            currentDeleteId = courseId;
            document.getElementById('deleteCourseName').textContent = courseName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        /**
         * Close delete modal
         */
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            currentDeleteId = null;
        }

        /**
         * Confirm delete
         */
        function confirmDelete() {
            if (currentDeleteId === null) return;

            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('course_id', currentDeleteId);

            document.getElementById('confirmDeleteBtn').disabled = true;
            document.getElementById('confirmDeleteBtn').textContent = 'Deleting...';

            fetch('../controllers/courseController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('confirmDeleteBtn').disabled = false;
                document.getElementById('confirmDeleteBtn').textContent = 'Delete';
                
                if (data.status === 'success') {
                    showAlert(data.message, 'success');
                    closeDeleteModal();
                    loadCourses();
                } else {
                    showAlert('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('confirmDeleteBtn').disabled = false;
                document.getElementById('confirmDeleteBtn').textContent = 'Delete';
                showAlert('Failed to delete course', 'error');
            });
        }

        /**
         * Handle form submission
         */
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const action = document.getElementById('modalAction').value;
            const courseName = document.getElementById('courseName').value.trim();
            const courseId = document.getElementById('course_id').value;

            if (!courseName) {
                showAlert('Course name is required', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('action', action);
            formData.append('course', courseName);
            if (courseId) {
                formData.append('course_id', courseId);
            }

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').textContent = 'Saving...';

            fetch('../controllers/courseController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').innerHTML = action === 'create' 
                    ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Add Course'
                    : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Update Course';
                
                if (data.status === 'success') {
                    showAlert(data.message, 'success');
                    closeAddModal();
                    loadCourses();
                } else {
                    showAlert('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('submitBtn').disabled = false;
                showAlert('Failed to save course', 'error');
            });
        });

        /**
         * Update character count
         */
        document.getElementById('courseName').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('charCount').textContent = count + ' / 255 characters';
        });

        /**
         * Show alert message
         */
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert_' + Date.now();
            
            const alertHTML = `
                <div id="${alertId}" class="mb-4 p-4 rounded-lg flex items-center gap-3 animate-slideIn ${
                    type === 'success' 
                        ? 'bg-green-100 border border-green-400 text-green-700' 
                        : 'bg-red-100 border border-red-400 text-red-700'
                }">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        ${type === 'success' 
                            ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                            : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                        }
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            
            alertContainer.insertAdjacentHTML('beforeend', alertHTML);
            
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    alert.remove();
                }
            }, 4000);
        }

        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        /**
         * Close modals on Escape key
         */
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddModal();
                closeDeleteModal();
            }
        });

        /**
         * Close modal when clicking outside
         */
        document.getElementById('courseModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        /**
         * Load courses on page load
         */
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
        });
    </script>

    <!-- Add animation styles -->
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
    </style>

    <!-- js scripts -->
    <script src="../js/script.js"></script>
</body>
</html>