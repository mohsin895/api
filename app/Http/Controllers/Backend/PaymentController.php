<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerPayment;
use Exception;
use DB;

class PaymentController extends Controller
{
   public function getSellerRequestPayment(Request $request)
   {
    $query = SellerPayment::with('sellerInfo')->orderBy('id','desc');
    $dataList=$query->paginate($request->numOfData);
    return response()->json($dataList,200);
   }

   public function getPaymentInfo(Request $request)
   {
      $dataInfo=SellerPayment::with('sellerInfo')->find($request->dataId);

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

   public function updatePaymentStatus(Request $request)
   {
      DB::beginTransaction();
      try{
         $dataInfo=SellerPayment::find($request->dataId);
         if(!empty($dataInfo)){
            $dataInfo->status=$request->status;
            $dataInfo->save();
            if($dataInfo->save()){
               DB::commit();
               $responseData=[
                  'errMsgFlag'=>false,
                  'msgFlag'=>true,
                  'msg'=>'Payment Status Update successfully.',
                  'errMsg'=>null,

               ];
            }else{
               DB::rollBack();
               $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'Failed To Update Payment Status.Please Try Again.',
                   ];
            }
   
         }else{
            DB::rollBack();
            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Update Payment Status.Please Try Again.',
                ];
   
         }
         return response()->json($responseData,200);

      }catch(Exception $err){
         DB::rollBack();
            
      
         $responseData=[
                     'errMsgFlag'=>true,
                     'msgFlag'=>false,
                     'msg'=>null,
                     'errMsg'=>'Something Went Wrong.Please Try Again.',
             ];

          return response()->json($responseData,200);

      }
     
   }
}
