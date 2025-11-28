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
<body class="bg-gray-100">

    <!-- registrar header -->
    <?php require '../includes/registrarHeader.php'; ?>
    
    <!-- main content -->
    <div class="max-w-2xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Semester Management</h1>
            <p class="text-gray-600 mb-6">Update the current active semester for the institution</p>

            <!-- Semester Update Form -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Set Current Semester</h2>
                
                <!-- Alert Messages -->
                <div id="alertContainer"></div>

                <!-- Current Semester Display -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Currently Active Semester:</p>
                    <p id="displayCurrentSem" class="text-2xl font-bold text-blue-600">Loading...</p>
                </div>

                <!-- Semester Selection -->
                <div class="mb-6">
                    <label for="semesterSelect" class="block text-sm font-semibold text-gray-700 mb-3">
                        Select Semester
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition"
                               data-semester="1st">
                            <input type="radio" name="semester" value="1st" class="w-4 h-4 text-green-600">
                            <span class="ml-3 font-semibold text-gray-700">First Semester</span>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition"
                               data-semester="2nd">
                            <input type="radio" name="semester" value="2nd" class="w-4 h-4 text-green-600">
                            <span class="ml-3 font-semibold text-gray-700">Second Semester</span>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition"
                               data-semester="Mid Year">
                            <input type="radio" name="semester" value="Mid Year" class="w-4 h-4 text-green-600">
                            <span class="ml-3 font-semibold text-gray-700">Mid-Year</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button id="updateSemesterBtn" type="button" 
                            class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                            disabled>
                        Update Semester
                    </button>
                    <button type="button" 
                            class="px-6 py-2 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 transition"
                            onclick="location.href='home.php'">
                        Cancel
                    </button>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h3 class="font-semibold text-yellow-800 mb-2">📋 Information</h3>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• The current semester applies institution-wide</li>
                    <li>• Changes are effective immediately after confirmation</li>
                    <li>• Make sure to select the correct semester before updating</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- js scripts -->
    <script src="../js/script.js"></script>
    <script src="../js/semester.js"></script>
</body>
</html>