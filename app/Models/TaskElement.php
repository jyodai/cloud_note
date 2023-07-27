<?php

namespace App\Models;

use Database\Factories\TaskElementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskElement extends Model
{
    use HasFactory;

    protected $table = 'tasks_elements';
    protected $casts = [];

    protected static function newFactory()
    {
        return TaskElementFactory::new();
    }
}
