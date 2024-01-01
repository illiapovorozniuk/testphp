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
        $responseBody =[];
        foreach ($bookings as $booking){
            $responseBody[] =
                [
                    'car_id'=>$booking['car_id'],
                    'start_date'=>$booking['start_date'],
                    'end_date'=>$booking['end_date'],
                    'registration_number'=> $booking['carWithModel']['registration_number'],
                    'car_slug'=> $booking['carWithModel']['carWithModel']['slug'],
                    'car_created_at'=> date_format($booking['carWithModel']['created_at'],'Y-m-d'),
                    'color'=>$booking['carWithModel']['carWithModel']['attribute_interior_color'],
                    'brand_slug'=>$booking['carWithModel']['carWithModel']['modelWithBrand']['slug'],
                ];
        }

        return $responseBody;
    }


    function getYears(){
        $data = RcBooking::getYears();
        return $data;
    }
}
