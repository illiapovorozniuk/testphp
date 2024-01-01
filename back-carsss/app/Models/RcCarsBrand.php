<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RcCarsBrand extends Model
{
    use HasFactory;

    protected $fillable = ['car_brand_id', 'slug'];
}
