<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompletionStartDateDueDateToTasksElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_elements', function (Blueprint $table) {
            $table->date('completion_date')->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->date('due_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_elements', function (Blueprint $table) {
            $table->date('completion_date')->nullable(false)->change();
            $table->date('start_date')->nullable(false)->change();
            $table->date('due_date')->nullable(false)->change();
        });
    }
}
