<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Redirect root to tasks
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Task Routes
Route::prefix('tasks')->group(function () {
    // Index with filter
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

    // Create
    Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');

    // Edit
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');

    // Delete
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Toggle complete
    Route::patch('/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

    // Show single task (optional)
    Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
});
