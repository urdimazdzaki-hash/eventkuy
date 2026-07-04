<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventKuy')</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && '{{ session('theme', 'light') }}' === 'dark')) {
            document.getElementById('html-root').classList.add('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        script: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        navy: '#1E3A5F',
                        coral: '#FF6B6B',
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateX(-16px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .animate-fade-slide-up {
            animation: fadeSlideUp 0.5s ease forwards;
            opacity: 0;
        }

        .animate-fade-slide-in {
            animation: fadeSlideIn 0.4s ease forwards;
            opacity: 0;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }

        html { transition: background-color 0.4s ease; }

        body, aside, main {
            transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
        }

        #sidebar {
            transition: width 0.3s ease, transform 0.3s ease;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: 0 !important;
            transform: translateX(-100%);
        }

        .sidebar-text {
            transition: opacity 0.2s ease;
        }

        #sidebar.collapsed .sidebar-text {
            opacity: 0;
        }

        #page-transition-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            background-color: #FF6B6B;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        #page-transition-overlay.animate-in {
            opacity: 1;
            pointer-events: all;
        }

        #page-transition-overlay.animate-out {
            opacity: 0;
        }
    </style>
</head>
<body class="font-sans bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-100">

    <div id="page-transition-overlay"></div>

    @if (session('success'))
        <div id="notif-success" class="fixed top-4 right-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-4 py-3 rounded-lg shadow z-50">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const notif = document.getElementById('notif-success');
                if (notif) {
                    notif.style.opacity = '0';
                    setTimeout(() => notif.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    @auth
        <div class="flex min-h-screen">
            <aside id="sidebar" class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col animate-fade-slide-in flex-shrink-0">
                <div class="px-6 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h1 class="text-2xl sidebar-text">
                        <span class="font-script italic text-gray-800 dark:text-gray-100">Event</span><span class="font-bold text-gray-800 dark:text-gray-100">kuy</span>
                    </h1>
                    <button onclick="toggleSidebar()"
                        class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-coral/10 hover:text-coral flex items-center justify-center text-gray-500 dark:text-gray-400 flex-shrink-0 transition">
                        <i data-lucide="chevron-left" class="w-4 h-4" id="sidebar-toggle-icon"></i>
                    </button>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1">
                    <a href="{{ route('events.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('events.index') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <p class="px-4 pt-5 pb-1 text-xs font-semibold text-gray-400 dark:text-gray-600 uppercase tracking-wide sidebar-text">Modul lain</p>

                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                        <i data-lucide="building-2" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="sidebar-text">Venue</span>
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full ml-auto sidebar-text">Mhs 2</span>
                    </span>

                    <a href="{{ route('events.create') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('events.create') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i data-lucide="circle-plus" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="sidebar-text">Acara</span>
                    </a>

                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                        <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="sidebar-text">Vendor</span>
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full ml-auto sidebar-text">Mhs 2</span>
                    </span>

                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                        <i data-lucide="cloud-rain" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="sidebar-text">Cuaca</span>
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full ml-auto sidebar-text">Mhs 3</span>
                    </span>

                    <a href="{{ route('rundowns.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('rundowns.index') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i data-lucide="calendar-clock" class="w-5 h-5 flex-shrink-0"></i>
                        <span class="sidebar-text">Rundown</span>
                    </a>
                </nav>

                <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-800">
                    <button onclick="toggleTheme()"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition mb-3">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <i data-lucide="moon" class="w-4 h-4 text-gray-500 flex-shrink-0" id="theme-icon"></i>
                            <span id="theme-text" class="sidebar-text">Mode Gelap</span>
                        </div>
                        <div class="w-8 h-4 rounded-full relative flex-shrink-0" id="theme-toggle-bg">
                            <div class="w-3 h-3 bg-white rounded-full absolute top-0.5 transition-all duration-300" id="theme-toggle-dot"></div>
                        </div>
                    </button>

                    <div class="flex items-center gap-3 px-2 mb-3">
                        <div class="w-9 h-9 rounded-full bg-coral/10 text-coral flex items-center justify-center font-semibold flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-sm sidebar-text">
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-xs">Event Organizer</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 text-left text-sm text-gray-500 dark:text-gray-400 hover:text-coral px-2">
                            <i data-lucide="log-out" class="w-4 h-4 flex-shrink-0"></i>
                            <span class="sidebar-text">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="relative flex-1 overflow-hidden">
                <button id="sidebar-open-btn" onclick="toggleSidebar()"
                    class="hidden fixed top-4 left-4 z-40 w-8 h-8 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow hover:bg-coral/10 hover:text-coral items-center justify-center text-gray-500 transition">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
                <main class="h-full overflow-y-auto bg-gray-50 dark:bg-gray-950">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="fixed top-4 right-4 z-50">
            <button onclick="toggleTheme()"
                class="flex items-center justify-between gap-3 px-3 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm transition">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <i data-lucide="moon" class="w-4 h-4 text-gray-500" id="theme-icon-guest"></i>
                    <span id="theme-text-guest">Mode Gelap</span>
                </div>
                <div class="w-8 h-4 rounded-full relative transition-colors duration-300" id="theme-toggle-bg-guest">
                    <div class="w-3 h-3 bg-white rounded-full absolute top-0.5 transition-all duration-300" id="theme-toggle-dot-guest"></div>
                </div>
            </button>
        </div>
        @yield('content')
    @endauth

    <script>
        lucide.createIcons();

        const overlay = document.getElementById('page-transition-overlay');

        function updateThemeUI(isDark) {
            const html = document.getElementById('html-root');
            const icon = document.getElementById('theme-icon') || document.getElementById('theme-icon-guest');
            const text = document.getElementById('theme-text') || document.getElementById('theme-text-guest');
            const bg = document.getElementById('theme-toggle-bg') || document.getElementById('theme-toggle-bg-guest');
            const dot = document.getElementById('theme-toggle-dot') || document.getElementById('theme-toggle-dot-guest');

            if (isDark) {
                html.classList.add('dark');
                if (icon) { icon.setAttribute('data-lucide', 'sun'); icon.className = 'w-4 h-4 text-yellow-400 flex-shrink-0'; }
                if (text) text.textContent = 'Mode Terang';
                if (bg) bg.style.backgroundColor = '#FF6B6B';
                if (dot) dot.style.left = '1rem';
            } else {
                html.classList.remove('dark');
                if (icon) { icon.setAttribute('data-lucide', 'moon'); icon.className = 'w-4 h-4 text-gray-500 flex-shrink-0'; }
                if (text) text.textContent = 'Mode Gelap';
                if (bg) bg.style.backgroundColor = '#D1D5DB';
                if (dot) dot.style.left = '0.125rem';
            }
            lucide.createIcons();
        }

        function toggleTheme() {
            const isDark = document.getElementById('html-root').classList.contains('dark');
            const newTheme = isDark ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            updateThemeUI(!isDark);
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('sidebar-open-btn');
            const isCollapsed = sidebar.classList.contains('collapsed');

            if (isCollapsed) {
                sidebar.classList.remove('collapsed');
                openBtn.classList.add('hidden');
                openBtn.style.display = '';
                localStorage.setItem('sidebar', 'open');
            } else {
                sidebar.classList.add('collapsed');
                openBtn.classList.remove('hidden');
                openBtn.style.display = 'flex';
                localStorage.setItem('sidebar', 'collapsed');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const isDark = savedTheme === 'dark' || (!savedTheme && '{{ session('theme', 'light') }}' === 'dark');
            updateThemeUI(isDark);

            const savedSidebar = localStorage.getItem('sidebar');
            if (savedSidebar === 'collapsed') {
                const sidebar = document.getElementById('sidebar');
                const openBtn = document.getElementById('sidebar-open-btn');
                if (sidebar) sidebar.classList.add('collapsed');
                if (openBtn) { openBtn.classList.remove('hidden'); openBtn.style.display = 'flex'; }
            }

            overlay.classList.add('animate-out');
            setTimeout(() => {
                overlay.classList.remove('animate-out');
            }, 250);
        });

        document.querySelectorAll('a[href]').forEach(link => {
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('mailto')) return;
            if (href.startsWith('http') && !href.includes(window.location.hostname)) return;

            link.addEventListener('click', function(e) {
                const target = this.getAttribute('href');
                if (!target || target === window.location.pathname) return;

                e.preventDefault();
                overlay.classList.add('animate-in');

                setTimeout(() => {
                    window.location.href = target;
                }, 200);
            });
        });
    </script>
</body>
</html>