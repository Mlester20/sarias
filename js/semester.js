document.addEventListener('DOMContentLoaded', function() {
    loadCurrentSemester();
    setupSemesterSelection();
    setupUpdateButton();
});

/**
 * Load and display the current semester
 */
function loadCurrentSemester() {
    fetch('../controllers/settings/updateSemesterController.php?action=get')
        .then(response => response.json())
        .then(data => {
            const displayElement = document.getElementById('displayCurrentSem');
            if(data.success && data.current_sem) {
                displayElement.textContent = data.current_sem + ' Semester';
                // Pre-select the radio button
                const radioBtn = document.querySelector(`input[name="semester"][value="${data.current_sem}"]`);
                if(radioBtn) {
                    radioBtn.checked = true;
                }
            } else {
                displayElement.textContent = 'No semester set';
                displayElement.classList.add('text-gray-400');
            }
        })
        .catch(error => {
            console.error('Error loading semester:', error);
            document.getElementById('displayCurrentSem').textContent = 'Error loading semester';
        });
}

/**
 * Setup semester selection interaction
 */
function setupSemesterSelection() {
    const radioButtons = document.querySelectorAll('input[name="semester"]');
    const updateBtn = document.getElementById('updateSemesterBtn');

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            updateBtn.disabled = false;
        });
    });
}

/**
 * Setup update button functionality
 */
function setupUpdateButton() {
    const updateBtn = document.getElementById('updateSemesterBtn');
    
    updateBtn.addEventListener('click', function() {
        const selectedSemester = document.querySelector('input[name="semester"]:checked');
        
        if(!selectedSemester) {
            showAlert('Please select a semester', 'error');
            return;
        }

        const semester = selectedSemester.value;
        updateSemester(semester);
    });
}

/**
 * Send update request to backend
 */
function updateSemester(semester) {
    const updateBtn = document.getElementById('updateSemesterBtn');
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Updating...';

    fetch('../controllers/settings/updateSemesterController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            current_sem: semester
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showAlert(`Semester updated to "${semester}" successfully!`, 'success');
            updateBtn.innerHTML = 'Update Semester';
            
            // Reload header semester display
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Failed to update semester', 'error');
            updateBtn.innerHTML = 'Update Semester';
            updateBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while updating semester', 'error');
        updateBtn.innerHTML = 'Update Semester';
        updateBtn.disabled = false;
    });
}

/**
 * Show alert message
 */
function showAlert(message, type) {
    const container = document.getElementById('alertContainer');
    const alertClass = type === 'success' 
        ? 'bg-green-50 border border-green-200 text-green-800' 
        : 'bg-red-50 border border-red-200 text-red-800';
    const icon = type === 'success' ? '✓' : '✕';

    const alertHTML = `
        <div class="p-4 rounded-lg ${alertClass} flex items-start gap-3 mb-4">
            <span class="text-lg font-bold">${icon}</span>
            <div>
                <p class="font-semibold">${type === 'success' ? 'Success' : 'Error'}</p>
                <p class="text-sm">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-lg opacity-50 hover:opacity-100">×</button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', alertHTML);

    // Auto-remove success alerts after 5 seconds
    if(type === 'success') {
        setTimeout(() => {
            const alerts = container.querySelectorAll('div');
            if(alerts.length > 0) {
                alerts[0].remove();
            }
        }, 5000);
    }
}
