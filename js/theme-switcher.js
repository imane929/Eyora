// Theme Switcher
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const htmlElement = document.documentElement;
    
    // Get current theme from storage or system preference
    function getCurrentTheme() {
        // Check cookie (for PHP)
        const cookieMatch = document.cookie.match(/(?:^|;\s*)theme=([^;]+)/);
        if (cookieMatch) return decodeURIComponent(cookieMatch[1]);
        
        // Check localStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) return savedTheme;
        
        // Check system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        
        // Default
        return 'light';
    }
    
    // Apply theme to page
    function applyTheme(theme) {
        htmlElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Set cookie for PHP (expires in 1 year)
        document.cookie = `theme=${encodeURIComponent(theme)};path=/;max-age=${60*60*24*365}`;
        
        // Update toggle button
        updateToggleButton(theme);
    }
    
    // Update toggle button appearance
    function updateToggleButton(theme) {
        if (!themeToggle) return;
        
        const moonIcon = themeToggle.querySelector('.fa-moon');
        const sunIcon = themeToggle.querySelector('.fa-sun');
        
        if (moonIcon && sunIcon) {
            if (theme === 'dark') {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'inline-block';
            } else {
                moonIcon.style.display = 'inline-block';
                sunIcon.style.display = 'none';
            }
        }
        
        themeToggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        themeToggle.setAttribute('aria-label', `Passer au thème ${theme === 'dark' ? 'clair' : 'sombre'}`);
    }
    
    // Initialize theme on page load
    const currentTheme = getCurrentTheme();
    applyTheme(currentTheme);
    
    // Toggle theme on button click
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-theme') || getCurrentTheme();
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(newTheme);
            
            // Animation feedback
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }
    
    // Listen for system theme changes
    if (window.matchMedia) {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        
        prefersDark.addEventListener('change', e => {
            // Only auto-switch if user hasn't explicitly chosen a theme
            if (!localStorage.getItem('theme') && !document.cookie.match(/theme=/)) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }
    
    // Smooth theme transition
    const style = document.createElement('style');
    style.textContent = `
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        img, video, iframe, canvas {
            transition: none;
        }
    `;
    document.head.appendChild(style);
});