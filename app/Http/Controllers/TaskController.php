<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Column;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $columns = Column::with(['tasks' => function($query) {
            $query->with('category');
        }])->orderBy('order')->get();

        $categories = Category::all();

        return view('tasks.index', compact('columns', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'column_id' => 'required|exists:columns,id'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'category_id' => $request->category_id ?: null,
            'column_id' => $request->column_id
        ]);

        $task->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Task added successfully!',
            'task' => $task
        ]);
    }

    public function show($id)
    {
        $task = Task::with('category')->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'column_id' => 'required|exists:columns,id'
        ]);

        $task = Task::findOrFail($id);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'category_id' => $request->category_id ?: null,
            'column_id' => $request->column_id
        ]);

        $task->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true]);
    }

    public function updateColumn(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update(['column_id' => $request->column_id]);

        return response()->json(['success' => true]);
    }
}
