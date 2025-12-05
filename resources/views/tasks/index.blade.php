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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            @include('partials.category.add-category-modal')
        </div>
    </div>
@endsection

@push('scripts')
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

            // Scroll control variables
            var isHorizontalScrollMode = false;
            var activeColumnId = null;
            var kanbanBoard = document.getElementById('kanbanBoard');
            var kanbanBoardContainer = document.getElementById('kanbanBoardContainer');
            var horizontalScrollHint = document.getElementById('horizontalScrollHint');

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

            // ==================== SCROLL CONTROL FUNCTIONALITY ====================

            // Show/hide scroll hint based on mouse position
            function updateScrollHint() {
                if (isHorizontalScrollMode && activeColumnId) {
                    horizontalScrollHint.style.opacity = '1';
                    horizontalScrollHint.innerHTML = '<i class="fas fa-arrows-alt-h"></i><span>Scroll horizontally (Active: Column ' + activeColumnId + ')</span>';
                } else {
                    horizontalScrollHint.style.opacity = '0.5';
                    horizontalScrollHint.innerHTML = '<i class="fas fa-arrows-alt-h"></i><span>Scroll horizontally</span>';
                }
            }

            // Activate horizontal scroll for a column
            function activateHorizontalScroll(columnId) {
                // Remove active class from all columns
                $('.column').removeClass('active-scroll');

                // Add active class to clicked column
                $('#column-' + columnId).addClass('active-scroll');

                // Set scroll mode
                isHorizontalScrollMode = true;
                activeColumnId = columnId;

                // Update hint
                updateScrollHint();

                // Prevent default vertical scroll in column content
                $('#column-' + columnId + ' .column-content').addClass('no-vertical-scroll');
            }

            // Deactivate horizontal scroll
            function deactivateHorizontalScroll() {
                // Remove active class from all columns
                $('.column').removeClass('active-scroll');
                $('.column-content').removeClass('no-vertical-scroll');

                // Reset scroll mode
                isHorizontalScrollMode = false;
                activeColumnId = null;

                // Update hint
                updateScrollHint();
            }

            // Handle mouse wheel events
            function handleMouseWheel(e) {
                if (!isHorizontalScrollMode || !activeColumnId) {
                    return; // Let default vertical scroll happen
                }

                // Only prevent default if we're in horizontal scroll mode
                e.preventDefault();

                // Scroll the kanban board container horizontally
                var delta = e.deltaY || e.detail || e.wheelDelta;
                kanbanBoardContainer.scrollLeft += delta;
            }

            // Event listeners for scroll control
            $(document).on('mousedown', '.column-header[data-column-scroll="horizontal"]', function(e) {
                var columnId = $(this).closest('.column').data('column-id');
                activateHorizontalScroll(columnId);
                e.stopPropagation();
            });

            $(document).on('mousedown', '.column-content[data-column-scroll="vertical"], .task-card, .empty-state', function(e) {
                deactivateHorizontalScroll();
                e.stopPropagation();
            });

            $(document).on('click', function(e) {
                // If click is outside any column, deactivate horizontal scroll
                if (!$(e.target).closest('.column').length) {
                    deactivateHorizontalScroll();
                }
            });

            // Add mouse wheel event listener
            kanbanBoardContainer.addEventListener('wheel', handleMouseWheel, { passive: false });

            // Also prevent wheel events from propagating to parent when in horizontal mode
            $('.column-content').on('wheel', function(e) {
                if (isHorizontalScrollMode && activeColumnId) {
                    e.stopPropagation();
                }
            });

            // Initialize scroll hint
            updateScrollHint();

            // ==================== MODAL HANDLERS ====================

            // Open Add Task Modal
            $('#openAddTaskModal').click(function() {
                closeAllDropdowns();
                deactivateHorizontalScroll(); // Reset scroll mode when opening modal
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
                deactivateHorizontalScroll(); // Reset scroll mode when opening modal

                // Initialize form values
                $('#selectedColor').val('#3498db');
                $('#selectedIcon').val('fas fa-folder');
                updateCategoryPreview();

                if (addCategoryModal) {
                    addCategoryModal.show();
                } else {
                    $('#addCategoryModal').modal('show');
                }
            });

            // Add task to specific column
            $(document).off('click', '.add-to-column').on('click', '.add-to-column', function(e) {
                e.preventDefault();
                deactivateHorizontalScroll(); // Reset scroll mode
                var columnId = $(this).data('column-id');
                $('#addColumnId').val(columnId);
                $('#selectColumnId').val(columnId);
                $('#openAddTaskModal').click();
            });

            // Edit task button
            $(document).off('click', '.edit-task-btn').on('click', '.edit-task-btn', function() {
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
            $('#addTaskForm').off('submit').submit(function(e) {
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
            $('#editTaskForm').off('submit').submit(function(e) {
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

                        // Auto-sort tasks dalam kolom yang sama setelah update
                        const columnId = response.task.column_id;
                        const column = document.querySelector('[data-column-id="' + columnId + '"]');
                        if (column) {
                            const taskList = column.querySelector('.tasks-list');
                            if (taskList) {
                                setTimeout(() => {
                                    sortTasksByDueDate(taskList);
                                }, 100);
                            }
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

            $('.filter-btn').off('click').click(function() {
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

            $(document).off('click', '.read-more-btn').on('click', '.read-more-btn', function() {
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
            var sortableInstances = {};
            $('.column').each(function() {
                var columnId = $(this).data('column-id');
                var taskList = document.getElementById('tasks-list-' + columnId);

                if (taskList) {
                    console.log('Initializing Sortable for task list:', taskList.id);
                    // Store Sortable instance so we can access/reinit if needed
                    sortableInstances[columnId] = Sortable.create(taskList, {
                        group: { name: 'tasks', put: true, pull: true },
                        sort: true,
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        dragClass: 'sortable-drag',
                        // Allow drag from entire task-card (no handle restriction)
                        // If you want to restrict to handle only, uncomment next line:
                        // handle: '.drag-handle',
                        draggable: '.task-card',
                        filter: '.add-to-column',  // Don't drag the "Add Task" button
                        preventOnFilter: false,
                        onStart: function(evt) {
                            console.log('Drag started on task:', evt.item.dataset.taskId);
                            $(evt.item).addClass('dragging');
                        },
                        onEnd: function(evt) {
                            console.log('Drag ended. Item:', evt.item.dataset.taskId, 'From:', evt.from.id, 'To:', evt.to.id);
                            $(evt.item).removeClass('dragging');

                            var taskId = evt.item.dataset.taskId;
                            var sourceColumnId = $(evt.from).closest('.column').data('column-id');
                            var targetColumnId = $(evt.to).closest('.column').data('column-id');

                            // If task didn't actually move to a different position/column, skip AJAX
                            if (!targetColumnId || (targetColumnId === sourceColumnId && evt.from === evt.to)) {
                                return;
                            }

                            console.log('Updating task', taskId, 'to column', targetColumnId);

                            $.ajax({
                                url: '/tasks/' + taskId + '/update-column',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    column_id: targetColumnId
                                },
                                success: function(response) {
                                    console.log('Task moved successfully');

                                    // Remove empty-state from target column if it exists
                                    const targetColumn = document.querySelector('[data-column-id="' + targetColumnId + '"]');
                                    if (targetColumn) {
                                        const targetTaskList = targetColumn.querySelector('.tasks-list');
                                        if (targetTaskList) {
                                            // Remove empty-state if task was moved to this column
                                            const emptyState = targetTaskList.querySelector('.empty-state');
                                            if (emptyState) {
                                                emptyState.remove();
                                                console.log('Removed empty-state from target column');
                                            }

                                            // Auto-sort tasks dalam kolom target setelah task dipindahkan
                                            setTimeout(() => {
                                                sortTasksByDueDate(targetTaskList);
                                            }, 100);
                                        }
                                    }

                                    // Check if source column is now empty and add empty-state if needed
                                    if (sourceColumnId) {
                                        const sourceColumn = document.querySelector('[data-column-id="' + sourceColumnId + '"]');
                                        if (sourceColumn) {
                                            const sourceTaskList = sourceColumn.querySelector('.tasks-list');
                                            if (sourceTaskList) {
                                                const visibleTasks = sourceTaskList.querySelectorAll('.task-card:not(.sortable-ghost)').length;
                                                if (visibleTasks === 0) {
                                                    // Add empty-state to source column
                                                    const emptyStateHtml = '<div class="empty-state"><i class="fas fa-tasks text-muted"></i><p class="mb-0">No tasks yet</p></div>';
                                                    sourceTaskList.insertAdjacentHTML('beforeend', emptyStateHtml);
                                                    console.log('Added empty-state to source column');
                                                }
                                            }
                                        }
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error moving task:', error, xhr);
                                    alert('Error moving task. Please try again.');
                                    // Optionally reload or revert the UI
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });

            // ==================== DELETE FUNCTIONALITY ====================

            // Delete Task
            $(document).off('click', '.delete-task').on('click', '.delete-task', function() {
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
            $(document).off('click', '.delete-column').on('click', '.delete-column', function() {
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
            $('#addColumnBtn').off('click').click(function() {
                deactivateHorizontalScroll(); // Reset scroll mode
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
            $(document).off('click', '.edit-column').on('click', '.edit-column', function() {
                deactivateHorizontalScroll(); // Reset scroll mode
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
            $(document).off('click', '.color-option').on('click', '.color-option', function() {
                $('.color-option').removeClass('selected');
                $(this).addClass('selected');
                var color = $(this).data('color');
                $('#selectedColor').val(color);
                updateCategoryPreview();
            });

            // Category Icon Selection
            $(document).off('click', '.icon-option').on('click', '.icon-option', function() {
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
            $('#addCategoryForm').off('submit').submit(function(e) {
                e.preventDefault();

                // Validate required fields
                var name = $('[name="name"]', this).val().trim();
                var color = $('#selectedColor').val();
                var icon = $('#selectedIcon').val();

                if (!name) {
                    alert('Category name is required!');
                    return false;
                }

                if (!color) {
                    alert('Color is required!');
                    return false;
                }

                if (!icon) {
                    alert('Icon is required!');
                    return false;
                }

                // Prepare form data
                var formData = {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    color: color,
                    icon: icon
                };

                console.log('Submitting category with data:', formData);

                $.ajax({
                    url: '{{ route("categories.store") }}',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Category created:', response);

                        // Add new category option to Select2 dropdowns WITHOUT reloading page
                        var newOption = $('<option></option>')
                            .attr('value', response.category.id)
                            .data('color', response.category.color)
                            .data('icon', response.category.icon)
                            .html('<i class="' + response.category.icon + ' me-2" style="color: ' + response.category.color + '"></i>' + response.category.name);

                        // Add to Add Task modal
                        $('#categorySelect').append(newOption.clone());
                        if (addTaskSelect2) {
                            addTaskSelect2.select2('destroy');
                        }
                        addTaskSelect2 = $('#categorySelect').select2({
                            dropdownParent: $('#addTaskModal'),
                            width: '100%',
                            templateResult: formatCategory,
                            templateSelection: formatCategory,
                            escapeMarkup: function(m) { return m; }
                        });

                        // Add to Edit Task modal
                        $('#editCategoryId').append(newOption.clone());
                        if (editTaskSelect2) {
                            editTaskSelect2.select2('destroy');
                        }
                        editTaskSelect2 = $('#editCategoryId').select2({
                            dropdownParent: $('#editTaskModal'),
                            width: '100%',
                            templateResult: formatCategory,
                            templateSelection: formatCategory,
                            escapeMarkup: function(m) { return m; }
                        });

                        // Close modal
                        if (addCategoryModal) {
                            addCategoryModal.hide();
                        } else {
                            $('#addCategoryModal').modal('hide');
                        }
                        alert('Category created successfully!');
                    },
                    error: function(xhr) {
                        console.error('Error response:', xhr);
                        console.log('Status:', xhr.status);
                        console.log('Response:', xhr.responseText);

                        var errorMsg = 'Error creating category';

                        try {
                            var jsonResponse = JSON.parse(xhr.responseText);
                            if (jsonResponse.errors) {
                                errorMsg = 'Validation errors:\n';
                                for (var field in jsonResponse.errors) {
                                    errorMsg += '- ' + jsonResponse.errors[field].join('\n  ') + '\n';
                                }
                            } else if (jsonResponse.message) {
                                errorMsg = jsonResponse.message;
                            }
                        } catch(e) {
                            if (xhr.statusText) {
                                errorMsg = 'Error: ' + xhr.statusText;
                            }
                        }

                        alert(errorMsg);
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

                // Auto-sort tasks by due date pada saat page load
                autoSortAllColumns();
            }, 500);

            /**
             * Fungsi untuk otomatis mengurutkan semua kolom berdasarkan due date
             */
            function autoSortAllColumns() {
                $('.column').each(function() {
                    const taskList = $(this).find('.tasks-list')[0];
                    if (taskList) {
                        sortTasksByDueDate(taskList);
                    }
                });
            }

            /**
             * Fungsi untuk mengurutkan task berdasarkan due date terdekat
             */
            function sortTasksByDueDate(container) {
                if (!container) return;

                const tasks = Array.from(container.querySelectorAll('.task-card'));

                tasks.sort((a, b) => {
                    const dateA = a.dataset.dueDate;
                    const dateB = b.dataset.dueDate;

                    // Task tanpa due date diletakkan di paling bawah
                    if (!dateA && !dateB) return 0;
                    if (!dateA) return 1;
                    if (!dateB) return -1;

                    // Urutkan berdasarkan tanggal terdekat (ascending)
                    return new Date(dateA) - new Date(dateB);
                });

                // Masukkan kembali ke container dengan urutan baru
                tasks.forEach(task => {
                    container.appendChild(task);
                });
            }
        });
    </script>
@endpush
