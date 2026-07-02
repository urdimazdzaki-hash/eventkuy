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

        html {
            transition: background-color 0.4s ease;
        }

        body, aside, main {
            transition: background-color 0.4s ease, color 0.4s ease, border-color 0.4s ease;
        }

        .animate-fade-slide-up, .animate-fade-slide-in {
            transition: none !important;
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
            <aside class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col animate-fade-slide-in">
                <div class="px-6 py-6 border-b border-gray-100 dark:border-gray-800">
                    <h1 class="text-2xl">
                        <span class="font-script italic text-gray-800 dark:text-gray-100">Event</span><span class="font-bold text-gray-800 dark:text-gray-100">kuy</span>
                    </h1>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1">
                    <a href="{{ route('events.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('events.index') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        Dashboard
                    </a>

                    <p class="px-4 pt-5 pb-1 text-xs font-semibold text-gray-400 dark:text-gray-600 uppercase tracking-wide">Modul lain</p>

                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        Venue
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full ml-auto">Mhs 2</span>
                    </span>

                    <a href="{{ route('events.create') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('events.create') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i data-lucide="circle-plus" class="w-5 h-5"></i>
                        Acara
                    </a>

                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        Vendor
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full ml-auto">Mhs 2</span>
                    </span>

                    <span class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 dark:text-gray-600 cursor-not-allowed">
                        <i data-lucide="cloud-rain" class="w-5 h-5"></i>
                        Cuaca
                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-400 px-2 py-0.5 rounded-full ml-auto">Mhs 3</span>
                    </span>

                    <a href="{{ route('rundowns.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('rundowns.index') ? 'bg-coral/10 text-coral' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                        Rundown
                    </a>
                </nav>

                <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-800">
                    <button onclick="toggleTheme()"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition mb-3">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400" id="theme-label">
                            <i data-lucide="moon" class="w-4 h-4 text-gray-500" id="theme-icon"></i>
                            <span id="theme-text">Mode Gelap</span>
                        </div>
                        <div class="w-8 h-4 rounded-full relative transition-colors duration-300" id="theme-toggle-bg">
                            <div class="w-3 h-3 bg-white rounded-full absolute top-0.5 transition-all duration-300" id="theme-toggle-dot"></div>
                        </div>
                    </button>

                    <div class="flex items-center gap-3 px-2 mb-3">
                        <div class="w-9 h-9 rounded-full bg-coral/10 text-coral flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="text-sm">
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-xs">Event Organizer</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 text-left text-sm text-gray-500 dark:text-gray-400 hover:text-coral px-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-950">
                @yield('content')
            </main>
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

        function updateThemeUI(isDark) {
            const html = document.getElementById('html-root');
            const icon = document.getElementById('theme-icon') || document.getElementById('theme-icon-guest');
            const text = document.getElementById('theme-text') || document.getElementById('theme-text-guest');
            const bg = document.getElementById('theme-toggle-bg') || document.getElementById('theme-toggle-bg-guest');
            const dot = document.getElementById('theme-toggle-dot') || document.getElementById('theme-toggle-dot-guest');

            if (isDark) {
                html.classList.add('dark');
                if (icon) { icon.setAttribute('data-lucide', 'sun'); icon.className = 'w-4 h-4 text-yellow-400'; }
                if (text) text.textContent = 'Mode Terang';
                if (bg) bg.style.backgroundColor = '#FF6B6B';
                if (dot) dot.style.left = '1rem';
            } else {
                html.classList.remove('dark');
                if (icon) { icon.setAttribute('data-lucide', 'moon'); icon.className = 'w-4 h-4 text-gray-500'; }
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

        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('theme');
            const isDark = saved === 'dark' || (!saved && '{{ session('theme', 'light') }}' === 'dark');
            updateThemeUI(isDark);
        });
    </script>
</body>
</html>