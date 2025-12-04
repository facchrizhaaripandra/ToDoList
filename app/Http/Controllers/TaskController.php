<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function board()
    {
        $tasks = Task::all();
        $todoCount = Task::where('status', 'To Do')->count();
        $inProgressCount = Task::where('status', 'In Progress')->count();
        $doneCount = Task::where('status', 'Done')->count();

        return view('tasks.board', compact('tasks', 'todoCount', 'inProgressCount', 'doneCount'));
    }

    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);

            return response()->json([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'category' => $task->category,
                'priority' => $task->priority,
                'due_date' => $task->due_date,
                'subtasks_total' => $task->subtasks_total,
                'subtasks_completed' => $task->subtasks_completed,
                'created_at' => $task->created_at ? $task->created_at->toDateTimeString() : null,
                'updated_at' => $task->updated_at ? $task->updated_at->toDateTimeString() : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Task not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:To Do,In Progress,Done'
            ]);

            $task = Task::findOrFail($id);
            $task->status = $request->status;
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully',
                'todoCount' => Task::where('status', 'To Do')->count(),
                'inProgressCount' => Task::where('status', 'In Progress')->count(),
                'doneCount' => Task::where('status', 'Done')->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating task status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:To Do,In Progress,Done',
            'category' => 'nullable|string',
            'priority' => 'required|in:High,Medium,Low',
            'due_date' => 'nullable|date',
            'subtasks_total' => 'nullable|integer|min:0',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'category' => $request->category,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'subtasks_total' => $request->subtasks_total ?? 0,
            'subtasks_completed' => 0,
        ]);

        return redirect()->route('tasks.board')->with('success', 'Task created successfully!');
    }

    public function index()
    {
        return redirect()->route('tasks.board');
    }

    public function create()
    {
        return redirect()->route('tasks.board');
    }

    public function edit($id)
    {
        return redirect()->route('tasks.board');
    }

    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);

            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'sometimes|required|in:To Do,In Progress,Done',
                'category' => 'nullable|string',
                'priority' => 'sometimes|required|in:High,Medium,Low',
                'due_date' => 'nullable|date',
                'subtasks_total' => 'nullable|integer|min:0',
                'subtasks_completed' => 'nullable|integer|min:0',
            ]);

            $task->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating task: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting task: ' . $e->getMessage()
            ], 500);
        }
    }
}
