<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingCharge;

class ShippinChargeController extends Controller
{
    public function shippingCharge(Request $request)
   {
   $dataInfo = ShippingCharge::find(1);
   if(!empty($dataInfo)){
    $dataInfo->argentInsideDhaka = $request->argentInsideDhaka;
    $dataInfo->argentOutsideDhaka = $request->argentOutsideDhaka;
    $dataInfo->veryArgentInsideDhaka = $request->shop_address;
    $dataInfo->veryArgentOutsideDhaka = $request->veryArgentOutsideDhaka;
  
   
    $dataInfo->save(); 

    if($dataInfo){
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Shipping Charge Update Successfully .',

        ];

    }else{
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Failed To Update General Setting, Try Again.'
        ];

    }
   

   }else{
    $dataInfo = new ShippingCharge();
    $dataInfo->argentInsideDhaka = $request->argentInsideDhaka;
    $dataInfo->argentOutsideDhaka = $request->argentOutsideDhaka;
    $dataInfo->veryArgentInsideDhaka = $request->veryArgentInsideDhaka;
    $dataInfo->veryArgentOutsideDhaka = $request->veryArgentOutsideDhaka;
  
   
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'Shipping Charge Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update Shipping Charge. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 
   public function shippingChargeInfo(Request $request)
   {
    $dataInfo = ShippingCharge::find(1);
    $flag=(!empty($dataInfo)) ? true:false;
    $responseData=[
                'errMsgFlag'=>!$flag,
                'msgFlag'=>$flag,
                'msg'=>null,
                'errMsg'=>null,
                'dataInfo'=>$dataInfo,
            ];

    return response()->json($responseData,200);
   }
}
