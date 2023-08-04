<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateAndDueDateToTasksElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_elements', function (Blueprint $table) {
            $table->date('start_date')->after('register_date');
            $table->date('due_date')->after('start_date');
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
            $table->dropColumn('start_date');
            $table->dropColumn('due_date');
        });
    }
}
