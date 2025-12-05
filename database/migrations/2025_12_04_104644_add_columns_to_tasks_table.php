<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Add columns only if they don't already exist to avoid duplicate column errors
        if (!Schema::hasColumn('tasks', 'status')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->enum('status', ['To Do', 'In Progress', 'Done'])->default('To Do');
            });
        }

        if (!Schema::hasColumn('tasks', 'category')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('category')->nullable(); // Design, Development, Research
            });
        }

        if (!Schema::hasColumn('tasks', 'priority')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->string('priority')->default('Medium'); // High, Medium, Low
            });
        }

        if (!Schema::hasColumn('tasks', 'subtasks_total')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->integer('subtasks_total')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns if they exist
        if (Schema::hasColumn('tasks', 'subtasks_total')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('subtasks_total');
            });
        }

        if (Schema::hasColumn('tasks', 'subtasks_completed')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('subtasks_completed');
            });
        }

        if (Schema::hasColumn('tasks', 'due_date')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('due_date');
            });
        }

        if (Schema::hasColumn('tasks', 'priority')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }

        if (Schema::hasColumn('tasks', 'category')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }

        if (Schema::hasColumn('tasks', 'status')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
