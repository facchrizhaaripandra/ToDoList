<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List - Kanban Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        /* RESET Z-INDEX FOR MODAL COMPONENTS */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Ensure modal is on top of everything */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }
        .modal-content {
            z-index: 1061 !important;
            position: relative;
        }

        /* Force dropdowns and calendars to appear above modal */
        .select2-container--open {
            z-index: 1062 !important;
        }
        .select2-dropdown {
            z-index: 1063 !important;
        }
        .flatpickr-calendar {
            z-index: 1064 !important;
        }

        /* Make sure modal close buttons work */
        .modal .btn-close {
            z-index: 1065 !important;
            position: relative;
        }

        .kanban-board-container {
            width: 100%;
            overflow-x: auto;
            position: relative;
            padding-bottom: 10px;
        }

        .kanban-board {
            display: flex;
            gap: 20px;
            padding: 20px;
            min-height: calc(100vh - 180px);
            transition: transform 0.1s ease;
            user-select: none;
        }

        .column {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            min-width: 320px;
            max-width: 350px;
            min-height: 600px;
            max-height: 80vh;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: all 0.3s ease;
        }

        .column.active-scroll {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
            border-color: #667eea;
        }

        .column-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        .column-header:hover {
            opacity: 0.95;
        }

        .column-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 5px;
        }

        /* Custom scrollbars */
        .kanban-board-container::-webkit-scrollbar {
            height: 12px;
        }
        .kanban-board-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .kanban-board-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        .kanban-board-container::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        .column-content::-webkit-scrollbar {
            width: 8px;
        }
        .column-content::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }
        .column-content::-webkit-scrollbar-thumb {
            background: #d1d1d1;
            border-radius: 10px;
        }
        .column-content::-webkit-scrollbar-thumb:hover {
            background: #b1b1b1;
        }

        /* Task Card with Urgency Colors */
        .task-card {
            background: white;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            cursor: move;
            transition: all 0.3s ease;
            border: 1px solid #e8e8e8;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Left border color based on urgency */
        .task-card.urgency-none {
            border-left: 5px solid #3498db;
        }
        .task-card.urgency-low {
            border-left: 5px solid #f39c12;
        }
        .task-card.urgency-medium {
            border-left: 5px solid #e67e22;
        }
        .task-card.urgency-high {
            border-left: 5px solid #e74c3c;
        }
        .task-card.urgency-overdue {
            border-left: 5px solid #c0392b;
            background-color: #fff5f5;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Urgency indicator dot */
        .urgency-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .urgency-dot.none { background-color: #3498db; }
        .urgency-dot.low { background-color: #f39c12; }
        .urgency-dot.medium { background-color: #e67e22; }
        .urgency-dot.high { background-color: #e74c3c; }
        .urgency-dot.overdue { background-color: #c0392b; }

        .task-title {
            font-weight: 600;
            font-size: 0.95rem;
            color: #2c3e50;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.4;
            max-width: 100%;
        }

        .task-description {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 10px;
            line-height: 1.5;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            max-height: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .task-description-expanded {
            max-height: none;
            -webkit-line-clamp: unset;
        }

        .read-more-btn {
            background: none;
            border: none;
            color: #3498db;
            padding: 0;
            font-size: 0.8rem;
            cursor: pointer;
            margin-top: 5px;
            display: inline-block;
        }

        .category-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .due-date-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .due-date-overdue {
            background-color: #c0392b20 !important;
            color: #c0392b !important;
            border: 1px solid #c0392b30 !important;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .due-date-urgent {
            background-color: #e74c3c20 !important;
            color: #e74c3c !important;
            border: 1px solid #e74c3c30 !important;
        }

        .due-date-warning {
            background-color: #e67e2220 !important;
            color: #e67e22 !important;
            border: 1px solid #e67e2230 !important;
        }

        .due-date-upcoming {
            background-color: #f39c1220 !important;
            color: #f39c12 !important;
            border: 1px solid #f39c1230 !important;
        }

        .due-date-normal {
            background-color: #3498db20 !important;
            color: #3498db !important;
            border: 1px solid #3498db30 !important;
        }

        .add-column-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .add-column-btn:hover {
            transform: scale(1.05);
        }

        .drag-handle {
            cursor: move;
            color: #7f8c8d;
        }
        .task-actions {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .task-card:hover .task-actions {
            opacity: 1;
        }

        .color-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .color-option.selected {
            border-color: #000;
        }
        .icon-option {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            cursor: pointer;
            border-radius: 8px;
            border: 2px solid transparent;
            font-size: 18px;
        }
        .icon-option.selected {
            border-color: #3498db;
            background: #ebf5fb;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #95a5a6;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            gap: 10px;
        }
        .task-header-content {
            flex: 1;
            min-width: 0;
        }

        .task-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .no-category {
            color: #95a5a6;
            font-style: italic;
        }

        /* Date picker styling */
        .flatpickr-input {
            background-color: white;
        }

        /* Filter buttons */
        .filter-buttons {
            margin-bottom: 20px;
        }
        .filter-btn.active {
            font-weight: bold;
            background-color: #e3f2fd;
        }

        /* Fix for Select2 in modals */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        /* Scroll hint */
        .scroll-hint {
            position: absolute;
            bottom: 10px;
            right: 20px;
            color: #95a5a6;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, 0.8);
            padding: 5px 10px;
            border-radius: 15px;
            z-index: 10;
        }

        /* Active column indicator */
        .active-column-indicator {
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 2px solid #667eea;
            border-radius: 12px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .column.active-scroll .active-column-indicator {
            opacity: 1;
        }

        /* ==================== DRAG & DROP STYLES ==================== */

        /* Task card during drag */
        .task-card.dragging {
            opacity: 0.5;
            transform: scale(0.95);
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }

        /* Ghost element shown during drag */
        .drag-ghost {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid #667eea;
        }

        /* Column when hovering during drag */
        .column.drag-over {
            background: #f0f4ff;
            border-color: #667eea;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.15);
        }

        /* Source column during drag */
        .column.drag-source {
            opacity: 0.8;
        }

        /* Task list drop zone */
        .tasks-list.drag-over {
            background: #f5f8ff;
            border-radius: 8px;
            min-height: 50px;
        }

        /* Visual feedback after task moved */
        .task-card.task-moved {
            animation: moveSuccess 0.6s ease-out;
        }

        @keyframes moveSuccess {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                background: #d4edda;
                transform: scale(1.02);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Column drag handle hover state */
        .column-header:hover {
            cursor: grab;
        }

        .column-header:active {
            cursor: grabbing;
        }

        /* Task drag handle styles */
        .drag-handle {
            display: inline-flex;
            align-items: center;
            cursor: grab;
            color: #bdc3c7;
            transition: all 0.2s ease;
        }

        .drag-handle:hover {
            color: #667eea;
        }

        .task-card:active .drag-handle {
            cursor: grabbing;
            color: #667eea;
        }

        /* Smooth transitions during drag operations */
        .task-card {
            transition: transform 0.2s ease, opacity 0.2s ease, box-shadow 0.2s ease;
        }

        .column {
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        /* Drag and drop hints */
        .drag-hint {
            display: none;
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            background: #ecf0f1;
            border-radius: 8px;
            margin: 10px 0;
            font-size: 0.9rem;
        }

        .column.drag-over .drag-hint {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Disabled state during drag */
        .kanban-board-container.is-dragging {
            overflow: hidden;
        }

        /* Scrollbar styling during drag */
        .kanban-board-container::-webkit-scrollbar-thumb.drag-active {
            background: #667eea;
        }
    </style>
</head>
<body>
    @include('layouts.header')

    @yield('content')

    @stack('scripts')
</body>
</html>
