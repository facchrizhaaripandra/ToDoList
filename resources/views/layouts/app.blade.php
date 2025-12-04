<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --todo-color: #e3f2fd;
            --progress-color: #fff3e0;
            --done-color: #e8f5e9;
            --design-color: #e3f2fd;
            --development-color: #f3e5f5;
            --research-color: #e8f5e9;
            --high-priority: #ffebee;
            --medium-priority: #fff3e0;
            --low-priority: #e8f5e9;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .task-board-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .header-subtitle {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .filter-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .filter-label {
            font-weight: 500;
            color: #34495e;
            font-size: 14px;
        }

        .filter-checkboxes {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #bdc3c7;
            cursor: pointer;
        }

        .filter-checkbox input[type="checkbox"]:checked {
            background-color: #3498db;
            border-color: #3498db;
        }

        .filter-checkbox label {
            font-weight: 500;
            color: #2c3e50;
            cursor: pointer;
            font-size: 15px;
        }

        hr {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, #dfe6e9, transparent);
            margin: 30px 0;
        }

        .column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 15px;
        }

        .todo-header {
            background-color: var(--todo-color);
            color: #1565c0;
        }

        .progress-header {
            background-color: var(--progress-color);
            color: #ef6c00;
        }

        .done-header {
            background-color: var(--done-color);
            color: #2e7d32;
        }

        .column-title {
            font-weight: 600;
            font-size: 18px;
        }

        .task-count {
            background: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .task-column {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            min-height: 600px;
            padding-bottom: 20px;
            transition: all 0.3s;
        }

        .task-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin: 0 15px 15px 15px;
            transition: all 0.3s;
            position: relative;
            cursor: pointer;
        }

        .task-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
            border-color: #3498db;
        }

        .task-checkbox {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .task-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 2px solid #ddd;
            cursor: pointer;
        }

        .task-title {
            font-weight: 600;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 8px;
            padding-right: 30px;
        }

        .task-description {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
            min-height: 40px;
        }

        .task-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .task-tag {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tag-design {
            background-color: var(--design-color);
            color: #1565c0;
        }

        .tag-development {
            background-color: var(--development-color);
            color: #7b1fa2;
        }

        .tag-research {
            background-color: var(--research-color);
            color: #2e7d32;
        }

        .tag-high-priority {
            background-color: var(--high-priority);
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .tag-medium-priority {
            background-color: var(--medium-priority);
            color: #ef6c00;
        }

        .tag-low-priority {
            background-color: var(--low-priority);
            color: #2e7d32;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }

        .task-date {
            font-size: 13px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .task-subtasks {
            font-size: 13px;
            color: #636e72;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 500;
        }

        .add-task-btn {
            background: transparent;
            border: 2px dashed #ddd;
            color: #7f8c8d;
            padding: 15px;
            border-radius: 10px;
            width: calc(100% - 30px);
            margin: 0 15px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-task-btn:hover {
            border-color: #3498db;
            color: #3498db;
            background: rgba(52, 152, 219, 0.05);
        }

        .drag-over {
            background: rgba(52, 152, 219, 0.1);
            border: 2px dashed #3498db;
        }

        .empty-column {
            text-align: center;
            padding: 40px 20px;
            color: #bdc3c7;
            font-size: 14px;
        }

        .empty-column i {
            font-size: 40px;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        .task-card.selected {
            border: 2px solid #3498db;
            background-color: #f8fafc;
        }

        .task-card.selected .task-title {
            color: #3498db;
        }

        input[type="checkbox"]:indeterminate {
            background-color: #3498db;
            border-color: #3498db;
        }

        .select-all-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .select-all-label {
            font-weight: 500;
            color: #2c3e50;
            font-size: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="task-board-container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
