<div class="inline-flex items-center">
    <button
        type="button"
        onclick="window.toggleTheme ? window.toggleTheme() : (function(){
            const current = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', current);
            if (document.body) { document.body.setAttribute('data-theme', current); }
            localStorage.setItem('glo_theme', current);
        })()"
        class="p-2 rounded-[var(--radius-md)] text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--border-color)]/50 transition-colors focus:outline-none focus:ring-2 focus:ring-[var(--brand-primary)] cursor-pointer"
        title="Alternar Tema (Escuro / Claro)"
        aria-label="Alternar Tema (Escuro / Claro)">
        <!-- Dark Mode Icon (Moon - visible when dark mode is active) -->
        <svg class="theme-icon-moon w-5 h-5 text-[var(--text-main)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>

        <!-- Light Mode Icon (Sun - visible when light mode is active) -->
        <svg class="theme-icon-sun w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    </button>
</div>