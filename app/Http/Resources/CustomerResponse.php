<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResponse extends JsonResource
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
            'customer_id' => $this->id,
            'name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'registered_at' => $this->registered_at,
        ];
        // return parent::toArray($request);
    }
}
