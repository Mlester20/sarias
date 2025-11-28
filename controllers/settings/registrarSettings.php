<?php
require '../includes/config.php';

$response = [
    'status' => 'error',
    'message' => ''
];

$user = null;
$stmt = null;
$con = null;

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        throw new Exception("Unauthorized access. Please log in.");
    }

    // Store session to variable
    $user_id = $_SESSION['user_id'];

    // Connection
    $db = new Database();
    $con = $db->getConnection();

    // Fetch user details from the database
    $query = "SELECT user_id, name, username, password, updated_at FROM registrar WHERE user_id = ?";
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // No user found with the given user_id
        http_response_code(404);
        throw new Exception("User not found.");
    }
    
    $user = $result->fetch_assoc();
    
    // Check if data is fetched
    if (!$user) {
        http_response_code(500);
        throw new Exception("Failed to fetch user details.");
    }
    
    // Handle form submission (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
        $change_password = isset($_POST['change_password']) && $_POST['change_password'] === 'on';
        
        // Validate inputs
        if (empty($name) || empty($username)) {
            throw new Exception("Name and username are required fields.");
        }
        
        if ($change_password) {
            // Validate password change inputs
            if (empty($old_password)) {
                throw new Exception("Please enter your current password for verification.");
            }
            
            if (empty($new_password)) {
                throw new Exception("Please enter a new password.");
            }
            
            if (strlen($new_password) < 6) {
                throw new Exception("New password must be at least 6 characters long.");
            }
            
            // Verify old password matches database password
            $old_password_hash = sha1($old_password);
            if ($old_password_hash !== $user['password']) {
                throw new Exception("Current password is incorrect. Please try again.");
            }
            
            // Prepare update query with password change
            $new_password_hash = sha1($new_password);
            $update_query = "UPDATE registrar SET name = ?, username = ?, password = ?, updated_at = NOW() WHERE user_id = ?";
            $update_stmt = $con->prepare($update_query);
            
            if (!$update_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $update_stmt->bind_param("sssi", $name, $username, $new_password_hash, $user_id);
        } else {
            // Prepare update query without password change
            $update_query = "UPDATE registrar SET name = ?, username = ?, updated_at = NOW() WHERE user_id = ?";
            $update_stmt = $con->prepare($update_query);
            
            if (!$update_stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $update_stmt->bind_param("ssi", $name, $username, $user_id);
        }
        
        // Execute update
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update profile: " . $update_stmt->error);
        }
        
        // Update session variables
        $_SESSION['name'] = $name;
        
        $response['status'] = 'success';
        $response['message'] = 'Profile updated successfully!';
        
        // Refresh user data
        $stmt->close();
        $query = "SELECT user_id, name, username, password FROM registrar WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $update_stmt->close();
    }
    
} catch (Exception $e) {
    // Handle exceptions
    error_log("Error: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
    
} finally {
    // Close statement and connection if they exist
    if (isset($stmt) && $stmt !== null) {
        $stmt->close();
    }
    if (isset($con) && $con !== null) {
        $con->close();
    }
}

?>