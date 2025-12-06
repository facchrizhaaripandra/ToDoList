<div class="modal fade" id="taskDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); position: relative; z-index: 1060;">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailsModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="taskDetailsContent">
                    <!-- Task details will be loaded here -->
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary edit-task-from-details" id="editTaskFromDetailsBtn" style="display: none;">
                    <i class="fas fa-edit me-1"></i>Edit Task
                </button>
                <button type="button" class="btn btn-danger delete-task-from-details" id="deleteTaskFromDetailsBtn" style="display: none;">
                    <i class="fas fa-trash me-1"></i>Delete Task
                </button>
            </div>
        </div>
    </div>
</div>
