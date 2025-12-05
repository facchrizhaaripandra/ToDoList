            <div class="kanban-board" id="kanbanBoard">
                @foreach($columns as $column)
                <div class="column" data-column-id="{{ $column->id }}" id="column-{{ $column->id }}">
                    <div class="active-column-indicator"></div>
                    <div class="column-header" data-column-scroll="horizontal">
                        <div class="d-flex justify-content-between align-items-center">
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
                        <small class="d-block mt-1 opacity-75">
                            <i class="fas fa-arrows-alt-h me-1"></i> Click for horizontal scroll
                        </small>
                    </div>

                    <div class="column-content" data-column-scroll="vertical">
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
                                        $diff = $today->diffInDays($dueDate, false); // false = not absolute
                                        $daysUntilDue = $diff < 0 ? (int) floor($diff) : (int) ceil($diff);

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

            <div class="scroll-hint" id="horizontalScrollHint">
                <i class="fas fa-arrows-alt-h"></i>
                <span>Scroll horizontally</span>
            </div>
