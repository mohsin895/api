<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductDeliveryCharge;
use App\Http\Controllers\GeneralController;
use Carbon\Carbon;
use Exception;
use DB;


class ProductDeliveryChargeController extends Controller
{

    public function getActiveCategoryList(Request $request)
    {
        $dataList=Category::where('status',1)->where('look_type',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }

    public function getActiveSubCategoryList(Request $request)
    {
        $query=Category::where('status',1)->where('look_type',2);

        if(isset($request->category) && $request->category!='')
        $query->where('parent_id',$request->category);

    $dataList=$query->orderBy('id','asc')->get();

    return response()->json($dataList,200);

    }
    public function getActiveSubSubCategoryList(Request $request)
    {
        $query=Category::where('status',1)->where('look_type',3);

        if(isset($request->subcategory) && $request->subcategory!='')
        $query->where('parent_id',$request->subcategory);

    $dataList=$query->orderBy('id','asc')->get();

    return response()->json($dataList,200);

    }

    public function updateDeliveryCharge(Request $request)
    {
       DB::beginTransaction();
        try{

            $dataInfo=ProductDeliveryCharge::find($request->dataId);

            if(!empty($dataInfo)){
                $dataInfo->sub_sub_cat_id=$request->subsubcategory;


                $dataInfo->cat_id=$request->category;

               $dataInfo->subCat_id=$request->subcategory;
                $dataInfo->charge_dhaka=$request->dhakaCityCharge;
                $dataInfo->charge_outside=$request->deliveryCharge;
                $dataInfo->status=1;
                 

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                    

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Delivery Charge Information Updated Successfully.',
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
                                            'errMsg'=>'Failed To Update Delivery Charge Infomation.',
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
    public function getDeliveryChargeInfo(Request $request)
    {
       $dataInfo=ProductDeliveryCharge::with('megaCategory','subCategory','normalCategory')->find($request->dataId);

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

    public function addDeliveryCharge(Request $request)
    {
    	DB::beginTransaction();
        try{

            $isCouponExist=ProductDeliveryCharge::whereNull('deleted_at');
            
            if(isset($request->subsubcategory) && !is_null($request->subsubcategory))
                $isCouponExist->where('sub_sub_cat_id',$request->subsubcategory);


            $isCouponExist=$isCouponExist->first();


            if(empty($isCouponExist)){

                    $dataInfo=new ProductDeliveryCharge();

                    $dataInfo->sub_sub_cat_id=$request->subsubcategory;


                    $dataInfo->cat_id=$request->category;
    
                   $dataInfo->subCat_id=$request->subcategory;
                    $dataInfo->charge_dhaka=$request->dhakaCityCharge;
                    $dataInfo->charge_outside=$request->deliveryCharge;
                    $dataInfo->status=1;

                  

                    $dataInfo->created_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='coupon Code';

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
                                        'msg'=>'Delivery Charge Information Added Successfully.',
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
                                            'errMsg'=>'Failed To Add Delivery Charge Infomation.',
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
                            'errMsg'=>'Delivery Charge Already Registered.',
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
    public function getDeliveryChargeList(Request $request)
    {
    	$query=ProductDeliveryCharge::with('megaCategory','subCategory','normalCategory')->whereNull('deleted_at');
        
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
   
    public function changeDeliveryChargeStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=ProductDeliveryCharge::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='coupon code';

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
                            'msg'=>'Coupon Code Status Changed Successfully.',
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
    public function deleteDeliveryCharge(Request $request)
    {
    	$dataInfo = ProductDeliveryCharge::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Coupon Code Delete Successfully .',

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
