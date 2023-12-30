<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcCarsModels extends Model
{
    use HasFactory;
    protected $fillable  = ['car_model_id', 'registration_number', 'created_at'];
}
