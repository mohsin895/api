<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Color;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function updateColor(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Color::find($request->dataId);

            if(!empty($dataInfo)) {
               
                $dataInfo->color=$request->colorName;

                $dataInfo->color_code=$request->colorCode;

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Color Updated Successfully.',
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
                        'errMsg'=>'Failed To Update Color.Please Try Again.',
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
                        'errMsg'=>'Requested Data Not Found.',
                     ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\ColorController@updateColor");

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
    public function getColorInfo(Request $request)
    {
       $dataInfo=Color::find($request->dataId);

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

    public function addColor(Request $request)
    {
    	 DB::beginTransaction();
        try{

            $dataInfo=new Color();

            $dataInfo->color=$request->colorName;

            $dataInfo->color_code=$request->colorCode;

            $dataInfo->status=1;

            $dataInfo->created_at=Carbon::now();

            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Color Added Successfully.',
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
                    'errMsg'=>'Failed To Add Color.Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\ColorController@addColor");

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
    public function getColorList(Request $request)
    {
    	$query=Color::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->color) && !is_null($request->color))
            $query->where('color','like',$request->color.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getColorListPC(Request $request)
    {
       $dataList=Color::whereNull('deleted_at')->where('status',1)->get();
        
        return response()->json($dataList,200); 
    }
    public function getActiveColorList(Request $request)
    {
    	$dataList=Color::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeColorStatus(Request $request)
    {
    	 DB::beginTransaction();

        try{
                $dataInfo=Color::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='colors';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Color Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Color Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Color Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\ColorController@changeColorStatus",$err);
            
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
    public function deleteColor(Request $request)
    {
    	$dataInfo= Color::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Color Delete Successfully.',
            ];

        }else{

            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Somethigwrong, please try again',

            ];

        }

        return response()->json($responseData,200);
    }
}
