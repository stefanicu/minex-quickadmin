document.querySelectorAll('.navprod-link').forEach(function (button) {
    button.addEventListener('click', function () {
        console.log('Clicked:', this); // Debugging log

        // Remove active class from all tabs and buttons
        document.querySelectorAll('.navprod-link').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));

        // Add active class to the clicked button and corresponding tab
        const targetId = this.getAttribute('aria-controls');
        const targetTab = document.getElementById(targetId);

        if (targetTab) {
            this.classList.add('active');
            targetTab.classList.add('show', 'active');
        } else {
            //console.error('No tab found for ID:', targetId); // Debugging log
        }
    });
});