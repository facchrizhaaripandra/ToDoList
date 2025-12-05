<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'category_id', 'column_id'];

    protected $with = ['category'];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected $appends = ['urgency_level', 'days_until_due'];

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

    // Accessor untuk urgency level
    public function getUrgencyLevelAttribute()
    {
        if (!$this->due_date) {
            return 'none';
        }

        $daysUntilDue = Carbon::now()->diffInDays($this->due_date, false);

        if ($daysUntilDue < 0) {
            return 'overdue'; // Sudah lewat deadline
        } elseif ($daysUntilDue <= 2) {
            return 'high'; // 2 hari atau kurang
        } elseif ($daysUntilDue <= 7) {
            return 'medium'; // 1 minggu atau kurang
        } elseif ($daysUntilDue <= 14) {
            return 'low'; // 2 minggu atau kurang
        } else {
            return 'none'; // Lebih dari 2 minggu
        }
    }

    // Accessor untuk days until due
    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->due_date, false);
    }

    // Scope untuk tasks yang urgent (due dalam 2 minggu)
    public function scopeUrgent($query)
    {
        return $query->whereNotNull('due_date')
                     ->where('due_date', '<=', Carbon::now()->addWeeks(2));
    }
}
