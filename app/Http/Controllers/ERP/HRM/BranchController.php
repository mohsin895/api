<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index(Request $request){
   
        $dataList = Branch::get();
      return response()->json($dataList);

   
   }

   public function dataInfoAddOrUpdate(Request $request){
    try{
        DB::beginTransaction();

         $dataInfo = Branch::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Branch();
            $dataInfo->branchName = $request->branchName;
            $dataInfo->address = $request->address;
            $dataInfo->save();
            if($dataInfo->save()){
               
                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Data insert sucessfully.',
                    'errMsg'=>null,

                ];
            }else{

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Data insert fail, Please Try again!',
                ];
            }
           

            DB::commit();
            return response()->json($responseData, 200);
         }else{
               $dataInfo = Branch::find($request->dataId);
               $dataInfo->branchName = $request->branchName;
               $dataInfo->address = $request->address;
               $dataInfo->save();
               if($dataInfo->save()){
               
               
                   $responseData=[
                       'errMsgFlag'=>false,
                       'msgFlag'=>true,
                       'msg'=>'Data Update sucessfully.',
                       'errMsg'=>null,
   
                   ];
               }else{
   
                   $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Data Update fail, Please Try again!',
                   ];
               }
              
   
               DB::commit();
               return response()->json($responseData, 200);
         }
     
         
           

      


    }catch(\Exception $err){
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Something Went Wrong . Please try again.',
            'errorDetails' => [
        'message' => $err->getMessage(),
        'trace' => $err->getTraceAsString()
    ],


        ];
        
        return response()->json($responseData, 200);
    }
   }

   public function dataInfoDelete(Request $request){
    try{
        DB::beginTransaction();

        $dataInfo = Branch::find($request->dataId);

        if(empty($dataInfo)){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Data not found!',
            ];
            return response()->json($responseData, 404);
        }

       

        if($dataInfo->delete()){
            DB::commit();
            $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'msg'=>'Data deleted successfully.',
                'errMsg'=>null,
            ];
            return response()->json($responseData, 200);
        } else {
            DB::rollBack();
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Data deletion failed. Please try again!',
            ];
            return response()->json($responseData, 500);
        }
    } catch(\Exception $err){
        DB::rollBack();
        $responseData = [
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Something went wrong. Please try again.',
            'errorDetails' => [
                'message' => $err->getMessage(),
                'trace' => $err->getTraceAsString()
            ],
        ];
        return response()->json($responseData, 500);
    }
}
}
