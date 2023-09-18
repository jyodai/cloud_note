<?php

namespace App\Http\Resources\Canvas;

use Illuminate\Http\Resources\Json\JsonResource;

class CanvasResource extends JsonResource
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
            'note_type' => $this->note_type,
            'content'   => $this->content,
        ];
    }
}
