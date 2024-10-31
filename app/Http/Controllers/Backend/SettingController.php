<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\MetaContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
   public function general_setting(Request $request)
   {
   $dataInfo = GeneralSetting::find(1);
   if(!empty($dataInfo)){
    $dataInfo->shop_name = $request->shop_name;
    $dataInfo->shop_phone = $request->shop_phone;
    $dataInfo->shop_address = $request->shop_address;
    $dataInfo->office_time = $request->office_time;
    $dataInfo->office_email = $request->office_email;
    $dataInfo->most_view = $request->most_view;
    $dataInfo->website=$request->website;
    if(isset($request->shopLogoImage) && !is_null($request->file('shopLogoImage')))
    {      

        $image=$request->file('shopLogoImage');
          
            $imageName = $image->getClientOriginalName();
               if (!Storage::disk('public')->exists('shopLogoImages')) {
                   Storage::disk('public')->makeDirectory('shopLogoImages');
               }
              
              
           Storage::disk('public')->put('generalImage/', $image);
           
           if(!is_null($imageName)){
              
               $path ='/storage/app/public/generalImage/'.$image->hashName();

               $dataInfo->base_url=env('APP_URL');

               $dataInfo->shop_logo=$path;
           }
       }
       if(isset($request->adminLogo) && !is_null($request->file('adminLogo')))
       {      
   
           $image=$request->file('adminLogo');
             
               $imageName = $image->getClientOriginalName();
                  if (!Storage::disk('public')->exists('generalImage')) {
                      Storage::disk('public')->makeDirectory('generalImage');
                  }
                 
                 
              Storage::disk('public')->put('generalImage/', $image);
              
              if(!is_null($imageName)){
                 
                  $path ='/storage/app/public/generalImage/'.$image->hashName();
   
                  $dataInfo->base_url=env('APP_URL');
   
                  $dataInfo->admin_logo=$path;
              }
          }
          if(isset($request->shopFavicon) && !is_null($request->file('shopFavicon')))
          {      
      
              $image=$request->file('shopFavicon');
                
                  $imageName = $image->getClientOriginalName();
                     if (!Storage::disk('public')->exists('generalImage')) {
                         Storage::disk('public')->makeDirectory('generalImage');
                     }
                    
                    
                 Storage::disk('public')->put('generalImage/', $image);
                 
                 if(!is_null($imageName)){
                    
                     $path ='/storage/app/public/generalImage/'.$image->hashName();
      
                     $dataInfo->base_url=env('APP_URL');
      
                     $dataInfo->shop_favicon=$path;
                 }
             }
             if(isset($request->adminFavicon) && !is_null($request->file('adminFavicon')))
             {      
         
                 $image=$request->file('adminFavicon');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('generalImage')) {
                            Storage::disk('public')->makeDirectory('generalImage');
                        }
                       
                       
                    Storage::disk('public')->put('generalImage/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/generalImage/'.$image->hashName();
         
                        $dataInfo->base_url=env('APP_URL');
         
                        $dataInfo->admin_favicon=$path;
                    }
                }
    $dataInfo->save(); 

    if($dataInfo){
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Setting Update Successfully .',

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
    $dataInfo = new GeneralSetting();
    $dataInfo->shop_name = $request->shop_name;
    $dataInfo->shop_phone = $request->shop_phone;
    $dataInfo->shop_address = $request->shop_address;
    $dataInfo->office_time = $request->office_time;
    $dataInfo->office_email = $request->office_email;
    $dataInfo->most_view = $request->most_view;
    $dataInfo->website=$request->website;
    if(isset($request->shopLogoImage) && !is_null($request->file('shopLogoImage')))
    {      

        $image=$request->file('shopLogoImage');
          
            $imageName = $image->getClientOriginalName();
               if (!Storage::disk('public')->exists('shopLogoImages')) {
                   Storage::disk('public')->makeDirectory('shopLogoImages');
               }
              
              
           Storage::disk('public')->put('generalImage/', $image);
           
           if(!is_null($imageName)){
              
               $path ='/storage/app/public/generalImage/'.$image->hashName();

               $dataInfo->base_url=env('APP_URL');

               $dataInfo->shop_logo=$path;
           }
       }
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'General Setting Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update General Setting. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 
   public function general_setting_info(Request $request)
   {
    $dataInfo = GeneralSetting::find(1);
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

   public function seo_setting(Request $request)
   {
   $dataInfo = MetaContent::find(1);
   if(!empty($dataInfo)){
    $dataInfo->title = $request->title;
    $dataInfo->description = $request->description;

    if(isset($request->shopLogoImage) && !is_null($request->file('shopLogoImage')))
    {      

        $image=$request->file('shopLogoImage');
          
            $imageName = $image->getClientOriginalName();
               if (!Storage::disk('public')->exists('shopLogoImages')) {
                   Storage::disk('public')->makeDirectory('shopLogoImages');
               }
              
              
           Storage::disk('public')->put('generalImage/', $image);
           
           if(!is_null($imageName)){
              
               $path ='/storage/app/public/generalImage/'.$image->hashName();


               $dataInfo->image=$path;
           }
       }
     
    $dataInfo->save(); 

    if($dataInfo){
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Setting Update Successfully .',

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
    $dataInfo = new MetaContent();
    $dataInfo->title = $request->title;
    $dataInfo->description = $request->description;

    if(isset($request->shopLogoImage) && !is_null($request->file('shopLogoImage')))
    {      

        $image=$request->file('shopLogoImage');
          
            $imageName = $image->getClientOriginalName();
               if (!Storage::disk('public')->exists('shopLogoImages')) {
                   Storage::disk('public')->makeDirectory('shopLogoImages');
               }
              
              
           Storage::disk('public')->put('generalImage/', $image);
           
           if(!is_null($imageName)){
              
               $path ='/storage/app/public/generalImage/'.$image->hashName();

         

               $dataInfo->image=$path;
           }
       }
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'General Setting Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update General Setting. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 
   public function seo_setting_info(Request $request)
   {
    $dataInfo = MetaContent::find(1);
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

   public function general_setting_footer(Request $request)
   {
       $dataInfo=GeneralSetting::find(1);
       
       if(!empty($dataInfo)) {
          
   

          $dataInfo->termsOfUse=$request->termsOfUse;

          $dataInfo->returnPolicy=$request->returnPolicy;

          $dataInfo->privecyPolicy=$request->privecyPolicy;



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

              $dataInfo=new GeneralSetting();

       

              $dataInfo->termsOfUse=$request->termsOfUse;

              $dataInfo->returnPolicy=$request->returnPolicy;

              $dataInfo->privecyPolicy=$request->privecyPolicy;


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
   public function getFooterInformation(Request $request)
   {
       $dataInfo=GeneralSetting::find(1);
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

   public function changeSmsRegOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_reg_otp_sms=$request->smsRegOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>'Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }

   public function changeSmsUpdatePhoneOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_update_phone_otp_sms=$request->smsUpdatePhoneOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }

   public function changeSmsCurrentPhoneOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_current_phone_otp_sms=$request->smsUpdatePhoneOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
   public function changeSmsOrderOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_order_otp_sms=$request->smsOrderOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
   public function changeSmsForgetPasswordOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_forget_pass_otp_sms=$request->smsForgetPassOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }

   public function changeEmailRegOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_reg_otp_email=$request->emailRegOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>'Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }

   public function changeEmailUpdatePhoneOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_update_phone_otp_email=$request->emailUpdatePhoneOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
   public function changeEmailOrderOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_order_otp_email=$request->emailOrderOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
   public function changeEmailForgetPasswordOtp(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->send_forget_pass_otp_email=$request->emailForgetPassOtp;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
   public function userPanelDown(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->user_panel_down=$request->serverDown;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
   public function sellerPanelDown(Request $request)
   {
       DB::beginTransaction();

       try{
               $dataInfo=GeneralSetting::find($request->dataId);

               $dataInfo->seller_panel_down=$request->serverDown;
 

               $dataInfo->updated_at=Carbon::now();

               if($dataInfo->save())
               {
                   $dataId=$dataInfo->id;

                   $tableName='brands';

                   $userId=1;

                   $userType=1;

                   $dataType=2;

                   $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                   // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                   GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                   $responseData=[
                           'errMsgFlag'=>false,
                           'msgFlag'=>true,
                           'msg'=>' Changed Successfully.',
                           'errMsg'=>null,
                       ];
               }
               else
               {
                  DB::rollBack();

                  $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Change .'
                       ];
               }

           return response()->json($responseData,200);
       }
       catch(\Exception $err)
       {
           DB::rollBack();
           
           GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
           
           DB::commit();

           $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Something Went Wrong.Please Try Again.'
           ];

           return response()->json($responseData,200);
       }
   }
}
