document.addEventListener('DOMContentLoaded', function() {
    fetch('../controllers/fetchCurrentSem.php')
        .then(response => response.json())
        .then(data => {
            const semesterElement = document.getElementById('currentSemesterText');
            if(data.success) {
                semesterElement.textContent = data.current_sem + ' Sem';
            } else {
                semesterElement.textContent = 'No semester set';
            }
        })
        .catch(error => {
            console.error('Error fetching semester:', error);
            document.getElementById('currentSemesterText').textContent = 'Error loading semester';
        });
});

        /**
         * Toggle visibility of password change fields
         */
        function togglePasswordFields() {
            const checkbox = document.getElementById('change_password');
            const passwordFields = document.getElementById('passwordFields');
            const oldPasswordInput = document.getElementById('old_password');
            const newPasswordInput = document.getElementById('new_password');
            
            if (checkbox.checked) {
                passwordFields.classList.remove('hidden');
            } else {
                passwordFields.classList.add('hidden');
                // Clear password fields if checkbox is unchecked
                oldPasswordInput.value = '';
                newPasswordInput.value = '';
            }
        }

        /**
         * Toggle password visibility
         */
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eyeIcon_' + (fieldId === 'old_password' ? 'old' : 'new'));
            
            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.style.opacity = '1';
            } else {
                field.type = 'password';
                eyeIcon.style.opacity = '0.5';
            }
        }

        /**
         * Check password strength
         */
        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const strengthBar = document.getElementById('strengthBar');
            const req1 = document.getElementById('req1');
            const passwordStrength = document.getElementById('passwordStrength');
            
            if (password.length > 0) {
                passwordStrength.classList.remove('hidden');
            } else {
                passwordStrength.classList.add('hidden');
            }
            
            let strength = 0;
            
            // Check minimum length
            if (password.length >= 6) {
                strength += 25;
                req1.classList.remove('bg-gray-300');
                req1.classList.add('bg-green-500');
            } else {
                req1.classList.remove('bg-green-500');
                req1.classList.add('bg-gray-300');
            }
            
            // Additional strength checks
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            strengthBar.style.width = Math.min(strength, 100) + '%';
            
            if (strength <= 25) {
                strengthBar.style.backgroundColor = '#ef4444';
            } else if (strength <= 50) {
                strengthBar.style.backgroundColor = '#f97316';
            } else if (strength <= 75) {
                strengthBar.style.backgroundColor = '#eab308';
            } else {
                strengthBar.style.backgroundColor = '#22c55e';
            }
        }

        /**
         * Handle form submission
         */
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            const changePassword = document.getElementById('change_password').checked;
            
            if (changePassword) {
                const oldPassword = document.getElementById('old_password').value;
                const newPassword = document.getElementById('new_password').value;
                
                if (!oldPassword || !newPassword) {
                    e.preventDefault();
                    alert('Please fill in both password fields.');
                    return;
                }
                
                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('New password must be at least 6 characters long.');
                    return;
                }
                
                if (oldPassword === newPassword) {
                    e.preventDefault();
                    alert('New password must be different from your current password.');
                    return;
                }
                
                if (!confirm('Are you sure you want to change your password? You will need to log in again with your new password.')) {
                    e.preventDefault();
                    return;
                }
            }
        });

        /**
         * Monitor new password field for strength checking
         */
        document.getElementById('new_password').addEventListener('input', function() {
            if (document.getElementById('change_password').checked) {
                checkPasswordStrength();
            }
        });

        /**
         * Initialize form on page load
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Set up initial state
            const checkbox = document.getElementById('change_password');
            if (checkbox.checked) {
                document.getElementById('passwordFields').classList.remove('hidden');
            }
        });