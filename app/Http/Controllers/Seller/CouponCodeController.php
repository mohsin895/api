<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use App\Models\Seller;
use App\Models\VoucherDiscount;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponCodeController extends Controller
{
    public function updateCouponCode(Request $request)
    {
      DB::beginTransaction();
        try{

            $dataInfo=VoucherDiscount::find($request->dataId);

            if(!empty($dataInfo)){
                $sellerInfo=Seller::with('shopInfo')
                ->where('id',Auth::guard('seller-api')->user()->id)
                     ->first();
           
                 $dataInfo->isdiscount_in_percent=$request->couponType;
                 $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
                 $dataInfo->voucher_name=$request->voucherName;
                 $dataInfo->discount_amount=$request->couponAmount;
                 $dataInfo->promo_code=$request->couponCode;
                 $dataInfo->startAt=$request->startDate;
                 $dataInfo->endAt=$request->expiredDate;
                 $dataInfo->min_order_value=$request->minOrderValue;
                 $dataInfo->per_customer=$request->perCustomer;
                 $dataInfo->canbe_used=$request->totalVoucher;
                 $dataInfo->product_type=$request->productType;
                 $dataInfo->status=1;
                 $dataInfo->created_at=Carbon::now();
                   
                 

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='coupon Code';

                        $userId=1;

                        $userType=1;

                        $dataType=2;

                        $comment='Coupon Code Updated By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                       DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Coupon Code Information Updated Successfully.',
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
    public function getCouponCodeInfo(Request $request)
    {
       $dataInfo=VoucherDiscount::find($request->dataId);

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

    public function addCouponCode(Request $request)
    {
    	DB::beginTransaction();
        try{

            $isCouponExist=VoucherDiscount::whereNull('deleted_at');
            
            if(isset($request->couponCode ) && !is_null($request->couponCode ))
                $isCouponExist->where('promo_code',$request->couponCode );


            $isCouponExist=$isCouponExist->first();


            if(empty($isCouponExist)){
                $sellerInfo=Seller::with('shopInfo')
                ->where('id',Auth::guard('seller-api')->user()->id)
                     ->first();

                    $dataInfo=new VoucherDiscount();
                    $dataInfo->isdiscount_in_percent=$request->couponType;
                    $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
                    $dataInfo->voucher_name=$request->voucherName;
                    $dataInfo->discount_amount=$request->couponAmount;
                    $dataInfo->promo_code=$request->couponCode;
                    $dataInfo->startAt=$request->startDate;
                    $dataInfo->endAt=$request->expiredDate;
                    $dataInfo->min_order_value=$request->minOrderValue;
                    $dataInfo->per_customer=$request->perCustomer;
                    $dataInfo->canbe_used=$request->totalVoucher;
                    $dataInfo->product_type=$request->productType;
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
                                        'msg'=>'Vaocher Added Successfully.',
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
                                            'errMsg'=>'Failed To Add Coupon Code Infomation.',
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
                            'errMsg'=>'Coupon Code Already Registered.',
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
    public function getCouponCodeList(Request $request)
    {
    	$query=VoucherDiscount::whereNull('deleted_at')->where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc');
        
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
                $dataInfo=VoucherDiscount::find($request->dataId);

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
    public function deleteCouponCode(Request $request)
    {
    	$dataInfo = VoucherDiscount::find($request->dataId);
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
