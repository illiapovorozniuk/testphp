<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcBooking extends Model
{
    use HasFactory;

    protected $fillable  = ['booking_id', 'car_id', 'start_date', 'end_date'];

    public function car(){
        return $this->hasOne(RcCar::class,'car_id','car_id')->select('car_id','car_model_id', 'registration_number', 'created_at');
    }
    public static function getYears(){
        return RcBooking::selectRaw('YEAR(created_at) as year')->distinct()->get();
    }


}
