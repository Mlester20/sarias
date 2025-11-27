<?php
session_start();
require '../../includes/config.php';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $db = new Database();
    $con = $db->getConnection();
    $username = $con->real_escape_string(trim($_POST['username']));
    $password = $con->real_escape_string(trim(sha1($_POST['password'])));

    try{
        $query = "SELECT user_id, name from registrar WHERE username = ? AND password = ? LIMIT 1";
        $stmt = $con->prepare($query);
        
        if($stmt){
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['name'] = $row['name'];
                
                // Redirect to registrar folder
                header("Location: ../../registrar/home.php");
                exit();
            } else {
                header("Location: ../../index.php?error=invalid_credentials");
                exit();
            }
        } else {
            header("Location: ../../index.php?error=query_error");
            exit();
        }
    } catch(Exception $e){
        throw new Exception("Error Processing Request", 1);
        exit();
    } finally {
        $db->closeConnection();
    }
}
?>