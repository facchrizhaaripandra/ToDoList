<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Resource routes dengan semua metode
Route::resource('tasks', TaskController::class);

// Route tambahan untuk toggle complete
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])
    ->name('tasks.toggle');

// Route untuk filter
Route::get('/tasks/filter/{filter}', [TaskController::class, 'filter'])
    ->name('tasks.filter');
