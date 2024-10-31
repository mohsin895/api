<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;

class DepartmentController extends Controller
{
   public function index(Request $request){
   
        $dataList = Department::with('designationInfo')->get();
      return response()->json($dataList);

   
   }

   public function dataInfoAddOrUpdate(Request $request){
    try{
        DB::beginTransaction();

         $dataInfo = Department::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Department();
            $dataInfo->deparmentName = $request->departmentName;
            $dataInfo->save();
            if($dataInfo->save()){
                if ($request->has('designations') && is_array($request->designations)) {
                    foreach ($request->designations as $designation) {
                        if (!empty($designation)) {
                            $designationInfo = new Designation();
                            $designationInfo->department_id = $dataInfo->id;
                            $designationInfo->designation = $designation;
                            $designationInfo->save();
                        }
                    }
                }
                DB::commit();
                    return ApiResponseHelper::formatSuccessResponseInsert();
            }else{

                DB::commit();
                return ApiResponseHelper::formatFailedResponseInsert();
            }
           

         }else{
               $dataInfo = Department::find($request->dataId);
               $dataInfo->deparmentName = $request->departmentName;
               $dataInfo->save();
               if($dataInfo->save()){
               
                if ($request->has('designations') && is_array($request->designations)) {
                    foreach ($request->designations as $index => $designation) {
                        if (empty($designation)) {
                            continue;
                        }
                        $designationId = $request->designationId[$index] ?? null;
        
                        
                        if ($designationId) {
                            Designation::where('id', $designationId)
                                ->update(['designation' => $designation, 'department_id' => $dataInfo->id]);
                        } else {
                            
                            Designation::create([
                                'designation' => $designation,
                                'department_id' => $dataInfo->id
                            ]);
                        }
                    }
                }
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

        $dataInfo = Department::find($request->dataId);

        if(empty($dataInfo)){
            return ApiResponseHelper::formatDataNotFound();
        }

        $designationList = Designation::where('department_id', $dataInfo->id)->get();
        foreach($designationList as $designation){
            $designation->delete();
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

public function deleteDesignation(Request $request){
    try{
        DB::beginTransaction();

        $dataInfo = Designation::find($request->dataId);

        if(empty($dataInfo)){
            return ApiResponseHelper::formatDataNotFound();
        }

       
        if($dataInfo->delete()){
            DB::commit();
                return ApiResponseHelper::formatSuccessResponseUpdate();
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
