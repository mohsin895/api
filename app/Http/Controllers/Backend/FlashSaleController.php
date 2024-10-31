<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use Carbon\Carbon;
use Exception;
use DB;

class FlashSaleController extends Controller
{
    public function updateFlashSale(Request $request)
    {
       DB::beginTransaction();
        try{

            $dataInfo=FlashSale::find($request->dataId);

            if(!empty($dataInfo)){
                    $dataInfo->startDate=$request->startDate;


                    $dataInfo->startTime=$request->startTime;

                    $dataInfo->endDate=$request->endDate;
                    $dataInfo->endTime=$request->endTime;

                      $dataInfo->status=1;
                   
                 

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='Flash Sale';

                        $userId=1;

                        $userType=1;

                        $dataType=2;

                        $comment='Flash Sale Updated By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Flash SaleInformation Updated Successfully.',
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
                                            'errMsg'=>'Failed To Update Coupon Code Infomation.',
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
            
            GeneralController::storeSystemErrorLog($err,"Backends\StaffController@updateStaff");
            
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
    public function getFlashSaleInfo(Request $request)
    {
       $dataInfo=FlashSale::find($request->dataId);

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

    public function addFlashSale(Request $request)
    {
    	DB::beginTransaction();
        try{

         


          

                    $dataInfo=new FlashSale();

                    $dataInfo->startDate=$request->startDate;


                    $dataInfo->startTime=$request->startTime;

                    $dataInfo->endDate=$request->endDate;
                    $dataInfo->endTime=$request->endTime;

                      $dataInfo->status=1;

                  

                    $dataInfo->created_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='Flash Sale';

                        $userId=1;

                        $userType=1;

                        $dataType=1;

                        $comment='Staff Added By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Flash Sale Information Added Successfully.',
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
                                            'errMsg'=>'Failed To Add Flash Sale Infomation.',
                                        ];

                            return response()->json($responseData,200);
                    }

            
           
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\StaffController@addStaff");
            
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
    public function getFlashSaleList(Request $request)
    {
    	$query=FlashSale::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name))
            $query->where('name','like',$request->name.'%');

        if(isset($request->phone) && !is_null($request->phone))
            $query->where('phone','like',$request->phone.'%');

        if(isset($request->email) && !is_null($request->email))
            $query->where('email','like',$request->email.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
   
    public function changeStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=FlashSale::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='Flash Sale';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Staff Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->name.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Flash Sale Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Staff Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\StaffController@changeStaffStatus",$err);
            
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
    public function deleteFlashSale(Request $request)
    {
    	$dataInfo = FlashSale::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Flash Sale Delete Successfully .',

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
