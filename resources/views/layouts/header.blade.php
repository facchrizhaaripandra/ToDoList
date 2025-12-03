<nav class="bg-white/60 backdrop-blur sticky top-0 z-40 shadow-sm">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between max-w-4xl">
        <a href="{{ route('tasks.index') }}" class="text-2xl font-bold text-gray-800">📝 My To-Do</a>

        <div class="flex items-center gap-3">
            <button onclick="toggleDrawer()" aria-label="Open menu" class="p-2 rounded-md bg-white shadow hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
</nav>
