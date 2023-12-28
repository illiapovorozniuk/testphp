<?php

namespace App\Http\Controllers;

use App\Models\RcBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarFilteringController extends Controller
{
    //
    function getData(Request $request){
        $data = RcBooking::where('booking_id', '=', $request->input())->get();
        return $data;
    }
    function hello_there(){
        return "dfdfd";
    }
}
