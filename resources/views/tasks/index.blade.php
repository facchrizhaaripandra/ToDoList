@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header dengan stats --}}
    <div class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Stat Cards --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg mr-4">
                        <i class="fas fa-tasks text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Tasks</p>
                        <p class="text-2xl font-bold">{{ $tasks->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Completed</p>
                        <p class="text-2xl font-bold">{{ $completedTasks }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Pending</p>
                        <p class="text-2xl font-bold">{{ $tasks->total() - $completedTasks }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions Bar --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 bg-white p-4 rounded-xl shadow">
            <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">
                <i class="fas fa-list-check mr-2 text-indigo-600"></i>My Tasks
            </h1>

            <div class="flex space-x-3">
                <a href="{{ route('tasks.create') }}"
                   class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>Create New Task
                </a>

                {{-- Filter Dropdown --}}
                <div class="relative">
                    <button onclick="toggleFilter()"
                            class="bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-filter mr-2"></i>Filter
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>

                    <div id="filterDropdown"
                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-10 border border-gray-200">
                        <a href="{{ route('tasks.index') }}"
                           class="block px-4 py-3 hover:bg-gray-50 {{ !request()->is('tasks/filter/*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                            <i class="fas fa-layer-group mr-2"></i>All Tasks
                        </a>
                        <a href="{{ route('tasks.filter', 'completed') }}"
                           class="block px-4 py-3 hover:bg-gray-50 {{ request()->is('tasks/filter/completed') ? 'bg-green-50 text-green-600' : '' }}">
                            <i class="fas fa-check-circle mr-2"></i>Completed
                        </a>
                        <a href="{{ route('tasks.filter', 'pending') }}"
                           class="block px-4 py-3 hover:bg-gray-50 {{ request()->is('tasks/filter/pending') ? 'bg-yellow-50 text-yellow-600' : '' }}">
                            <i class="fas fa-clock mr-2"></i>Pending
                        </a>
                        <a href="{{ route('tasks.filter', 'high') }}"
                           class="block px-4 py-3 hover:bg-gray-50 {{ request()->is('tasks/filter/high') ? 'bg-red-50 text-red-600' : '' }}">
                            <i class="fas fa-exclamation-triangle mr-2"></i>High Priority
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tasks List --}}
    @if($tasks->isEmpty())
    <div class="text-center py-16 bg-white rounded-xl shadow">
        <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No tasks found</h3>
        <p class="text-gray-500 mb-6">Get started by creating your first task!</p>
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create Your First Task
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($tasks as $task)
        <div class="task-card bg-white rounded-xl shadow hover:shadow-xl transition-all duration-300 overflow-hidden
                    {{ $task->completed ? 'task-completed' : '' }} {{ 'priority-' . $task->priority }}">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            {{-- Priority Badge --}}
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold mr-3
                                {{ $task->priority == 'high' ? 'bg-red-100 text-red-800' :
                                   ($task->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-green-100 text-green-800') }}">
                                <i class="fas fa-flag mr-1"></i>
                                {{ ucfirst($task->priority) }}
                            </span>

                            {{-- Status Badge --}}
                            @if($task->completed)
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                <i class="fas fa-check mr-1"></i> Completed
                            </span>
                            @elseif($task->due_date && $task->due_date->isPast())
                            <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Overdue
                            </span>
                            @endif
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-2 {{ $task->completed ? 'line-through text-gray-500' : '' }}">
                            {{ $task->title }}
                        </h3>

                        @if($task->description)
                        <p class="text-gray-600 mb-4 line-clamp-2">
                            {{ Str::limit($task->description, 150) }}
                        </p>
                        @endif
                    </div>

                    {{-- Task Actions --}}
                    <div class="flex space-x-2 ml-4">
                        {{-- Toggle Complete --}}
                        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="p-2 rounded-full {{ $task->completed ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}
                                           transition duration-200"
                                    title="{{ $task->completed ? 'Mark as Pending' : 'Mark as Complete' }}">
                                <i class="fas {{ $task->completed ? 'fa-rotate-left' : 'fa-check' }}"></i>
                            </button>
                        </form>

                        {{-- Edit --}}
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="p-2 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition duration-200"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-2 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition duration-200"
                                    title="Delete"
                                    onclick="return confirm('Delete this task?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Task Meta --}}
                <div class="flex flex-wrap items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        @if($task->due_date)
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt mr-2"></i>
                            <span class="{{ $task->due_date->isPast() && !$task->completed ? 'text-red-600 font-semibold' : '' }}">
                                Due: {{ $task->due_date->format('M d, Y') }}
                            </span>
                        </div>
                        @endif

                        <div class="flex items-center">
                            <i class="far fa-clock mr-2"></i>
                            <span>Created: {{ $task->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex space-x-2">
                        @if(!$task->completed)
                        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="text-sm bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1.5 rounded-lg transition duration-200">
                                <i class="fas fa-check mr-1"></i>Mark Complete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($tasks->hasPages())
    <div class="mt-8">
        {{ $tasks->links() }}
    </div>
    @endif
    @endif
</div>

<script>
function toggleFilter() {
    const dropdown = document.getElementById('filterDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('filterDropdown');
    const button = document.querySelector('[onclick="toggleFilter()"]');

    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
@endsection
