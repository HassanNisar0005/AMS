document.addEventListener('DOMContentLoaded', function() {
    const toggler = document.querySelector('.custom-navbar-toggler');
    const navbarCollapse = document.querySelector('.custom-navbar-collapse');

    toggler.addEventListener('click', function() {
        navbarCollapse.classList.toggle('active');
        toggler.classList.toggle('active');
    });
});