<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// Task Board Routes
Route::get('/board', [TaskController::class, 'board'])->name('tasks.board');
Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

// Resource Routes for CRUD
Route::resource('tasks', TaskController::class)->except(['show']);

// Home page redirect to board
Route::redirect('/', '/board');
