@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl mb-4">
                <i class="fas fa-plus text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Create New Task</h1>
            <p class="text-white/80">Add a new task to your workflow</p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            {{-- Form Progress --}}
            <div class="px-8 pt-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center justify-center w-10 h-10 bg-indigo-600 text-white rounded-full font-semibold">
                            1
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Step 1</p>
                            <p class="font-semibold">Basic Info</p>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-1 bg-indigo-600 rounded-full"></div>
                            <div class="w-8 h-1 bg-gray-300 rounded-full"></div>
                            <div class="w-8 h-1 bg-gray-300 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('tasks.store') }}" method="POST" class="space-y-8 px-8 pb-8" id="taskForm">
                @csrf

                {{-- Task Title --}}
                <div class="space-y-3">
                    <label class="block text-lg font-semibold text-gray-800">
                        <i class="fas fa-heading text-indigo-600 mr-2"></i>What needs to be done? *
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 text-lg"
                           placeholder="Enter task title...">
                    @error('title')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="space-y-3">
                    <label class="block text-lg font-semibold text-gray-800">
                        <i class="fas fa-align-left text-indigo-600 mr-2"></i>Description
                    </label>
                    <div class="relative">
                        <textarea name="description"
                                  rows="5"
                                  class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 resize-none"
                                  placeholder="Describe the task in detail...">{{ old('description') }}</textarea>
                        <div class="absolute bottom-3 right-3 flex items-center space-x-2">
                            <span id="charCount" class="text-sm text-gray-500">0/1000</span>
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
                            <i class="far fa-calendar-alt text-indigo-600 mr-2"></i>Due Date
                        </label>
                        <div class="relative">
                            <input type="date"
                                   name="due_date"
                                   value="{{ old('due_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 appearance-none">
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
                            <i class="fas fa-flag text-indigo-600 mr-2"></i>Priority Level
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="priority-option {{ old('priority') == 'low' || old('priority') == '' ? 'selected' : '' }}" data-value="low">
                                <input type="radio" name="priority" value="low" class="hidden" {{ old('priority') == 'low' ? 'checked' : '' }}>
                                <div class="text-center p-4 rounded-xl border-2 transition duration-200">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-flag text-green-600"></i>
                                    </div>
                                    <span class="font-medium">Low</span>
                                </div>
                            </label>

                            <label class="priority-option {{ old('priority') == 'medium' ? 'selected' : '' }}" data-value="medium">
                                <input type="radio" name="priority" value="medium" class="hidden" {{ old('priority') == 'medium' || old('priority') == '' ? 'checked' : '' }}>
                                <div class="text-center p-4 rounded-xl border-2 transition duration-200">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <i class="fas fa-flag text-yellow-600"></i>
                                    </div>
                                    <span class="font-medium">Medium</span>
                                </div>
                            </label>

                            <label class="priority-option {{ old('priority') == 'high' ? 'selected' : '' }}" data-value="high">
                                <input type="radio" name="priority" value="high" class="hidden" {{ old('priority') == 'high' ? 'checked' : '' }}>
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

                {{-- Additional Options --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Mark as completed</p>
                                <p class="text-sm text-gray-500">Check this if the task is already done</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="completed"
                                   value="1"
                                   {{ old('completed') ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 pt-8 border-t border-gray-200">
                    <a href="{{ route('tasks.index') }}"
                       class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Board</span>
                    </a>

                    <div class="flex space-x-4">
                        <button type="reset"
                                class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition duration-200 font-semibold">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </button>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:shadow-xl transition-all duration-200 font-semibold group">
                            <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-200"></i>
                            Create Task
                        </button>
                    </div>
                </div>
            </form>

            {{-- Tips Section --}}
            <div class="px-8 pb-8">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-indigo-100 rounded-xl">
                            <i class="fas fa-lightbulb text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">Best Practices</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Use specific, actionable titles
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Set realistic due dates
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Assign appropriate priority levels
                                </li>
                                <li class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    Add detailed descriptions for clarity
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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

    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        cursor: pointer;
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
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

            // Add animation
            this.querySelector('div').classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                this.querySelector('div').classList.remove('animate__animated', 'animate__pulse');
            }, 300);
        });
    });

    // Set default date to tomorrow
    const dueDateInput = document.querySelector('input[name="due_date"]');
    if (dueDateInput && !dueDateInput.value) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dueDateInput.valueAsDate = tomorrow;
    }

    // Form validation
    const form = document.getElementById('taskForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = this.querySelector('input[name="title"]');
            if (title && !title.value.trim()) {
                e.preventDefault();
                title.focus();
                title.classList.add('border-red-500', 'ring-2', 'ring-red-200');

                // Show error message
                if (!title.nextElementSibling || !title.nextElementSibling.classList.contains('text-red-600')) {
                    const error = document.createElement('p');
                    error.className = 'text-red-600 text-sm mt-2';
                    error.textContent = 'Task title is required';
                    title.parentNode.appendChild(error);
                }

                // Scroll to error
                title.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        // Clear validation on input
        form.querySelector('input[name="title"]').addEventListener('input', function() {
            this.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            const error = this.parentNode.querySelector('.text-red-600');
            if (error) error.remove();
        });
    }

    // Add floating animation to form card
    const formCard = document.querySelector('.bg-white');
    if (formCard) {
        formCard.classList.add('animate__animated', 'animate__fadeInUp');
    }
});
</script>
@endsection
