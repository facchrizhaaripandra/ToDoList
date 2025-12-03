<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ToDo Master')</title>
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/3208/3208720.png">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        .task-completed {
            opacity: 0.7;
            position: relative;
        }

        .task-completed::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 2px;
            background: #10b981;
            transform: translateY(-50%);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .task-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .priority-high { border-left-color: #ef4444; }
        .priority-medium { border-left-color: #f59e0b; }
        .priority-low { border-left-color: #10b981; }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    {{-- Navbar --}}
    <nav class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-tasks text-2xl"></i>
                    <h1 class="text-2xl font-bold">ToDo Master</h1>
                    <span class="bg-white/20 px-2 py-1 rounded-full text-sm">v2.0</span>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('tasks.index') }}"
                       class="hover:bg-white/20 px-4 py-2 rounded-lg transition duration-200 {{ request()->routeIs('tasks.index') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-list mr-2"></i>Tasks
                    </a>
                    <a href="{{ route('tasks.create') }}"
                       class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>New Task
                    </a>

                    {{-- Stats -- HANYA tampilkan di halaman tasks.index --}}
                    @if(request()->routeIs('tasks.index'))
                    <div class="hidden md:flex items-center space-x-3">
                        <div class="text-center">
                            <div class="text-sm opacity-80">Total Tasks</div>
                            <div class="font-bold text-lg">{{ $tasks->total() ?? 0 }}</div>
                        </div>
                        <div class="h-6 w-px bg-white/30"></div>
                        <div class="text-center">
                            <div class="text-sm opacity-80">Completed</div>
                            <div class="font-bold text-lg">{{ $completedTasks ?? 0 }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="animate__animated animate__fadeInDown bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    {{-- Footer dengan stats global --}}
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">© {{ date('Y') }} ToDo Master. Made with <i class="fas fa-heart text-red-400"></i> using Laravel & Tailwind CSS</p>
            <div class="flex justify-center space-x-4 text-sm text-gray-400">
                @php
                    use App\Models\Task;
                    $totalTasks = Task::count();
                    $completedTasks = Task::where('completed', true)->count();
                @endphp
                <span><i class="fas fa-tasks mr-1"></i> {{ $totalTasks }} Tasks</span>
                <span>•</span>
                <span><i class="fas fa-check-circle mr-1"></i> {{ $completedTasks }} Completed</span>
                <span>•</span>
                <span><i class="fas fa-clock mr-1"></i> {{ $totalTasks - $completedTasks }} Pending</span>
            </div>
        </div>
    </footer>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk task cards (hanya jika ada)
            const cards = document.querySelectorAll('.task-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.05}s`;
                card.classList.add('animate__animated', 'animate__fadeIn');
            });

            // Konfirmasi delete
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this task?')) {
                        e.preventDefault();
                    }
                });
            });

            // Auto-hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>
