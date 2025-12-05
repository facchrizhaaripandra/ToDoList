<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List - Kanban Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        /* RESET Z-INDEX FOR MODAL COMPONENTS */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Ensure modal is on top of everything */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-content {
            z-index: 1061 !important;
            position: relative;
        }

        /* Force dropdowns and calendars to appear above modal */
        .select2-container--open {
            z-index: 1062 !important;
        }
        .select2-dropdown {
            z-index: 1063 !important;
        }
        .flatpickr-calendar {
            z-index: 1064 !important;
        }

        /* Make sure modal close buttons work */
        .modal .btn-close {
            z-index: 1065 !important;
            position: relative;
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

        /* Task Card with Urgency Colors */
        .task-card {
            background: white;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            cursor: move;
            transition: all 0.3s ease;
            border: 1px solid #e8e8e8;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Left border color based on urgency */
        .task-card.urgency-none {
            border-left: 5px solid #3498db;
        }
        .task-card.urgency-low {
            border-left: 5px solid #f39c12;
        }
        .task-card.urgency-medium {
            border-left: 5px solid #e67e22;
        }
        .task-card.urgency-high {
            border-left: 5px solid #e74c3c;
        }
        .task-card.urgency-overdue {
            border-left: 5px solid #c0392b;
            background-color: #fff5f5;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Urgency indicator dot */
        .urgency-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .urgency-dot.none { background-color: #3498db; }
        .urgency-dot.low { background-color: #f39c12; }
        .urgency-dot.medium { background-color: #e67e22; }
        .urgency-dot.high { background-color: #e74c3c; }
        .urgency-dot.overdue { background-color: #c0392b; }

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

        .due-date-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .due-date-overdue {
            background-color: #c0392b20 !important;
            color: #c0392b !important;
            border: 1px solid #c0392b30 !important;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .due-date-urgent {
            background-color: #e74c3c20 !important;
            color: #e74c3c !important;
            border: 1px solid #e74c3c30 !important;
        }

        .due-date-warning {
            background-color: #e67e2220 !important;
            color: #e67e22 !important;
            border: 1px solid #e67e2230 !important;
        }

        .due-date-upcoming {
            background-color: #f39c1220 !important;
            color: #f39c12 !important;
            border: 1px solid #f39c1230 !important;
        }

        .due-date-normal {
            background-color: #3498db20 !important;
            color: #3498db !important;
            border: 1px solid #3498db30 !important;
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

        /* Date picker styling */
        .flatpickr-input {
            background-color: white;
        }

        /* Filter buttons */
        .filter-buttons {
            margin-bottom: 20px;
        }
        .filter-btn.active {
            font-weight: bold;
            background-color: #e3f2fd;
        }

        /* Fix for Select2 in modals */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        /* Custom scrollbar */
        .kanban-board::-webkit-scrollbar {
            height: 8px;
        }
        .kanban-board::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .kanban-board::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .kanban-board::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
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
                <button class="btn btn-primary me-2" id="openAddTaskModal">
                    <i class="fas fa-plus-circle me-1"></i> Add Task
                </button>
                <button class="btn btn-outline-secondary" id="openAddCategoryModal">
                    <i class="fas fa-tag me-1"></i> Add Category
                </button>
            </div>
        </div>
    </nav>

    <!-- Filter Section -->
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title mb-3">Filter by Due Date:</h6>
                        <div class="filter-buttons">
                            <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="all">
                                All Tasks
                            </button>
                            <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="overdue">
                                <i class="fas fa-exclamation-circle me-1"></i> Overdue
                            </button>
                            <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="urgent">
                                <i class="fas fa-exclamation-triangle me-1"></i> Urgent (≤ 2 weeks)
                            </button>
                            <button class="btn btn-sm btn-outline-info filter-btn" data-filter="this-week">
                                <i class="fas fa-calendar-week me-1"></i> This Week
                            </button>
                            <button class="btn btn-sm btn-outline-success filter-btn" data-filter="no-due-date">
                                <i class="far fa-calendar me-1"></i> No Due Date
                            </button>
                        </div>

                        <div class="mt-2">
                            <small class="text-muted">
                                <span class="urgency-dot high me-1"></span> High (≤ 2 days) |
                                <span class="urgency-dot medium me-1"></span> Medium (≤ 1 week) |
                                <span class="urgency-dot low me-1"></span> Low (≤ 2 weeks) |
                                <span class="urgency-dot none me-1"></span> Normal (> 2 weeks)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <span class="badge bg-light text-dark column-task-count">{{ $column->tasks->count() }}</span>
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
                        @php
                            // Calculate urgency level locally to avoid float issues
                            $urgencyLevel = 'none';
                            $daysUntilDue = null;
                            $dueDateClass = '';

                            if ($task->due_date) {
                                $dueDate = \Carbon\Carbon::parse($task->due_date);
                                $today = \Carbon\Carbon::now();
                                $daysUntilDue = $today->diffInDays($dueDate, false); // false = not absolute

                                if ($daysUntilDue < 0) {
                                    $urgencyLevel = 'overdue';
                                    $dueDateClass = 'due-date-overdue';
                                } elseif ($daysUntilDue <= 2) {
                                    $urgencyLevel = 'high';
                                    $dueDateClass = 'due-date-urgent';
                                } elseif ($daysUntilDue <= 7) {
                                    $urgencyLevel = 'medium';
                                    $dueDateClass = 'due-date-warning';
                                } elseif ($daysUntilDue <= 14) {
                                    $urgencyLevel = 'low';
                                    $dueDateClass = 'due-date-upcoming';
                                } else {
                                    $urgencyLevel = 'none';
                                    $dueDateClass = 'due-date-normal';
                                }
                            }

                            // Format days text
                            $daysText = '';
                            if ($daysUntilDue !== null) {
                                if ($daysUntilDue < 0) {
                                    $daysText = '(' . abs($daysUntilDue) . ' days overdue)';
                                } elseif ($daysUntilDue == 0) {
                                    $daysText = '(Today)';
                                } elseif ($daysUntilDue == 1) {
                                    $daysText = '(Tomorrow)';
                                } else {
                                    $daysText = '(in ' . $daysUntilDue . ' days)';
                                }
                            }
                        @endphp

                        <div class="task-card urgency-{{ $urgencyLevel }}"
                             draggable="true"
                             data-task-id="{{ $task->id }}"
                             id="task-{{ $task->id }}"
                             data-category-id="{{ $task->category_id }}"
                             data-due-date="{{ $task->due_date }}"
                             data-urgency="{{ $urgencyLevel }}">
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
                                    <button class="btn btn-sm btn-outline-primary edit-task-btn"
                                            data-task-id="{{ $task->id }}">
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
                                <div class="d-flex flex-wrap gap-2">
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

                                    @if($task->due_date)
                                    <span class="badge due-date-badge {{ $dueDateClass }}" title="Due date">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                        @if($daysText)
                                        <span class="ms-1">{{ $daysText }}</span>
                                        @endif
                                    </span>
                                    @endif
                                </div>

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
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addTaskForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <label class="form-label">Due Date (Optional)</label>
                                <input type="text" name="due_date" class="form-control flatpickr-date-add"
                                       placeholder="Select due date" readonly>
                                <small class="text-muted">Leave empty if no due date</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Column</label>
                                <select name="column_id" class="form-select" id="selectColumnId">
                                    @foreach($columns as $column)
                                    <option value="{{ $column->id }}">{{ $column->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Category (Optional)</label>
                                <select name="category_id" class="form-control select2-category-add" id="categorySelect">
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
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editTaskForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <label class="form-label">Due Date</label>
                                <input type="text" name="due_date" id="editDueDate" class="form-control flatpickr-date-edit"
                                       placeholder="Select due date" readonly>
                                <small class="text-muted">Leave empty to remove due date</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Column</label>
                                <select name="column_id" class="form-select" id="editTaskColumnId">
                                    @foreach($columns as $column)
                                    <option value="{{ $column->id }}">{{ $column->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control select2-category-edit" id="editCategoryId">
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
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addCategoryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            // Variables to store plugin instances
            var addTaskSelect2 = null;
            var editTaskSelect2 = null;
            var addDatePicker = null;
            var editDatePicker = null;

            // Initialize Bootstrap modals
            var addTaskModal = null;
            var editTaskModal = null;
            var addCategoryModal = null;

            // Initialize modals
            function initializeModals() {
                addTaskModal = new bootstrap.Modal(document.getElementById('addTaskModal'));
                editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                addCategoryModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
            }

            initializeModals();

            // ==================== MODAL HANDLERS ====================

            // Open Add Task Modal
            $('#openAddTaskModal').click(function() {
                closeAllDropdowns();
                if (addTaskModal) {
                    addTaskModal.show();
                } else {
                    $('#addTaskModal').modal('show');
                }
                setTimeout(initializeAddTaskComponents, 100);
            });

            // Open Add Category Modal
            $('#openAddCategoryModal').click(function() {
                closeAllDropdowns();
                if (addCategoryModal) {
                    addCategoryModal.show();
                } else {
                    $('#addCategoryModal').modal('show');
                }
            });

            // Add task to specific column
            $('.add-to-column').click(function(e) {
                e.preventDefault();
                var columnId = $(this).data('column-id');
                $('#addColumnId').val(columnId);
                $('#selectColumnId').val(columnId);
                $('#openAddTaskModal').click();
            });

            // Edit task button - FIXED: Works for all tasks
            $(document).on('click', '.edit-task-btn', function() {
                var taskId = $(this).data('task-id');
                loadTaskDataForEdit(taskId);
            });

            function loadTaskDataForEdit(taskId) {
                $('#currentTaskId').val(taskId);

                $.ajax({
                    url: '/tasks/' + taskId,
                    method: 'GET',
                    success: function(response) {
                        console.log('Task data loaded for edit:', response);

                        // Reset form first
                        $('#editTitle').val('');
                        $('#editDescription').val('');
                        $('#editDueDate').val('');
                        $('#editTaskColumnId').val('');
                        $('#editCategoryId').val('').trigger('change');

                        // Set values from response
                        $('#editTitle').val(response.title || '');
                        $('#editDescription').val(response.description || '');
                        $('#editTaskColumnId').val(response.column_id || '{{ $columns->first()->id ?? 1 }}');
                        $('#editColumnId').val(response.column_id || '');

                        // Set due date
                        if (response.due_date) {
                            $('#editDueDate').val(response.due_date.split(' ')[0]); // Get date part only
                        } else {
                            $('#editDueDate').val('');
                        }

                        // Set form action
                        $('#editTaskForm').attr('action', '/tasks/' + taskId);

                        // Show modal
                        closeAllDropdowns();
                        if (editTaskModal) {
                            editTaskModal.show();
                        } else {
                            $('#editTaskModal').modal('show');
                        }

                        // Initialize components after modal is shown
                        setTimeout(function() {
                            initializeEditTaskComponents();

                            // Set category value
                            if (response.category_id) {
                                $('#editCategoryId').val(response.category_id).trigger('change');
                            } else {
                                $('#editCategoryId').val('').trigger('change');
                            }
                        }, 200);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading task data:', error);
                        alert('Error loading task data. Please try again.');
                    }
                });
            }

            // Function to close all dropdowns
            function closeAllDropdowns() {
                if (addTaskSelect2 && addTaskSelect2.select2 && typeof addTaskSelect2.select2 === 'function') {
                    try {
                        addTaskSelect2.select2('close');
                    } catch(e) {
                        console.log('Error closing addTaskSelect2:', e);
                    }
                }
                if (editTaskSelect2 && editTaskSelect2.select2 && typeof editTaskSelect2.select2 === 'function') {
                    try {
                        editTaskSelect2.select2('close');
                    } catch(e) {
                        console.log('Error closing editTaskSelect2:', e);
                    }
                }
            }

            // ==================== COMPONENT INITIALIZATION ====================

            // Initialize components for Add Task modal
            function initializeAddTaskComponents() {
                // Initialize Select2 for Add Task
                if ($('#categorySelect').hasClass('select2-hidden-accessible')) {
                    $('#categorySelect').select2('destroy');
                }

                addTaskSelect2 = $('#categorySelect').select2({
                    dropdownParent: $('#addTaskModal'),
                    width: '100%',
                    templateResult: formatCategory,
                    templateSelection: formatCategory,
                    escapeMarkup: function(m) { return m; }
                });

                // Initialize DatePicker for Add Task
                if (addDatePicker) {
                    try {
                        addDatePicker.destroy();
                    } catch(e) {
                        console.log('Error destroying addDatePicker:', e);
                    }
                }

                addDatePicker = flatpickr('.flatpickr-date-add', {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disableMobile: true,
                    wrap: false,
                    onOpen: function() {
                        try {
                            if (addTaskSelect2 && addTaskSelect2.select2) {
                                addTaskSelect2.select2('close');
                            }
                        } catch(e) {
                            console.log('Error closing select2 on datepicker open:', e);
                        }
                    }
                });

                // Close Select2 dropdown when option is selected
                $('#categorySelect').off('select2:select').on('select2:select', function() {
                    setTimeout(function() {
                        try {
                            if (addTaskSelect2 && addTaskSelect2.select2) {
                                addTaskSelect2.select2('close');
                            }
                        } catch(e) {
                            console.log('Error closing select2 on select:', e);
                        }
                    }, 100);
                });
            }

            // Initialize components for Edit Task modal
            function initializeEditTaskComponents() {
                // Initialize Select2 for Edit Task
                if ($('#editCategoryId').hasClass('select2-hidden-accessible')) {
                    $('#editCategoryId').select2('destroy');
                }

                editTaskSelect2 = $('#editCategoryId').select2({
                    dropdownParent: $('#editTaskModal'),
                    width: '100%',
                    escapeMarkup: function(m) { return m; }
                });

                // Initialize DatePicker for Edit Task
                if (editDatePicker) {
                    try {
                        editDatePicker.destroy();
                    } catch(e) {
                        console.log('Error destroying editDatePicker:', e);
                    }
                }

                editDatePicker = flatpickr('.flatpickr-date-edit', {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disableMobile: true,
                    wrap: false,
                    onOpen: function() {
                        try {
                            if (editTaskSelect2 && editTaskSelect2.select2) {
                                editTaskSelect2.select2('close');
                            }
                        } catch(e) {
                            console.log('Error closing select2 on datepicker open:', e);
                        }
                    }
                });

                // Close Select2 dropdown when option is selected
                $('#editCategoryId').off('select2:select').on('select2:select', function() {
                    setTimeout(function() {
                        try {
                            if (editTaskSelect2 && editTaskSelect2.select2) {
                                editTaskSelect2.select2('close');
                            }
                        } catch(e) {
                            console.log('Error closing select2 on select:', e);
                        }
                    }, 100);
                });
            }

            // Format category for Select2
            function formatCategory(category) {
                if (!category.id) return category.text;

                var color = $(category.element).data('color');
                var icon = $(category.element).data('icon');

                if (!color) return category.text;

                var $container = $('<span></span>');
                $container.append($('<i></i>').addClass(icon).addClass('me-2').css('color', color));
                $container.append(document.createTextNode(category.text));

                return $container;
            }

            // Clean up when modals are hidden
            $('#addTaskModal').on('hidden.bs.modal', function() {
                try {
                    if (addTaskSelect2 && addTaskSelect2.select2) {
                        addTaskSelect2.select2('destroy');
                    }
                    if (addDatePicker) {
                        addDatePicker.destroy();
                    }
                } catch(e) {
                    console.log('Error cleaning up add modal:', e);
                }

                $('#addTaskForm')[0].reset();
                $('.flatpickr-date-add').val('');
                addTaskSelect2 = null;
                addDatePicker = null;
            });

            $('#editTaskModal').on('hidden.bs.modal', function() {
                try {
                    if (editTaskSelect2 && editTaskSelect2.select2) {
                        editTaskSelect2.select2('destroy');
                    }
                    if (editDatePicker) {
                        editDatePicker.destroy();
                    }
                } catch(e) {
                    console.log('Error cleaning up edit modal:', e);
                }

                $('#currentTaskId').val('');
                $('#editTaskForm')[0].reset();
                editTaskSelect2 = null;
                editDatePicker = null;
            });

            // ==================== FORM SUBMISSIONS ====================

            // Submit Add Task Form
            $('#addTaskForm').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: '{{ route("tasks.store") }}',
                    method: 'POST',
                    data: formData,
                    success: function() {
                        if (addTaskModal) {
                            addTaskModal.hide();
                        } else {
                            $('#addTaskModal').modal('hide');
                        }
                        location.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            var errorMsg = '';
                            for (var field in errors) {
                                errorMsg += errors[field].join('\n') + '\n';
                            }
                            alert('Error: ' + errorMsg);
                        } else {
                            alert('Error adding task');
                        }
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

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData + '&_method=PUT',
                    success: function(response) {
                        console.log('Task update response:', response);
                        updateTaskCardInDOM(taskId, response.task);
                        if (editTaskModal) {
                            editTaskModal.hide();
                        } else {
                            $('#editTaskModal').modal('hide');
                        }
                        alert('Task updated successfully!');
                    },
                    error: function(xhr) {
                        console.error('Error updating task:', xhr);
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            var errorMsg = '';
                            for (var field in errors) {
                                errorMsg += errors[field].join('\n') + '\n';
                            }
                            alert('Error: ' + errorMsg);
                        } else {
                            alert('Error updating task: ' + (xhr.responseJSON?.message || 'Unknown error'));
                        }
                    }
                });
            });

            // Function to update task card in DOM
            function updateTaskCardInDOM(taskId, taskData) {
                var taskCard = $('#task-' + taskId);
                if (!taskCard.length) {
                    console.error('Task card not found:', taskId);
                    location.reload();
                    return;
                }

                // Calculate urgency locally
                var urgencyLevel = calculateUrgencyLevel(taskData.due_date);
                var urgencyClass = 'urgency-' + urgencyLevel;

                // Update classes
                taskCard.removeClass('urgency-none urgency-low urgency-medium urgency-high urgency-overdue');
                taskCard.addClass(urgencyClass);

                // Update data attributes
                taskCard.data('due-date', taskData.due_date);
                taskCard.data('urgency', urgencyLevel);
                taskCard.data('category-id', taskData.category_id || '');

                // Update title
                taskCard.find('.task-title').text(taskData.title || '');

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

                // Update due date badge
                var dueDateBadge = '';
                if (taskData.due_date) {
                    var dueDate = new Date(taskData.due_date);
                    var today = new Date();
                    var daysDiff = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));

                    var dueText = '';
                    if (daysDiff < 0) {
                        dueText = '(' + Math.abs(daysDiff) + ' days overdue)';
                    } else if (daysDiff === 0) {
                        dueText = '(Today)';
                    } else if (daysDiff === 1) {
                        dueText = '(Tomorrow)';
                    } else {
                        dueText = '(in ' + daysDiff + ' days)';
                    }

                    var dueDateClass = '';
                    if (urgencyLevel === 'overdue') {
                        dueDateClass = 'due-date-overdue';
                    } else if (urgencyLevel === 'high') {
                        dueDateClass = 'due-date-urgent';
                    } else if (urgencyLevel === 'medium') {
                        dueDateClass = 'due-date-warning';
                    } else if (urgencyLevel === 'low') {
                        dueDateClass = 'due-date-upcoming';
                    } else {
                        dueDateClass = 'due-date-normal';
                    }

                    var formattedDate = dueDate.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });

                    dueDateBadge = `
                        <span class="badge due-date-badge ${dueDateClass}" title="Due date">
                            <i class="far fa-calendar-alt me-1"></i>
                            ${formattedDate}
                            <span class="ms-1">${dueText}</span>
                        </span>
                    `;
                }

                // Update task meta section
                taskCard.find('.task-meta').html(`
                    <div class="d-flex flex-wrap gap-2">
                        ${categoryBadge}
                        ${dueDateBadge}
                    </div>
                    <small class="text-muted">
                        <i class="far fa-clock me-1"></i>
                        ${new Date(taskData.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}
                    </small>
                `);
            }

            // Function to calculate urgency level
            function calculateUrgencyLevel(dueDate) {
                if (!dueDate) return 'none';

                var due = new Date(dueDate);
                var today = new Date();
                var daysUntilDue = Math.ceil((due - today) / (1000 * 60 * 60 * 24));

                if (daysUntilDue < 0) {
                    return 'overdue';
                } else if (daysUntilDue <= 2) {
                    return 'high';
                } else if (daysUntilDue <= 7) {
                    return 'medium';
                } else if (daysUntilDue <= 14) {
                    return 'low';
                } else {
                    return 'none';
                }
            }

            // ==================== FILTER FUNCTIONALITY ====================

            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                var filter = $(this).data('filter');
                filterTasks(filter);
            });

            function filterTasks(filter) {
                $('.task-card').show();

                switch(filter) {
                    case 'overdue':
                        $('.task-card').each(function() {
                            var urgency = $(this).data('urgency');
                            if (urgency !== 'overdue') {
                                $(this).hide();
                            }
                        });
                        break;

                    case 'urgent':
                        $('.task-card').each(function() {
                            var urgency = $(this).data('urgency');
                            if (!['low', 'medium', 'high', 'overdue'].includes(urgency)) {
                                $(this).hide();
                            }
                        });
                        break;

                    case 'this-week':
                        $('.task-card').each(function() {
                            var dueDate = $(this).data('due-date');
                            if (!dueDate) {
                                $(this).hide();
                            } else {
                                var taskDate = new Date(dueDate);
                                var today = new Date();
                                var nextWeek = new Date(today);
                                nextWeek.setDate(today.getDate() + 7);

                                if (taskDate > nextWeek) {
                                    $(this).hide();
                                }
                            }
                        });
                        break;

                    case 'no-due-date':
                        $('.task-card').each(function() {
                            var dueDate = $(this).data('due-date');
                            if (dueDate) {
                                $(this).hide();
                            }
                        });
                        break;
                }

                updateColumnTaskCounts();
            }

            function updateColumnTaskCounts() {
                $('.column').each(function() {
                    var visibleTasks = $(this).find('.task-card:visible').length;
                    $(this).find('.column-task-count').text(visibleTasks);

                    var tasksList = $(this).find('.tasks-list');
                    if (visibleTasks === 0) {
                        if (!tasksList.find('.empty-state').length) {
                            tasksList.append(
                                '<div class="empty-state">' +
                                '<i class="fas fa-tasks text-muted"></i>' +
                                '<p class="mb-0">No tasks match filter</p>' +
                                '</div>'
                            );
                        }
                    } else {
                        tasksList.find('.empty-state').remove();
                    }
                });
            }

            // ==================== READ MORE FUNCTIONALITY ====================

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

            // ==================== DRAG & DROP FUNCTIONALITY ====================

            // Initialize Sortable for Kanban Board
            var kanbanBoard = document.getElementById('kanbanBoard');
            if (kanbanBoard) {
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
            }

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
                                }
                            });
                        }
                    });
                }
            });

            // ==================== DELETE FUNCTIONALITY ====================

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
                            updateColumnTaskCounts();
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

            // ==================== COLUMN MANAGEMENT ====================

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

            // ==================== CATEGORY MANAGEMENT ====================

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
                        if (addCategoryModal) {
                            addCategoryModal.hide();
                        } else {
                            $('#addCategoryModal').modal('hide');
                        }
                        location.reload();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            var errorMsg = '';
                            for (var field in errors) {
                                errorMsg += errors[field].join('\n') + '\n';
                            }
                            alert('Error: ' + errorMsg);
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

            // Initialize on page load
            setTimeout(function() {
                updateColumnTaskCounts();
            }, 500);
        });
    </script>
</body>
</html>
