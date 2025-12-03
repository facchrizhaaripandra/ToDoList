<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('is_completed', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $completedCount = Task::where('is_completed', true)->count();
        $pendingCount = Task::where('is_completed', false)->count();
        $totalCount = Task::count();

        return view('tasks.index', compact('tasks', 'completedCount', 'pendingCount', 'totalCount'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil ditambahkan! 🎉');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil diupdate! ✨');
    }

    public function toggleComplete(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task berhasil dihapus! 🗑️');
    }
}
