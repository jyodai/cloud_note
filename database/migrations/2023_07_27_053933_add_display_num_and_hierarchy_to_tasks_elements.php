<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisplayNumAndHierarchyToTasksElements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_elements', function (Blueprint $table) {
            $table->integer('display_num')->after('content');
            $table->integer('hierarchy')->after('display_num');
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
            $table->dropColumn('display_num');
            $table->dropColumn('hierarchy');
        });
    }
}
