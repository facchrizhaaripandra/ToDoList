<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkannya
            if (!Schema::hasColumn('tasks', 'completed')) {
                $table->boolean('completed')->default(false)->after('description');
            }

            if (!Schema::hasColumn('tasks', 'due_date')) {
                $table->date('due_date')->nullable()->after('completed');
            }

            if (!Schema::hasColumn('tasks', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('due_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Hapus kolom jika diperlukan
            $table->dropColumn(['completed', 'due_date', 'priority']);
        });
    }
};
