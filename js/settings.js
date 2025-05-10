// Dark mode / Light mode toggle
const themeToggle = document.getElementById('input');
const body = document.body;
const settingsContainer = document.querySelector('.settings-container');
const backButton = document.querySelector('.back-btn');

function applyTheme(theme) {
    if (theme === 'dark') {
        body.classList.add('dark-mode');
        body.classList.remove('light-mode');
        settingsContainer.style.boxShadow = '0 0 15px rgba(162, 0, 255, 0.8)';
        backButton.style.boxShadow = '0 0 10px #a100ff';
    } else {
        body.classList.add('light-mode');
        body.classList.remove('dark-mode');
        settingsContainer.style.boxShadow = '0 0 15px rgba(128, 192, 255, 0.8)';
        backButton.style.boxShadow = '0 0 10px #80C0FF';
    }
}

themeToggle.addEventListener('change', () => {
    const theme = themeToggle.checked ? 'dark' : 'light';
    localStorage.setItem('theme', theme);
    applyTheme(theme);
});

// Apply the saved theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    themeToggle.checked = savedTheme === 'dark';
    applyTheme(savedTheme);
});
