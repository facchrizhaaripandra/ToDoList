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
        // Pastikan ada minimal satu kolom
        if (Column::count() === 0) {
            Column::create(['name' => 'To Do', 'order' => 1]);
            Column::create(['name' => 'In Progress', 'order' => 2]);
            Column::create(['name' => 'Done', 'order' => 3]);
        }

        $columns = Column::with('tasks.category')->orderBy('order')->get();
        $categories = Category::all();

        return view('tasks.index', compact('columns', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'column_id' => 'nullable|exists:columns,id'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id
        ];

        // Jika column_id tidak ada, gunakan kolom pertama
        if ($request->column_id) {
            $data['column_id'] = $request->column_id;
        } else {
            $data['column_id'] = Column::first()->id;
        }

        Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Task added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'column_id' => 'required|exists:columns,id'
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'column_id' => $request->column_id
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    // Method untuk update kolom task (drag & drop)
    public function updateColumn(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update(['column_id' => $request->column_id]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $task = Task::with('category', 'column')->findOrFail($id);
        return response()->json($task);
    }
}
