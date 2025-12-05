<div class="modal-content">
    <form id="addCategoryForm">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create New Category</h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Category Name *</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    required
                    placeholder="Enter category name"
                    maxlength="50"
                />
            </div>

            <div class="mb-3">
                <label class="form-label">Select Color</label>
                <div class="d-flex flex-wrap">
                    <div
                        class="color-option selected"
                        style="background-color: #3498db"
                        data-color="#3498db"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #e74c3c"
                        data-color="#e74c3c"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #2ecc71"
                        data-color="#2ecc71"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #9b59b6"
                        data-color="#9b59b6"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #f39c12"
                        data-color="#f39c12"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #1abc9c"
                        data-color="#1abc9c"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #34495e"
                        data-color="#34495e"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #e84393"
                        data-color="#e84393"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #00b894"
                        data-color="#00b894"
                    ></div>
                    <div
                        class="color-option"
                        style="background-color: #6c5ce7"
                        data-color="#6c5ce7"
                    ></div>
                </div>
                <input
                    type="hidden"
                    name="color"
                    id="selectedColor"
                    value="#3498db"
                />
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
                <input
                    type="hidden"
                    name="icon"
                    id="selectedIcon"
                    value="fas fa-folder"
                />
            </div>

            <div class="preview mb-3">
                <label class="form-label">Preview:</label>
                <div
                    class="d-inline-block p-3 rounded"
                    id="categoryPreview"
                    style="
                        background-color: #3498db20;
                        border: 1px solid #3498db30;
                    "
                >
                    <i class="fas fa-folder me-2" style="color: #3498db"></i>
                    <span style="color: #3498db">Your Category</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Create Category
            </button>
        </div>
    </form>
</div>
