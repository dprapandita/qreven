function syncThemeSwitches(isDark) {
    document.querySelectorAll('[data-theme-toggle]').forEach((el) => {
        el.setAttribute('aria-checked', isDark ? 'true' : 'false');
    });
}

window.toggleQrevenTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    syncThemeSwitches(isDark);
    try {
        localStorage.setItem('qreven-theme', isDark ? 'dark' : 'light');
    } catch (_) {}
};

document.addEventListener('DOMContentLoaded', () => {
    syncThemeSwitches(document.documentElement.classList.contains('dark'));
});
