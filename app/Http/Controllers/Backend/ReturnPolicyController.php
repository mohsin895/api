<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Models\ReturnPolicy;
use Exception;
use Carbon\Carbon;
use DB;

class ReturnPolicyController extends Controller
{
  
    public function getPolicyList(Request $request)
    {
    	$query=ReturnPolicy::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%')
                        ->orWhere('name_bd','like',$request->name.'%');
            });
        }

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }

    public function updatePolicy(Request $request)
    {
        DB::beginTransaction();
        try{
             
             $dataInfo=ReturnPolicy::find($request->dataId);

             if(!empty($dataInfo)){
            

                $dataInfo->name=$request->name;



                // $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->updated_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='Return Policy';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Return Policy Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Update Return Policy.',
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
                                'errMsg'=>'Failed To Update Return Policy.Please Try Again.',
                        ];
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
             }

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\BrandController@updateBrand");
            
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
    public function getPolicyInfo(Request $request)
    {
       $dataInfo=ReturnPolicy::find($request->dataId);

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

    public function addPolicy(Request $request)
    {
    	
        DB::beginTransaction();
        try{
             $dataInfo=new ReturnPolicy();

                $dataInfo->name=$request->name;

                $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='ReturnPolicy';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='ReturnPolicy Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save Return Policy.',
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
                                'errMsg'=>'Failed To Save Return Policy.Please Try Again.',
                        ];
                }
            

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\ReturnPolicyController@addReturnPolicy");
            
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
  
   
    public function changePolicyStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=ReturnPolicy::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='Return Policy';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Return Policy Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Return Policy Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Return Policy Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\BrandController@changePolicyStatus",$err);
            
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
    public function deletePolicy(Request $request)
    {
    	$dataInfo= ReturnPolicy::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Return Policy Delete Successfully.',
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
