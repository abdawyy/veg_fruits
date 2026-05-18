<script>
    function aldawyApplyColorScheme() {
        var dark = document.documentElement.classList.contains('dark');
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        var m = document.querySelector('meta[name="theme-color"]');
        if (m) {
            m.setAttribute('content', dark ? '#0f172a' : '#16a34a');
        }
    }
    function aldawyToggleTheme() {
        var el = document.documentElement;
        el.classList.toggle('dark');
        try {
            localStorage.setItem('aldawy-theme', el.classList.contains('dark') ? 'dark' : 'light');
        } catch (e) {}
        aldawyApplyColorScheme();
    }
    (function () {
        try {
            var k = 'aldawy-theme';
            var s = localStorage.getItem(k);
            if (s === 'dark' || (s !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {}
        aldawyApplyColorScheme();
    })();
</script>
