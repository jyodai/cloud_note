<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\NoteSetting;

class CreateNotesSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('notes_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('editor_option');
            $table->timestamps();
        });
        $this->dml();
    }

    public function down()
    {
        Schema::dropIfExists('notes_settings');
    }

    public function dml()
    {
        $users = User::all();
        foreach ($users as $user) {
            $data = [
                'user_id'       => $user->id,
                'editor_option' => '{}',
            ];
            NoteSetting::create($data);
        }
    }
}
