document.getElementById('notification-button').addEventListener('click', function (e) {
    e.stopPropagation();
    const dropdown = this.querySelector('#notification-dropdown');
    dropdown.classList.toggle('hidden');
});

// Close when clicking outside
document.addEventListener('click', function () {
    const dropdown = document.querySelector('#notification-dropdown');
    dropdown.classList.add('hidden');
});