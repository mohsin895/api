<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Unit;
use DB;
use Carbon\Carbon;
use Exception;

class UnitController extends Controller
{
    public function updateUnit(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Unit::find($request->dataId);

            if(!empty($dataInfo)) {
              
                $dataInfo->label=$request->unitLabel;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Unit Updated Successfully.',
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
                }
                else{

                     DB::rollBack();

                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Update Unit.Please Try Again.',
                     ];

                    return response()->json($responseData,200);
                }
            }
            else{
                DB::rollBack();

                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Requested Data No Found.',
                     ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\UnitController@updateUnit");

            DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Something Went Wrong.Please Try Again.',
                 ];

            return response()->json($responseData,200);
        }
    }
    public function getUnitInfo(Request $request)
    {
       $dataInfo=Unit::find($request->dataId);

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];
       }

       return response()->json($responseData,200);
    }
    
    public function addUnit(Request $request)
    {
    	DB::beginTransaction();
        try{

            $dataInfo=new Unit();

            $dataInfo->label=$request->unitLabel;

            $dataInfo->status=1;

            $dataInfo->created_at=Carbon::now();

            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Unit Added Successfully.',
                    'errMsg'=>null,
                 ];

                return response()->json($responseData,200);
            }
            else{

                 DB::rollBack();

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Add Unit.Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\UnitController@addunit");

            DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Something Went Wrong.Please Try Again.',
                 ];

            return response()->json($responseData,200);
        }
    }
    public function getUnitList(Request $request)
    {
    	$query=Unit::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);
            

        if(isset($request->label) && !is_null($request->label))
        	$query->where('label','like',$request->label.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getUnitListPC(Request $request)
    {
        $dataList=Unit::whereNull('deleted_at')->where('status',1)->orderBy('label','asc')->get();

        return response()->json($dataList,200);
    }
    public function getActiveUnitList(Request $request)
    {
    	$dataList=Unit::where('status',1)->orderBy('label','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeUnitStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Unit::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='units';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->label.' Unit Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Unit Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Unit Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Unit Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\UnitController@changeUnitStatus",$err);
            
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
    public function deleteUnit(Request $request)
    {
    	$dataInfo = Unit::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Unit Delete successfully',
            ];

        }else{
             $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Something wrong, plese try again',

             ];
        }
        return response()->json($responseData,200);
    }
}
