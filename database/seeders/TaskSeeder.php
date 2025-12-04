<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Clear existing tasks
        Task::truncate();

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
