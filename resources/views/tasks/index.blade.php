@extends('layouts.app')

@section('title', 'To-Do List Application')

@section('content')
    <!-- Filter Section -->
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                @include('partials.filters')
            </div>
        </div>
    </div>

    <!-- Kanban Board Container -->
    <div class="container-fluid">
        <div class="kanban-board-container" id="kanbanBoardContainer">
            @include('partials.column')
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            @include('partials.task.add-task-modal', ['categories' => $categories, 'columns' => $columns])
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            @include('partials.task.edit-task-modal', ['categories' => $categories, 'columns' => $columns])
        </div>
    </div>

    <!-- Task Details Modal -->
    <div class="modal fade" id="taskDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            @include('partials.task.task-details-modal')
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            @include('partials.category.add-category-modal')
        </div>
    </div>
@endsection
