<?php
session_start();
require '../../includes/config.php';


// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

if($method === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    
    if(!isset($data['current_sem'])){
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Current semester is required'
        ]);
        exit;
    }

    $current_sem = $data['current_sem'];
    
    // Validate the semester value (1st, 2nd, Mid Year)
    $validSemesters = ['1st', '2nd', 'Mid Year'];
    if(!in_array($current_sem, $validSemesters)){
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid semester value. Valid values are: 1st, 2nd, Mid Year'
        ]);
        exit;
    }

    $db = new Database();
    $con = $db->getConnection();

    // Update or insert the current semester
    $query = "UPDATE semester SET current_sem = ?, updated_at = NOW() WHERE semester_id = 1";
    $stmt = $con->prepare($query);
    
    if(!$stmt){
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $con->error
        ]);
        exit;
    }

    $stmt->bind_param('s', $current_sem);
    
    if($stmt->execute()){
        // Check if any rows were affected
        if($stmt->affected_rows > 0){
            echo json_encode([
                'success' => true,
                'message' => 'Semester updated successfully',
                'current_sem' => $current_sem
            ]);
        } else {
            // If no rows affected, try to insert
            $query_insert = "INSERT INTO semester (current_sem, created_at, updated_at) VALUES (?, NOW(), NOW())";
            $stmt_insert = $con->prepare($query_insert);
            $stmt_insert->bind_param('s', $current_sem);
            
            if($stmt_insert->execute()){
                echo json_encode([
                    'success' => true,
                    'message' => 'Semester created successfully',
                    'current_sem' => $current_sem
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create semester'
                ]);
            }
            $stmt_insert->close();
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update semester: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $db->closeConnection();
    exit;
}

// GET request - fetch current semester
if($method === 'GET'){
    $db = new Database();
    $con = $db->getConnection();

    $query = "SELECT current_sem FROM semester LIMIT 1";
    $result = $con->query($query);

    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'current_sem' => $row['current_sem']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'current_sem' => null,
            'message' => 'No semester set'
        ]);
    }

    $db->closeConnection();
    exit;
}

http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Method not allowed'
]);
?>
