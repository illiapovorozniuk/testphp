<?php

namespace App\Http\Controllers;

use App\Models\RcBooking;
use App\Models\RcCar;
use App\Models\RcCarsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarFilteringController extends Controller
{
    //
    function getFilteringData(Request $request){
//        ['booking_id', '=', $request->input()['id']]
        $whereArr = [['start_date', '>=', 2021 . '-01-01'],['start_date', '<=', 2022 . '-12-31']];
        array_push($whereArr,['status','=',1]);
        array_push($whereArr,['company_id','=',1]);
        $bookings = RcBooking::select('booking_id', 'car_id','created_at','start_date','end_date')->where($whereArr)
            ->with('carWithModel')
            ->get();
        $cars =[];
        foreach($bookings as $booking){
            $cars[] = $booking->car;
        }
        return $bookings;
    }

    function getTemptest(){
//        $cars = RcCarsModel::select('car_model_id',"slug")->where('car_model_id',"<",50)->get();
        $cars = RcCar::select("car_id","car_model_id")->where('car_id','<',50)->with('model')->get();
//            ->get();
        return $cars;
    }
    function getYears(){
        $data = RcBooking::getYears();
        return $data;
    }
}
