<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColumnController;

// Route utama
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// Routes untuk Task
Route::prefix('tasks')->group(function () {
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/{task}/update-column', [TaskController::class, 'updateColumn'])->name('tasks.updateColumn');
});

// Routes untuk Category
Route::prefix('categories')->group(function () {
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Routes untuk Column
Route::prefix('columns')->group(function () {
    Route::post('/', [ColumnController::class, 'store'])->name('columns.store');
    Route::put('/{column}', [ColumnController::class, 'update'])->name('columns.update');
    Route::delete('/{column}', [ColumnController::class, 'destroy'])->name('columns.destroy');
    Route::post('/reorder', [ColumnController::class, 'reorder'])->name('columns.reorder');
});
