<div class="task-card" data-task-id="{{ $task->id }}">
    <div class="task-checkbox">
        <input type="checkbox" class="task-complete-checkbox"
               data-task-id="{{ $task->id }}"
               {{ $task->status == 'Done' ? 'checked' : '' }}>
    </div>

    <h6 class="task-title">{{ $task->title }}</h6>

    <p class="task-description">{{ $task->description }}</p>

    <div class="task-tags">
        @if($task->category)
            <span class="task-tag tag-{{ strtolower($task->category) }}">
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
            <span class="task-tag tag-{{ strtolower($task->priority) }}-priority">
                @if($task->priority == 'High')
                    <i class="fas fa-exclamation-circle"></i>
                @elseif($task->priority == 'Medium')
                    <i class="fas fa-exclamation-triangle"></i>
                @else
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
                {{ \Carbon\Carbon::parse($task->due_date)->format('m/d/Y') }}
            </span>
        @else
            <span class="task-date">
                <i class="far fa-calendar"></i>
                No due date
            </span>
        @endif

        <span class="task-subtasks">
            {{ $task->subtasks_completed }}/{{ $task->subtasks_total }}
        </span>
    </div>
</div>
