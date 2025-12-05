<?php

namespace App\Http\Controllers;

use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $lastColumn = Column::orderBy('order', 'desc')->first();
        $order = $lastColumn ? $lastColumn->order + 1 : 1;

        Column::create([
            'name' => $request->name,
            'order' => $order
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $column = Column::findOrFail($id);
        $column->update(['name' => $request->name]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $column = Column::findOrFail($id);
        $defaultColumn = Column::where('id', '!=', $id)->first();

        if ($defaultColumn) {
            // Pindahkan semua task ke kolom default
            $column->tasks()->update(['column_id' => $defaultColumn->id]);
        }

        $column->delete();

        // Reorder kolom yang tersisa
        $columns = Column::orderBy('order')->get();
        foreach ($columns as $index => $col) {
            $col->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $columnId) {
            Column::where('id', $columnId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
