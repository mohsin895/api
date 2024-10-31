<?php

namespace App\Http\Controllers\ERP\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\Leavetype;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;
use Auth;

class LeaveController extends Controller
{
    public function index(Request $request){
   
        $dataList = LeaveApplication::with('leaveType')->where('employee_id',Auth::guard('employee-api')->user()->id)->orderBy('id','desc')->get();
         $leaveList = Leavetype::get();
         $row = Employee::select(
            'employees.employeeID as employeeID',
         
           
            \DB::raw('GROUP_CONCAT(DISTINCT a.leave_type SEPARATOR ",") as leave_types'),
            \DB::raw('GROUP_CONCAT(a.leave_count SEPARATOR ",") as leave_count'),
           
         
        
        )
        ->leftJoin(\DB::raw('(
            SELECT 
                attendences.leaveType as leave_type, 
                COUNT(attendences.leaveType) as leave_count, 
                attendences.employee_id, 
                MAX(attendences.date) as last_absent
            FROM attendences 
            INNER JOIN employees ON employees.id = attendences.employee_id
            WHERE attendences.leaveType IS NOT NULL
            AND YEAR(attendences.date) = ' . Carbon::now()->year . '
            GROUP BY attendences.leaveType, attendences.employee_id
        ) as a'), 'a.employee_id', '=', 'employees.id')
        ->where('employees.id', Auth::guard('employee-api')->user()->id)
        ->groupBy(
            'employees.employeeID',
           
        )
        ->first();
        

        $takenLeaveTypes = explode(",", $row->leave_types);
        $takenLeaves = explode(",", $row->leave_count);
        $color = ['pending' => 'bg-deleteColor', 'approved' => 'bg-rightActiveColor', 'rejected' => 'bg-bgDanger'];
      $responseData=[
          'dataList'=>$dataList,
          'leaveList'=>$leaveList,
          'takenLeaveTypes'=>$takenLeaveTypes,
          'takenLeaves'=>$takenLeaves,
          'row'=>$row,
          'color'=>$color,
      ];
      return response()->json($responseData);
   }
   public function leaveType(Request $request){
   
    $dataList = Leavetype::get();
    
  return response()->json($dataList);

}
   public function dataInfoAddOrUpdate(Request $request)
   {
       try {
           DB::beginTransaction();
   
         
           $dataId = $request->dataId;
           $holiday = LeaveApplication::find($dataId);
   
           if (empty($holiday)) {
             
             
                   foreach ($request->date as $key => $date) {
                       if (!empty($date)) {
                           $holiday = new LeaveApplication();
                           $holiday->employee_id = Auth::guard('employee-api')->user()->id;
                           $holiday->start_date = $date;
                           $holiday->end_date = NULL;
                           $holiday->application_status='pending';
                           $holiday->days=1;
                           $holiday->leaveType = $request->leaveType[$key] ?? null; 
                           $holiday->halfDayType = (isset($request->halfleaveType[$key]) && $request->halfleaveType[$key] == 'yes') ? 'yes' : 'no';
                           $holiday->reason = $request->reason[$key] ?? null; 
                           $holiday->applied_on=Carbon::now();
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
   
   
   public function dataInfoAddOrUpdateMultiple(Request $request)
   {
       try {
           DB::beginTransaction();
   
         
           $dataId = $request->dataId;
           $holiday = LeaveApplication::find($dataId);
   
           if (empty($holiday)) {
             
             
                  
                           $holiday = new LeaveApplication();
                           $holiday->employee_id = Auth::guard('employee-api')->user()->id;
                           $holiday->start_date = $request->startDate;
                           $holiday->end_date = $request->endDate;
                           $holiday->days=$request->days;;
                           $holiday->leaveType = $request->leaveType; 
                           $holiday->application_status='pending';
                           $holiday->reason = $request->reason ?? null; 
                           $holiday->save();
                       
                   
               
   
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

        $dataInfo = LeaveApplication::find($request->dataId);

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
