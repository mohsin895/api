<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Carbon\Carbon;
use DB;

class OrderController extends Controller
{
    public function getOrderInfo(Request $request)
    {

       $dataInfo=Order::with('customerInfo','orderItems','orderItems.productInfo','orderItems.stockInfo','orderItems.stockInfo.colorInfo','orderItems.stockInfo.sizeInfo','addressInfo','statusInfo','addressInfo.districtInfo','addressInfo.unionInfo','addressInfo.thanaInfo','orderItems.sellerInfo','orderItems.sellerInfo.shopInfo','promoCode','orderItems.sellerOrderStatus')->where('id',$request->dataId)->first();
       $orderItem=OrderItem::where('order_id',$dataInfo->id)->where('item_status',2)->get();
       $totalPrice=0;
       $deliveryCharge=0;
       $discount=0;
       foreach($orderItem as $item){
           $totalPrice +=$item->sell_price;
           $deliveryCharge +=$item->delivery_charge;
           $discount +=$item->discount;
       }
       $totalAmount=$totalPrice + $deliveryCharge;

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo,
                'totalPrice'=>$totalPrice,
                'deliveryCharge'=>$deliveryCharge,
                'totalAmount'=>$totalAmount,
                'discount'=>$discount,
             
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


    public function printInvoice(Request $request)
    {

        $dataInfo=Order::with('customerInfo','orderItems','orderItems.productInfo','orderItems.stockInfo','orderItems.stockInfo.colorInfo','orderItems.stockInfo.sizeInfo','addressInfo','statusInfo','addressInfo.districtInfo','addressInfo.unionInfo','addressInfo.thanaInfo','orderItems.sellerInfo','orderItems.sellerInfo.shopInfo','promoCode','orderItems.sellerOrderStatus')->where('id',$request->dataId)->first();
        $orderItem=OrderItem::where('order_id',$dataInfo->id)->where('item_status',2)->get();
        $totalPrice=0;
        $deliveryCharge=0;
        $discount=0;
        foreach($orderItem as $item){
            $totalPrice +=$item->sell_price;
            $deliveryCharge +=$item->delivery_charge;
            $discount +=$item->discount;
        }
        $totalAmount=$totalPrice + $deliveryCharge;
 
        if(!empty($dataInfo)) {
           $responseData=[
                 'errMsgFlag'=>false,
                 'msgFlag'=>true,
                 'errMsg'=>null,
                 'msg'=>null,
                 'dataInfo'=>$dataInfo,
                 'totalPrice'=>$totalPrice,
                 'deliveryCharge'=>$deliveryCharge,
                 'totalAmount'=>$totalAmount,
                 'discount'=>$discount,
              
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

       $dataInfo=OrderItem::with('orderInfo.customerInfo','productInfo','stockInfo','stockInfo.colorInfo','stockInfo.sizeInfo','orderInfo.addressInfo','orderInfo.statusInfo','orderInfo.addressInfo.districtInfo','orderInfo.addressInfo.unionInfo','orderInfo.addressInfo.thanaInfo','sellerInfo','sellerInfo.shopInfo')->where('id',$request->dataId)->first();



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
    public function addOrder(Request $request)
    {
    	
    }
    public function getOrderList(Request $request)
    {
        if($request->dataId==1){
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->orderBy('id','desc')->whereNull('deleted_at');
           
            if(isset($request->status) && !is_null($request->status))
                $query->where('status',$request->status);
                if(isset($request->orderDateTo) && !is_null($request->orderDateTo))
                $query->where('status',$request->orderDateTo);
    
            
    
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==2){

            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_proccessing',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_shipping',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_waiting_for_delivery',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_delivered',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_complete',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_cancelled',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_pending',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_refund',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_returned',1)->orderBy('id','desc')->whereNull('deleted_at');

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
            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->where('is_delivered_failed',1)->orderBy('id','desc')->whereNull('deleted_at');

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

            $query=Order::withCount('orderItems')->with('customerInfo','addressInfo','statusInfo','orderItems')->orderBy('id','desc')->whereNull('deleted_at');
        
            if(isset($request->status) && !is_null($request->status))
                $query->where('status',$request->status);
    
            
    
            $dataList=$query->paginate($request->numOfData);
        }


        $allOrderTotal=Order::whereNull('deleted_at')->count('id');
        $newAllOrder=Order::where('status',1)->whereNull('deleted_at')->count('id');
         $processingAllOrderd=Order::where('is_proccessing',1)->whereNull('deleted_at')->count('id');
        $shippingAllOrderd=Order::where('is_shipping',1)->whereNull('deleted_at')->count('id');
         $waitingForDeliveredAllOrderd=Order::where('is_waiting_for_delivery',1)->whereNull('deleted_at')->count('id');
        $deliveredAllOrderd=Order::where('is_delivered',1)->whereNull('deleted_at')->count('id');
         $completedAllOrderd=Order::where('is_complete',1)->whereNull('deleted_at')->count('id');
        $cancelledAllOred=Order::where('is_cancelled',1)->whereNull('deleted_at')->count('id');
         $penddingAllOrderd=Order::where('is_pending',1)->whereNull('deleted_at')->count('id');
        $refundAllOrderd=Order::where('is_refund',1)->whereNull('deleted_at')->count('id');
         $returnedAllOrderd=Order::where('is_returned',1)->whereNull('deleted_at')->count('id');
        $failedToDeliveredAllOrderd=Order::where('is_delivered_failed',1)->whereNull('deleted_at')->count('id');
  
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
    }

    public function getOrderItemList(Request $request)
    {
    	$query=OrderItem::with('orderInfo','orderInfo.customerInfo','orderInfo.addressInfo')->orderBy('id','desc')->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getActiveOrderList(Request $request)
    {
    	
    }
    public function changeOrderStatus(Request $request)
    {
    	
    }
    public function changeItemStatus(Request $request)
    {
      $dataInfo  = OrderItem::find($request->dataId);
      $dataInfo->item_status = $request->item_status;
      $dataInfo->save();
      if($dataInfo){
        $responseData=[
            'errMsgFlag'=>true,
           'msgFlag'=>false,
           'msg'=>null,
           'errMsg'=>'Order Delete Successfully.',
        ];

    }else{

        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Somethigwrong, please try again',

        ];

    }
    return response()->json($responseData, 200);
    }
    public function deleteOrder(Request $request)
    {
        $dataInfo = Order::find($request->dataId);
        $getOrderItem = OrderItem::where('order_id',$request->dataId)->get();
        foreach($getOrderItem as $item){
            $oerderItem =OrderItem::find($item->id);
            $oerderItem->delete();
        }
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Order Delete Successfully.',
            ];

        }else{

            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Somethigwrong, please try again',

            ];

        }
        return response()->json($responseData, 200);
    	
    }

    public function singleProductDelete(Request $request)
    {
        $dataInfo = OrderItem::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Order Product Delete Successfully.',
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
    public function singleProductShow(Request $request)
    {
        $itemDetails = OrderItem::with('productInfo','shopInfo','sellerInfo')->where('id',$request->dataId)->first();
       

        return response()->json($itemDetails,200);
    }
    public function singleProductUpdate(Request $request)
    {
        $itemDetails = OrderItem::where('id',$request->dataId)->first();
    
        $itemDetails->item_status = $request->item_status;
        $itemDetails->save();
        if($itemDetails->save())
        {
            $itemstatus=new OrderStatus();
            $itemstatus->item_id=$itemDetails->id;
            $itemstatus->order_id=$itemDetails->order_id;
            $itemstatus->customer_message=$request->message;
            $itemstatus->status=$request->item_status;
            $itemstatus->save();

            $itemStatusSingle = OrderStatus::where('seller_id','!=', NULL)->where('item_id',$itemDetails->id)->first();
            if(!empty($itemStatusSingle)){

             
                    if($itemDetails->item_status ==2){
                     $itemStatusSingle->is_processing=1;
                     $itemStatusSingle->processing_date=Carbon::now();
                    }elseif($itemDetails->item_status ==3){
                        $itemStatusSingle->is_packaging=1;
                        $itemStatusSingle->packaging_date=Carbon::now();
                    }elseif($itemDetails->item_status ==4){
                        $itemStatusSingle->is_shipping=1;
                        $itemStatusSingle->shipping_date=Carbon::now();
                    }elseif($itemDetails->item_status ==5){
                        $itemStatusSingle->is_waiting_for_delivery=1;
                        $itemStatusSingle->waiting_for_delivery_date=Carbon::now();
                    }elseif($itemDetails->item_status ==6){
                        $itemStatusSingle->is_delivered=1;
                        $itemStatusSingle->delivered_date=Carbon::now();
                    }elseif($itemDetails->item_status ==7){
                        $itemStatusSingle->is_complete=1;
                        $itemStatusSingle->complete_date=Carbon::now();
                    }elseif($itemDetails->item_status ==8){
                        $itemStatusSingle->is_cancel=1;
                        $itemStatusSingle->cancel_date=Carbon::now();
                    }elseif($itemDetails->item_status ==9){
                        $itemStatusSingle->is_pending=1;
                        $itemStatusSingle->pending_date=Carbon::now();
                    }elseif($itemDetails->item_status ==10){
                        $itemStatusSingle->is_refund=1;
                        $itemStatusSingle->refund_date=Carbon::now();
                    }elseif($itemDetails->item_status ==11){
                        $itemStatusSingle->is_returned=1;
                        $itemStatusSingle->returned_date=Carbon::now();
                    }elseif($itemDetails->item_status ==12){
                        $itemStatusSingle->is_delivered_failed=1;
                        $itemStatusSingle->failed_to_delevered_date=Carbon::now();
                    }
                    $itemStatusSingle->save();
                    if($itemStatusSingle->save()){
                        $order = Order::where('id',$itemDetails->order_id)->first();

                        if($itemDetails->item_status ==2){
                            $order->is_processing=1;
                            $order->status=0;
                          
                           }elseif($itemDetails->item_status ==3){
                               $order->is_packing=1;
                                $order->status=0;
                              
                           }elseif($itemDetails->item_status ==4){
                               $order->is_shipping=1;
                              $order->status=0;
                           }elseif($itemDetails->item_status ==5){
                               $order->is_waiting_for_delivery=1;
                              $order->status=0;
                           }elseif($itemDetails->item_status ==6){
                               $order->is_delivered=1;
                              $order->status=0;
                           }elseif($itemDetails->item_status ==7){
                               $order->is_complete=1;
                              $order->status=0;
                           }elseif($itemDetails->item_status ==8){
                               $order->is_cancelled=1;
                               $order->status=0;
                           }elseif($itemDetails->item_status ==9){
                               $order->is_pending=1;
                             $order->status=0;
                           }elseif($itemDetails->item_status ==10){
                               $order->is_refund=1;
                           $order->status=0;
                           }elseif($itemDetails->item_status ==11){
                               $order->is_returned=1;
                              $order->status=0;
                           }elseif($itemDetails->item_status ==12){
                               $order->is_delivered_failed=1;
                              $order->status=0;
                           }
                           $order->save();
                    }


        
                   

            }

            $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Successfully Save Order Status.',
                        'errMsg'=>null,
                ];
                return response()->json($responseData,200);
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
                return response()->json($responseData,200);
        }
       

        
    }

    public function singleProductPrint(Request $request)
    {
        $dataListPrint = OrderItem::with('productInfo','shopInfo','sellerInfo')->where('item_status',5)->where('id',$request->dataId)->get();
       


        return response()->json($dataListPrint,200);
    }
}
