<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueDateToTasks extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('description');
            $table->boolean('is_urgent')->default(false)->after('due_date');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'is_urgent']);
        });
    }
}
