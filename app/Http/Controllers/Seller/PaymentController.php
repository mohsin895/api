<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\SellerPayment;
use App\Models\Financial;
use Exception;
use Auth;
use DB;

class PaymentController extends Controller
{
    public function getPayment(Request $request)
    {
      
         $itemPrice=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('sell_price');
         $itemComission=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('commission');
         $itemDiscount=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('discount');
         $totalWithdraw=SellerPayment::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('amount');
         $totalPaynding=SellerPayment::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('amount');
         $totalComplete=SellerPayment::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('amount');
         $itemPrice=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('sell_price');
         $totalPayable=$itemPrice-($itemComission +$itemDiscount);
       $data =[
       'itemPrice'=>$itemPrice,
       'itemComission'=>$itemComission,
       'itemDiscount'=>$itemDiscount,
       'totalWithdraw'=>$totalWithdraw,
       'totalPaynding'=>$totalPaynding,
       'totalComplete'=>$totalComplete,
       'totalPayable'=>$totalPayable,
        
  ];

    return response()->json($data);
    


    }

    public function RequestPayment(Request $request)
    {
      DB::beginTransaction();
       try{
        $itemPrice=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('sell_price');
        $itemComission=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('commission');
        $itemDiscount=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('discount');
        $totalWithdraw=SellerPayment::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('amount');
        $totalSellingAmount=$itemPrice-($itemComission + $itemDiscount);
        $totalAmount = $totalSellingAmount - $totalWithdraw;
        if($totalAmount < $request->amount){
         
        DB::commit();
          $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Please Enter Less then Total Amount.'

          ];
          return response()->json($responseData,200);
        }else{
          $dataInfo=new SellerPayment();
        $dataInfo->seller_id=$request->dataId;
        $dataInfo->bank_name=$request->bank_name;
        $dataInfo->branch_name=$request->branch_name;
        $dataInfo->account_name=$request->account_name;
        $dataInfo->acount_number=$request->account_number;
        $dataInfo->routing_no=$request->routing_no;
        $dataInfo->swift_code=$request->swift_code;
        $dataInfo->amount=$request->amount;
        $dataInfo->comments=$request->comments;
        $dataInfo->save();
        if($dataInfo->save()){
          DB::commit();
          $responseData=[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'msg'=>'Payment request Send Successfully',
            'errMsg'=>null,

          ];

        }else{
          $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Something went to wrong, Plese Try Again.'

          ];

        }
     return response()->json($responseData,200);

        }
        
       }catch(Exception $err){
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

    public function getAllTranscation(Request $request)
    {
      $query=SellerPayment::where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc');
      $dataList=$query->paginate($request->numOfData);
      return response()->json($dataList,200);
    }

    public function getPaymentInfo(Request $request)
    {
      $dataInfo=Financial::where('seller_id',Auth::guard('seller-api')->user()->id)->first();
      return response()->json($dataInfo,200);
    }
}
