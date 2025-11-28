<?php
session_start();
require '../controllers/settings/registrarSettings.php';

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
    <title>Settings</title>
    <link rel="stylesheet" href="../dist/output.css">
</head>
<body>
    
    <!-- registrar header -->
    <?php require '../includes/registrarHeader.php'; ?>

    <!-- Main Content -->
    <main class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-2xl mx-auto px-4">
            
            <!-- Header -->
            <div class="mb-8">
                <h3 class="text-3xl font-bold text-gray-600 text-center">Account Settings</h3>
            </div>

            <!-- Alert Messages -->
            <?php if ($response['status'] === 'success'): ?>
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo htmlspecialchars($response['message']); ?></span>
                </div>
            <?php elseif ($response['status'] === 'error' && $response['message']): ?>
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo htmlspecialchars($response['message']); ?></span>
                </div>
            <?php endif; ?>

            <!-- Settings Form -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <form id="settingsForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                    <!-- Profile Information Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6 pb-2 border-b-2 border-green-600">Profile Information</h2>
                        
                        <!-- Name Field -->
                        <div class="mb-6">
                            <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="<?php echo $user ? htmlspecialchars($user['name']) : ''; ?>"
                                placeholder="Enter your full name"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Username Field -->
                        <div class="mb-6">
                            <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                value="<?php echo $user ? htmlspecialchars($user['username']) : ''; ?>"
                                placeholder="Enter your username"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            >
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 pb-2 border-b-2 border-green-600 mb-6">
                            <input 
                                type="checkbox" 
                                id="change_password" 
                                name="change_password"
                                class="w-5 h-5 text-green-600 rounded focus:ring-2 focus:ring-green-500"
                                onchange="togglePasswordFields()"
                            >
                            <label for="change_password" class="text-xl font-semibold text-gray-800 cursor-pointer">Change Password</label>
                        </div>

                        <!-- Password Fields (Hidden by default) -->
                        <div id="passwordFields" class="hidden space-y-6">
                            <!-- Current Password Field -->
                            <div>
                                <label for="old_password" class="block text-gray-700 font-semibold mb-2">Current Password</label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="old_password" 
                                        name="old_password" 
                                        placeholder="Enter your current password for verification"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    >
                                    <button 
                                        type="button" 
                                        onclick="togglePasswordVisibility('old_password')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    >
                                        <svg id="eyeIcon_old" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-gray-600 text-sm mt-1">We need your current password for security verification.</p>
                            </div>

                            <!-- New Password Field -->
                            <div>
                                <label for="new_password" class="block text-gray-700 font-semibold mb-2">New Password</label>
                                <div class="relative">
                                    <input 
                                        type="password" 
                                        id="new_password" 
                                        name="new_password" 
                                        placeholder="Enter your new password (minimum 6 characters)"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    >
                                    <button 
                                        type="button" 
                                        onclick="togglePasswordVisibility('new_password')"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    >
                                        <svg id="eyeIcon_new" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div id="passwordStrength" class="mt-2 hidden">
                                    <p class="text-sm text-gray-600 mb-1">Password strength:</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div id="strengthBar" class="h-2 rounded-full w-0 transition-all" style="background-color: #ef4444;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm font-semibold text-blue-900 mb-2">Password Requirements:</p>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li class="flex items-center gap-2">
                                        <span id="req1" class="inline-block w-4 h-4 bg-gray-300 rounded-full"></span>
                                        At least 6 characters
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <button 
                            type="submit" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2"
                            id="submitBtn"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </button>
                        <a 
                            href="home.php" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Last Updated Info -->
            <?php if ($user): ?>
                <div class="mt-6 text-center text-gray-600 text-sm">
                    <p>Account last updated on <span class="font-semibold"><?php echo htmlspecialchars($user['updated_at']); ?></span></p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- js scripts -->
    <script src="../js/script.js"></script>
</body>
</html>