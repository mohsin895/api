<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\OrderStatus;
use Auth;
use Carbon\Carbon;
use DB;

class ReviewController extends Controller
{
    public function getReview(Request $request)
    {

        if($request->dataId==1){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo','productInfo','orderReviewInfo','orderReviewInfo.images')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',[6,7])->orderBy('id','desc')->whereNull('deleted_at');
           
 
    
            
    
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==2){

            $query=OrderItem::with('orderInfo','orderInfo.customerInfo','productInfo','orderReviewInfo','orderReviewInfo.images')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',[6,7])->orderBy('id','desc')->whereNull('deleted_at');

            // if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

            //     if($request->orderDateTo!='' && $request->orderDateFrom!='')
            //         $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
            //         elseif($request->orderDateTo!='')
            //         $query->whereDate('created_at',$request->orderDateTo);
            //     elseif($request->orderDateFrom!='')
            //         $query->whereDate('created_at','<=',$request->orderDateFrom);
            //     elseif($request->orderNumber)
            //     $query->where('randomOrderCode',$request->orderNumber);
            // }elseif($request->orderNumber){
            //     if(isset($request->orderNumber) && !is_null($request->orderNumber))
            //     $query->where('randomOrderCode',$request->orderNumber);

            // }elseif($request->orderDateTo){
            //     if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
            //  	$query->whereDate('created_at',$request->orderDateTo);
            // }else{
            //     if(isset($request->orderNumber) && !is_null($request->orderNumber))
            //     $query->where('randomOrderCode',$request->orderNumber);
            // }
            $dataList=$query->paginate($request->numOfData);
            // if(isset($request->orderNumber) && !is_null($request->orderNumber))
            //     $query->where('randomOrderCode',$request->orderNumber);
            //     if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
            // 	$query->whereDate('created_at',$request->orderDateTo);
            //     if(isset($request->orderDateFrom) && !is_null($request->orderDateFrom))
            // 	$query->whereDate('created_at',$request->orderDateFrom);
    
            
    
           

        }
        
        else{

            $query=OrderItem::with('orderInfo','orderInfo.customerInfo','productInfo','orderReviewInfo','orderReviewInfo.images')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',[6,7])->orderBy('id','desc')->whereNull('deleted_at');
        
            if(isset($request->status) && !is_null($request->status))
                $query->where('status',$request->status);
    
            
    
            $dataList=$query->paginate($request->numOfData);
        }


       
        $data=[
            'dataList'=>$dataList,
    
        ];

        return response()->json($data,200);



        // $shopInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)->first();
        //$query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc');
                               

        // $query=Order::with('statusInfo')->whereNull('deleted_at')
        //                 ->whereIn('id',$orderIds);

        // $dataList=$query->paginate($request->numOfData);

        // return response()->json($dataList,200);
    }

}
