<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RestructureTodoList extends Migration
{
    public function up()
    {
        // Cek dan hapus tabel subtasks jika ada
        if (Schema::hasTable('subtasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                // Hapus foreign key jika ada
                $table->dropForeignIfExists(['subtask_id']);
            });
            Schema::dropIfExists('subtasks');
        }

        // Hapus kolom subtask_id jika ada
        if (Schema::hasColumn('tasks', 'subtask_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('subtask_id');
            });
        }

        // Buat tabel categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#3498db');
            $table->string('icon')->default('fas fa-folder');
            $table->timestamps();
        });

        // Buat tabel columns
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Tambahkan kolom category_id dan column_id ke tasks
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('description')->constrained()->onDelete('set null');
            $table->foreignId('column_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropForeign(['column_id']);
            $table->dropColumn('column_id');
        });

        Schema::dropIfExists('columns');
        Schema::dropIfExists('categories');
    }
}
