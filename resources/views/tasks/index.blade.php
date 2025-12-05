<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List - Kanban Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* FIX Z-INDEX ISSUE FOR SELECT2 IN MODALS */
        .select2-container--open {
            z-index: 9999 !important;
        }
        .select2-dropdown {
            z-index: 99999 !important;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .kanban-board {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 20px;
            min-height: calc(100vh - 80px);
        }
        .column {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            min-width: 320px;
            max-width: 350px;
            min-height: 600px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
        }
        .column-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .task-card {
            background: white;
            border-left: 5px solid #3498db;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            cursor: move;
            transition: all 0.3s ease;
            border: 1px solid #e8e8e8;
            max-width: 100%;
            overflow: hidden;
        }
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .task-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #2c3e50;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.4;
            max-width: 100%;
        }

        .task-description {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 10px;
            line-height: 1.5;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            max-height: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .task-description-expanded {
            max-height: none;
            -webkit-line-clamp: unset;
        }

        .read-more-btn {
            background: none;
            border: none;
            color: #3498db;
            padding: 0;
            font-size: 0.8rem;
            cursor: pointer;
            margin-top: 5px;
            display: inline-block;
        }

        .category-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .add-column-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .add-column-btn:hover {
            transform: scale(1.05);
        }

        .drag-handle {
            cursor: move;
            color: #7f8c8d;
        }
        .task-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .task-card:hover .task-actions {
            opacity: 1;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .color-option.selected {
            border-color: #000;
        }
        .icon-option {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            cursor: pointer;
            border-radius: 8px;
            border: 2px solid transparent;
            font-size: 18px;
        }
        .icon-option.selected {
            border-color: #3498db;
            background: #ebf5fb;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #95a5a6;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            gap: 10px;
        }
        .task-header-content {
            flex: 1;
            min-width: 0;
        }

        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .no-category {
            color: #95a5a6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tasks me-2"></i> Kanban ToDo List
            </a>
            <div class="d-flex align-items-center">
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                    <i class="fas fa-plus-circle me-1"></i> Add Task
                </button>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-tag me-1"></i> Add Category
                </button>
            </div>
        </div>
    </nav>

    <!-- Kanban Board -->
    <div class="container-fluid">
        <div class="kanban-board" id="kanbanBoard">
            @foreach($columns as $column)
            <div class="column" data-column-id="{{ $column->id }}">
                <div class="column-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 me-2 column-title" data-column-id="{{ $column->id }}">
                            {{ $column->name }}
                        </h5>
                        <span class="badge bg-light text-dark">{{ $column->tasks->count() }}</span>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-light edit-column"
                                data-column-id="{{ $column->id }}"
                                data-column-name="{{ $column->name }}"
                                title="Edit Column">
                            <i class="fas fa-edit"></i>
                        </button>
                        @if($columns->count() > 1)
                        <button class="btn btn-sm btn-light text-danger delete-column"
                                data-column-id="{{ $column->id }}"
                                title="Delete Column">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </div>

                <div class="tasks-list" id="tasks-list-{{ $column->id }}">
                    @if($column->tasks->count() > 0)
                        @foreach($column->tasks as $task)
                        <div class="task-card" draggable="true" data-task-id="{{ $task->id }}" id="task-{{ $task->id }}" data-category-id="{{ $task->category_id }}">
                            <div class="task-header">
                                <div class="task-header-content">
                                    <div class="d-flex align-items-start">
                                        <span class="drag-handle me-2 mt-1">
                                            <i class="fas fa-grip-vertical"></i>
                                        </span>
                                        <h6 class="mb-0 task-title">{{ $task->title }}</h6>
                                    </div>
                                </div>
                                <div class="task-actions">
                                    <button class="btn btn-sm btn-outline-primary edit-task"
                                            data-task-id="{{ $task->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editTaskModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-task"
                                            data-task-id="{{ $task->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            @if($task->description)
                            <div class="description-container">
                                <p class="text-muted small mb-2 task-description" id="description-{{ $task->id }}">
                                    {{ $task->description }}
                                </p>
                                @if(strlen($task->description) > 100)
                                <button class="read-more-btn" data-task-id="{{ $task->id }}">
                                    <span class="read-more-text">Read more</span>
                                    <span class="read-less-text" style="display: none;">Read less</span>
                                </button>
                                @endif
                            </div>
                            @endif

                            <div class="task-meta">
                                @if($task->category)
                                <span class="badge category-badge"
                                      style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}; border: 1px solid {{ $task->category->color }}30;"
                                      title="{{ $task->category->name }}">
                                    <i class="{{ $task->category->icon }} me-1"></i>
                                    {{ $task->category->name }}
                                </span>
                                @else
                                <span class="badge category-badge no-category"
                                      style="background-color: #95a5a620; color: #95a5a6; border: 1px solid #95a5a630;">
                                    <i class="fas fa-question me-1"></i>
                                    No Category
                                </span>
                                @endif
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $task->created_at->format('M d') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tasks text-muted"></i>
                            <p class="mb-0">No tasks yet</p>
                        </div>
                    @endif
                </div>

                <button class="btn btn-outline-primary w-100 mt-3 add-to-column"
                        data-column-id="{{ $column->id }}">
                    <i class="fas fa-plus me-1"></i> Add Task
                </button>
            </div>
            @endforeach

            <div class="column d-flex align-items-center justify-content-center">
                <button class="add-column-btn" id="addColumnBtn">
                    <i class="fas fa-plus me-2"></i> Add New Column
                </button>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addTaskForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="column_id" id="addColumnId" value="{{ $columns->first()->id ?? 1 }}">

                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" required
                                   placeholder="Enter task title" maxlength="200">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Enter task description" maxlength="1000"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Column</label>
                                <select name="column_id" class="form-select" id="selectColumnId">
                                    @foreach($columns as $column)
                                    <option value="{{ $column->id }}">{{ $column->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control select2-modal" id="categorySelect">
                                    <option value="">No Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            data-color="{{ $category->color }}"
                                            data-icon="{{ $category->icon }}">
                                        <i class="{{ $category->icon }} me-2" style="color: {{ $category->color }}"></i>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="column_id" id="editColumnId">
                        <input type="hidden" id="currentTaskId">

                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" id="editTitle" class="form-control" required maxlength="200">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="3" maxlength="1000"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Column</label>
                                <select name="column_id" class="form-select" id="editTaskColumnId">
                                    @foreach($columns as $column)
                                    <option value="{{ $column->id }}">{{ $column->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control select2-modal" id="editCategoryId">
                                    <option value="">No Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addCategoryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name *</label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="Enter category name" maxlength="50">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Color</label>
                            <div class="d-flex flex-wrap">
                                <div class="color-option selected" style="background-color: #3498db;" data-color="#3498db"></div>
                                <div class="color-option" style="background-color: #e74c3c;" data-color="#e74c3c"></div>
                                <div class="color-option" style="background-color: #2ecc71;" data-color="#2ecc71"></div>
                                <div class="color-option" style="background-color: #9b59b6;" data-color="#9b59b6"></div>
                                <div class="color-option" style="background-color: #f39c12;" data-color="#f39c12"></div>
                                <div class="color-option" style="background-color: #1abc9c;" data-color="#1abc9c"></div>
                                <div class="color-option" style="background-color: #34495e;" data-color="#34495e"></div>
                                <div class="color-option" style="background-color: #e84393;" data-color="#e84393"></div>
                                <div class="color-option" style="background-color: #00b894;" data-color="#00b894"></div>
                                <div class="color-option" style="background-color: #6c5ce7;" data-color="#6c5ce7"></div>
                            </div>
                            <input type="hidden" name="color" id="selectedColor" value="#3498db">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Icon</label>
                            <div class="d-flex flex-wrap">
                                <div class="icon-option selected" data-icon="fas fa-folder">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-user">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-briefcase">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-shopping-cart">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-heartbeat">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-home">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-car">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-graduation-cap">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-utensils">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="icon-option" data-icon="fas fa-gamepad">
                                    <i class="fas fa-gamepad"></i>
                                </div>
                            </div>
                            <input type="hidden" name="icon" id="selectedIcon" value="fas fa-folder">
                        </div>

                        <div class="preview mb-3">
                            <label class="form-label">Preview:</label>
                            <div class="d-inline-block p-3 rounded" id="categoryPreview"
                                 style="background-color: #3498db20; border: 1px solid #3498db30;">
                                <i class="fas fa-folder me-2" style="color: #3498db;"></i>
                                <span style="color: #3498db;">Your Category</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for modals
            function initializeSelect2ForModal() {
                $('.select2-modal').select2({
                    dropdownParent: $('#addTaskModal, #editTaskModal'),
                    width: '100%'
                });
            }

            $('#addTaskModal').on('shown.bs.modal', function() {
                initializeSelect2ForModal();
            });

            $('#editTaskModal').on('shown.bs.modal', function() {
                initializeSelect2ForModal();
            });

            // Read More functionality
            $(document).on('click', '.read-more-btn', function() {
                var taskId = $(this).data('task-id');
                var description = $('#description-' + taskId);
                var readMoreText = $(this).find('.read-more-text');
                var readLessText = $(this).find('.read-less-text');

                if (description.hasClass('task-description-expanded')) {
                    description.removeClass('task-description-expanded');
                    readMoreText.show();
                    readLessText.hide();
                } else {
                    description.addClass('task-description-expanded');
                    readMoreText.hide();
                    readLessText.show();
                }
            });

            // Initialize Sortable for Kanban Board
            var kanbanBoard = document.getElementById('kanbanBoard');
            Sortable.create(kanbanBoard, {
                group: 'columns',
                animation: 150,
                handle: '.column-header',
                onEnd: function(evt) {
                    var columnOrder = [];
                    $('.column').each(function() {
                        if ($(this).data('column-id')) {
                            columnOrder.push($(this).data('column-id'));
                        }
                    });

                    $.ajax({
                        url: '{{ route("columns.reorder") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order: columnOrder
                        }
                    });
                }
            });

            // Initialize Sortable for each column's task list
            $('.column').each(function() {
                var columnId = $(this).data('column-id');
                var taskList = document.getElementById('tasks-list-' + columnId);

                if (taskList) {
                    Sortable.create(taskList, {
                        group: 'tasks',
                        animation: 150,
                        handle: '.drag-handle',
                        onEnd: function(evt) {
                            var taskId = evt.item.dataset.taskId;
                            var newColumnId = $(evt.to).closest('.column').data('column-id');

                            if (!newColumnId) return;

                            $.ajax({
                                url: '/tasks/' + taskId + '/update-column',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    column_id: newColumnId
                                },
                                success: function() {
                                    console.log('Task moved successfully');
                                }
                            });
                        }
                    });
                }
            });

            // Add Task to specific column
            $('.add-to-column').click(function(e) {
                e.preventDefault();
                var columnId = $(this).data('column-id');
                $('#addColumnId').val(columnId);
                $('#selectColumnId').val(columnId);
                $('#addTaskModal').modal('show');
            });

            // Edit Task - Load data via AJAX
            $('.edit-task').click(function() {
                var taskId = $(this).data('task-id');
                $('#currentTaskId').val(taskId);

                $.ajax({
                    url: '/tasks/' + taskId,
                    method: 'GET',
                    success: function(response) {
                        console.log('Task data loaded:', response);

                        $('#editTitle').val(response.title);
                        $('#editDescription').val(response.description);
                        $('#editTaskColumnId').val(response.column_id);
                        $('#editColumnId').val(response.column_id);

                        // Set category - check if category exists
                        if (response.category_id) {
                            $('#editCategoryId').val(response.category_id).trigger('change');
                        } else {
                            $('#editCategoryId').val('').trigger('change');
                        }

                        // Set form action
                        $('#editTaskForm').attr('action', '/tasks/' + taskId);
                    },
                    error: function(xhr) {
                        console.error('Error loading task:', xhr);
                        alert('Error loading task data');
                    }
                });
            });

            // Submit Edit Task Form
            $('#editTaskForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();
                var taskId = $('#currentTaskId').val();

                console.log('Submitting edit form:', formData);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData + '&_method=PUT',
                    success: function(response) {
                        console.log('Task updated successfully:', response);

                        // Update the task card in the DOM without reloading
                        updateTaskCardInDOM(taskId, response.task);

                        $('#editTaskModal').modal('hide');
                        alert('Task updated successfully!');
                    },
                    error: function(xhr) {
                        console.error('Error updating task:', xhr.responseJSON);
                        alert('Error updating task: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            });

            // Function to update task card in DOM
            function updateTaskCardInDOM(taskId, taskData) {
                var taskCard = $('#task-' + taskId);
                var categoryBadge = '';

                if (taskData.category && taskData.category.id) {
                    categoryBadge = `
                        <span class="badge category-badge"
                              style="background-color: ${taskData.category.color}20; color: ${taskData.category.color}; border: 1px solid ${taskData.category.color}30;"
                              title="${taskData.category.name}">
                            <i class="${taskData.category.icon} me-1"></i>
                            ${taskData.category.name}
                        </span>
                    `;
                } else {
                    categoryBadge = `
                        <span class="badge category-badge no-category"
                              style="background-color: #95a5a620; color: #95a5a6; border: 1px solid #95a5a630;">
                            <i class="fas fa-question me-1"></i>
                            No Category
                        </span>
                    `;
                }

                // Update task title
                taskCard.find('.task-title').text(taskData.title);

                // Update description
                var descriptionContainer = taskCard.find('.description-container');
                if (taskData.description) {
                    var readMoreBtn = '';
                    if (taskData.description.length > 100) {
                        readMoreBtn = `
                            <button class="read-more-btn" data-task-id="${taskId}">
                                <span class="read-more-text">Read more</span>
                                <span class="read-less-text" style="display: none;">Read less</span>
                            </button>
                        `;
                    }

                    descriptionContainer.html(`
                        <p class="text-muted small mb-2 task-description" id="description-${taskId}">
                            ${taskData.description}
                        </p>
                        ${readMoreBtn}
                    `);
                } else {
                    descriptionContainer.html('');
                }

                // Update category badge
                taskCard.find('.task-meta').html(`
                    ${categoryBadge}
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i>
                        ${new Date(taskData.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                    </small>
                `);

                // Update data attributes if needed
                taskCard.data('category-id', taskData.category_id || '');
            }

            // Submit Add Task Form
            $('#addTaskForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();

                console.log('Submitting add form:', formData);

                $.ajax({
                    url: '{{ route("tasks.store") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addTaskModal').modal('hide');
                        form[0].reset();
                        $('.select2-modal').val(null).trigger('change');
                        location.reload(); // Reload to show new task
                    },
                    error: function(xhr) {
                        console.error('Error adding task:', xhr.responseJSON);
                        alert('Error adding task: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            });

            // Delete Task
            $(document).on('click', '.delete-task', function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    var taskId = $(this).data('task-id');

                    $.ajax({
                        url: '/tasks/' + taskId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function() {
                            $('#task-' + taskId).remove();
                        }
                    });
                }
            });

            // Add Column
            $('#addColumnBtn').click(function() {
                var columnName = prompt('Enter column name:', 'New Column');
                if (columnName && columnName.trim() !== '') {
                    $.ajax({
                        url: '{{ route("columns.store") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            name: columnName.trim()
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });

            // Edit Column
            $(document).on('click', '.edit-column', function() {
                var columnId = $(this).data('column-id');
                var currentName = $(this).data('column-name');
                var newName = prompt('Edit column name:', currentName);

                if (newName && newName.trim() !== '' && newName.trim() !== currentName) {
                    $.ajax({
                        url: '/columns/' + columnId,
                        method: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            name: newName.trim()
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });

            // Delete Column
            $(document).on('click', '.delete-column', function() {
                if (confirm('Are you sure you want to delete this column? All tasks in this column will be moved to the first column.')) {
                    var columnId = $(this).data('column-id');

                    $.ajax({
                        url: '/columns/' + columnId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });

            // Category Color Selection
            $(document).on('click', '.color-option', function() {
                $('.color-option').removeClass('selected');
                $(this).addClass('selected');
                var color = $(this).data('color');
                $('#selectedColor').val(color);
                updateCategoryPreview();
            });

            // Category Icon Selection
            $(document).on('click', '.icon-option', function() {
                $('.icon-option').removeClass('selected');
                $(this).addClass('selected');
                var icon = $(this).data('icon');
                $('#selectedIcon').val(icon);
                updateCategoryPreview();
            });

            // Update Category Preview
            function updateCategoryPreview() {
                var color = $('#selectedColor').val();
                var icon = $('#selectedIcon').val();

                $('#categoryPreview').css({
                    'background-color': color + '20',
                    'border-color': color + '30'
                });

                $('#categoryPreview i')
                    .attr('class', icon + ' me-2')
                    .css('color', color);

                $('#categoryPreview span').css('color', color);
            }

            // Submit Add Category Form
            $('#addCategoryForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route("categories.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addCategoryModal').modal('hide');
                        location.reload(); // Reload to show new category in dropdown
                    },
                    error: function(xhr) {
                        console.error('Error creating category:', xhr.responseJSON);
                        alert('Error creating category: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            });

            // Reset Add Category Form when modal closes
            $('#addCategoryModal').on('hidden.bs.modal', function() {
                $('#addCategoryForm')[0].reset();
                $('.color-option').removeClass('selected');
                $('.color-option[data-color="#3498db"]').addClass('selected');
                $('.icon-option').removeClass('selected');
                $('.icon-option[data-icon="fas fa-folder"]').addClass('selected');
                $('#selectedColor').val('#3498db');
                $('#selectedIcon').val('fas fa-folder');
                updateCategoryPreview();
            });
        });
    </script>
</body>
</html>
