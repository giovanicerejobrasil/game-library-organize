// resources/js/app.js

window.toggleTheme = function () {
    const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('glo_theme') || 'dark';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    document.documentElement.setAttribute('data-theme', newTheme);
    if (document.body) {
        document.body.setAttribute('data-theme', newTheme);
    }
    localStorage.setItem('glo_theme', newTheme);

    window.dispatchEvent(new CustomEvent('glo-theme-changed', { detail: { theme: newTheme } }));
};

window.setTheme = function (theme) {
    document.documentElement.setAttribute('data-theme', theme);
    if (document.body) {
        document.body.setAttribute('data-theme', theme);
    }
    localStorage.setItem('glo_theme', theme);

    window.dispatchEvent(new CustomEvent('glo-theme-changed', { detail: { theme } }));
};

// Initialize theme on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('glo_theme') || document.documentElement.getAttribute('data-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
});

window.togglePasswordVisibility = function (button) {
    const container = button.closest('.relative');
    if (!container) return;

    const input = container.querySelector('input');
    if (!input) return;

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';

    const eyeOpen = button.querySelector('.eye-open');
    const eyeClosed = button.querySelector('.eye-closed');

    if (eyeOpen && eyeClosed) {
        if (isPassword) {
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
};

