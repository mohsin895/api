<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\HRMNotification;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
   public function generalSetting(Request $request){
    $dataInfo = Company::find(1);
    if(!empty($dataInfo)){
      if($request->attendance==1){
         $dataInfo->office_start_time = $request->startTime;
         $dataInfo->office_end_time = $request->endTime;
         $dataInfo->save();
      }
      if($dataInfo){
         $responseData=[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'msg'=>'Setting Update Successfully .',
            'errMsg'=>null,

        ];
 
     }else{
         $responseData=[
             'errMsgFlag'=>true,
             'msgFlag'=>false,
             'msg'=>null,
             'errMsg'=>'Failed To Update General Setting, Try Again.'
         ];
 
     }

    }else{
       $data = new Company();
       if($request->attendance==1){
       $data->office_start_time = $request->startTime;
       $data->office_end_time = $request->endTime;
       $data->save();
       }
       if($dataInfo){
         $responseData=[
             'errMsgFlag'=>false,
             'msgFlag'=>true,
             'msg'=>'Setting Update Successfully .',
             'errMsg'=>null,
 
         ];
 
     }else{
         $responseData=[
             'errMsgFlag'=>true,
             'msgFlag'=>false,
             'msg'=>null,
             'errMsg'=>'Failed To Update General Setting, Try Again.'
         ];
 
     }
    }
    return response()->json($responseData,200);
   }

   public function generalSettingInfo(){
      $dataInfo = Company::first();
      return response()->json($dataInfo,200);
   }

   public function notification(Request $request){
    $dataInfo = Company::find(1);
    if(!empty($dataInfo)){
   
         $dataInfo->award_notification = $request->award;
         $dataInfo->leave_notification = $request->leave;
         $dataInfo->payroll_notification = $request->payroll;
         $dataInfo->attendance_notification = $request->attendance_notification;
         $dataInfo->notice_notification = $request->noticeboard;
         $dataInfo->expense_notification = $request->expense;
         $dataInfo->employee_add = $request->employee;
       
         $dataInfo->save();
    
      if($dataInfo){
         $responseData=[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'msg'=>'Notification Update Successfully .',
            'errMsg'=>null,

        ];
 
     }else{
         $responseData=[
             'errMsgFlag'=>true,
             'msgFlag'=>false,
             'msg'=>null,
             'errMsg'=>'Failed To Update Notification Setting, Try Again.'
         ];
 
     }

    }else{
       $data = new Company();
       $dataInfo->award_notification = $request->award;
       $dataInfo->leave_notification = $request->leave;
       $dataInfo->payroll_notification = $request->payroll;
       $dataInfo->attendance_notification = $request->attendance_notification;
       $dataInfo->notice_notification = $request->noticeboard;
       $dataInfo->expense_notification = $request->expense;
       $dataInfo->employee_add = $request->employee;;
       $data->save();
       
       if($dataInfo){
         $responseData=[
             'errMsgFlag'=>false,
             'msgFlag'=>true,
             'msg'=>'Notification Update Successfully .',
             'errMsg'=>null,
 
         ];
 
     }else{
         $responseData=[
             'errMsgFlag'=>true,
             'msgFlag'=>false,
             'msg'=>null,
             'errMsg'=>'Failed To Update Notification Setting, Try Again.'
         ];
 
     }
    }
    return response()->json($responseData,200);
   }
}
