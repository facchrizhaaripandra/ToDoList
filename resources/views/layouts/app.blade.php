<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TaskFlow')</title>
    <link rel="icon" type="image/x-icon" href="https://cdn-icons-png.flaticon.com/512/3208/3208720.png">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --background: #f8fafc;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .board-column {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            min-width: 320px;
            flex-shrink: 0;
        }

        .task-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
            background: white;
            cursor: move;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .priority-high { border-left-color: #ef4444; background: linear-gradient(to right, #fef2f2 0%, white 100%); }
        .priority-medium { border-left-color: #f59e0b; background: linear-gradient(to right, #fffbeb 0%, white 100%); }
        .priority-low { border-left-color: #10b981; background: linear-gradient(to right, #f0fdf4 0%, white 100%); }

        .filter-active {
            background: white !important;
            color: #4f46e5 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .blur-backdrop {
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="h-full">
    {{-- Navbar --}}
    <nav class="glass-effect text-white shadow-xl sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="p-2 bg-white/20 rounded-xl">
                        <i class="fas fa-trello text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">TaskFlow</h1>
                        <p class="text-xs opacity-90">Visual Task Management</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    {{-- Filter Tabs --}}
                    <div class="hidden md:flex items-center space-x-1 bg-white/10 rounded-lg p-1">
                        <a href="{{ route('tasks.index') }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('tasks.index') && !request()->has('filter') ? 'filter-active' : 'text-white/90 hover:text-white' }}">
                            <i class="fas fa-th-large mr-2"></i>All
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'pending']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200
                                  {{ request('filter') == 'pending' ? 'filter-active' : 'text-white/90 hover:text-white' }}">
                            <i class="fas fa-clock mr-2"></i>Pending
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'progress']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200
                                  {{ request('filter') == 'progress' ? 'filter-active' : 'text-white/90 hover:text-white' }}">
                            <i class="fas fa-spinner mr-2"></i>In Progress
                        </a>
                        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}"
                           class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200
                                  {{ request('filter') == 'completed' ? 'filter-active' : 'text-white/90 hover:text-white' }}">
                            <i class="fas fa-check-circle mr-2"></i>Completed
                        </a>
                    </div>

                    {{-- New Task Button --}}
                    <a href="{{ route('tasks.create') }}"
                       class="bg-white text-indigo-600 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200
                              hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center space-x-2 group">
                        <i class="fas fa-plus-circle group-hover:rotate-90 transition-transform duration-200"></i>
                        <span>New Task</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="container mx-auto px-6 mt-6">
        <div class="animate__animated animate__fadeInDown bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div>
                        <p class="font-semibold">{{ session('success') }}</p>
                        <p class="text-sm text-green-600 mt-1">Your task has been updated successfully</p>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                        class="text-green-600 hover:text-green-800 p-1 hover:bg-green-100 rounded-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main class="min-h-[calc(100vh-140px)] py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="glass-effect text-white py-6 mt-8 border-t border-white/10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-trello text-xl"></i>
                        <h2 class="text-lg font-bold">TaskFlow Pro</h2>
                    </div>
                    <p class="text-white/70 text-sm mt-1">Visual task management for modern teams</p>
                </div>

                <div class="flex items-center space-x-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ \App\Models\Task::count() }}</div>
                        <div class="text-xs text-white/70">Total Tasks</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ \App\Models\Task::where('completed', true)->count() }}</div>
                        <div class="text-xs text-white/70">Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ \App\Models\Task::where('completed', false)->count() }}</div>
                        <div class="text-xs text-white/70">Pending</div>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/20 mt-6 pt-6 text-center text-white/60 text-sm">
                <p>© {{ date('Y') }} TaskFlow. Built with Laravel & Tailwind CSS</p>
            </div>
        </div>
    </footer>

    {{-- JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);

            // Smooth scroll for horizontal board
            const boardContainer = document.querySelector('.board-container');
            if (boardContainer) {
                let isDown = false;
                let startX;
                let scrollLeft;

                boardContainer.addEventListener('mousedown', (e) => {
                    isDown = true;
                    boardContainer.classList.add('active');
                    startX = e.pageX - boardContainer.offsetLeft;
                    scrollLeft = boardContainer.scrollLeft;
                });

                boardContainer.addEventListener('mouseleave', () => {
                    isDown = false;
                    boardContainer.classList.remove('active');
                });

                boardContainer.addEventListener('mouseup', () => {
                    isDown = false;
                    boardContainer.classList.remove('active');
                });

                boardContainer.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - boardContainer.offsetLeft;
                    const walk = (x - startX) * 2;
                    boardContainer.scrollLeft = scrollLeft - walk;
                });
            }

            // Task counter animation
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / 100;

                if (count < target) {
                    const updateCount = () => {
                        const currentCount = +counter.innerText;
                        if (currentCount < target) {
                            counter.innerText = Math.ceil(currentCount + increment);
                            setTimeout(updateCount, 10);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    updateCount();
                }
            });
        });

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-6 right-6 p-4 rounded-xl shadow-2xl text-white z-50 animate__animated animate__fadeInRight ${
                type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : 'bg-gradient-to-r from-red-500 to-pink-500'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="p-2 bg-white/20 rounded-lg mr-3">
                        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}"></i>
                    </div>
                    <div>
                        <p class="font-semibold">${message}</p>
                        <p class="text-sm opacity-90">${type === 'success' ? 'Action completed successfully' : 'Something went wrong'}</p>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('animate__fadeOutRight');
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
    </script>
</body>
</html>
