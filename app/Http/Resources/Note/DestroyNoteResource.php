<?php

namespace App\Http\Resources\Note;

use Illuminate\Http\Resources\Json\JsonResource;

class DestroyNoteResource extends JsonResource
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
            'delete_note_id' => $this['delete_note_id'],
        ];
    }
}
