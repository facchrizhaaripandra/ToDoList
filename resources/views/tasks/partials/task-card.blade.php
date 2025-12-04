<div class="task-card" data-task-id="{{ $task->id }}" onclick="showTaskDetail({{ $task->id }})">
    <div class="task-checkbox" onclick="event.stopPropagation();">
        <input type="checkbox" class="task-complete-checkbox"
               data-task-id="{{ $task->id }}"
               {{ $task->status == 'Done' ? 'checked' : '' }}
               onclick="event.stopPropagation();">
    </div>

    <h6 class="task-title">{{ $task->title }}</h6>

    <p class="task-description">
        @if($task->description)
            {{ Str::limit($task->description, 100) }}
        @else
            <span class="text-muted">No description</span>
        @endif
    </p>

    <div class="task-tags">
        @if($task->category)
            @php
                $categoryClass = '';
                switch(strtolower($task->category)) {
                    case 'design':
                        $categoryClass = 'tag-design';
                        break;
                    case 'development':
                        $categoryClass = 'tag-development';
                        break;
                    case 'research':
                        $categoryClass = 'tag-research';
                        break;
                    default:
                        $categoryClass = '';
                }
            @endphp

            <span class="task-tag {{ $categoryClass }}">
                @if($task->category == 'Design')
                    <i class="fas fa-palette"></i>
                @elseif($task->category == 'Development')
                    <i class="fas fa-code"></i>
                @elseif($task->category == 'Research')
                    <i class="fas fa-search"></i>
                @endif
                {{ $task->category }}
            </span>
        @endif

        @if($task->priority)
            @php
                $priorityClass = '';
                switch(strtolower($task->priority)) {
                    case 'high':
                        $priorityClass = 'tag-high-priority';
                        break;
                    case 'medium':
                        $priorityClass = 'tag-medium-priority';
                        break;
                    case 'low':
                        $priorityClass = 'tag-low-priority';
                        break;
                    default:
                        $priorityClass = '';
                }
            @endphp

            <span class="task-tag {{ $priorityClass }}">
                @if($task->priority == 'High')
                    <i class="fas fa-exclamation-circle"></i>
                @elseif($task->priority == 'Medium')
                    <i class="fas fa-exclamation-triangle"></i>
                @elseif($task->priority == 'Low')
                    <i class="fas fa-info-circle"></i>
                @endif
                {{ $task->priority }} Priority
            </span>
        @endif
    </div>

    <div class="task-footer">
        @if($task->due_date)
            <span class="task-date">
                <i class="far fa-calendar"></i>
                @php
                    try {
                        echo \Carbon\Carbon::parse($task->due_date)->format('m/d/Y');
                    } catch (\Exception $e) {
                        echo date('m/d/Y', strtotime($task->due_date));
                    }
                @endphp
            </span>
        @else
            <span class="task-date">
                <i class="far fa-calendar"></i>
                No due date
            </span>
        @endif

        <span class="task-subtasks">
            {{ $task->subtasks_completed ?? 0 }}/{{ $task->subtasks_total ?? 0 }}
        </span>
    </div>
</div>
