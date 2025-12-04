@extends('layouts.app')

@section('content')
<div class="header-section">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h1 class="header-title">Task Board</h1>
            <p class="header-subtitle">Organize your tasks with drag and drop</p>
        </div>
        <div class="select-all-container">
            <input type="checkbox" id="selectAllTasks" style="width: 18px; height: 18px;">
            <label for="selectAllTasks" class="select-all-label">Select All</label>
        </div>
    </div>

    <div class="filter-section">
        <span class="filter-label">Filter by Columns</span>
        <div class="filter-checkboxes">
            <div class="filter-checkbox">
                <input type="checkbox" id="filterTodo" checked class="column-filter" data-column="todo">
                <label for="filterTodo">To Do</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filterProgress" checked class="column-filter" data-column="progress">
                <label for="filterProgress">In Progress</label>
            </div>
            <div class="filter-checkbox">
                <input type="checkbox" id="filterDone" checked class="column-filter" data-column="done">
                <label for="filterDone">Done</label>
            </div>
        </div>
    </div>
</div>

<hr>

<div class="row g-4" id="taskColumns">
    <!-- To Do Column -->
    <div class="col-md-4 column-container column-todo">
        <div class="column-header todo-header">
            <h5 class="column-title">To Do</h5>
            <span class="task-count">{{ $todoCount }}</span>
        </div>
        <div class="task-column">
            <div class="tasks-container" id="todoTasks" data-status="To Do">
                @foreach($tasks->where('status', 'To Do') as $task)
                    @include('tasks.partials.task-card', ['task' => $task])
                @endforeach

                @if($tasks->where('status', 'To Do')->isEmpty())
                    <div class="empty-column">
                        <i class="fas fa-clipboard-list"></i>
                        <p>No tasks in To Do</p>
                    </div>
                @endif
            </div>
            <button class="add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-status="To Do">
                <i class="fas fa-plus"></i> Add Task
            </button>
        </div>
    </div>

    <!-- In Progress Column -->
    <div class="col-md-4 column-container column-progress">
        <div class="column-header progress-header">
            <h5 class="column-title">In Progress</h5>
            <span class="task-count">{{ $inProgressCount }}</span>
        </div>
        <div class="task-column">
            <div class="tasks-container" id="inProgressTasks" data-status="In Progress">
                @foreach($tasks->where('status', 'In Progress') as $task)
                    @include('tasks.partials.task-card', ['task' => $task])
                @endforeach

                @if($tasks->where('status', 'In Progress')->isEmpty())
                    <div class="empty-column">
                        <i class="fas fa-spinner"></i>
                        <p>No tasks in Progress</p>
                    </div>
                @endif
            </div>
            <button class="add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-status="In Progress">
                <i class="fas fa-plus"></i> Add Task
            </button>
        </div>
    </div>

    <!-- Done Column -->
    <div class="col-md-4 column-container column-done">
        <div class="column-header done-header">
            <h5 class="column-title">Done</h5>
            <span class="task-count">{{ $doneCount }}</span>
        </div>
        <div class="task-column">
            <div class="tasks-container" id="doneTasks" data-status="Done">
                @foreach($tasks->where('status', 'Done') as $task)
                    @include('tasks.partials.task-card', ['task' => $task])
                @endforeach

                @if($tasks->where('status', 'Done')->isEmpty())
                    <div class="empty-column">
                        <i class="fas fa-check-circle"></i>
                        <p>No completed tasks</p>
                    </div>
                @endif
            </div>
            <button class="add-task-btn" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-status="Done">
                <i class="fas fa-plus"></i> Add Task
            </button>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="status" id="modalStatus" value="To Do">

                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title *</label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="e.g., Design homepage mockup">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Create a modern homepage design with hero section and key..."></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="Design">Design</option>
                                <option value="Development">Development</option>
                                <option value="Research">Research</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="High">High Priority</option>
                                <option value="Medium">Medium Priority</option>
                                <option value="Low">Low Priority</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                        </div>
                        <div class="col-md-6">
                            <label for="subtasks_total" class="form-label">Total Subtasks</label>
                            <input type="number" class="form-control" id="subtasks_total" name="subtasks_total" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Set CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Set modal status when Add Task button is clicked
    $('.add-task-btn').click(function() {
        var status = $(this).data('status');
        $('#modalStatus').val(status);
    });

    // SIMPLE FILTER FUNCTION - FIXED VERSION
    $('.column-filter').change(function() {
        var column = $(this).data('column');
        var isChecked = $(this).is(':checked');

        console.log('Filter changed:', column, isChecked);

        // Hide/show the column
        if (column === 'todo') {
            if (isChecked) {
                $('.column-todo').show();
            } else {
                $('.column-todo').hide();
            }
        }
        else if (column === 'progress') {
            if (isChecked) {
                $('.column-progress').show();
            } else {
                $('.column-progress').hide();
            }
        }
        else if (column === 'done') {
            if (isChecked) {
                $('.column-done').show();
            } else {
                $('.column-done').hide();
            }
        }

        // Adjust grid layout
        adjustGridLayout();

        // Save filter state
        saveFilterState();
    });

    // Adjust grid layout based on visible columns
    function adjustGridLayout() {
        var visibleColumns = $('.column-container:visible').length;
        console.log('Visible columns:', visibleColumns);

        // Remove all column classes first
        $('.column-container').removeClass('col-md-4 col-md-6 col-md-12');

        // Apply appropriate column width
        if (visibleColumns === 3) {
            $('.column-container:visible').addClass('col-md-4');
        } else if (visibleColumns === 2) {
            $('.column-container:visible').addClass('col-md-6');
        } else if (visibleColumns === 1) {
            $('.column-container:visible').addClass('col-md-12');
        }
    }

    // Select All Tasks functionality
    $('#selectAllTasks').change(function() {
        var isChecked = $(this).is(':checked');

        // Select/Deselect all task checkboxes
        $('.task-complete-checkbox').prop('checked', isChecked);

        // Update visual selection
        if (isChecked) {
            $('.task-card').addClass('selected');
        } else {
            $('.task-card').removeClass('selected');
        }

        updateSelectAllState();
    });

    // Individual task checkbox
    $(document).on('change', '.task-complete-checkbox', function() {
        var isChecked = $(this).is(':checked');

        if (isChecked) {
            $(this).closest('.task-card').addClass('selected');
        } else {
            $(this).closest('.task-card').removeClass('selected');
        }

        updateSelectAllState();
    });

    // Update Select All checkbox state
    function updateSelectAllState() {
        var totalTasks = $('.task-complete-checkbox').length;
        var checkedTasks = $('.task-complete-checkbox:checked').length;

        if (checkedTasks === 0) {
            $('#selectAllTasks').prop('checked', false);
            $('#selectAllTasks').prop('indeterminate', false);
        } else if (checkedTasks === totalTasks) {
            $('#selectAllTasks').prop('checked', true);
            $('#selectAllTasks').prop('indeterminate', false);
        } else {
            $('#selectAllTasks').prop('checked', false);
            $('#selectAllTasks').prop('indeterminate', true);
        }
    }

    // Save filter state to localStorage
    function saveFilterState() {
        var filterState = {
            todo: $('#filterTodo').is(':checked'),
            progress: $('#filterProgress').is(':checked'),
            done: $('#filterDone').is(':checked')
        };
        localStorage.setItem('taskBoardFilter', JSON.stringify(filterState));
    }

    // Load filter state from localStorage
    function loadFilterState() {
        var savedState = localStorage.getItem('taskBoardFilter');
        if (savedState) {
            try {
                var filterState = JSON.parse(savedState);

                // Set checkbox states
                $('#filterTodo').prop('checked', filterState.todo !== false);
                $('#filterProgress').prop('checked', filterState.progress !== false);
                $('#filterDone').prop('checked', filterState.done !== false);

                // Apply filter immediately
                $('.column-filter').each(function() {
                    var column = $(this).data('column');
                    var isChecked = $(this).is(':checked');

                    if (column === 'todo') {
                        $('.column-todo').toggle(isChecked);
                    } else if (column === 'progress') {
                        $('.column-progress').toggle(isChecked);
                    } else if (column === 'done') {
                        $('.column-done').toggle(isChecked);
                    }
                });

                adjustGridLayout();
            } catch (e) {
                console.error('Error loading filter state:', e);
            }
        }
    }

    // Initialize
    loadFilterState();
    updateSelectAllState();

    // Drag and Drop functionality
    $('.task-card').attr('draggable', 'true');

    $('.task-card').on('dragstart', function(e) {
        e.originalEvent.dataTransfer.setData('text/plain', $(this).data('task-id'));
        $(this).addClass('dragging');
    });

    $('.task-card').on('dragend', function() {
        $(this).removeClass('dragging');
    });

    $('.tasks-container').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    });

    $('.tasks-container').on('dragleave', function() {
        $(this).removeClass('drag-over');
    });

    $('.tasks-container').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');

        var taskId = e.originalEvent.dataTransfer.getData('text/plain');
        var newStatus = $(this).data('status');

        $.ajax({
            url: '/tasks/' + taskId + '/update-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    var taskCard = $('[data-task-id="' + taskId + '"]');
                    $(this).append(taskCard);

                    $('.todo-header .task-count').text(response.todoCount);
                    $('.progress-header .task-count').text(response.inProgressCount);
                    $('.done-header .task-count').text(response.doneCount);

                    $(this).find('.empty-column').remove();

                    showNotification('Task moved successfully!', 'success');
                }
            }.bind(this),
            error: function(xhr) {
                showNotification('Error moving task!', 'error');
                console.error('Error:', xhr.responseText);
            }
        });
    });

    // Notification function
    function showNotification(message, type) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

        var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="top:20px; right:20px; z-index:9999;">' +
                            '<i class="fas ' + icon + ' me-2"></i>' + message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                            '</div>');

        $('body').append(notification);

        setTimeout(function() {
            notification.alert('close');
        }, 3000);
    }
});
</script>
@endpush
