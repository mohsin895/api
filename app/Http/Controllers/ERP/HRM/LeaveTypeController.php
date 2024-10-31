<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Leavetype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;

class LeaveTypeController extends Controller
{
    public function index(Request $request){
   
        $dataList = Leavetype::get();
      return response()->json($dataList);

   
   }
  
   public function dataInfoAddOrUpdate(Request $request){
    try{
        DB::beginTransaction();

         $dataInfo = Leavetype::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Leavetype();
            $dataInfo->leaveType = $request->leaveType;
            $dataInfo->num_of_leave = $request->num_of_leave;
     
            $dataInfo->save();
            if($dataInfo->save()){
                
                DB::commit();
                    return ApiResponseHelper::formatSuccessResponseInsert();
            }else{

                DB::commit();
                return ApiResponseHelper::formatFailedResponseInsert();
            }
           

         }else{
               $dataInfo = Leavetype::find($request->dataId);
               $dataInfo->leaveType = $request->leaveType;
               $dataInfo->num_of_leave = $request->num_of_leave;
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

   public function dataInfoDelete(Request $request){
    try{
        DB::beginTransaction();

        $dataInfo = Leavetype::find($request->dataId);

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
}
