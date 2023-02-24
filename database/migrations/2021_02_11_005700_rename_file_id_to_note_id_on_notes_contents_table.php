<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFileIdToNoteIdOnNotesContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes_contents', function (Blueprint $table) {
            $table->renameColumn('file_id', 'note_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes_contents', function (Blueprint $table) {
            $table->renameColumn('note_id', 'file_id');
        });
    }
}
