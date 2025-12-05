<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Buat task sample
        $tasks = [
            [
                'title' => 'Belajar Laravel',
                'description' => 'Pelajari dasar-dasar framework Laravel untuk pengembangan web',
                'due_date' => now()->addDays(7),
                'category_id' => 1, // Personal
                'column_id' => 1, // To Do
            ],
            [
                'title' => 'Membuat Aplikasi Todo',
                'description' => 'Buat aplikasi todo list dengan fitur drag & drop',
                'due_date' => now()->addDays(14),
                'category_id' => 2, // Work
                'column_id' => 2, // In Progress
            ],
            [
                'title' => 'Beli Bahan Makanan',
                'description' => 'Beli sayur, buah, dan bahan makanan untuk seminggu',
                'due_date' => now()->addDays(2),
                'category_id' => 3, // Shopping
                'column_id' => 1, // To Do
            ],
            [
                'title' => 'Olahraga Rutin',
                'description' => 'Lakukan olahraga selama 30 menit setiap hari',
                'due_date' => now()->addDays(1),
                'category_id' => 4, // Health
                'column_id' => 1, // To Do
            ],
            [
                'title' => 'Review Kode',
                'description' => 'Review dan refactor kode aplikasi untuk performa yang lebih baik',
                'due_date' => now()->addDays(5),
                'category_id' => 2, // Work
                'column_id' => 3, // Done
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
