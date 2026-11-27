<script>
(function () {
    try {
        var saved = localStorage.getItem('qreven-theme');
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (saved === 'dark' || (!saved && prefersDark)) {
            document.documentElement.classList.add('dark');
        }
    } catch (_) {}
})();
</script>

