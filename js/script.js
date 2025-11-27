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