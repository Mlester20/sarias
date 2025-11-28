<?php
session_start();

    if(!isset($_SESSION['user_id'])){
        http_response_code(401);
        throw new Exception("Unauthorized access. Please log in.");
        // Alternatively, you can redirect to login page
        header("Location: ../index.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Semester</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body>

    <!-- registrar header -->
    <?php require '../includes/registrarHeader.php'; ?>
    


    <!-- js scripts -->
    <script src="../js/script.js"></script>
</body>
</html>