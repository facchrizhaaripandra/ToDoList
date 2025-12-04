<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        // Base query
        $query = Task::query();

        // Apply filter if specified
        if ($filter) {
            switch ($filter) {
                case 'pending':
                    $query->where('completed', false);
                    break;
                case 'progress':
                    $query->where('priority', 'high')->where('completed', false);
                    break;
                case 'completed':
                    $query->where('completed', true);
                    break;
            }
        }

        // Get paginated tasks
        $tasks = $query->latest()->paginate(12);

        // Get counts for stats
        $pendingCount = Task::where('completed', false)->count();
        $inProgressCount = Task::where('priority', 'high')->where('completed', false)->count();
        $completedCount = Task::where('completed', true)->count();

        // Get tasks for each column
        if (!$filter) {
            // Show all columns with limited tasks
            $pendingTasks = Task::where('completed', false)
                ->orderBy('due_date', 'asc')
                ->orderBy('priority', 'desc')
                ->limit(10)
                ->get();

            $inProgressTasks = Task::where('priority', 'high')
                ->where('completed', false)
                ->orderBy('due_date', 'asc')
                ->limit(8)
                ->get();

            $completedTasks = Task::where('completed', true)
                ->orderBy('updated_at', 'desc')
                ->limit(6)
                ->get();
        } else {
            // If filtered, use paginated tasks
            $pendingTasks = $filter == 'pending' ? $tasks->items() : [];
            $inProgressTasks = $filter == 'progress' ? $tasks->items() : [];
            $completedTasks = $filter == 'completed' ? $tasks->items() : [];

            // Convert to collection
            $pendingTasks = collect($pendingTasks);
            $inProgressTasks = collect($inProgressTasks);
            $completedTasks = collect($completedTasks);
        }

        return view('tasks.index', compact(
            'tasks',
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'filter'
        ));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'completed' => 'boolean'
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'completed' => 'boolean'
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    public function toggle(Request $request, Task $task)
    {
        $completed = $request->get('completed', !$task->completed);

        $task->update([
            'completed' => $completed
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully'
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task status updated!');
    }
}
