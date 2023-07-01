<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditorCssToNotesSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('notes_settings', function (Blueprint $table) {
            $table->text('editor_css')->after('editor_option');
        });
    }

    public function down()
    {
        Schema::table('notes_settings', function (Blueprint $table) {
            $table->dropColumn('editor_css');
        });
    }
}
