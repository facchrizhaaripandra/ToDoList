<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'title' => 'Complete Laravel Project',
            'description' => 'Finish the ToDo List application',
            'completed' => false,
            'due_date' => now()->addDays(3),
            'priority' => 'high'
        ]);

        Task::create([
            'title' => 'Buy Groceries',
            'description' => 'Milk, Eggs, Bread, Fruits',
            'completed' => true,
            'due_date' => now()->subDays(1),
            'priority' => 'medium'
        ]);

        Task::create([
            'title' => 'Read Book',
            'description' => 'Finish reading Clean Code',
            'completed' => false,
            'due_date' => now()->addWeek(),
            'priority' => 'low'
        ]);
    }
}
