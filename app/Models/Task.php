<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'category',
        'priority',
        'due_date',
        'subtasks_total',
        'subtasks_completed'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];
}
