<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Holyday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;
class HolydayController extends Controller
{
   
    public function index(Request $request){
   
        $year = $request->query('year', date("Y"));

        $holidays = Holyday::whereYear('date', $year)->orderBy('date', 'ASC')->get();
     
    
        $dateArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', 0);
        $dateSatArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', 6);
        $dateFriArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', 5);
    
        $sat_sun = Holyday::selectRaw('SUM(IF(WEEKDAY(date) = 6,1,0)) as sun,
                                       SUM(IF(WEEKDAY(date) = 5, 1, 0)) as sat,
                                       SUM(IF(WEEKDAY(date) = 4, 1, 0)) as fri')
                          ->whereYear('date', $year)
                          ->first();
    
        $holidaysArray = [];
        foreach ($holidays as $holiday) {
            $month = date('F', strtotime($holiday->date));
            $holidaysArray[$month]['id'][] = $holiday->id;
            $holidaysArray[$month]['date'][] = date('d F Y', strtotime($holiday->date));
            $holidaysArray[$month]['ocassion'][] = $holiday->occassion;
            $holidaysArray[$month]['day'][] = date('D', strtotime($holiday->date));
        }
    
        return response()->json([
            'year' => $year,
            'holidays' => $holidaysArray,
            'number_of_sundays' => count($dateArr),
            'number_of_saturdays' => count($dateSatArr),
            'number_of_fridays' => count($dateFriArr),
            'number_of_sat_db' => $sat_sun->sat,
            'number_of_sun_db' => $sat_sun->sun,
            'number_of_fri_db' => $sat_sun->fri,
            'holidays_in_db' => count($holidays),
            'all_sundays' => $dateArr,
            'all_saturdays' => $dateSatArr,
            'all_fridays' => $dateFriArr,
        ]);

   
   }
   public function dataInfoAddOrUpdate(Request $request)
   {
       try {
           DB::beginTransaction();
   
         
           $dataId = $request->dataId;
           $holiday = Holyday::find($dataId);
   
           if (empty($holiday)) {
             
             
                   foreach ($request->date as $key => $date) {
                       if (!empty($date)) {
                           $holiday = new Holyday();
                           $holiday->date = $date;
                           $holiday->occassion = $request->occassion[$key] ?? null; 
                           $holiday->save();
                       }
                   }
               
   
               DB::commit();
               return ApiResponseHelper::formatSuccessResponseInsert();
           } else {
               //
               if ($request->has('date')) {
                   $holiday->date = $request->date;
               }
               if ($request->has('occassion')) {
                   $holiday->occassion = $request->occassion;
               }
               $holiday->save();
   
               DB::commit();
               return ApiResponseHelper::formatSuccessResponseUpdate();
           }
       } catch (\Exception $err) {
           DB::rollBack();
           return ApiResponseHelper::formatErrorResponse($err);
       }
   }
   
   

   public function dataInfoDelete(Request $request){
    try{
        DB::beginTransaction();

        $dataInfo = Holyday::find($request->dataId);

        if(empty($dataInfo)){
            return ApiResponseHelper::formatDataNotFound();
        }

      

        if($dataInfo->delete()){
            DB::commit();
            return ApiResponseHelper::formatSuccessResponseDelete();
        } else {
            DB::rollBack();
            return ApiResponseHelper::formatFailedResponseDelete();   
             }
    } catch(\Exception $err){
     
        DB::rollBack();
        return ApiResponseHelper::formatErrorResponse($err);
    }
}



public function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
{
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    $dateArr = [];

    do {
        if (date("w", $startDate) != $weekdayNumber) {
            $startDate += (24 * 3600); 
        }
    } while (date("w", $startDate) != $weekdayNumber);


    while ($startDate <= $endDate) {
        $dateArr[] = date('Y-m-d', $startDate);
        $startDate += (7 * 24 * 3600); 
    }

    return ($dateArr);
}

}
