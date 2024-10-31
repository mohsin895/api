<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Size;
use App\Models\SizeAttribute;
use Carbon\Carbon;
use Exception;
use DB;

class SizeController extends Controller
{
    public function updateSize(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Size::find($request->dataId);

            if(!empty($dataInfo)) {
                
                $dataInfo->size=$request->size;

                $dataInfo->label=$request->label;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Size Updated Successfully.',
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
                        'errMsg'=>'Failed To Update Size.Please Try Again.',
                     ];

                    return response()->json($responseData,200);
                }
            }
            else{

                $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Requested Data Not Found.',
                     ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\SizeController@updateSize");

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
    public function getSizeInfo(Request $request)
    {
       $dataInfo=Size::find($request->dataId);

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
    public function addSize(Request $request)
    {
    	DB::beginTransaction();
        try{

            $dataInfo=new Size();

            $dataInfo->size=$request->size;

            $dataInfo->label=$request->label;

            $dataInfo->status=1;

            $dataInfo->created_at=Carbon::now();

            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Size  Added Successfully.',
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
                    'errMsg'=>'Failed To Add Size .Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\SizeController@addSize");

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
    public function getSizeList(Request $request)
    {
    	$query=Size::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->label) && !is_null($request->label))
            $query->where('label','like',$request->label.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getSizeListPC(Request $request)
    {
        $dataList=Size::whereNull('deleted_at')->where('status',1)->get();

        return response()->json($dataList,200);
    }
    public function getActiveSizeList(Request $request)
    {
    	$dataList=Size::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeSizeStatus(Request $request)
    {
    	 DB::beginTransaction();

        try{
                $dataInfo=Size::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='sizes';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Size Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Size Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Size Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Size Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\SizeController@changeSizeStatus",$err);
            
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
    public function deleteSize(Request $request)
    {
    	$dataInfo = Size::find($request->dataId);
        $sizeAttribute=SizeAttribute::where('size_id',$dataInfo->id)->count('id');
        if($sizeAttribute < 1){
            $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Size Delete successfully',
            ];

        }else{
             $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Something wrong, plese try again',

             ];
        }

        }else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Please First Delete Size Attribute',

             ];

        }
        
        return response()->json($responseData,200);
    }
}
