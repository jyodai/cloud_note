<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class TreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'task_id'                => $this->task_id,
            'parent_task_element_id' => $this->parent_task_element_id,
            'name'                   => $this->name,
            'content'                => $this->content,
            'display_num'            => $this->display_num,
            'hierarchy'              => $this->hierarchy,
            'completion_flag'        => $this->completion_flag,
            'register_date'          => $this->register_date,
            'start_date'             => $this->start_date,
            'due_date'               => $this->due_date,
            'completion_date'        => $this->completion_date,
            'children'               => $this->children,
            'hasChild'               => $this->hasChild,
        ];
    }
}
