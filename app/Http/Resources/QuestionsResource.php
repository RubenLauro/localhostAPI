<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class QuestionsResource extends Resource
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
            'id' => $this->id,
            'place_name' => $this->place->name,
            'place_image_url' => $this->place->image_url,
            'place_city' => $this->place->city,
            'isMine' => $this->isMine
        ];
    }
}
