<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        $completedTasks = Task::where('completed', true)->count();

        return view('tasks.index', compact('tasks', 'completedTasks'));
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

    public function toggle(Task $task)
    {
        $task->update([
            'completed' => !$task->completed
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task status updated!');
    }

    public function filter($filter)
    {
        $query = Task::query();

        switch ($filter) {
            case 'completed':
                $query->where('completed', true);
                break;
            case 'pending':
                $query->where('completed', false);
                break;
            case 'high':
                $query->where('priority', 'high');
                break;
            case 'overdue':
                $query->where('due_date', '<', now())
                      ->where('completed', false);
                break;
        }

        $tasks = $query->latest()->paginate(10);
        $completedTasks = Task::where('completed', true)->count();

        return view('tasks.index', compact('tasks', 'completedTasks'));
    }
}
