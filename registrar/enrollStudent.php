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
    <title>Enroll Students</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body>
    
    <!-- header -->
    <?php require '../includes/registrarHeader.php'; ?>

    <!-- main content -->
    <div class="max-w-4xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Enroll Students</h1>
            <p class="text-gray-600">This is the enroll students page. Functionality to enroll students will be implemented here.</p>
        </div>
    </div>
    
    
    <!-- js scripts -->
    <script src="../js/script.js"></script>
</body>
</html>