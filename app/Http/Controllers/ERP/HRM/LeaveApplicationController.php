<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\LeaveApplication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;
class LeaveApplicationController extends Controller
{
    public function index(Request $request){
   
        $dataList = LeaveApplication::with('leaveType','employee')->orderBy('id','desc')->get();
        $color = ['pending' => 'bg-deleteColor', 'approved' => 'bg-rightActiveColor', 'rejected' => 'bg-bgDanger'];
 
      $responseData=[
          'dataList'=>$dataList,
          'color'=>$color,
        
      ];
      return response()->json($responseData);
   }
   public function updateStatus(Request $request)
   {
       try {
           DB::beginTransaction();
   
         
           $dataId = $request->dataId;
           $holiday = LeaveApplication::find($dataId);
   
           if (!empty($holiday)) {
            if($request->status ==1){
                $holiday->application_status='approved';

                $start = Carbon::createFromFormat("Y-m-d", $holiday->start_date);

                if ($holiday->end_date == null) {
                    $end = clone $start;
                } else {
                    $end = Carbon::createFromFormat("Y-m-d", $holiday->end_date);
                }
        
        
                $diffDays = $end->diffInDays($start);
                $startDate = $start;
                if ($holiday->application_status == 'approved') {
                    for ($i = 0; $i <= $diffDays; $i++) {
                        $date = $startDate;
                        $attendance = Attendence::firstOrCreate(['date' => $date->format("Y-m-d"),
                            'employee_id' => $holiday->employee_id]);
                        //  $attendance->date = $start ;
                        $attendance->employee_id = $holiday->employee_id;
                        $attendance->leaveType = $holiday->leaveType;
                        $attendance->halfDayType = $holiday->halfDayType;
                        $attendance->reason = $holiday->reason;
                        $attendance->status = 'absent';
                        $attendance->applied_on = $holiday->applied_on;
                        $attendance->application_status = 'approved';
                        $attendance->save();
                        $startDate->addDays(1);
                    }
                }
            }elseif($request->status ==2){
                $holiday->application_status='pending';
            }elseif($request->status==3){
                $holiday->application_status='rejected';
            }
            $holiday->save();
             
             
                //    foreach ($request->date as $key => $date) {
                //        if (!empty($date)) {
                //            $holiday = new LeaveApplication();
                //            $holiday->employee_id = Auth::guard('employee-api')->user()->id;
                //            $holiday->start_date = $date;
                //            $holiday->end_date = NULL;
                //            $holiday->application_status='pending';
                //            $holiday->days=1;
                //            $holiday->leaveType = $request->leaveType[$key] ?? null; 
                //            $holiday->halfDayType = (isset($request->halfleaveType[$key]) && $request->halfleaveType[$key] == 'yes') ? 'yes' : 'no';
                //            $holiday->reason = $request->reason[$key] ?? null; 
                //            $holiday->applied_on=Carbon::now();
                //            $holiday->save();
                //        }
                //    }
               
   
               DB::commit();
               return ApiResponseHelper::formatSuccessResponseUpdate();
           } else {
              
   
              
           }
       } catch (\Exception $err) {
           DB::rollBack();
           return ApiResponseHelper::formatErrorResponse($err);
       }
   }
   
   public function dataInfoDelete(Request $request){
    try{
        DB::beginTransaction();

        $leave_application = LeaveApplication::findOrFail($request->dataId);


       $start = Carbon::createFromFormat("Y-m-d", $leave_application->start_date);

       if ($leave_application->end_date == null) {
           $end = clone $start;
       } else {
           $end = Carbon::createFromFormat("Y-m-d", $leave_application->end_date);
       }

       $diffDays = $end->diffInDays($start);
       for ($i = 0; $i < $diffDays; $i++) {
           $date = $start->addDays(1);

           Attendence::where('date', '=', $date->format('Y-m-d'))
               ->where('employee_id', $leave_application->employee_id)
               ->delete();
       }
       $leave_application =LeaveApplication::find($request->dataId);
     
        if(empty($leave_application)){
            return ApiResponseHelper::formatDataNotFound();
        }

        if($leave_application->delete()){
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
   public function destroy($id)
   {

       $leave_application = LeaveApplication::findOrFail($id);


       $start = Carbon::createFromFormat("Y-m-d", $leave_application->start_date);

       if ($leave_application->end_date == null) {
           $end = clone $start;
       } else {
           $end = Carbon::createFromFormat("Y-m-d", $leave_application->end_date);
       }

       $diffDays = $end->diffInDays($start);
       for ($i = 0; $i < $diffDays; $i++) {
           $date = $start->addDays(1);

           Attendence::where('date', '=', $date->format('Y-m-d'))
               ->where('employee_id', $leave_application->employee_id)
               ->delete();
       }

       LeaveApplication::destroy($id);
       $output['success'] = 'deleted';

       return Response::json($output, 200);
   }
}
