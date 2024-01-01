<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class
RcCar extends Model
{
    use HasFactory;

    protected $fillable  = ['car_id','car_model_id', 'registration_number', 'created_at'];

    public function carWithModel(){
        return $this->hasOne(RcCarsModel::class,'car_model_id','car_model_id')
            ->select('car_model_id', 'car_brand_id', 'slug')
            ->with('modelWithBrand');
    }

}
