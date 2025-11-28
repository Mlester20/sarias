<?php
require '../includes/config.php';

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
        'message' => 'No current semester set'
    ]);
}

$db->closeConnection();
?>