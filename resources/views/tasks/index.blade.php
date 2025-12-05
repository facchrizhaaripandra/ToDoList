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
        .modal {
            z-index: 1050;
        }
        .modal-backdrop {
            z-index: 1040;
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
        }
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .category-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            margin-right: 5px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .color-preview {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .icon-preview {
            width: 20px;
            text-align: center;
            margin-right: 5px;
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
        .navbar-brand {
            font-weight: 600;
            color: #2c3e50 !important;
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
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* FIX SELECT2 STYLING IN MODALS */
        .modal .select2-container {
            width: 100% !important;
        }
        .modal .select2-selection {
            border: 1px solid #ced4da;
            height: 38px;
            display: flex;
            align-items: center;
        }
        .modal .select2-selection__rendered {
            line-height: 36px !important;
        }
        .modal .select2-selection__arrow {
            height: 36px !important;
        }

        /* Ensure dropdown appears above modal */
        .select2-dropdown {
            z-index: 99999 !important;
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
        <div class="kanban-board custom-scrollbar" id="kanbanBoard">
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
                        <div class="task-card" draggable="true" data-task-id="{{ $task->id }}" id="task-{{ $task->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="drag-handle me-2">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                    <h6 class="mb-0 task-title">{{ $task->title }}</h6>
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
                            <p class="text-muted small mb-3 task-description">{{ $task->description }}</p>
                            @endif

                            @if($task->category)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge category-badge"
                                      style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}; border: 1px solid {{ $task->category->color }}30;">
                                    <i class="{{ $task->category->icon }} me-1"></i>
                                    {{ $task->category->name }}
                                </span>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $task->created_at->format('M d') }}
                                </small>
                            </div>
                            @endif
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
                    <i class="fas fa-plus me-1"></i> Add Task to this Column
                </button>
            </div>
            @endforeach

            <!-- Add Column Button -->
            <div class="column d-flex align-items-center justify-content-center">
                <button class="add-column-btn" id="addColumnBtn">
                    <i class="fas fa-plus me-2"></i> Add New Column
                </button>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tasks.store') }}" method="POST" id="addTaskForm">
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
                                   placeholder="Enter task title">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Enter task description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Column</label>
                            <select name="column_id" class="form-select" id="selectColumnId">
                                @foreach($columns as $column)
                                <option value="{{ $column->id }}">{{ $column->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control select2-modal" id="categorySelect">
                                <option value="">Select Category</option>
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

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Category can be created using "Add Category" button in the navbar
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
        <div class="modal-dialog">
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

                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" id="editTitle" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Column</label>
                            <select name="column_id" class="form-select" id="editTaskColumnId">
                                @foreach($columns as $column)
                                <option value="{{ $column->id }}">{{ $column->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-control select2-modal" id="editCategoryId">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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
                                   placeholder="Enter category name">
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for modals with proper z-index fix
            function initializeSelect2ForModal() {
                $('.select2-modal').select2({
                    dropdownParent: $('#addTaskModal, #editTaskModal'),
                    templateResult: formatCategory,
                    templateSelection: formatCategory,
                    width: '100%',
                    dropdownCssClass: 'select2-dropdown-modal'
                });
            }

            // Re-initialize Select2 when modal opens
            $('#addTaskModal').on('shown.bs.modal', function() {
                initializeSelect2ForModal();
            });

            $('#editTaskModal').on('shown.bs.modal', function() {
                initializeSelect2ForModal();
            });

            function formatCategory(category) {
                if (!category.id) return category.text;

                var color = $(category.element).data('color');
                var icon = $(category.element).data('icon');

                if (!color) return category.text;

                var $container = $('<span></span>');
                $container.append(
                    $('<i class="me-2"></i>')
                        .addClass(icon)
                        .css('color', color)
                );
                $container.append(
                    $('<span></span>').text(category.text)
                );

                return $container;
            }

            // Initialize Sortable for Kanban Board
            var kanbanBoard = document.getElementById('kanbanBoard');
            var columnSortable = Sortable.create(kanbanBoard, {
                group: 'columns',
                animation: 150,
                handle: '.column-header',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
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
                        },
                        success: function() {
                            console.log('Columns reordered successfully');
                        },
                        error: function() {
                            alert('Error reordering columns');
                            location.reload();
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
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
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

                                    // Update task count badge
                                    var oldColumnId = $(evt.from).closest('.column').data('column-id');
                                    updateColumnTaskCount(oldColumnId);
                                    updateColumnTaskCount(newColumnId);
                                },
                                error: function() {
                                    alert('Error moving task');
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });

            function updateColumnTaskCount(columnId) {
                var column = $('.column[data-column-id="' + columnId + '"]');
                var taskCount = column.find('.task-card').length;
                column.find('.badge.bg-light').text(taskCount);

                // Show/hide empty state
                var tasksList = column.find('.tasks-list');
                if (taskCount === 0) {
                    if (!tasksList.find('.empty-state').length) {
                        tasksList.html(
                            '<div class="empty-state">' +
                            '<i class="fas fa-tasks text-muted"></i>' +
                            '<p class="mb-0">No tasks yet</p>' +
                            '</div>'
                        );
                    }
                }
            }

            // Add Task to specific column
            $('.add-to-column').click(function(e) {
                e.preventDefault();
                var columnId = $(this).data('column-id');
                $('#addColumnId').val(columnId);
                $('#selectColumnId').val(columnId);
                $('#addTaskModal').modal('show');
            });

            // Edit Task
            $('.edit-task').click(function() {
                var taskId = $(this).data('task-id');
                var taskCard = $('#task-' + taskId);

                // Get task data from DOM
                var title = taskCard.find('.task-title').text();
                var description = taskCard.find('.task-description').text() || '';
                var columnId = taskCard.closest('.column').data('column-id');

                // Get category ID if exists
                var categoryId = '';
                var categoryBadge = taskCard.find('.category-badge');
                if (categoryBadge.length) {
                    var categoryName = categoryBadge.text().trim();
                    // Find category by name
                    var categoryOption = $('#editCategoryId option').filter(function() {
                        return $(this).text().trim() === categoryName;
                    }).first();
                    if (categoryOption.length) {
                        categoryId = categoryOption.val();
                    }
                }

                // Set form values
                $('#editTitle').val(title);
                $('#editDescription').val(description);
                $('#editTaskColumnId').val(columnId);
                $('#editColumnId').val(columnId);

                // Set form action
                $('#editTaskForm').attr('action', '/tasks/' + taskId);

                // Show modal first, then set category value
                $('#editTaskModal').modal('show');

                // Set category after a small delay to ensure Select2 is initialized
                setTimeout(function() {
                    if (categoryId) {
                        $('#editCategoryId').val(categoryId).trigger('change');
                    }
                }, 500);
            });

            // Submit Edit Task Form
            $('#editTaskForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var method = form.find('input[name="_method"]').val();
                var formData = form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData + '&_method=' + method,
                    success: function(response) {
                        $('#editTaskModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            alert('Error: ' + Object.values(errors).join('\n'));
                        } else {
                            alert('Error updating task');
                        }
                    }
                });
            });

            // Delete Task
            $(document).on('click', '.delete-task', function() {
                if (confirm('Are you sure you want to delete this task?')) {
                    var taskId = $(this).data('task-id');
                    var columnId = $(this).closest('.column').data('column-id');

                    $.ajax({
                        url: '/tasks/' + taskId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function() {
                            // Remove task from DOM
                            $('#task-' + taskId).remove();
                            updateColumnTaskCount(columnId);
                        },
                        error: function() {
                            alert('Error deleting task');
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
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                alert('Error: ' + xhr.responseJSON.message);
                            } else {
                                alert('Error creating column');
                            }
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
                            // Update column name in DOM
                            $('.column-title[data-column-id="' + columnId + '"]').text(newName.trim());
                            $('.edit-column[data-column-id="' + columnId + '"]').data('column-name', newName.trim());

                            // Update select options
                            $('#selectColumnId option[value="' + columnId + '"]').text(newName.trim());
                            $('#editTaskColumnId option[value="' + columnId + '"]').text(newName.trim());
                        },
                        error: function() {
                            alert('Error updating column');
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
                        },
                        error: function() {
                            alert('Error deleting column');
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
                    success: function() {
                        $('#addCategoryModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = [];
                            for (var field in errors) {
                                errorMessages.push(errors[field].join(', '));
                            }
                            alert('Error: ' + errorMessages.join('\n'));
                        } else {
                            alert('Error creating category');
                        }
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

            // Submit Add Task Form
            $('#addTaskForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#addTaskModal').modal('hide');
                        form[0].reset();
                        // Reset Select2
                        $('.select2-modal').val(null).trigger('change');
                        location.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = [];
                            for (var field in errors) {
                                errorMessages.push(errors[field].join(', '));
                            }
                            alert('Error: ' + errorMessages.join('\n'));
                        } else {
                            alert('Error creating task');
                        }
                    }
                });
            });

            // Close Select2 dropdown when modal closes
            $('.modal').on('hidden.bs.modal', function() {
                $('.select2-modal').select2('close');
            });

            // Show success messages if any
            @if(session('success'))
                alert('{{ session('success') }}');
            @endif

            @if($errors->any())
                alert('{{ $errors->first() }}');
            @endif
        });
    </script>
</body>
</html>
