<?php

namespace App\Models;

use Database\Factories\NoteSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteSetting extends Model
{
    use HasFactory;

    protected $table = 'notes_settings';

    protected $fillable = [
        'user_id',
        'editor_option',
        'editor_css',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    protected static function newFactory()
    {
        return NoteSettingFactory::new();
    }
}
