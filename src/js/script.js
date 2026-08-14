document.addEventListener('DOMContentLoaded', function () {
    // Mobile nav toggle
    var hamburger = document.getElementById('hamburger');
    var links = document.getElementById('navLinks');
    if (hamburger && links) {
        hamburger.addEventListener('click', function () {
            links.classList.toggle('is-open');
        });
    }

    // Auto-dismiss alerts after 4 seconds
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () { alert.remove(); }, 4000);
    });
});
