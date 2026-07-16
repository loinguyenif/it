(function ($) {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        // Dark mode toggle (persists via localStorage)
        var toggle = document.getElementById('fdThemeToggle');
        var root = document.documentElement;
        var STORAGE_KEY = 'fd-dark-mode';

        function applyMode(isDark) {
            root.classList.toggle('fd-dark-mode', isDark);
            if (toggle) {
                var icon = toggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-moon', !isDark);
                    icon.classList.toggle('fa-sun', isDark);
                }
            }
        }

        try {
            applyMode(localStorage.getItem(STORAGE_KEY) === '1');
        } catch (e) { /* localStorage unavailable */ }

        if (toggle) {
            toggle.addEventListener('click', function () {
                var isDark = !root.classList.contains('fd-dark-mode');
                applyMode(isDark);
                try {
                    localStorage.setItem(STORAGE_KEY, isDark ? '1' : '0');
                } catch (e) { /* ignore */ }
            });
        }

        // Sticky header shrink-on-scroll
        var header = document.querySelector('.fd-header');
        if (header) {
            window.addEventListener('scroll', function () {
                header.classList.toggle('fd-header-scrolled', window.scrollY > 10);
            });
        }
    });

})(window.jQuery);
