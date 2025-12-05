<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50'
        ]);

        Category::create($request->all());

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:50'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json(['success' => true]);
    }
}
