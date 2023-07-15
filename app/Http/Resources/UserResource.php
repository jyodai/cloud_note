<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $includeAttrs;

    public function __construct($resource, array $includeAttrs = [])
    {
        parent::__construct($resource);
        $this->includeAttrs = $includeAttrs;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'user_type'    => $this->user_type,
            'email'        => $this->email,
            'note_setting' => $this->noteSetting,
            'api_token'    => $this->when(in_array('api_token', $this->includeAttrs), $this->api_token),
        ];
    }
}
