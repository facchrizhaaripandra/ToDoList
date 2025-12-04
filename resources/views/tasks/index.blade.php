@extends('layouts.app')

@section('title', 'Task Board')

@section('content')
<div class="container mx-auto px-6">
    {{-- Stats Dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-effect rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Tasks</p>
                    <p class="text-3xl font-bold counter" data-count="{{ $tasks->total() }}">0</p>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-tasks text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('tasks.index') }}"
               class="inline-block mt-4 text-sm opacity-90 hover:opacity-100 transition duration-200">
                <i class="fas fa-eye mr-1"></i> View all tasks
            </a>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-2xl p-6 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-800 mb-1">Pending</p>
                    <p class="text-3xl font-bold text-gray-800 counter" data-count="{{ $pendingCount }}">0</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-xl">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
            </div>
            <a href="{{ route('tasks.index', ['filter' => 'pending']) }}"
               class="inline-block mt-4 text-sm text-yellow-700 hover:text-yellow-900 transition duration-200">
                <i class="fas fa-filter mr-1"></i> Filter pending tasks
            </a>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-800 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-gray-800 counter" data-count="{{ $inProgressCount }}">0</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl">
                    <i class="fas fa-spinner text-2xl text-blue-600"></i>
                </div>
            </div>
            <a href="{{ route('tasks.index', ['filter' => 'progress']) }}"
               class="inline-block mt-4 text-sm text-blue-700 hover:text-blue-900 transition duration-200">
                <i class="fas fa-filter mr-1"></i> Filter in progress
            </a>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-800 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-800 counter" data-count="{{ $completedCount }}">0</p>
                </div>
                <div class="p-3 bg-green-100 rounded-xl">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
            <a href="{{ route('tasks.index', ['filter' => 'completed']) }}"
               class="inline-block mt-4 text-sm text-green-700 hover:text-green-900 transition duration-200">
                <i class="fas fa-filter mr-1"></i> Filter completed
            </a>
        </div>
    </div>

    {{-- Board Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">
                @if(request('filter') == 'pending')
                <i class="fas fa-clock mr-2"></i>Pending Tasks
                @elseif(request('filter') == 'progress')
                <i class="fas fa-spinner mr-2"></i>In Progress Tasks
                @elseif(request('filter') == 'completed')
                <i class="fas fa-check-circle mr-2"></i>Completed Tasks
                @else
                <i class="fas fa-trello mr-2"></i>Task Board
                @endif
            </h1>
            <p class="text-white/80">
                @if(request('filter') == 'pending')
                Tasks that require attention
                @elseif(request('filter') == 'progress')
                Tasks currently being worked on
                @elseif(request('filter') == 'completed')
                Successfully completed tasks
                @else
                Drag and drop tasks between columns
                @endif
            </p>
        </div>

        <div class="flex items-center space-x-3 mt-4 md:mt-0">
            <div class="relative group">
                <button class="glass-effect text-white px-4 py-2.5 rounded-xl hover:bg-white/30 transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-sort-amount-down"></i>
                    <span>Sort</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl z-10 border border-gray-200 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'due_date']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="far fa-calendar mr-2"></i>Due Date
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'priority']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-flag mr-2"></i>Priority
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                        <i class="far fa-clock mr-2"></i>Created Date
                    </a>
                </div>
            </div>

            <a href="{{ route('tasks.create') }}"
               class="bg-white text-indigo-600 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200
                      hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl flex items-center space-x-2 group">
                <i class="fas fa-plus-circle group-hover:rotate-90 transition-transform duration-200"></i>
                <span>Add Task</span>
            </a>
        </div>
    </div>

    {{-- Task Board --}}
    @if($tasks->isEmpty())
    <div class="text-center py-16">
        <div class="max-w-md mx-auto glass-effect rounded-2xl p-8">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clipboard-list text-3xl text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">
                @if(request('filter'))
                No {{ request('filter') }} tasks found
                @else
                Your board is empty
                @endif
            </h3>
            <p class="text-white/80 mb-6">
                @if(request('filter'))
                Try creating a new task or check other filters
                @else
                Start by creating your first task
                @endif
            </p>
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold">
                <i class="fas fa-plus mr-2"></i>Create First Task
            </a>
        </div>
    </div>
    @else
    {{-- Horizontal Scrollable Board --}}
    <div class="board-container flex space-x-6 pb-6 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">

        @if(!request('filter') || request('filter') == 'pending')
        {{-- Pending Column --}}
        <div class="board-column rounded-2xl p-5 flex-shrink-0">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                    <h3 class="font-bold text-gray-800">Pending</h3>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $pendingCount }}
                    </span>
                </div>
                <div class="flex items-center space-x-1">
                    <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                    <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-ellipsis-h text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-4 min-h-[400px]" id="pending-column">
                @foreach($pendingTasks as $task)
                <div class="task-card rounded-xl p-4 shadow-sm border border-gray-200 fade-in {{ 'priority-' . $task->priority }}"
                     draggable="true"
                     data-task-id="{{ $task->id }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            {{-- Priority Badge --}}
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold
                                    {{ $task->priority == 'high' ? 'bg-red-100 text-red-700' :
                                       ($task->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-green-100 text-green-700') }}">
                                    <i class="fas fa-{{ $task->priority == 'high' ? 'exclamation-triangle' : 'flag' }} text-xs mr-1.5"></i>
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            {{-- Task Title --}}
                            <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $task->title }}</h4>

                            {{-- Description Preview --}}
                            @if($task->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($task->description, 100) }}</p>
                            @endif

                            {{-- Tags --}}
                            <div class="flex flex-wrap gap-1.5">
                                @if($task->due_date && $task->due_date->isPast())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Overdue
                                </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    <i class="far fa-clock mr-1"></i>{{ $task->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="flex flex-col space-y-1">
                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition duration-200"
                                        title="Mark Complete">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>

                            <a href="{{ route('tasks.edit', $task->id) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Due Date --}}
                    @if($task->due_date)
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt text-gray-400 mr-2"></i>
                            <span class="text-sm {{ $task->due_date->isPast() ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                Due {{ $task->due_date->format('M d, Y') }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">
                            {{ $task->due_date->diffForHumans() }}
                        </span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button onclick="openQuickCreate('pending')"
                        class="w-full py-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Add a task
                </button>
            </div>
        </div>
        @endif

        @if(!request('filter') || request('filter') == 'progress')
        {{-- In Progress Column --}}
        <div class="board-column rounded-2xl p-5 flex-shrink-0">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                    <h3 class="font-bold text-gray-800">In Progress</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $inProgressCount }}
                    </span>
                </div>
                <div class="flex items-center space-x-1">
                    <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                    <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-ellipsis-h text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-4 min-h-[400px]" id="progress-column">
                @foreach($inProgressTasks as $task)
                <div class="task-card rounded-xl p-4 shadow-sm border border-gray-200 fade-in {{ 'priority-' . $task->priority }}"
                     draggable="true"
                     data-task-id="{{ $task->id }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            {{-- Priority Badge --}}
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700">
                                    <i class="fas fa-bolt text-xs mr-1.5"></i>
                                    In Progress
                                </span>
                            </div>

                            <h4 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $task->title }}</h4>

                            @if($task->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($task->description, 100) }}</p>
                            @endif
                        </div>

                        <div class="flex flex-col space-y-1">
                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition duration-200"
                                        title="Mark Complete">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>

                            <a href="{{ route('tasks.edit', $task->id) }}"
                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Progress</span>
                            <span>65%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>

                    @if($task->due_date)
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-600">
                                Due {{ $task->due_date->format('M d') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <button onclick="openQuickCreate('progress')"
                        class="w-full py-3 text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Add a task
                </button>
            </div>
        </div>
        @endif

        @if(!request('filter') || request('filter') == 'completed')
        {{-- Completed Column --}}
        <div class="board-column rounded-2xl p-5 flex-shrink-0">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <h3 class="font-bold text-gray-800">Completed</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $completedCount }}
                    </span>
                </div>
                <div class="flex items-center space-x-1">
                    <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-plus text-sm"></i>
                    </button>
                    <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-ellipsis-h text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-4 min-h-[400px]" id="completed-column">
                @foreach($completedTasks as $task)
                <div class="task-card rounded-xl p-4 shadow-sm border border-gray-200 fade-in bg-gradient-to-r from-green-50 to-white"
                     draggable="true"
                     data-task-id="{{ $task->id }}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            {{-- Status Badge --}}
                            <div class="flex items-center mb-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle text-xs mr-1.5"></i>
                                    Completed
                                </span>
                            </div>

                            <h4 class="font-semibold text-gray-500 line-through mb-2 line-clamp-2">{{ $task->title }}</h4>

                            @if($task->description)
                            <p class="text-sm text-gray-400 mb-3 line-clamp-2">{{ Str::limit($task->description, 100) }}</p>
                            @endif

                            <div class="flex items-center text-sm text-gray-400">
                                <i class="far fa-calendar-check mr-1.5"></i>
                                <span>Completed {{ $task->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-1">
                            <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition duration-200"
                                        title="Reopen">
                                    <i class="fas fa-rotate-left"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center text-xs text-gray-400">
                                <i class="far fa-clock mr-1"></i>
                                <span>Created {{ $task->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                <a href="{{ route('tasks.index', ['filter' => 'completed']) }}"
                   class="block w-full py-3 text-center text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 transition duration-200">
                    <i class="fas fa-list mr-2"></i>View all completed
                </a>
            </div>
        </div>
        @endif

        {{-- Add New Column --}}
        @if(!request('filter'))
        <div class="board-column rounded-2xl p-5 flex-shrink-0 border-2 border-dashed border-gray-300 flex items-center justify-center">
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-plus text-xl text-gray-400"></i>
                </div>
                <h3 class="font-semibold text-gray-600 mb-2">Add New Column</h3>
                <p class="text-sm text-gray-500 mb-4">Create a custom workflow stage</p>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                    Create Column
                </button>
            </div>
        </div>
        @endif
    </div>

    {{-- Mobile Filter Tabs --}}
    <div class="flex md:hidden space-x-2 mt-6 overflow-x-auto pb-2">
        <a href="{{ route('tasks.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap
                  {{ !request('filter') ? 'bg-white text-indigo-600' : 'glass-effect text-white' }}">
            All
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'pending']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap
                  {{ request('filter') == 'pending' ? 'bg-white text-yellow-600' : 'glass-effect text-white' }}">
            Pending
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'progress']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap
                  {{ request('filter') == 'progress' ? 'bg-white text-blue-600' : 'glass-effect text-white' }}">
            In Progress
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap
                  {{ request('filter') == 'completed' ? 'bg-white text-green-600' : 'glass-effect text-white' }}">
            Completed
        </a>
    </div>
    @endif
</div>

{{-- Quick Create Modal --}}
<div id="quickCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 slide-in">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Quick Create</h3>
            <button onclick="closeQuickCreate()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="quickCreateForm" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <input type="hidden" name="status" id="quickCreateStatus">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Task Title *</label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeQuickCreate()"
                        class="px-4 py-2.5 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    // Initialize drag and drop
    document.addEventListener('DOMContentLoaded', function() {
        const columns = ['pending-column', 'progress-column', 'completed-column'];

        columns.forEach(columnId => {
            const column = document.getElementById(columnId);
            if (column) {
                new Sortable(column, {
                    group: 'shared',
                    animation: 150,
                    ghostClass: 'blur-backdrop',
                    onEnd: function(evt) {
                        const taskId = evt.item.dataset.taskId;
                        const newColumn = evt.to.id;
                        let newStatus = 'pending';

                        if (newColumn.includes('progress')) newStatus = 'progress';
                        if (newColumn.includes('completed')) newStatus = 'completed';

                        updateTaskStatus(taskId, newStatus);
                    }
                });
            }
        });
    });

    function openQuickCreate(status) {
        document.getElementById('quickCreateStatus').value = status;
        document.getElementById('quickCreateModal').classList.remove('hidden');
    }

    function closeQuickCreate() {
        document.getElementById('quickCreateModal').classList.add('hidden');
    }

    function updateTaskStatus(taskId, status) {
        const completed = status === 'completed';

        fetch(`/tasks/${taskId}/toggle`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                completed: completed,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Task moved successfully!', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to update task', 'error');
        });
    }

    // Counter animation
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-count');
        let count = 0;
        const increment = target / 50;

        const updateCounter = () => {
            if (count < target) {
                count += increment;
                counter.innerText = Math.ceil(count);
                setTimeout(updateCounter, 20);
            } else {
                counter.innerText = target;
            }
        };

        updateCounter();
    });
</script>
@endsection
