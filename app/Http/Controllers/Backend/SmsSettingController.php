<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsSetting;
use Carbon\Carbon;

class SmsSettingController extends Controller
{
    public function general_setting_sms(Request $request)
   {
       $dataInfo=SmsSetting::find(1);
       
       if(!empty($dataInfo)) {
          
   

          $dataInfo->maskingApiKey=$request->maskingApiKey;

          $dataInfo->maskingClientId=$request->maskingClientId;

          $dataInfo->nonMaskingApiKey=$request->nonMaskingApiKey;

          $dataInfo->nonMaskingClientId=$request->nonMaskingClientId;

       $dataInfo->orderSmsDescription=$request->orderSmsDescription;

        //   $dataInfo->applicationSms=$request->applicationSms;

          $dataInfo->updated_at=Carbon::now();

          if($dataInfo->save()){
                   $responseData=[
                       'errMsgFlag'=>false,
                       'msgFlag'=>true,
                       'msg'=>'General Setting Updated Successfully.',
                       'errMsg'=>null
                   ];
              }
              else{
                       
                   $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Failed To Update General Setting.'
                   ];
              }
       }
       else{

              $dataInfo=new SmsSetting();

       

              $dataInfo->maskingApiKey=$request->maskingApiKey;

              $dataInfo->maskingClientId=$request->maskingClientId;

              $dataInfo->nonMaskingApiKey=$request->nonMaskingApiKey;

              $dataInfo->nonMaskingClientId=$request->nonMaskingClientId;

            //   $dataInfo->summonSms=$request->summonSms;

            //   $dataInfo->applicationSms=$request->applicationSms;

              $dataInfo->created_at=Carbon::now();

              if($dataInfo->save()){
                   $responseData=[
                       'errMsgFlag'=>false,
                       'msgFlag'=>true,
                       'msg'=>'General Setting Updated Successfully.',
                       'errMsg'=>null
                   ];
              }
              else{
                       
                   $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Failed To Update General Setting.'
                   ];
              }
       }

       return response()->json($responseData,200);
   }
   public function getSmsInformation(Request $request)
   {
       $dataInfo=SmsSetting::find(1);
       $flag=(!empty($dataInfo)) ? true:false;
       $responseData=[
                   'errMsgFlag'=>!$flag,
                   'msgFlag'=>$flag,
                   'msg'=>null,
                   'errMsg'=>null,
                   'dataInfo'=>$dataInfo,
               ];

       return response()->json($responseData,200);
   }
}
