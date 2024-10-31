<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Carbon\Carbon;
use Exception;
use DB;

class SubscriberController extends Controller
{
    public function getSubscriberList(Request $request)
    {
        $query=Subscription::orderBy('id','desc');
        
      
        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function changeSubscriberStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Subscription::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='Subscriber';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.'  Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Slider Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>' Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Slider Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\SliderController@changeSliderStatus",$err);
            
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

    public function deleteSubscriber(Request $request)
    {
    	$dataInfo = Subscription::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Subscriber Delete Successfully .',

            ];

        }else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To delete Role, Try Again.'
            ];

        }
        return response()->json($responseData,200);
    }
}
