<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Note;
use App\Models\NoteContent;

class ConvertImageTokenNotesContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users   = \App\Models\User::get();
        $replace = '%cn_api_token%';
        foreach ($users as $user) {
            $notes = Note::get()->where('user_id', $user->id);
            foreach ($notes as $note) {
                $noteContent = NoteContent::where('note_id', $note->id)->first();
                $pattern     = $user->api_image_token;

                if (strpos($noteContent->content, $pattern)) {
                    $replaceContent       = str_replace($pattern, $replace, $noteContent->content);
                    $noteContent->content = $replaceContent;
                    $noteContent->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $users   = \App\Models\User::get();
        $pattern = '%cn_api_token%';
        foreach ($users as $user) {
            $notes = Note::get()->where('user_id', $user->id);
            foreach ($notes as $note) {
                $noteContent = NoteContent::where('note_id', $note->id)->first();
                $replace     = $user->api_image_token;

                if (strpos($noteContent->content, $pattern)) {
                    $replaceContent       = str_replace($pattern, $replace, $noteContent->content);
                    $noteContent->content = $replaceContent;
                    $noteContent->save();
                }
            }
        }
    }
}
