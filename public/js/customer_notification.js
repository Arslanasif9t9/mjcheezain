// Toggle notification dropdown
document.getElementById('notification-button').addEventListener('click', function () {
    const dropdown = document.getElementById('notification-dropdown');
    dropdown.classList.toggle('hidden');
});

// Close dropdown when clicking outside
document.addEventListener('click', function (event) {
    const notificationButton = document.getElementById('notification-button');
    const dropdown = document.getElementById('notification-dropdown');

    if (!notificationButton.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});