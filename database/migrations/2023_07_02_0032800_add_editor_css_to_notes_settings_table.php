<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\NoteSetting;

class AddEditorCssToNotesSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('notes_settings', function (Blueprint $table) {
            $table->text('editor_css')->after('editor_option');
        });
        $this->dml();
    }

    public function down()
    {
        Schema::table('notes_settings', function (Blueprint $table) {
            $table->dropColumn('editor_css');
        });
    }

    public function dml()
    {
        $settings = NoteSetting::all();
        foreach ($settings as $setting) {
            $setting->editor_css = '';
            $setting->save();
        }
    }
}
