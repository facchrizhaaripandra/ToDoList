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
