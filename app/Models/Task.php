<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'category_id', 'column_id'];

    protected static function boot()
    {
        parent::boot();

        // Set column_id default jika tidak ada
        static::creating(function ($task) {
            if (!$task->column_id) {
                $task->column_id = Column::first()->id ?? null;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}
