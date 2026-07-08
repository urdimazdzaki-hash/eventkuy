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
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
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
                        coral: '#C9A84C',
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
        .sidebar-text { transition: opacity 0.2s ease; }
        #sidebar.collapsed .sidebar-text { opacity: 0; }

        .theme-track {
            background-color: #D1D5DB;
            transition: background-color 0.3s ease;
        }
        .theme-track.dark-mode {
            background-color: #C9A84C;
        }
        .theme-knob {
            transform: translateX(0);
            transition: transform 0.3s ease;
        }
        .theme-knob.dark-mode {
            transform: translateX(20px);
        }
    </style>
</head>
<body class="font-sans bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-100">

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

                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h1 class="text-2xl sidebar-text">
                        <span class="font-script italic text-gray-800 dark:text-gray-100">Event</span><span class="font-bold text-gray-800 dark:text-gray-100">kuy</span>
                    </h1>
                    <button onclick="toggleSidebar()"
                        class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-coral/10 hover:text-coral flex items-center justify-center text-gray-500 dark:text-gray-400 flex-shrink-0 transition">
                        <i class="ph ph-caret-left text-base"></i>
                    </button>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1">
                    <a href="{{ route('events.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('events.index') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i class="ph-duotone ph-squares-four text-xl flex-shrink-0"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <a href="{{ route('venues.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('venues.*') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i class="ph-duotone ph-buildings text-xl flex-shrink-0"></i>
                        <span class="sidebar-text">Venue</span>
                    </a>

                    <a href="{{ route('events.create') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('events.create') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i class="ph-duotone ph-plus-circle text-xl flex-shrink-0"></i>
                        <span class="sidebar-text">Acara</span>
                    </a>

                    <a href="{{ route('vendors.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('vendors.*') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i class="ph-duotone ph-users text-xl flex-shrink-0"></i>
                        <span class="sidebar-text">Vendor</span>
                    </a>

                    <a href="{{ route('cuaca.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('cuaca.*') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i class="ph-duotone ph-cloud-rain text-xl flex-shrink-0"></i>
                        <span class="sidebar-text">Cuaca</span>
                    </a>

                    <a href="{{ route('rundowns.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('rundowns.index') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i class="ph-duotone ph-clock-countdown text-xl flex-shrink-0"></i>
                        <span class="sidebar-text">Rundown</span>
                    </a>
                </nav>

                <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-2 rounded-xl transition">
                            <i class="ph ph-sign-out text-lg"></i>
                            <span class="sidebar-text">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <div class="relative flex-1 overflow-hidden flex flex-col">
                <button id="sidebar-open-btn" onclick="toggleSidebar()"
                    class="hidden fixed top-4 left-4 z-40 w-8 h-8 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow hover:bg-coral/10 hover:text-coral items-center justify-center text-gray-500 transition">
                    <i class="ph ph-caret-right text-base"></i>
                </button>

                <header class="h-14 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-end px-6 gap-3 flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-sun text-base text-yellow-400"></i>
                        <button onclick="toggleTheme()" class="relative w-11 h-6 rounded-full focus:outline-none" title="Ganti tema">
                            <span class="theme-track absolute inset-0 rounded-full" id="theme-track"></span>
                            <span class="theme-knob absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow" id="theme-knob"></span>
                        </button>
                        <i class="ph ph-moon text-base text-gray-400"></i>
                    </div>

                    <button class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-coral/10 hover:text-coral flex items-center justify-center text-gray-500 dark:text-gray-400 transition" title="Notifikasi">
                        <i class="ph-duotone ph-bell text-xl"></i>
                    </button>

                    <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center font-semibold text-sm flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-sm">
                            <p class="font-medium text-gray-800 dark:text-gray-100 leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-xs leading-tight">Event Organizer</p>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="fixed top-4 right-4 z-50">
            <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                <i class="ph ph-sun text-base text-yellow-400"></i>
                <button onclick="toggleTheme()" class="relative w-11 h-6 rounded-full focus:outline-none">
                    <span class="theme-track absolute inset-0 rounded-full" id="theme-track-guest"></span>
                    <span class="theme-knob absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow" id="theme-knob-guest"></span>
                </button>
                <i class="ph ph-moon text-base text-gray-400"></i>
            </div>
        </div>
        @yield('content')
    @endauth

    <script>
        lucide.createIcons();

        function updateThemeUI(isDark) {
            const html = document.getElementById('html-root');
            const track = document.getElementById('theme-track') || document.getElementById('theme-track-guest');
            const knob = document.getElementById('theme-knob') || document.getElementById('theme-knob-guest');

            if (isDark) {
                html.classList.add('dark');
                if (track) track.classList.add('dark-mode');
                if (knob) knob.classList.add('dark-mode');
            } else {
                html.classList.remove('dark');
                if (track) track.classList.remove('dark-mode');
                if (knob) knob.classList.remove('dark-mode');
            }
        }

        function toggleTheme() {
            const isDark = document.getElementById('html-root').classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
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
        });
    </script>
</body>
</html>