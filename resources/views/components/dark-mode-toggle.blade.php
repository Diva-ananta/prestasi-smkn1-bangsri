<button
    id="darkModeToggle"
    class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-700 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-emerald-300"
    aria-label="Toggle Dark Mode"
>
    <i class="fas fa-moon text-gray-600 dark:hidden"></i>
    <i class="fas fa-sun hidden dark:inline text-yellow-400"></i>
</button>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;

        // Cek preferensi user
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        toggle.addEventListener('click', function() {
            html.classList.toggle('dark');
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    });
</script>