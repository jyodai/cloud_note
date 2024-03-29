<?php

namespace App\Models;

use App\Consts\Note as C_Note;
use App\Models\TaskElement;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $casts = [];

    protected static function newFactory()
    {
        return TaskFactory::new();
    }

    public function create($note)
    {
         $this->note_id           = $note['id'];
         $this->note_type         = C_Note::NOTE_TYPE_TASK;
         $this->invalidation_flag = 0;
         $this->save();
    }

    public static function getTree(int $id, ?bool $completionFlag = null)
    {
        $allElements = TaskElement::where('task_id', $id)
                     ->orderBy('display_num', 'asc')
                     ->get();

        $groupedElements = $allElements->groupBy('parent_task_element_id');

        return self::buildTree($groupedElements, $completionFlag);
    }

    private static function buildTree($groupedElements, ?bool $completionFlag = null, int $elementId = 0)
    {
        $collect = $groupedElements->get($elementId, collect());
        return $collect->map(
            function ($element) use ($groupedElements, $completionFlag) {
                if (
                    $completionFlag !== null &&
                    ($element->parent_task_element_id === 0 && $element->completion_flag !== $completionFlag)
                ) {
                    return;
                }
                $element->children = self::buildTree($groupedElements, $completionFlag, $element->id);
                return $element;
            }
        )->filter();
    }
}
