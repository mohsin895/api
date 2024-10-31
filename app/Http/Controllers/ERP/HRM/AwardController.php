<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;

class AwardController extends Controller
{
  

   public function index(Request $request)
   {
       // Get perPage value from the request
       $perPage = $request->perPage;
   
       // Start building the query
       $query = Award::with('employee');
   
       if ($request->filled('search')) {
        $search = $request->search;

        // Apply search filters for award name and employee details
        $query->where(function ($q) use ($search) {
            $q->where('award_name', 'LIKE', "%$search%")
              ->orWhereHas('employee', function ($q) use ($search) {
                  $q->where('employeeID', 'LIKE', "%$search%")
                    ->orWhere('full_name', 'LIKE', "%$search%");
              });
        });
    }
   
       // If perPage is 'all', get all records; otherwise, paginate
       if ($perPage === 'all') {
           $dataList = $query->get();
       } else {
           $dataList = $query->paginate($perPage);
       }
   
       return response()->json($dataList);
   }
   public function getEmployee(){
    $dataList = Employee::get();
    return response()->json($dataList, 200);
}
   public function dataInfoAddOrUpdate(Request $request){
    try{
        DB::beginTransaction();

         $dataInfo = Award::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Award();
            $dataInfo->employee_id = $request->employee_id;
            $dataInfo->award_name = $request->award_name;
            $dataInfo->gift = $request->gift;
            $dataInfo->cash_price = $request->cash_price;
            $dataInfo->month = $request->month;
            $dataInfo->year = $request->year;
            $dataInfo->save();
            if($dataInfo->save()){
                
                DB::commit();
                    return ApiResponseHelper::formatSuccessResponseInsert();
            }else{

                DB::commit();
                return ApiResponseHelper::formatFailedResponseInsert();
            }
           

         }else{
               $dataInfo = Award::find($request->dataId);
               $dataInfo->employee_id = $request->employee_id;
               $dataInfo->award_name = $request->award_name;
               $dataInfo->gift = $request->gift;
               $dataInfo->cash_price = $request->cash_price;
               $dataInfo->month = $request->month;
               $dataInfo->year = $request->year;
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

        $dataInfo = Award::find($request->dataId);

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
