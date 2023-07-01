<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteSetting extends Model
{
    protected $table = 'notes_settings';

    protected $fillable = [
        'user_id',
        'editor_option',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];
}
