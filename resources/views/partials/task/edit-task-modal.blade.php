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
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
