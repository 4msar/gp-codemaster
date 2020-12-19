<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * Fillable properties
     * @var array
     */
    protected $fillable = [ 'booking_id', 'customer_id', 'amount', 'due', 'date' ];

    /**
     * Relation with customer
     * @return mixed Relation Instance
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relation with bookings
     * @return mixed Relation Instance
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

}
