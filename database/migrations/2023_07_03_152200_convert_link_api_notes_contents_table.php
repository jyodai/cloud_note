<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\NoteContent;

class ConvertLinkApiNotesContentsTable extends Migration
{
    public function up()
    {
        $contents = NoteContent::all();
        foreach ($contents as $content) {
            $urlPattern       = '/public/api/libraries/files?';
            $urlReplace       = '/public/api/libraries/image?';
            $replaceContent   = str_replace($urlPattern, $urlReplace, $content->content);
            $content->content = $replaceContent;
            $content->save();
        }
    }

    public function down()
    {
        $contents = NoteContent::all();
        foreach ($contents as $content) {
            $urlPattern       = '/public/api/libraries/image?';
            $urlReplace       = '/public/api/libraries/files?';
            $replaceContent   = str_replace($urlPattern, $urlReplace, $content->content);
            $content->content = $replaceContent;
            $content->save();
        }
    }
}
