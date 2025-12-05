<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Column;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat kategori default
        $categories = [
            ['name' => 'Personal', 'color' => '#3498db', 'icon' => 'fas fa-user'],
            ['name' => 'Work', 'color' => '#e74c3c', 'icon' => 'fas fa-briefcase'],
            ['name' => 'Shopping', 'color' => '#2ecc71', 'icon' => 'fas fa-shopping-cart'],
            ['name' => 'Health', 'color' => '#9b59b6', 'icon' => 'fas fa-heartbeat'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Buat kolom default
        $columns = [
            ['name' => 'To Do', 'order' => 1],
            ['name' => 'In Progress', 'order' => 2],
            ['name' => 'Done', 'order' => 3],
        ];

        foreach ($columns as $column) {
            Column::create($column);
        }
    }
}
