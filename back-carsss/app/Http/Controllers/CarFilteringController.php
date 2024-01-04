<?php

namespace App\Http\Controllers;

use App\Models\RcBooking;
use App\Models\RcCar;
use App\Models\RcCarsModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarFilteringController extends Controller
{
    //

    function getFilteringData(Request $request){
        $year = $request->input()['year'];
        $month = $request->input()['month'];
//        ['booking_id', '=', $request->input()['id']]

        $whereArr = $month==null&& $month==-1?
            [['start_date', '>=', $year . '-01-01'],['start_date', '<=', $year . '-12-31']]
            : [['start_date', '>=', $year . '-'.$month.'-01'],['start_date', '<=', $year . '-'.$month.'-'.cal_days_in_month(CAL_GREGORIAN, $month, $year)]]
        ;


        array_push($whereArr,['status','=',1]);
        array_push($whereArr,['company_id','=',1]);
        $bookings = RcBooking::select('booking_id', 'car_id','created_at','start_date','end_date')->where($whereArr)
            ->with('bookingWithCar')
            ->get();
        $responseBody =[];
        foreach ($bookings as $booking){
            //Check if car exist in current array
            if(in_array($booking['car_id'],array_column($responseBody,'car_id'))&&$booking['bookingWithCar']){
                //Index of existing car in our array in order to add booking period
                $index = array_search($booking['car_id'],array_column($responseBody,'car_id'));
                $responseBody[$index]['schedule'][] = [$booking['start_date'], $booking['end_date']];
            }
            else if($booking['bookingWithCar']) {
                $responseBody[] =
                    [
                        'car_id' => $booking['car_id'],
                        'schedule' => [[$booking['start_date'], $booking['end_date']]],
                        'registration_number' => $booking['bookingWithCar']['registration_number'],
                        'car_slug' => $booking['bookingWithCar']['carWithModel']['slug'],
                        'car_created_at' => date_format($booking['bookingWithCar']['created_at'], 'Y-m-d'),
                        'color' => $booking['bookingWithCar']['carWithModel']['attribute_interior_color'],
                        'brand_slug' => $booking['bookingWithCar']['carWithModel']['modelWithBrand']['slug'],
                    ];
            }
        }
        foreach ($responseBody as $key => $elem){
            $responseBody[$key]['schedule'] = $this->countOccupiedDays($elem['schedule']);
        }


//        return $responseBody;

//        $someddedd = $this->countOccupiedDays($responseBody[63]['schedule']);
        return $responseBody;
    }


    function getYears(){
        $data = RcBooking::getYears();
        return $data;
    }


    private function countOccupiedDays( $schedule)
    {
        //Сортування періодів
        usort($schedule,function ($a, $b){
            return strtotime($a[0]) - strtotime($b[0]);
        });

        $periodHours=[];
        $start_period = new DateTime( $schedule[0][0]);
        $end_period = new DateTime( $schedule[count($schedule) - 1][1]);

        for ($i = $start_period->format('d');
             $i <= $end_period->format('d');
             $i++) {
            $periodHours[$i] = 0;
        }
        $currentStart = new DateTime($schedule[0][0]);
        $currentEnd =  new DateTime($schedule[0][1]);
        foreach ($periodHours as $key => $value){
            repeatLogicAdding:
            if($currentStart->format('d')<=$key
                &&$currentEnd->format('d')>=$key)
            {

                //Перевірка, якщо букінг починається і закінчується в один і той самий день
                if($currentStart->format('d')==$currentEnd->format('d')){
                    if($currentStart->format('H:i:s')>="09:00:00"&&$currentEnd->format('H:i:s')>="21:00:00")
                        $periodHours[$key] +=  (new DateTime('21:00:00'))->getTimestamp()
                            - (new DateTime($currentStart->format('H:i:s')))->getTimestamp();
                    else if($currentStart->format('H:i:s')<="09:00:00"&&$currentEnd->format('H:i:s')<="21:00:00"){
                        $periodHours[$key] += (new DateTime($currentEnd->format('H:i:s')))->getTimestamp()
                            - (new DateTime('09:00:00'))->getTimestamp();
                    }
                    else if($currentStart->format('H:i:s')>="09:00:00"&&$currentEnd->format('H:i:s')<="21:00:00"){
                        $periodHours[$key] += (new DateTime($currentEnd->format('H:i:s')))->getTimestamp()
                            - (new DateTime($currentStart->format('H:i:s')))->getTimestamp();
                    }
                    else {
                        $periodHours[$key] +=12*3600;
                    }
                }
                //Якщо букінг починається і закінчується в різні дні
                else{
                    //Обрахунок для початку бронювання
                    if($key == $currentStart->format('d')){
                        if( $currentStart->format('H:i:s')<="09:00:00" )
                            $periodHours[$key] +=12*3600;
                        else if($currentStart->format('H:i:s')<="21:00:00"){
                            $periodHours[$key] +=  (new DateTime('21:00:00'))->getTimestamp()
                                - (new DateTime($currentStart->format('H:i:s')))->getTimestamp();
                        }
                    }//Обрахунок для кінця бронювання
                    else if ($key == $currentEnd->format('d')){
                        if( $currentEnd->format('H:i:s')>="21:00:00" )
                            $periodHours[$key] +=12*3600;
                        else if($currentEnd->format('H:i:s')>="09:00:00"){
                            $periodHours[$key] += (new DateTime($currentEnd->format('H:i:s')))->getTimestamp()
                            - (new DateTime('09:00:00'))->getTimestamp();
                        }
                    }
                    else
                        $periodHours[$key] +=12*3600;

                }

//                $periodHours[$key] +=1;
               //Якщо періоди закінчення і початку накладаються, або наступний день це початок нового букінгу
                if(isset($schedule[1])&&$currentEnd->format('d')==$key){
                     if((new DateTime($schedule[1][0]))->format('d')==$key
                    ){

                        array_shift($schedule);
                        $currentStart = new DateTime($schedule[0][0]);
                        $currentEnd =  new DateTime($schedule[0][1]);
//                        $periodHours[$key] +=1;
                        if($currentStart->format('d')==$key) {
//                            $periodHours[$key] +="NIGHT";
                            goto repeatLogicAdding;
                        }
                    }
                     else if((new DateTime($schedule[1][0]))->format('d')-$key==1){
                        array_shift($schedule);
                        $currentStart = new DateTime($schedule[0][0]);
                        $currentEnd =  new DateTime($schedule[0][1]);
//                        $periodHours[$key] +="DAY";
                        goto repeatLogicAdding;
                    }

                }
//                $periodHours[$key] =$currentStart->format('d'). '  '. $currentEnd->format('d');
            }
            else if($currentEnd->format('d')>$key )
                continue;
            else {

                array_shift($schedule);
                if(isset($schedule[0])){
                    $currentStart = new DateTime($schedule[0][0]);
                    $currentEnd =  new DateTime($schedule[0][1]);

                }
            }
        }

        $countOcupiedDays = 0;
        foreach ($periodHours as $day)
            if($day>3*3600)
                $countOcupiedDays++;


        return $countOcupiedDays;
    }

function diffDatesToSec(DateTime $datetime1, DateTime $datetime2){

    $dif = $datetime1->diff($datetime2);
    $seconds = $dif->days*24*60*60
    + $dif->h*60*60 +$dif->i*60 + $dif->s;

    return $seconds;
}
}
