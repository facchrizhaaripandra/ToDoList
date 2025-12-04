<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data safely (disable foreign key checks during truncation)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate dependent tables first (checklist_items, then subtasks)
        if (Schema::hasTable('checklist_items')) {
            DB::table('checklist_items')->truncate();
        }
        if (Schema::hasTable('subtasks')) {
            DB::table('subtasks')->truncate();
        }

        // Then truncate tasks
        if (Schema::hasTable('tasks')) {
            Task::truncate();
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tasks = [
            [
                'title' => 'Design homepage mockup',
                'description' => 'Create a modern homepage design with hero section and key...',
                'status' => 'To Do',
                'category' => 'Design',
                'priority' => 'High',
                'due_date' => '2025-12-10',
                'subtasks_total' => 3,
                'subtasks_completed' => 0,
            ],
            [
                'title' => 'Create database schema',
                'description' => 'Design and implement the database structure',
                'status' => 'To Do',
                'category' => 'Development',
                'priority' => 'High',
                'due_date' => '2025-12-12',
                'subtasks_total' => 3,
                'subtasks_completed' => 0,
            ],
            [
                'title' => 'Set up project repository',
                'description' => 'Initialize git repo and configure CI/CD pipeline',
                'status' => 'In Progress',
                'category' => 'Development',
                'priority' => 'Medium',
                'due_date' => '2025-12-08',
                'subtasks_total' => 2,
                'subtasks_completed' => 0,
            ],
            [
                'title' => 'Research user requirements',
                'description' => 'Conduct user interviews and gather requirements',
                'status' => 'Done',
                'category' => 'Research',
                'priority' => 'Medium',
                'due_date' => '2025-12-05',
                'subtasks_total' => 2,
                'subtasks_completed' => 2,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
