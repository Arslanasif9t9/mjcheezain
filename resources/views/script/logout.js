document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const confirmLogout = document.getElementById('confirmLogout');

    // Show modal when logout button is clicked
    logoutBtn.addEventListener('click', function() {
        logoutModal.classList.remove('hidden');
    });

    // Hide modal when cancel button is clicked
    cancelBtn.addEventListener('click', function() {
        logoutModal.classList.add('hidden');
    });

    // Perform logout when confirm button is clicked
    confirmLogout.addEventListener('click', function() {
        // Here you would typically make a logout request to your server
        // location.href = '../index.php'
        logoutModal.classList.add('hidden');
        
        // Example: Redirect to login page after logout
        // window.location.href = '/login';
    });

    // Close modal when clicking outside the modal content
    logoutModal.addEventListener('click', function(e) {
        if (e.target === logoutModal) {
            logoutModal.classList.add('hidden');
        }
    });
});