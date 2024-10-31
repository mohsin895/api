<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\OrderShop;
use App\Models\OrderStatus;
use Auth;
use Carbon\Carbon;
use DB;


class OrderController extends Controller
{
    public function getOrderList(Request $request)
    {

        if($request->dataId==1){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc')->whereNull('deleted_at');
           
            if(isset($request->status) && !is_null($request->status))
                $query->where('status',$request->status);
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
                $query->where('status',$request->orderDateTo);
    
            
    
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==2){

            $query=OrderShop::with('shopInfo','orderInfo','shopInfo.productInfo','shopInfo.orderStockInfo','shopInfo.orderStockInfo.stockInfo.sizeVariantInfo','shopInfo.orderStockInfo.stockInfo.sizeInfo','shopInfo.orderStockInfo.stockInfo.colorInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);
            // if(isset($request->orderNumber) && !is_null($request->orderNumber))
            //     $query->where('randomOrderCode',$request->orderNumber);
            //     if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
            // 	$query->whereDate('created_at',$request->orderDateTo);
            //     if(isset($request->orderDateFrom) && !is_null($request->orderDateFrom))
            // 	$query->whereDate('created_at',$request->orderDateFrom);
    
            
    
           

        }elseif($request->dataId==3){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',3)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==4){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',4)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==5){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',5)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==6){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',6)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==7){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',7)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==8){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',8)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==9){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',9)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==10){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',10)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==11){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',11)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==12){
            $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_status',12)->orderBy('id','desc')->whereNull('deleted_at');

            if(isset($request->orderDateTo) && isset($request->orderDateFrom)){

                if($request->orderDateTo!='' && $request->orderDateFrom!='')
                    $query->whereBetween(DB::raw('date(created_at)'),[$request->orderDateTo,$request->orderDateFrom]);
                    elseif($request->orderDateTo!='')
                    $query->whereDate('created_at',$request->orderDateTo);
                elseif($request->orderDateFrom!='')
                    $query->whereDate('created_at','<=',$request->orderDateFrom);
                elseif($request->orderNumber)
                $query->where('randomOrderCode',$request->orderNumber);
            }elseif($request->orderNumber){
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);

            }elseif($request->orderDateTo){
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
             	$query->whereDate('created_at',$request->orderDateTo);
            }else{
                if(isset($request->orderNumber) && !is_null($request->orderNumber))
                $query->where('randomOrderCode',$request->orderNumber);
            }
            $dataList=$query->paginate($request->numOfData);

        }
        
        else{

            $query=OrderShop::with(['shopInfo','orderInfo','shopInfo.productInfo','shopInfo.orderStockInfo','shopInfo.orderStockInfo.stockInfo.sizeVariantInfo','shopInfo.orderStockInfo.stockInfo.sizeInfo','shopInfo.orderStockInfo.stockInfo.colorInfo','orderInfo.orderItems'])->where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc');
        
            if(isset($request->status) && !is_null($request->status))
                $query->where('status',$request->status);
    
            
    
            $dataList=$query->paginate($request->numOfData);
        }


        $allOrderTotal=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
        $newAllOrder=OrderItem::where('status',1)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
         $processingAllOrderd=OrderItem::where('item_status',2)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
        $shippingAllOrderd=OrderItem::where('item_status',4)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
         $waitingForDeliveredAllOrderd=OrderItem::where('item_status',5)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
        $deliveredAllOrderd=OrderItem::where('item_status',6)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
         $completedAllOrderd=OrderItem::where('item_status',7)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
        $cancelledAllOred=OrderItem::where('item_status',8)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
         $penddingAllOrderd=OrderItem::where('item_status',9)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
        $refundAllOrderd=OrderItem::where('item_status',10)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
         $returnedAllOrderd=OrderItem::where('item_status',11)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
        $failedToDeliveredAllOrderd=OrderItem::where('item_status',12)->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->count('id');
  
        $data=[
            'dataList'=>$dataList,
            'allOrderTotal'=>$allOrderTotal,
            'newAllOrder'=>$newAllOrder,
             'processingAllOrderd'=>$processingAllOrderd,
             'shippingAllOrderd'=>$shippingAllOrderd,
            'waitingForDeliveredAllOrderd'=>$waitingForDeliveredAllOrderd,
            'deliveredAllOrderd'=>$deliveredAllOrderd,
             'completedAllOrderd'=>$completedAllOrderd,
            'cancelledAllOred'=>$cancelledAllOred,
            'penddingAllOrderd'=>$penddingAllOrderd,
             'refundAllOrderd'=>$refundAllOrderd,
            'returnedAllOrderd'=>$returnedAllOrderd,
            'failedToDeliveredAllOrderd'=>$failedToDeliveredAllOrderd,
        ];

        return response()->json($data,200);



        // $shopInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)->first();
        //$query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->orderBy('id','desc');
                               

        // $query=Order::with('statusInfo')->whereNull('deleted_at')
        //                 ->whereIn('id',$orderIds);

        // $dataList=$query->paginate($request->numOfData);

        // return response()->json($dataList,200);
    }

    public function getOrderReview(Request $request)
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

    public function getOrderProductDetails(Request $request)
    {
        // $shopInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)->first();
        $dataInfo=OrderItem::with('orderInfo.customerInfo','sellerOrderStatus','productInfo','stockInfo','stockInfo.colorInfo','stockInfo.sizeInfo','stockInfo.sizeVariantInfo','orderInfo.addressInfo','orderInfo.statusInfo','orderInfo.addressInfo.districtInfo','orderInfo.addressInfo.unionInfo','orderInfo.addressInfo.thanaInfo','sellerInfo','sellerInfo.shopInfo')->where('id',$request->dataId)->first();
                               

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

    public function singleProductUpdate(Request $request)
    {
        $itemDetails = OrderItem::where('id',$request->dataId)->first();
    
        $itemDetails->seller_status = $request->seller_status;
        $itemDetails->save();
        if($itemDetails->save())
        {
            $itemStatus = OrderStatus::where('seller_id',Auth::guard('seller-api')->user()->id)->where('item_id',$itemDetails->id)->first();
            if(!empty($itemStatus)){

       
             
                    if($itemDetails->seller_status ==1){
                     $itemStatus->is_accept=1;
                     $itemStatus->accept_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==2){
                        $itemStatus->is_processing_seller=1;
                        $itemStatus->processing_seller_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==3){
                        $itemStatus->is_complete_seller=1;
                        $itemStatus->complete_seller_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==4){
                        $itemStatus->is_cancelled_seller=1;
                        $itemStatus->cancelle_seller_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==5){
                        $itemStatus->is_pending_seller=1;
                        $itemStatus->pending_seller_date=Carbon::now();
                    }
                    $itemStatus->save();
        
                   

            }else{
               
                    $itemStatus=new OrderStatus();
                    $itemStatus->seller_id =Auth::guard('seller-api')->user()->id;
                    $itemStatus->item_id =$itemDetails->id;
                    if($itemDetails->seller_status ==1){
                     $itemStatus->is_accept=1;
                     $itemStatus->accept_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==2){
                        $itemStatus->is_processing_seller=1;
                        $itemStatus->processing_seller_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==3){
                        $itemStatus->is_complete_seller=1;
                        $itemStatus->	complete_seller_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==4){
                        $itemStatus->is_cancelled_seller=1;
                        $itemStatus->cancelle_seller_date=Carbon::now();
                    }elseif($itemDetails->seller_status ==5){
                        $itemStatus->is_pending_seller=1;
                        $itemStatus->pending_seller_date=Carbon::now();
                    }
                    $itemStatus->save();
        
                   
            }

            $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Successfully Save Order Status.',
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
                        'errMsg'=>'Failed To Save Banner.Please Try Again.',
                ];
        }
       

        return response()->json($responseData,200);
    }

    public function getOrderSaleList(Request $request)
    {
        // $shopInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)->first();
        $query=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->where('seller_status',2);
                               

        // $query=Order::with('statusInfo')->whereNull('deleted_at')
        //                 ->whereIn('id',$orderIds);

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList,200);
    }


    public function singleProductPrint(Request $request)
    {
        $dataListPrint = OrderItem::with('productInfo','shopInfo','sellerInfo')->where('item_status',5)->where('id',$request->dataId)->get();
       


        return response()->json($dataListPrint,200);
    }


    public function getOrderInfo(Request $request)
    {

        $dataInfo=OrderItem::with('orderInfo.customerInfo','productInfo','stockInfo','stockInfo.colorInfo','stockInfo.sizeInfo','stockInfo.sizeVariantInfo','orderInfo.addressInfo','orderInfo.statusInfo','orderInfo.addressInfo.districtInfo','orderInfo.addressInfo.unionInfo','orderInfo.addressInfo.thanaInfo','sellerInfo','sellerInfo.shopInfo')->where('id',$request->dataId)->first();

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo,
            
             
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

    public function getOrderItemInfo(Request $request)
    {

       $dataInfo=OrderItem::with('orderInfo.customerInfo','productInfo','stockInfo','stockInfo.colorInfo','stockInfo.sizeInfo','stockInfo.sizeVariantInfo','orderInfo.addressInfo','orderInfo.statusInfo','orderInfo.addressInfo.districtInfo','orderInfo.addressInfo.unionInfo','orderInfo.addressInfo.thanaInfo','sellerInfo','sellerInfo.shopInfo')->where('id',$request->dataId)->first();



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
    
}
