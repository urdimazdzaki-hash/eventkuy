<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EventKuy')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
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
</head>
<body class="font-sans bg-gray-50 text-gray-800">

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow z-50">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')

</body>
</html>