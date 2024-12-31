$(document).ready(function () {
    const $html = $('html');
    const $themeToggler = $('#themeToggler');

    // Load the theme from localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        $html.attr('data-bs-theme', savedTheme);
        $themeToggler.attr('data-theme', savedTheme);
    }

    // Toggle theme and save to localStorage
    $themeToggler.on('click', function () {
        const currentTheme = $html.attr('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        $html.attr('data-bs-theme', newTheme);
        $themeToggler.attr('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
});
