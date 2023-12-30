<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class
RcCar extends Model
{
    use HasFactory;

    protected $fillable  = ['car_id','car_model_id', 'registration_number', 'created_at'];

}
