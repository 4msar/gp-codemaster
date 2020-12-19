<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'booking_id' => $this->id,
            'customer_name' => $this->customer->full_name,
            'room_number' => $this->room_number,
            'arrival_time' => $this->arrival->format('d-m-Y h:ia'),
            'checkout_time' => $this->checkout->format('d-m-Y h:ia'),
            'total_paid' => $this->payment->amount ?? 0
        ];
        if( $this->payment->due ?? 0 > 0 ){
            $data['due_amount'] = $this->payment->due ?? 0;
        }
        return $data;
        // return parent::toArray($request);
    }
}
