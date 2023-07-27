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

    public static function getChildren($id): array
    {
        $ret          = [];
        $taskElements = self::where('parent_task_element_id', $id)->get();
        foreach ($taskElements as $taskElement) {
            array_push($ret, $taskElement);
            $children = self::getChildren($taskElement->id);
            foreach ($children as $child) {
                array_push($ret, $child);
            }
        }
        return $ret;
    }
}
