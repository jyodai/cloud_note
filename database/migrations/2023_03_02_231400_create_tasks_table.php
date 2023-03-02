<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_task_id');
            $table->integer('note_id');
            $table->string('note_type');
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
        Schema::dropIfExists('tasks');
    }
}
