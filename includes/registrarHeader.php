<header class="bg-green-600 text-white p-4 flex justify-between items-center shadow-lg">
    <h3 class="text-xl font-bold">Registrar Dashboard</h3>
    
    <nav class="flex items-center gap-6">
        <!-- Dropdown Menu -->
        <div class="relative group">
            <button class="flex items-center gap-2 hover:bg-green-700 px-4 py-2 rounded transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span>Menu</span>
            </button>
            
            <!-- Dropdown Content -->
            <div class="absolute left-0 mt-0 w-48 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <a href="../registrar/home.php" class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200">
                    <span class="font-semibold">Home</span>
                </a>
                <a href="manageStudents.php" class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200">
                    <span class="font-semibold">Student Management</span>
                </a>
                <a href="#" class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200">
                    <span class="font-semibold">Faculty Management</span>
                </a>
                <a href="#" class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200">
                    <span class="font-semibold">Grades & Reports</span>
                </a>
                <a href="setSemester.php" class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200">
                    <span class="font-semibold">Settings</span>
                </a>
                <a href="manageCourses.php" class="block px-4 py-3 hover:bg-gray-100 border-b border-gray-200">
                    <span class="font-semibold">Course Management</span>
                </a>
            </div>
        </div>
        
        <!-- User Info Dropdown -->
        <div class="relative group">
            <button class="flex items-center gap-2 hover:bg-green-700 px-4 py-2 rounded transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?></span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>
            
            <!-- User Dropdown Content -->
            <div class="absolute right-0 mt-0 w-56 bg-white text-gray-800 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <!-- Current Semester Section -->
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <p class="text-xs text-gray-600 font-semibold uppercase">Current Semester</p>
                    <p id="currentSemesterText" class="text-sm font-semibold text-gray-800 mt-1">Loading...</p>
                </div>
                
                <!-- Settings Link -->
                <a href="settings.php" class="flex px-4 py-3 hover:bg-gray-100 border-b border-gray-200 items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="font-semibold">Settings</span>
                </a>
                
                <!-- Logout Link -->
                <a href="../registrar/logout.php" class="flex px-4 py-3 hover:bg-red-100 text-red-600 font-semibold items-center gap-2" onclick="return confirm('Are you sure you want to logout?')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>
</header>