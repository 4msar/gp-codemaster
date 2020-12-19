<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    /**
     * Fillable properties
     * @var array
     */
    protected $fillable = ['room_number', 'price', 'locked', 'max_persons', 'room_type'];

    /**
     * Check the room is locked
     * @return boolean 
     */
    public function isLocked()
    {
        return $this->locked == true;
    }
}
