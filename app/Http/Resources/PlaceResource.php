<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PlaceResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'average_rating' => $this->average_rating,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'city' => $this->city,
            'review' => $this->reviews,
            'qt_reviews' => $this->qt_reviews
        ];
    }
}
