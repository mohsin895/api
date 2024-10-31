<?php

namespace App\Http\Controllers\ERP\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;
use Auth;

class AttendanceController extends Controller
{
    public function index(Request $request){
   
        
        $dataInfo = Attendence::where('employee_id',Auth::guard('employee-api')->user()->id)->orderBy('date','desc')->get();
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        
        $attendanceActive = 'active';

     
        $today = Carbon::now();
        $yesterday = Carbon::yesterday();

        $end_time = $this->getOfficeEndTime($today);


        $start_time = $this->getOfficeStartTime($today);

  
        $yesterday_end_time = clone $this->getOfficeEndTime($yesterday);
        $yesterday_end_time->subDay();

   
        $yesterday_start_time = clone $this->getOfficeStartTime($yesterday);
        $yesterday_start_time->subDay();

     
        $dates = [$today->format("Y-m-d"), $yesterday->format("Y-m-d")];

        $today_attendance = Attendence::where('date', $dates[0])
            ->where('employee_id', '=', Auth::guard('employee-api')->user()->id)
            ->orderBy('date')
            ->first();

        $yesterday_attendance = Attendence::where('date', $dates[1])
            ->where('employee_id', '=', Auth::guard('employee-api')->user()->id)
            ->orderBy('date')
            ->first();

        $working_attendance = null;

  

        if ($today->diffInHours($yesterday_end_time) <= 6) {
            $working_attendance = $yesterday_attendance;
            $working_end_time = $yesterday_end_time;
        } else {
            $working_attendance = $today_attendance;
            $working_end_time = $end_time;
        }

   
        $clock_out_time="";
        $clock_in_time="";
        $clock_set = "";
        if ($working_attendance != null) {
            if ($working_attendance->status == "absent") {
                $set_attendance = 3;
            } else {
                if ($working_attendance->clock_in != null) {
                    if ($working_attendance->clock_out != null) {
                        $set_attendance = 0;
                        $clock_set = 0;
                        $clock_in_time = $working_attendance->clock_in;
                        $clock_out_time = $working_attendance->clock_out;
                    } else {
                        $clock_set = 1;
                        $clock_in_time = $working_attendance->clock_in;
                        $clock_out_time = $working_attendance->clock_out;
                        $set_attendance = 1;
                    }
                } else {

                    $clock_set = 0;
                    $set_attendance = 1;

                }
                $notes = $working_attendance->notes;
                $working_from = $working_attendance->working_from;
            }
        } else {
            if ($today > $working_end_time) {
               
                $clock_set = 0;
                $set_attendance = 2;
            } else {
                $set_attendance = 1;
                $clock_set = 0;
                $notes = '';
                $working_from = '';
            }

        }
       
        $dataList=[
            'dataInfo'=>$dataInfo,
            'ipAddress'=>$ipAddress,
            'set_attendance'=>$set_attendance,
            'clock_set'=>$clock_set,
            'clock_out_time'=>$clock_out_time,
            'clock_in_time'=>$clock_in_time,
        
        ];
      return response()->json($dataList);

   
   }
  
