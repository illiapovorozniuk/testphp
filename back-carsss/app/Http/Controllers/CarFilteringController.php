<?php

namespace App\Http\Controllers;

use App\Models\RcBooking;
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
            ->with('car')->with

            ->get();
        $cars =[];
        foreach($bookings as $booking){
            $cars[] = $booking->car;
        }
        return $bookings;
    }
    function getYears(){
        $data = RcBooking::getYears();
        return $data;
    }
}
