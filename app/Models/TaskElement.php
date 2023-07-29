<?php

namespace App\Models;

use App\Consts\Common as C_Common;
use App\Models\Traits\Nested;
use Database\Factories\TaskElementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskElement extends Model
{
    use HasFactory;
    use Nested;

    protected $table    = 'tasks_elements';
    protected $casts    = [];
    protected $fillable = [
        'task_id',
        'parent_task_element_id',
        'name',
        'content',
        'display_num',
        'hierarchy',
        'completion_flag',
        'register_date',
        'start_date',
        'due_date',
        'completion_date',
        'invalidation_flag',
    ];

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

    public static function createCustom(array $attrs)
    {
        $data = [
            'task_id'                => $attrs['task_id'],
            'parent_task_element_id' => $attrs['parent_task_element_id'],
            'name'                   => $attrs['name'],
            'display_num'            => self::nextDisplayNum(
                $attrs['parent_task_element_id'],
                'parent_task_element_id'
            ),
            'hierarchy'              => self::belongHierarchy($attrs['parent_task_element_id']),
            'content'                => '',
            'completion_flag'        => C_Common::FLAG_OFF,
            'register_date'          => now()->format('Y-m-d'),
            'invalidation_flag'      => C_Common::FLAG_OFF,
        ];
        return static::create($data);
    }
}
