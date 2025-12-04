@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl mb-4">
                <i class="fas fa-edit text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Edit Task</h1>
            <p class="text-white/80">Update task details</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            {{-- Form Progress --}}
            <div class="px-8 pt-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-semibold">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Editing Task</p>
                            <p class="font-semibold">{{ Str::limit($task->title, 30) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-8 px-8 pb-8" id="taskForm">
                @csrf
                @method('PUT')

                {{-- Task Title --}}
                <div class="space-y-3">
                    <label class="block text-lg font-semibold text-gray-800">
                        <i class="fas fa-heading text-blue-600 mr-2"></i>Task Title *
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $task->title) }}"
                           required
                           class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 text-lg"
                           placeholder="Enter task title...">
                    @error('title')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="space-y-3">
                    <label class="block text-lg font-semibold text-gray-800">
                        <i class="fas fa-align-left text-blue-600 mr-2"></i>Description
                    </label>
                    <div class="relative">
                        <textarea name="description"
                                  rows="5"
                                  class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 resize-none"
                                  placeholder="Describe the task in detail...">{{ old('description', $task->description) }}</textarea>
                        <div class="absolute bottom-3 right-3 flex items-center space-x-2">
                            <span id="charCount" class="text-sm text-gray-500">{{ strlen(old('description', $task->description)) }}/1000</span>
                            <i class="fas fa-text-height text-gray-400"></i>
                        </div>
                    </div>
                    @error('description')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date & Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Due Date --}}
                    <div class="space-y-3">
                        <label class="block text-lg font-semibold text-gray-800">
                            <i class="far fa-calendar-alt text-blue-600 mr-2"></i>Due Date
                        </label>
                        <div class="relative">
                            <input type="date"
                                   name="due_date"
                                   value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 appearance-none">
                            <div class="absolute right-4 top-4 text-gray-400 pointer-events-none">
                                <i class="far fa-calendar text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Set a deadline for this task</p>
                        @error('due_date')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div class="space-y-3">
                        <label class="block text-lg font-semibold text-gray-800">
                            <i class="fas fa-flag text-blue-600 mr-2"></i>Priority Level
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="priority-option {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}" data-value="low">
                                <input type="radio" name="priority" value="low" class="hidden" {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
                                <div class="text-center p-4 rounded-xl border-2 transition duration-200">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-flag text-green-600"></i>
                                    </div>
                                    <span class="font-medium">Low</span>
                                </div>
                            </label>

                            <label class="priority-option {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}" data-value="medium">
                                <input type="radio" name="priority" value="medium" class="hidden" {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
                                <div class="text-center p-4 rounded-xl border-2 transition duration-200">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-flag text-yellow-600"></i>
                                    </div>
                                    <span class="font-medium">Medium</span>
                                </div>
                            </label>

                            <label class="priority-option {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}" data-value="high">
                                <input type="radio" name="priority" value="high" class="hidden" {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
                                <div class="text-center p-4 rounded-xl border-2 transition duration-200">
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-flag text-red-600"></i>
                                    </div>
                                    <span class="font-medium">High</span>
                                </div>
                            </label>
                        </div>
                        @error('priority')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 {{ $task->completed ? 'bg-green-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center">
                            <i class="fas {{ $task->completed ? 'fa-check-circle text-green-600' : 'fa-clock text-blue-600' }}"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Task Status</p>
                            <p class="text-sm text-gray-500">Mark task as completed</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                               name="completed"
                               value="1"
                               {{ old('completed', $task->completed) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    </label>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 pt-8 border-t border-gray-200">
                    <a href="{{ route('tasks.index') }}"
                       class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>

                    <div class="flex space-x-4">
                        <button type="reset"
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition duration-200 font-semibold">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:shadow-xl transition-all duration-200 font-semibold group">
                            <i class="fas fa-save mr-2 group-hover:rotate-12 transition-transform duration-200"></i>
                            Update Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .priority-option div {
        border-color: #e5e7eb;
        background: white;
    }

    .priority-option.selected div {
        border-color: #4f46e5;
        background: #f5f3ff;
    }

    .priority-option:hover div {
        border-color: #c7d2fe;
        background: #f8fafc;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter
    const description = document.querySelector('textarea[name="description"]');
    const charCount = document.getElementById('charCount');

    if (description && charCount) {
        description.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/1000`;
            charCount.className = `text-sm ${length > 1000 ? 'text-red-500 font-medium' : 'text-gray-500'}`;
        });
    }

    // Priority selector
    const priorityOptions = document.querySelectorAll('.priority-option');
    priorityOptions.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.dataset.value;

            // Update radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Update visual selection
            priorityOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
});
</script>
@endsection