   public function dataInfoAddOrUpdate(Request $request){
    try{
        DB::beginTransaction();

         $dataInfo = Expense::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Expense();
            $dataInfo->employee_id = Auth::guard('employee-api')->user()->id;
            $dataInfo->item_name = $request->item_name;
            $dataInfo->purchase_date = $request->purchase_date;
            $dataInfo->purchase_from = $request->purchase_from;
            $dataInfo->price = $request->price;
            $dataInfo->status = 2;
            $dataInfo->bill = $request->bill;
            $dataInfo->save();
            if($dataInfo->save()){
                
                DB::commit();
                    return ApiResponseHelper::formatSuccessResponseInsert();
            }else{

                DB::commit();
                return ApiResponseHelper::formatFailedResponseInsert();
            }
           

         }else{
               $dataInfo = Expense::find($request->dataId);
               $dataInfo->employee_id = Auth::guard('employee-api')->user()->id;
               $dataInfo->item_name = $request->item_name;
               $dataInfo->purchase_date = $request->purchase_date;
               $dataInfo->purchase_from = $request->purchase_from;
               $dataInfo->price = $request->price;
               $dataInfo->status = 2;
               $dataInfo->bill = $request->bill;
               $dataInfo->save();
               if($dataInfo->save()){
               
                DB::commit();
                return ApiResponseHelper::formatSuccessResponseUpdate();
               }else{
   
                DB::commit();
                return ApiResponseHelper::formatFailedResponseUpdate();
               }
              
   
         }
     
         
           

      


    }catch(\Exception $err){
        DB::rollBack();
        return ApiResponseHelper::formatErrorResponse($err);
    }
   }

   public function getOfficeEndTime(Carbon $date = null)
   {
    $setting = Company::first();
       if ($date == null) {
           $date = Carbon::now();
       }

       $dateStr = $date->format("Y-m-d");

       $end = Carbon::createFromFormat("Y-m-d H:i:s", $dateStr . " " . $setting->office_end_time);
       $start = Carbon::createFromFormat("Y-m-d H:i:s", $dateStr . " " . $setting->office_start_time);

       if ($end < $start) {
           $end->addDay();
       }

       return $end;
   }

   public function getOfficeStartTime(Carbon $date = null)
   {
    $setting = Company::first();
       if ($date == null) {
           $date = Carbon::now();
       }

       $dateStr = $date->format("Y-m-d");

       $start = Carbon::createFromFormat("Y-m-d H:i:s", $dateStr . " " . $setting->office_start_time);

       return $start;
   }


   public function clockIn(Request $request)
   {
       $setting = Company::first();
       $today = Carbon::now();
       $yesterday = Carbon::yesterday();
       
       $yesterday_end_time = $this->getOfficeEndTime($yesterday)->subDay();
       $yesterday_start_time = $this->getOfficeStartTime($yesterday)->subDay();
       
       $employeeId = Auth::guard('employee-api')->user()->id;
       
       $todayAttendance = Attendence::where('date', $today->format("Y-m-d"))
                                   ->where('employee_id', $employeeId)
                                   ->first();
       
       $yesterdayAttendance = Attendence::where('date', $yesterday->format("Y-m-d"))
                                        ->where('employee_id', $employeeId)
                                        ->first();
   
       $workingAttendance = $todayAttendance;
       $workingDay = $today;
       
       if ($today->diffInHours($yesterday_end_time) <= 6) {
           $workingAttendance = $yesterdayAttendance;
           $workingDay = $yesterday;
       }
       
       $currentTime = $today->format('H:i:s');
       $currentIpAddress = $request->ip();
       $notes = $request->get('notes');
       $workingFrom = $request->get('work_from');
       
       if ($workingAttendance) {
           if ($workingAttendance->status == "absent") {
               $responseData = [
                   'errMsgFlag' => false,
                   'msgFlag' => true,
                   'msg' => 'You have been marked absent for today.',
                   'errMsg' => null,
               ];
               return response()->json($responseData, 200);
           }
           
           $workingAttendance->clock_in = $currentTime;
           $workingAttendance->clock_in_ip_address = $currentIpAddress;
           $workingAttendance->status = 'present';
           $workingAttendance->notes = $notes;
           $workingAttendance->working_from = $workingFrom;
           $workingAttendance->office_start_time = $setting->office_start_time;
           $workingAttendance->office_end_time = $setting->office_end_time;
           
           if ($setting->late_mark_after != null) {
               $startDiff = $this->getOfficeStartTime($workingDay)->diffInMinutes($workingAttendance->clock_in, false);
               $workingAttendance->is_late = $startDiff < -($setting->late_mark_after) ? 1 : 0;
           }
           
           $workingAttendance->save();
           
           $responseData = [
               'errMsgFlag' => false,
               'msgFlag' => true,
               'msg' => 'You have successfully clocked in.',
               'errMsg' => null,
           ];
           return response()->json($responseData, 200);
       }
       
       $newAttendance = new Attendence();
       $newAttendance->employee_id = $employeeId;
       $newAttendance->date = $workingDay->format("Y-m-d");
       $newAttendance->status = 'present';
       $newAttendance->clock_in = $currentTime;
       $newAttendance->clock_in_ip_address = $currentIpAddress;
       $newAttendance->notes = $notes;
       $newAttendance->working_from = $workingFrom;
       $newAttendance->office_start_time = $setting->office_start_time;
       $newAttendance->office_end_time = $setting->office_end_time;
       
       if ($setting->late_mark_after != null) {
           $startDiff = $this->getOfficeStartTime($workingDay)->diffInMinutes($newAttendance->clock_in, false);
           $newAttendance->is_late = $startDiff < -($setting->late_mark_after) ? 1 : 0;
       }
       
       $newAttendance->save();
       
       $responseData = [
           'errMsgFlag' => false,
           'msgFlag' => true,
           'msg' => 'You have successfully clocked in.',
           'errMsg' => null,
       ];
       
       return response()->json($responseData, 200);
   }

   function clockOut()
   {
      
       $setting = Company::first();
       $today = Carbon::now();
       $yesterday = Carbon::yesterday();

      
       $yesterday_end_time = clone $this->getOfficeEndTime($yesterday);
       $yesterday_end_time->subDay();

    
       $yesterday_start_time = clone $this->getOfficeStartTime($yesterday);
       $yesterday_start_time->subDay();

       $dates = [$today->format("Y-m-d"), $yesterday->format("Y-m-d")];

       $today_attendance = Attendence::where('date', $dates[0])
           ->where('employee_id', '=', Auth::guard('employee-api')->user()->id)
           ->orderBy('date')
           ->first();

       $yesterday_attendance = Attendence::where('date', $dates[1])
           ->where('employee_id', '=', Auth::guard('employee-api')->user()->id)
           ->orderBy('date')
           ->first();

       $working_attendance = null;


       if ($today->diffInHours($yesterday_end_time) <= 6) {
           $working_attendance = $yesterday_attendance;
       } else {
           $working_attendance = $today_attendance;
       }

       $cur_time = $today->format('H:i:s');

  
       if ($working_attendance != null) {
           if ($working_attendance->status == "absent") {
            $responseData = [
                'errMsgFlag' => false,
                'msgFlag' => true,
                'msg' => 'You have been marked absent for today.',
                'errMsg' => null,
            ];
            
            return response()->json($responseData, 200);
              
           }
           if ($working_attendance->clock_in != null) {

               if ($working_attendance->clock_out != null) {
                $responseData = [
                    'errMsgFlag' => false,
                    'msgFlag' => true,
                    'msg' => 'Your attendance for today has already been marked.',
                    'errMsg' => null,
                ];
                
                return response()->json($responseData, 200);
                   
               }
               $working_attendance->clock_out = $cur_time;
               $working_attendance->clock_out_ip_address = $_SERVER['REMOTE_ADDR'];
         
               $working_attendance->save();

               
               $responseData = [
                'errMsgFlag' => false,
                'msgFlag' => true,
                'msg' => 'Clock out time was set successfully.',
                'errMsg' => null,
            ];
            
            return response()->json($responseData, 200);

          
           }
           $responseData = [
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'You have to clock in first.',
            'errMsg' => null,
        ];
        
        return response()->json($responseData, 200);
        
       }
       $responseData = [
        'errMsgFlag' => false,
        'msgFlag' => true,
        'msg' => 'You have to clock in first.',
        'errMsg' => null,
    ];
    
    return response()->json($responseData, 200);
   

   }



}
