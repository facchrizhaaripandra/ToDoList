<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'category_id', 'column_id'];

    protected $with = ['category']; // Auto-load category

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'No Category',
            'color' => '#95a5a6',
            'icon' => 'fas fa-question'
        ]);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}
