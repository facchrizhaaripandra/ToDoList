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
