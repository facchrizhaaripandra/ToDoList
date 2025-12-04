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

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Title -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <div class="form-control-plaintext border-bottom pb-2" id="detailTaskTitleText">Loading...</div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <div class="form-control-plaintext border-bottom pb-2" id="detailTaskDescription">Loading...</div>
                </div>

                <!-- Due Date -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="far fa-calendar me-1"></i> Due Date
                    </label>
                    <div class="form-control-plaintext border-bottom pb-2" id="detailTaskDueDate">Loading...</div>
                </div>

                <!-- Labels -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-tags me-1"></i> Labels
                    </label>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info" id="detailTaskCategoryBadge">Loading...</span>
                        <span class="badge bg-danger" id="detailTaskPriorityBadge">Loading...</span>
                    </div>
                </div>

                <!-- Checklist -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-tasks me-1"></i> Checklist
                    </label>
                    <div id="checklistContainer" class="border rounded p-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checklistItem1">
                            <label class="form-check-label" for="checklistItem1">Create git repo</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checklistItem2" checked>
                            <label class="form-check-label" for="checklistItem2">Set up CI/CD pipeline</label>
                        </div>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="far fa-clock me-1"></i> Created
                    </label>
                    <div class="form-control-plaintext border-bottom pb-2" id="detailTaskCreatedAt">Loading...</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" onclick="enableEditMode()">
                    <i class="fas fa-edit me-1"></i> Edit
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteCurrentTask()">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTaskForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="editTaskId">

                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="editTitle" required>
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editCategory" class="form-label">Category</label>
                            <select class="form-select" id="editCategory">
                                <option value="Design">Design</option>
                                <option value="Development">Development</option>
                                <option value="Research">Research</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editPriority" class="form-label">Priority</label>
                            <select class="form-select" id="editPriority">
                                <option value="High">High Priority</option>
                                <option value="Medium">Medium Priority</option>
                                <option value="Low">Low Priority</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="editDueDate">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variable to store current task ID
var currentTaskId = null;

// Function to show task detail modal
function showTaskDetail(taskId) {
    console.log('Showing task detail for ID:', taskId);
    currentTaskId = taskId;

    // Show loading state
    $('#detailTaskTitleText').text('Loading...');
    $('#detailTaskDescription').text('Loading...');

    // Fetch task data via AJAX
    $.ajax({
        url: '/tasks/' + taskId,
        method: 'GET',
        success: function(task) {
            console.log('Task data loaded:', task);

            // Populate modal with task data
            $('#detailTaskTitleText').text(task.title || 'No title');
            $('#detailTaskDescription').text(task.description || 'No description');

            // Format due date
            if (task.due_date) {
                try {
                    var dueDate = new Date(task.due_date);
                    $('#detailTaskDueDate').text(formatDate(dueDate));
                } catch (e) {
                    $('#detailTaskDueDate').text(task.due_date);
                }
            } else {
                $('#detailTaskDueDate').text('No due date');
            }

            // Set category and priority badges
            if (task.category) {
                $('#detailTaskCategoryBadge')
                    .text(task.category)
                    .removeClass('bg-info bg-warning bg-success')
                    .addClass(getCategoryColor(task.category))
                    .show();
            } else {
                $('#detailTaskCategoryBadge').hide();
            }

            if (task.priority) {
                $('#detailTaskPriorityBadge')
                    .text(task.priority + ' Priority')
                    .removeClass('bg-danger bg-warning bg-success')
                    .addClass(getPriorityColor(task.priority))
                    .show();
            } else {
                $('#detailTaskPriorityBadge').hide();
            }

            // Format created date
            if (task.created_at) {
                try {
                    var createdDate = new Date(task.created_at);
                    $('#detailTaskCreatedAt').text(formatDateTime(createdDate));
                } catch (e) {
                    $('#detailTaskCreatedAt').text(task.created_at);
                }
            }

            // Populate edit modal
            $('#editTaskId').val(task.id);
            $('#editTitle').val(task.title || '');
            $('#editDescription').val(task.description || '');
            $('#editCategory').val(task.category || 'Design');
            $('#editPriority').val(task.priority || 'Medium');
            $('#editDueDate').val(task.due_date ? task.due_date.split('T')[0] : '');

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            modal.show();

        },
        error: function(xhr, status, error) {
            console.error('Error fetching task:', error);
            console.log('Response:', xhr.responseText);

            // Show error in modal
            $('#detailTaskTitleText').text('Error loading task');
            $('#detailTaskDescription').text('Could not load task details. Please try again.');

            // Still show the modal so user knows something happened
            var modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            modal.show();
        }
    });
}

// Function to enable edit mode (opens edit modal)
function enableEditMode() {
    // Close the detail modal
    $('#taskDetailModal').modal('hide');

    // Open the edit modal after a short delay
    setTimeout(function() {
        var editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        editModal.show();
    }, 300);
}

// Function to delete current task
function deleteCurrentTask() {
    if (!currentTaskId) return;

    if (confirm('Are you sure you want to delete this task?')) {
        $.ajax({
            url: '/tasks/' + currentTaskId,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Task deleted successfully!', 'success');

                    // Remove task card from DOM
                    $('[data-task-id="' + currentTaskId + '"]').remove();

                    // Close modal
                    $('#taskDetailModal').modal('hide');

                    // Update counts
                    updateColumnCounts();
                }
            },
            error: function(xhr) {
                console.error('Error deleting task:', xhr.responseText);
                showNotification('Error deleting task!', 'error');
            }
        });
    }
}

// Handle edit form submission
$('#editTaskForm').on('submit', function(e) {
    e.preventDefault();

    var taskId = $('#editTaskId').val();
    var formData = {
        title: $('#editTitle').val(),
        description: $('#editDescription').val(),
        category: $('#editCategory').val(),
        priority: $('#editPriority').val(),
        due_date: $('#editDueDate').val(),
        _method: 'PUT',
        _token: '{{ csrf_token() }}'
    };

    $.ajax({
        url: '/tasks/' + taskId,
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showNotification('Task updated successfully!', 'success');
                $('#editTaskModal').modal('hide');

                // Reload the page to see changes
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr) {
            console.error('Error updating task:', xhr.responseText);
            showNotification('Error updating task!', 'error');
        }
    });
});

// Helper function to format date
function formatDate(date) {
    return (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
           date.getDate().toString().padStart(2, '0') + '/' +
           date.getFullYear();
}

// Helper function to format date with time
function formatDateTime(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12;

    return formatDate(date) + ', ' +
           hours.toString().padStart(2, '0') + ':' +
           minutes.toString().padStart(2, '0') + ':' +
           seconds.toString().padStart(2, '0') + ' ' + ampm;
}

// Helper function to get category color
function getCategoryColor(category) {
    switch(category.toLowerCase()) {
        case 'design': return 'bg-info';
        case 'development': return 'bg-warning';
        case 'research': return 'bg-success';
        default: return 'bg-secondary';
    }
}

// Helper function to get priority color
function getPriorityColor(priority) {
    switch(priority.toLowerCase()) {
        case 'high': return 'bg-danger';
        case 'medium': return 'bg-warning';
        case 'low': return 'bg-success';
        default: return 'bg-secondary';
    }
}

// Function to update column counts
function updateColumnCounts() {
    // You can implement this to update the counts after delete
    // For now, we'll reload the page
    location.reload();
}

// Make sure function is available globally
window.showTaskDetail = showTaskDetail;
window.enableEditMode = enableEditMode;
window.deleteCurrentTask = deleteCurrentTask;

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

    // SIMPLE FILTER FUNCTION
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
