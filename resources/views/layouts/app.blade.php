<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-br from-purple-100 via-pink-100 to-blue-100 min-h-screen">
    @include('layouts.header')

    <main class="container mx-auto px-4 py-8 max-w-4xl">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Right-side Drawer (hamburger menu) -->
    <div id="drawer" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-xl transform translate-x-full transition-transform z-50">
        <div class="p-6 overflow-y-auto h-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Menu</h3>
                <button onclick="toggleDrawer()" class="text-gray-600 text-lg">✖</button>
            </div>

            <div class="space-y-4">
                <a href="{{ route('tasks.index') }}" class="block text-purple-600 font-medium">📋 Lihat Daftar Task</a>
                <hr class="my-2">
                @includeWhen(View::exists('tasks.create'), 'tasks.create')
            </div>
        </div>
    </div>

    <script>
        function toggleDrawer(){
            const d = document.getElementById('drawer');
            d.classList.toggle('translate-x-full');
        }
    </script>
</body>
</html>
