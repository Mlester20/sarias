<?php
session_start();
require '../includes/config.php';

$response = [
    'status' => 'error',
    'message' => ''
];

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        throw new Exception("Unauthorized access. Please log in.");
    }

    // Connection
    $db = new Database();
    $con = $db->getConnection();

    // Determine action
    $action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

    switch ($action) {
        case 'fetch':
            // Fetch all courses
            $query = "SELECT course_id, course, created_at, updated_at FROM courses ORDER BY created_at DESC";
            $result = $con->query($query);
            
            if (!$result) {
                throw new Exception("Query failed: " . $con->error);
            }
            
            $courses = [];
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
            
            $response['status'] = 'success';
            $response['courses'] = $courses;
            break;

        case 'create':
            // Create new course
            $course_name = isset($_POST['course']) ? trim($_POST['course']) : '';
            
            if (empty($course_name)) {
                throw new Exception("Course name is required.");
            }
            
            if (strlen($course_name) > 255) {
                throw new Exception("Course name must not exceed 255 characters.");
            }
            
            // Check for duplicate course name
            $check_query = "SELECT course_id FROM courses WHERE course = ?";
            $check_stmt = $con->prepare($check_query);
            
            if (!$check_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $check_stmt->bind_param("s", $course_name);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                throw new Exception("Course already exists.");
            }
            
            $check_stmt->close();
            
            // Insert course
            $insert_query = "INSERT INTO courses (course, created_at, updated_at) VALUES (?, NOW(), NOW())";
            $insert_stmt = $con->prepare($insert_query);
            
            if (!$insert_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $insert_stmt->bind_param("s", $course_name);
            
            if (!$insert_stmt->execute()) {
                throw new Exception("Failed to create course: " . $insert_stmt->error);
            }
            
            $response['status'] = 'success';
            $response['message'] = 'Course created successfully!';
            $response['course_id'] = $con->insert_id;
            
            $insert_stmt->close();
            break;

        case 'update':
            // Update course
            $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
            $course_name = isset($_POST['course']) ? trim($_POST['course']) : '';
            
            if ($course_id <= 0) {
                throw new Exception("Invalid course ID.");
            }
            
            if (empty($course_name)) {
                throw new Exception("Course name is required.");
            }
            
            if (strlen($course_name) > 255) {
                throw new Exception("Course name must not exceed 255 characters.");
            }
            
            // Check if course exists
            $check_query = "SELECT course_id FROM courses WHERE course_id = ?";
            $check_stmt = $con->prepare($check_query);
            
            if (!$check_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $check_stmt->bind_param("i", $course_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows === 0) {
                throw new Exception("Course not found.");
            }
            
            $check_stmt->close();
            
            // Check for duplicate course name (excluding current course)
            $dup_query = "SELECT course_id FROM courses WHERE course = ? AND course_id != ?";
            $dup_stmt = $con->prepare($dup_query);
            
            if (!$dup_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $dup_stmt->bind_param("si", $course_name, $course_id);
            $dup_stmt->execute();
            $dup_result = $dup_stmt->get_result();
            
            if ($dup_result->num_rows > 0) {
                throw new Exception("Course name already exists for another course.");
            }
            
            $dup_stmt->close();
            
            // Update course
            $update_query = "UPDATE courses SET course = ?, updated_at = NOW() WHERE course_id = ?";
            $update_stmt = $con->prepare($update_query);
            
            if (!$update_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $update_stmt->bind_param("si", $course_name, $course_id);
            
            if (!$update_stmt->execute()) {
                throw new Exception("Failed to update course: " . $update_stmt->error);
            }
            
            $response['status'] = 'success';
            $response['message'] = 'Course updated successfully!';
            
            $update_stmt->close();
            break;

        case 'delete':
            // Delete course
            $course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
            
            if ($course_id <= 0) {
                throw new Exception("Invalid course ID.");
            }
            
            // Check if course exists
            $check_query = "SELECT course_id FROM courses WHERE course_id = ?";
            $check_stmt = $con->prepare($check_query);
            
            if (!$check_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $check_stmt->bind_param("i", $course_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows === 0) {
                throw new Exception("Course not found.");
            }
            
            $check_stmt->close();
            
            // Delete course
            $delete_query = "DELETE FROM courses WHERE course_id = ?";
            $delete_stmt = $con->prepare($delete_query);
            
            if (!$delete_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $delete_stmt->bind_param("i", $course_id);
            
            if (!$delete_stmt->execute()) {
                throw new Exception("Failed to delete course: " . $delete_stmt->error);
            }
            
            $response['status'] = 'success';
            $response['message'] = 'Course deleted successfully!';
            
            $delete_stmt->close();
            break;

        case 'get':
            // Get single course for editing
            $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
            
            if ($course_id <= 0) {
                throw new Exception("Invalid course ID.");
            }
            
            $query = "SELECT course_id, course, created_at, updated_at FROM courses WHERE course_id = ?";
            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $stmt->bind_param("i", $course_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                throw new Exception("Course not found.");
            }
            
            $course = $result->fetch_assoc();
            $response['status'] = 'success';
            $response['course'] = $course;
            
            $stmt->close();
            break;

        default:
            throw new Exception("Invalid action.");
    }

} catch (Exception $e) {
    // Handle exceptions
    error_log("Error: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    
} finally {
    // Close connection if it exists
    if (isset($con) && $con !== null) {
        $con->close();
    }
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>