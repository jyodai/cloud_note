<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\NoteContent;
use App\Consts\Note as C_Note;

class AddNoteTypeToNotesContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes_contents', function (Blueprint $table) {
            $table->integer('note_type')->after('note_id')->default(null);
        });

        $notes = NoteContent::get();
        foreach ($notes as $note) {
            $note->note_type = C_Note::NOTE_TYPE_NORMAL;
            $note->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes_contents', function (Blueprint $table) {
            $table->dropColumn('note_type');
        });
    }
}
