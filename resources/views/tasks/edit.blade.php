@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- Form Header --}}
        <div class="gradient-bg text-white p-6">
            <div class="flex items-center">
                <div class="p-3 bg-white/20 rounded-lg mr-4">
                    <i class="fas fa-edit text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Edit Task</h1>
                    <p class="opacity-90">Update task details</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="p-6">
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-2"></i>Task Title *
                    </label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $task->title) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                           placeholder="What needs to be done?">
                    @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2"></i>Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                              placeholder="Add more details about this task...">{{ old('description', $task->description) }}</textarea>
                    <div class="flex justify-between mt-1">
                        <span class="text-xs text-gray-500">Optional but recommended</span>
                        <span id="charCount" class="text-xs text-gray-500">{{ strlen(old('description', $task->description)) }}/500</span>
                    </div>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Due Date --}}
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="far fa-calendar-alt mr-2"></i>Due Date
                        </label>
                        <input type="date"
                               name="due_date"
                               id="due_date"
                               value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                        @error('due_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-flag mr-2"></i>Priority
                        </label>
                        <select name="priority"
                                id="priority"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Completed --}}
                <div class="flex items-center">
                    <input type="checkbox"
                           name="completed"
                           id="completed"
                           value="1"
                           {{ old('completed', $task->completed) ? 'checked' : '' }}
                           class="h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                    <label for="completed" class="ml-3 text-sm font-medium text-gray-700">
                        Mark as completed
                    </label>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div>
                        <a href="{{ route('tasks.index') }}"
                           class="text-gray-600 hover:text-gray-800 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
                        </a>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('tasks.index') }}"
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:shadow-lg transition-all duration-300 font-medium transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i>Update Task
                        </button>
                    </div>
                </div>
            </form>

            {{-- Danger Zone --}}
            <div class="mt-8 pt-6 border-t border-red-200">
                <div class="bg-red-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                    </h3>
                    <p class="text-red-600 mb-4">Once you delete a task, there is no going back. Please be certain.</p>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                          onsubmit="return confirm('Are you absolutely sure you want to delete this task? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition duration-200 font-medium">
                            <i class="fas fa-trash mr-2"></i>Delete This Task
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter untuk description
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');

    description.addEventListener('input', function() {
        charCount.textContent = `${this.value.length}/500`;
        if (this.value.length > 500) {
            charCount.classList.add('text-red-600');
        } else {
            charCount.classList.remove('text-red-600');
        }
    });
});
</script>
@endsection
