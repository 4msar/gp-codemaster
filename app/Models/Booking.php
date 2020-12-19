<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * Fillable properties
     * @var array
     */
    protected $fillable = [ 'room_id', 'room_number', 'arrival', 'checkout', 'customer_id', 'book_type', 'book_time' ];

    /**
     * Cast the value after render
     * @var array
     */
    protected $casts = [ 'arrival' => 'datetime', 'checkout' => 'datetime', 'book_time' => 'datetime' ];

    /**
     * Relation with customer
     * @return mixed Relation Instance
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relation with room
     * @return mixed Relation Instance
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Relation with payment
     * @return mixed Relation Instance
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id',);
    }
}
