@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <div class="flex items-center space-x-2 text-sm text-white mb-6">
            <a href="{{ route('tasks.index') }}" class="hover:opacity-80">
                <i class="fas fa-tasks"></i> Board
            </a>
            <i class="fas fa-chevron-right text-xs opacity-60"></i>
            <span class="opacity-90">{{ Str::limit($task->title, 30) }}</span>
        </div>

        {{-- Task Detail Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="p-8 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            @if($task->completed)
                            <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                                <i class="fas fa-check-circle mr-2"></i>Completed
                            </span>
                            @else
                            <span class="inline-flex items-center px-4 py-2
                                {{ $task->priority == 'high' ? 'bg-red-100 text-red-800' :
                                   ($task->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-green-100 text-green-800') }} rounded-full font-semibold">
                                <i class="fas fa-flag mr-2"></i>{{ ucfirst($task->priority) }} Priority
                            </span>
                            @endif

                            @if($task->due_date && $task->due_date->isPast() && !$task->completed)
                            <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Overdue
                            </span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $task->title }}</h1>

                        <div class="flex items-center space-x-4 text-gray-500">
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <span>Created {{ $task->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($task->due_date)
                            <div class="flex items-center">
                                <i class="far fa-clock mr-2"></i>
                                <span class="{{ $task->due_date->isPast() && !$task->completed ? 'text-red-600 font-semibold' : '' }}">
                                    Due {{ $task->due_date->format('M d, Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex space-x-2">
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="p-3 bg-blue-100 text-blue-600 rounded-xl hover:bg-blue-200 transition duration-200"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="p-3 {{ $task->completed ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} rounded-xl transition duration-200"
                                    title="{{ $task->completed ? 'Mark as Pending' : 'Mark as Complete' }}">
                                <i class="fas {{ $task->completed ? 'fa-rotate-left' : 'fa-check' }}"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="p-8">
                @if($task->description)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-align-left text-indigo-600 mr-2"></i>Description
                    </h3>
                    <div class="prose max-w-none">
                        <div class="bg-gray-50 rounded-xl p-6">
                            {!! nl2br(e($task->description)) !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Task Metadata --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>Task Information
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status</span>
                                <span class="font-medium {{ $task->completed ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $task->completed ? 'Completed' : 'Pending' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Priority</span>
                                <span class="font-medium {{ $task->priority == 'high' ? 'text-red-600' : ($task->priority == 'medium' ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created</span>
                                <span class="font-medium">{{ $task->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="font-medium">{{ $task->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-calculator text-indigo-600 mr-2"></i>Time Information
                        </h4>
                        <div class="space-y-3">
                            @if($task->due_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Due Date</span>
                                <span class="font-medium {{ $task->due_date->isPast() && !$task->completed ? 'text-red-600' : '' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Days Remaining</span>
                                <span class="font-medium {{ $task->due_date->isPast() && !$task->completed ? 'text-red-600' : '' }}">
                                    {{ $task->due_date->diffForHumans() }}
                                </span>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-infinity text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600">No due date set</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Footer --}}
            <div class="p-8 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <a href="{{ route('tasks.index') }}"
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition duration-200 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Board
                    </a>

                    <div class="flex space-x-3">
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition duration-200 font-semibold">
                            <i class="fas fa-edit mr-2"></i>Edit Task
                        </a>

                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition duration-200 font-semibold">
                                <i class="fas fa-trash mr-2"></i>Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
