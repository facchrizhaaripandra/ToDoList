<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->enum('status', ['To Do', 'In Progress', 'Done'])->default('To Do');
            }
            if (!Schema::hasColumn('tasks', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->enum('priority', ['High', 'Medium', 'Low'])->default('Medium');
            }
            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->date('due_date')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'subtasks_total')) {
                $table->integer('subtasks_total')->default(0);
            }
            if (!Schema::hasColumn('tasks', 'subtasks_completed')) {
                $table->integer('subtasks_completed')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Optional: Add drop columns if needed
        });
    }
};
