<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResponse extends JsonResource
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
            'room_id' => $this->id,
            'room_number' => $this->room_number,
            'price' => $this->price,
            // 'locked' => $this->locked,
            'max_persons' => $this->max_persons,
            'room_type' => $this->room_type,
        ];
        // return parent::toArray($request);
    }
}
