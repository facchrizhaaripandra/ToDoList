/**
 * Enhanced Drag and Drop Module for Kanban Board
 * Provides smooth drag and drop functionality for tasks and columns
 */

class KanbanDragDrop {
    constructor() {
        this.draggedElement = null;
        this.draggedSource = null;
        this.dropZones = [];
        this.ghostElement = null;
        this.isDragging = false;
        this.offset = { x: 0, y: 0 };
        this.scrollSpeed = 5;
        this.scrollInterval = null;

        this.init();
    }

    /**
     * Initialize drag and drop functionality
     */
    init() {
        this.setupTaskDragDrop();
        this.setupColumnDragDrop();
        this.setupDropZones();
        this.setupAutoScroll();
    }

    /**
     * Setup drag and drop for tasks
     */
    setupTaskDragDrop() {
        document.addEventListener("dragstart", (e) => {
            if (e.target.closest(".task-card")) {
                this.onTaskDragStart(e);
            }
        });

        document.addEventListener("dragend", (e) => {
            if (e.target.closest(".task-card")) {
                this.onTaskDragEnd(e);
            }
        });

        document.addEventListener("dragover", (e) => {
            if (this.isDragging) {
                e.preventDefault();
                this.onDragOver(e);
            }
        });

        document.addEventListener("drop", (e) => {
            if (this.isDragging) {
                e.preventDefault();
                this.onTaskDrop(e);
            }
        });

        document.addEventListener("dragleave", (e) => {
            if (this.isDragging && !e.relatedTarget?.closest(".task-card")) {
                this.onDragLeave(e);
            }
        });
    }

    /**
     * Setup drag and drop for columns
     */
    setupColumnDragDrop() {
        // Column drag would go here if needed
    }

    /**
     * Setup drop zones
     */
    setupDropZones() {
        // Update drop zones whenever DOM changes
        this.updateDropZones();
    }

    /**
     * Update list of drop zones
     */
    updateDropZones() {
        this.dropZones = Array.from(document.querySelectorAll(".tasks-list"));
    }

    /**
     * Handle task drag start
     */
    onTaskDragStart(e) {
        const taskCard = e.target.closest(".task-card");

        if (!taskCard) return;

        this.draggedElement = taskCard;
        this.isDragging = true;
        this.draggedSource = taskCard.closest(".column");

        // Calculate offset
        const rect = taskCard.getBoundingClientRect();
        this.offset.x = e.clientX - rect.left;
        this.offset.y = e.clientY - rect.top;

        // Create ghost element for visual feedback
        this.createGhostElement(taskCard, e);

        // Set drag image
        e.dataTransfer.effectAllowed = "move";
        e.dataTransfer.setDragImage(
            this.ghostElement,
            this.offset.x,
            this.offset.y
        );

        // Add dragging class for visual feedback
        taskCard.classList.add("dragging");
        this.draggedSource.classList.add("drag-source");

        // Start auto-scroll
        this.startAutoScroll();

        // Log action
        console.log("Task drag started:", taskCard.dataset.taskId);
    }

    /**
     * Create ghost element for dragging
     */
    createGhostElement(element, e) {
        if (this.ghostElement) {
            this.ghostElement.remove();
        }

        this.ghostElement = element.cloneNode(true);
        this.ghostElement.classList.add("drag-ghost");
        this.ghostElement.style.position = "fixed";
        this.ghostElement.style.pointerEvents = "none";
        this.ghostElement.style.zIndex = "10000";
        this.ghostElement.style.opacity = "0.8";
        this.ghostElement.style.width = element.offsetWidth + "px";

        document.body.appendChild(this.ghostElement);
    }

    /**
     * Handle drag over
     */
    onDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = "move";

        // Move ghost element
        if (this.ghostElement) {
            this.ghostElement.style.left = e.clientX - this.offset.x + "px";
            this.ghostElement.style.top = e.clientY - this.offset.y + "px";
        }

