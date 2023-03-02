<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_elements', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->integer('parent_task_id');
            $table->string('name');
            $table->text('content');
            $table->integer('completion_flag');
            $table->date('register_date');
            $table->date('completion_date');
            $table->timestamps();
            $table->integer('invalidation_flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_elements');
    }
}
