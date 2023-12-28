<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcBooking extends Model
{
    use HasFactory;

    protected $fillable  = ['booking_id', 'car_id', 'source'];


}
