<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteContentResource extends JsonResource
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
            'id'        => $this->id,
            'note_id'   => $this->note_id,
            'content'   => $this->content,
            'note_type' => $this->note_type,
        ];
    }
}
