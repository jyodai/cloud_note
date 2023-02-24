<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Models\Note;
use \App\Models\NoteContent;

class ConvertLinkNotesContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\Models\User::get();
        foreach ($users as $user) {
            $notes = Note::get()->where('user_id', $user->id);
            foreach ($notes as $note) {
                $noteContent = NoteContent::where('note_id', $note->id)->first();
                $urlPattern = 'memo-web/backend/public/api/image-show?';
                $urlReplace = 'memo-web/backend/public/api/libraries/files?';
                $queryPattern = '&token=' . $user->api_image_token;
                $queryReplace = '&type=show&token=' . $user->api_image_token;
                
                if (strpos($noteContent->content, $urlPattern)
                    && !strpos($noteContent->content, $urlReplace)
                ) {
                    $replaceContent = str_replace($urlPattern, $urlReplace, $noteContent->content);
                    $replaceContent = str_replace($queryPattern, $queryReplace, $replaceContent);
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
        $users = \App\Models\User::get();
        foreach ($users as $user) {
            $notes = Note::get()->where('user_id', $user->id);
            foreach ($notes as $note) {
                $noteContent = NoteContent::where('note_id', $note->id)->first();
                $urlReplace = 'memo-web/backend/public/api/image-show?';
                $urlPattern = 'memo-web/backend/public/api/libraries/files?';
                $queryPattern = '&type=show&token=' . $user->api_image_token;
                $queryReplace = '&token=' . $user->api_image_token;
                
                if (strpos($noteContent->content, $urlPattern)
                    && !strpos($noteContent->content, $urlReplace)
                ) {
                    $replaceContent = str_replace($urlPattern, $urlReplace, $noteContent->content);
                    $replaceContent = str_replace($queryPattern, $queryReplace, $replaceContent);
                    $noteContent->content = $replaceContent;
                    $noteContent->save();
                }
            }
        }
    }
}
