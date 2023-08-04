<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameParentTaskIdToParentTaskElementIdInTasksElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_elements', function (Blueprint $table) {
            $table->renameColumn('parent_task_id', 'parent_task_element_id');
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
            $table->renameColumn('parent_task_element_id', 'parent_task_id');
        });
    }
}
