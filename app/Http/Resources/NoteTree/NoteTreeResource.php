<?php

namespace App\Http\Resources\NoteTree;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteTreeResource extends JsonResource
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
            'id'                => $this->id,
            'parent_note_id'    => $this->parent_note_id,
            'user_id'           => $this->user_id,
            'note_type'         => $this->note_type,
            'title'             => $this->title,
            'display_num'       => $this->display_num,
            'hierarchy'         => $this->hierarchy,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'invalidation_flag' => $this->invalidation_flag,
            'children'          => $this->children,
            'path'              => $this->path,
            'hasChild'          => $this->hasChild,
        ];
    }
}
