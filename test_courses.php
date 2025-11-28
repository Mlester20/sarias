<!-- 
    Course Management CRUD - Test Page
    Use this file to verify all CRUD operations work correctly
    Navigate to: http://localhost/sarias/test_courses.php
-->

<?php
session_start();

// Check if user is logged in, if not redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require 'includes/config.php';

// Check if courses table exists
$db = new Database();
$con = $db->getConnection();

$tableExists = false;
$result = $con->query("SHOW TABLES LIKE 'courses'");
if ($result && $result->num_rows > 0) {
    $tableExists = true;
}

// Get course count
$courseCount = 0;
if ($tableExists) {
    $countResult = $con->query("SELECT COUNT(*) as count FROM courses");
    if ($countResult) {
        $row = $countResult->fetch_assoc();
        $courseCount = $row['count'];
    }
}

// Get sample courses
$courses = [];
if ($tableExists) {
    $result = $con->query("SELECT * FROM courses LIMIT 5");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course CRUD - Test Page</title>
    <link rel="stylesheet" href="dist/output.css">
    <style>
        .test-box {
            border-left: 4px solid #10b981;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f0fdf4;
        }
        .test-box.error {
            border-left-color: #ef4444;
            background-color: #fef2f2;
        }
        .test-box.warning {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
        }
        .test-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .test-box.error h3 {
            color: #dc2626;
        }
        .test-box.warning h3 {
            color: #d97706;
        }
        .test-box h3 {
            color: #059669;
        }
        code {
            background-color: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        .api-test {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .api-test button {
            background-color: #3b82f6;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
            font-size: 14px;
        }
        .api-test button:hover {
            background-color: #2563eb;
        }
        .response-box {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
            max-height: 200px;
            overflow-y: auto;
            font-size: 12px;
            font-family: monospace;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="max-w-6xl mx-auto p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">🧪 Course CRUD - Test Page</h1>
            <p class="text-gray-600">Verify all components are working correctly</p>
        </div>

        <!-- System Status -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Database Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">🗄️ Database</h3>
                <?php if ($tableExists): ?>
                    <p class="text-green-600 font-semibold">✅ Table Exists</p>
                    <p class="text-gray-600 text-sm mt-2">Courses in DB: <strong><?php echo $courseCount; ?></strong></p>
                <?php else: ?>
                    <p class="text-red-600 font-semibold">❌ Table Missing</p>
                    <p class="text-gray-600 text-sm mt-2">Run SQL script to create table</p>
                <?php endif; ?>
            </div>

            <!-- PHP Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">🐘 PHP</h3>
                <p class="text-green-600 font-semibold">✅ Version <?php echo phpversion(); ?></p>
                <p class="text-gray-600 text-sm mt-2">Controller: <code>courseController.php</code></p>
            </div>

            <!-- Frontend Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">🎨 Frontend</h3>
                <p class="text-green-600 font-semibold">✅ Ready</p>
                <p class="text-gray-600 text-sm mt-2">UI: <code>manageCourses.php</code></p>
            </div>
        </div>

        <!-- Setup Instructions -->
        <div class="test-box">
            <h3>📋 Setup Instructions</h3>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
                <li>Run the SQL script to create the courses table:
                    <br><code>database/create_courses_table.sql</code>
                </li>
                <li>Navigate to: <code>registrar/manageCourses.php</code></li>
                <li>Test the Add, Edit, Delete functions</li>
                <li>Check browser console (F12) for any errors</li>
            </ol>
        </div>

        <!-- Database Status -->
        <div class="<?php echo $tableExists ? 'test-box' : 'test-box error'; ?>">
            <h3><?php echo $tableExists ? '✅ Database Table' : '❌ Database Table Missing'; ?></h3>
            <?php if ($tableExists): ?>
                <p class="text-gray-700 mb-4">The courses table exists and is ready to use.</p>
                <div class="bg-white p-4 rounded border border-green-200">
                    <p class="font-semibold mb-2">Table Info:</p>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>✓ Table name: <code>courses</code></li>
                        <li>✓ Total courses: <code><?php echo $courseCount; ?></code></li>
                        <li>✓ Columns: course_id, course, created_at, updated_at</li>
                    </ul>
                </div>
            <?php else: ?>
                <p class="text-gray-700 mb-4">The courses table does not exist. You need to create it first.</p>
                <div class="bg-white p-4 rounded border border-red-200">
                    <p class="font-semibold mb-2">Run this SQL command:</p>
                    <pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto">
CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_unique` (`course`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;</pre>
                </div>
            <?php endif; ?>
        </div>

        <!-- Files Status -->
        <div class="test-box">
            <h3>📁 Required Files</h3>
            <div class="space-y-2">
                <?php
                $files = [
                    'controllers/courseController.php' => 'Backend CRUD operations',
                    'registrar/manageCourses.php' => 'Frontend UI with modals',
                ];
                
                foreach ($files as $file => $description) {
                    $exists = file_exists($file);
                    $icon = $exists ? '✅' : '❌';
                    $status = $exists ? 'text-green-600' : 'text-red-600';
                    echo "<div class='{$status}'><strong>{$icon} {$file}</strong> - {$description}</div>";
                }
                ?>
            </div>
        </div>

        <!-- CRUD Operations Test -->
        <div class="test-box">
            <h3>🧬 CRUD Operations Tests</h3>
            <p class="text-gray-700 mb-4">Use these buttons to test each operation. Results will show below each button.</p>

            <!-- Test: Fetch All -->
            <div class="api-test">
                <strong>1️⃣ FETCH (Read All)</strong>
                <button onclick="testFetch()">Test Fetch</button>
                <div id="fetch-response" class="response-box hidden">Response will appear here...</div>
            </div>

            <!-- Test: Create -->
            <div class="api-test">
                <strong>2️⃣ CREATE (Add New)</strong>
                <input type="text" id="createInput" placeholder="Enter course name" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; margin-right: 10px; width: 300px;">
                <button onclick="testCreate()">Test Create</button>
                <div id="create-response" class="response-box hidden">Response will appear here...</div>
            </div>

            <!-- Test: Get Single -->
            <div class="api-test">
                <strong>3️⃣ GET (Read One)</strong>
                <input type="number" id="getInput" placeholder="Enter course ID" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; margin-right: 10px; width: 200px;">
                <button onclick="testGet()">Test Get</button>
                <div id="get-response" class="response-box hidden">Response will appear here...</div>
            </div>

            <!-- Test: Update -->
            <div class="api-test">
                <strong>4️⃣ UPDATE (Edit)</strong>
                <input type="number" id="updateIdInput" placeholder="Course ID" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; margin-right: 5px; width: 100px;">
                <input type="text" id="updateInput" placeholder="New course name" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; margin-right: 10px; width: 300px;">
                <button onclick="testUpdate()">Test Update</button>
                <div id="update-response" class="response-box hidden">Response will appear here...</div>
            </div>

            <!-- Test: Delete -->
            <div class="api-test">
                <strong>5️⃣ DELETE (Remove)</strong>
                <input type="number" id="deleteInput" placeholder="Enter course ID to delete" style="padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; margin-right: 10px; width: 200px;">
                <button onclick="testDelete()" style="background-color: #ef4444;">Test Delete</button>
                <div id="delete-response" class="response-box hidden">Response will appear here...</div>
            </div>
        </div>

        <!-- Sample Data -->
        <?php if ($tableExists && $courseCount > 0): ?>
        <div class="test-box">
            <h3>📊 Sample Data</h3>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Course Name</th>
                        <th class="px-4 py-2">Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?php echo $course['course_id']; ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($course['course']); ?></td>
                        <td class="px-4 py-2"><?php echo $course['created_at']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Next Steps -->
        <div class="test-box">
            <h3>✅ Next Steps</h3>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
                <li>If database table exists, click the test buttons above</li>
                <li>Open browser console (F12) to check for JavaScript errors</li>
                <li>Verify all CRUD operations return success responses</li>
                <li>Go to <a href="registrar/manageCourses.php" class="text-blue-600 hover:underline"><code>registrar/manageCourses.php</code></a> to use the full UI</li>
                <li>Read <code>QUICK_START_COURSES.md</code> for complete documentation</li>
            </ol>
        </div>

        <!-- Return Link -->
        <div class="mt-8 text-center">
            <a href="registrar/manageCourses.php" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                Go to Course Management →
            </a>
        </div>
    </div>

    <!-- Test Scripts -->
    <script>
        function displayResponse(elementId, data) {
            const element = document.getElementById(elementId);
            element.classList.remove('hidden');
            element.textContent = JSON.stringify(data, null, 2);
        }

        function testFetch() {
            fetch('controllers/courseController.php?action=fetch')
                .then(res => res.json())
                .then(data => displayResponse('fetch-response', data))
                .catch(err => displayResponse('fetch-response', { error: err.message }));
        }

        function testCreate() {
            const name = document.getElementById('createInput').value.trim();
            if (!name) {
                alert('Please enter a course name');
                return;
            }
            const formData = new FormData();
            formData.append('action', 'create');
            formData.append('course', name);
            
            fetch('controllers/courseController.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    displayResponse('create-response', data);
                    if (data.status === 'success') {
                        document.getElementById('createInput').value = '';
                    }
                })
                .catch(err => displayResponse('create-response', { error: err.message }));
        }

        function testGet() {
            const id = document.getElementById('getInput').value.trim();
            if (!id) {
                alert('Please enter a course ID');
                return;
            }
            fetch(`controllers/courseController.php?action=get&course_id=${id}`)
                .then(res => res.json())
                .then(data => displayResponse('get-response', data))
                .catch(err => displayResponse('get-response', { error: err.message }));
        }

        function testUpdate() {
            const id = document.getElementById('updateIdInput').value.trim();
            const name = document.getElementById('updateInput').value.trim();
            if (!id || !name) {
                alert('Please enter both course ID and new name');
                return;
            }
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('course_id', id);
            formData.append('course', name);
            
            fetch('controllers/courseController.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    displayResponse('update-response', data);
                    if (data.status === 'success') {
                        document.getElementById('updateInput').value = '';
                    }
                })
                .catch(err => displayResponse('update-response', { error: err.message }));
        }

        function testDelete() {
            const id = document.getElementById('deleteInput').value.trim();
            if (!id) {
                alert('Please enter a course ID');
                return;
            }
            if (!confirm('Delete course ID ' + id + '? This cannot be undone.')) return;
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('course_id', id);
            
            fetch('controllers/courseController.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    displayResponse('delete-response', data);
                    if (data.status === 'success') {
                        document.getElementById('deleteInput').value = '';
                    }
                })
                .catch(err => displayResponse('delete-response', { error: err.message }));
        }

        console.log('%c🧪 Course CRUD Test Page Loaded', 'color: #10b981; font-size: 16px; font-weight: bold;');
        console.log('Use the test buttons above or the functions in console:');
        console.log('testFetch(), testCreate(), testGet(), testUpdate(), testDelete()');
    </script>

</body>
</html>