        // Find drop zone under cursor
        const column = e.target.closest(".column");
        if (column) {
            column.classList.add("drag-over");

            // Find insertion point in task list
            const taskList = column.querySelector(".tasks-list");
            if (taskList) {
                const tasks = Array.from(
                    taskList.querySelectorAll(".task-card:not(.dragging)")
                );
                const afterElement = this.getDragAfterElement(
                    taskList,
                    e.clientY
                );

                if (afterElement == null) {
                    taskList.appendChild(this.draggedElement);
                } else {
                    taskList.insertBefore(this.draggedElement, afterElement);
                }
            }
        }
    }

    /**
     * Get element after which dragged element should be inserted
     */
    getDragAfterElement(container, y) {
        const draggableElements = [
            ...container.querySelectorAll(".task-card:not(.dragging)"),
        ];

        return draggableElements.reduce(
            (closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;

                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            },
            { offset: Number.NEGATIVE_INFINITY }
        ).element;
    }

    /**
     * Handle drag leave
     */
    onDragLeave(e) {
        if (e.target.closest(".column")) {
            e.target.closest(".column")?.classList.remove("drag-over");
        }
    }

    /**
     * Handle task drop
     */
    onTaskDrop(e) {
        e.preventDefault();

        const targetColumn = e.target.closest(".column");
        if (!targetColumn) return;

        const taskId = this.draggedElement.dataset.taskId;
        const newColumnId = targetColumn.dataset.columnId;

        // Only make request if column actually changed
        if (this.draggedSource.dataset.columnId !== newColumnId) {
            this.updateTaskColumn(taskId, newColumnId);
        }

        this.cleanup();
    }

    /**
     * Update task column via AJAX
     */
    updateTaskColumn(taskId, newColumnId) {
        fetch(`/tasks/${taskId}/update-column`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                column_id: newColumnId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Task moved successfully:", data);

                // Update visual feedback
                this.draggedElement.classList.add("task-moved");
                setTimeout(() => {
                    this.draggedElement.classList.remove("task-moved");
                }, 500);
            })
            .catch((error) => {
                console.error("Error updating task:", error);
                // Revert change by reloading
                location.reload();
            });
    }

    /**
     * Handle drag end
     */
    onTaskDragEnd(e) {
        this.cleanup();
    }

    /**
     * Cleanup after drag operation
     */
    cleanup() {
        this.isDragging = false;

        // Remove dragging classes
        document.querySelectorAll(".dragging").forEach((el) => {
            el.classList.remove("dragging");
        });

        document.querySelectorAll(".drag-source").forEach((el) => {
            el.classList.remove("drag-source");
        });

        document.querySelectorAll(".drag-over").forEach((el) => {
            el.classList.remove("drag-over");
        });

        // Remove ghost element
        if (this.ghostElement) {
            this.ghostElement.remove();
            this.ghostElement = null;
        }

        // Stop auto-scroll
        this.stopAutoScroll();

        this.draggedElement = null;
        this.draggedSource = null;
    }

    /**
     * Setup auto-scroll functionality
     */
    setupAutoScroll() {
        document.addEventListener("dragover", (e) => {
            if (!this.isDragging) return;

            const container = document.getElementById("kanbanBoardContainer");
            if (!container) return;

            const scrollArea = 50;
            const rect = container.getBoundingClientRect();

            if (e.clientX < rect.left + scrollArea) {
                // Scroll left
                container.scrollLeft -= this.scrollSpeed;
            } else if (e.clientX > rect.right - scrollArea) {
                // Scroll right
                container.scrollLeft += this.scrollSpeed;
            }
        });
    }

    /**
     * Start auto-scroll interval
     */
    startAutoScroll() {
        // Auto-scroll is handled via dragover listener
    }

    /**
     * Stop auto-scroll interval
     */
    stopAutoScroll() {
        if (this.scrollInterval) {
            clearInterval(this.scrollInterval);
            this.scrollInterval = null;
        }
    }

    /**
     * Reinitialize after DOM changes
     */
    reinitialize() {
        this.updateDropZones();
    }
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.kanbanDragDrop = new KanbanDragDrop();
    });
} else {
    window.kanbanDragDrop = new KanbanDragDrop();
}

export default KanbanDragDrop;
