<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;
class ExpenseController extends Controller
{
    public function index(Request $request){
   
        $dataList = Expense::with('employee')->get();
      return response()->json($dataList);

   
   }
  
   public function dataInfoAddOrUpdate(Request $request){
    try{
        DB::beginTransaction();

         $dataInfo = Expense::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Expense();
            $dataInfo->employee_id = $request->employee_id;
            $dataInfo->item_name = $request->item_name;
            $dataInfo->purchase_date = $request->purchase_date;
            $dataInfo->purchase_from = $request->purchase_from;
            $dataInfo->price = $request->price;
            $dataInfo->status = $request->status;
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
               $dataInfo->employee_id = $request->employee_id;
               $dataInfo->item_name = $request->item_name;
               $dataInfo->purchase_date = $request->purchase_date;
               $dataInfo->purchase_from = $request->purchase_from;
               $dataInfo->price = $request->price;
               $dataInfo->status = $request->status;
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

   public function dataInfoDelete(Request $request){
    try{
        DB::beginTransaction();

        $dataInfo = Expense::find($request->dataId);

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
