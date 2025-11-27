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
    <title>Lists of Students</title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body>

    <!-- header -->
    <?php require '../includes/registrarHeader.php'; ?>    

    <!-- Dummy table data -->
    <main class="p-6 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-600 mb-2 text-center">Student Management</h1>
                <!-- <p class="text-gray-600">Manage and view all enrolled students</p> -->
            </div>

            <!-- Action Buttons -->
            <div class="mb-6 flex gap-3">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <a href="enrollStudent.php">Enroll Student</a>
                </button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    Export
                </button>
            </div>

            <!-- Table Section -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-6 py-4 text-left font-semibold">Student ID</th>
                            <th class="px-6 py-4 text-left font-semibold">Full Name</th>
                            <th class="px-6 py-4 text-left font-semibold">Course</th>
                            <th class="px-6 py-4 text-left font-semibold">Year</th>
                            <th class="px-6 py-4 text-left font-semibold">Status</th>
                            <th class="px-6 py-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">STU001</td>
                            <td class="px-6 py-4 text-gray-700">Juan Dela Cruz</td>
                            <td class="px-6 py-4 text-gray-700">Bachelor of Science in IT</td>
                            <td class="px-6 py-4 text-gray-700">2nd Year</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Active</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold mr-3"><i class="fas fa-pencil"></i></button>
                                <button class="text-red-600 hover:text-red-800 font-semibold"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <!-- Row 2 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">STU002</td>
                            <td class="px-6 py-4 text-gray-700">Maria Santos</td>
                            <td class="px-6 py-4 text-gray-700">Bachelor of Science in Business</td>
                            <td class="px-6 py-4 text-gray-700">3rd Year</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Active</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold mr-3"><i class="fas fa-pencil"></i></button>
                                <button class="text-red-600 hover:text-red-800 font-semibold"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <!-- Row 3 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">STU003</td>
                            <td class="px-6 py-4 text-gray-700">Pedro Reyes</td>
                            <td class="px-6 py-4 text-gray-700">Bachelor of Science in Engineering</td>
                            <td class="px-6 py-4 text-gray-700">1st Year</td>
                            <td class="px-6 py-4">
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Inactive</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold mr-3"><i class="fas fa-pencil"></i></button>
                                <button class="text-red-600 hover:text-red-800 font-semibold"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <!-- Row 4 -->
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">STU004</td>
                            <td class="px-6 py-4 text-gray-700">Anna Lopez</td>
                            <td class="px-6 py-4 text-gray-700">Bachelor of Science in IT</td>
                            <td class="px-6 py-4 text-gray-700">2nd Year</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Active</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold mr-3"><i class="fas fa-pencil"></i></button>
                                <button class="text-red-600 hover:text-red-800 font-semibold"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                        <!-- Row 5 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800">STU005</td>
                            <td class="px-6 py-4 text-gray-700">Carlos Fernandez</td>
                            <td class="px-6 py-4 text-gray-700">Bachelor of Science in Commerce</td>
                            <td class="px-6 py-4 text-gray-700">3rd Year</td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Active</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-600 hover:text-blue-800 font-semibold mr-3"> <i class="fas fa-pencil"></i> </button>
                                <button class="text-red-600 hover:text-red-800 font-semibold"> <i class="fas fa-trash"></i> </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-between items-center">
                <p class="text-gray-600">Showing 1 to 5 of 150 students</p>
                <div class="flex gap-2">
                    <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">Previous</button>
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">Next</button>
                </div>
            </div>
        </div>
    </main>


    <!-- js scripts -->
    <script src="../js/script.js"></script>
</body>
</html>