// Handle tab switching without affecting the URL
document.querySelectorAll('.nav-link').forEach(function (button) {
    button.addEventListener('click', function () {
        // Remove active class from all tabs and buttons
        document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));

        // Add active class to the clicked button and corresponding tab
        const targetId = this.getAttribute('aria-controls');
        this.classList.add('active');
        document.getElementById(targetId).classList.add('show', 'active');
    });
});