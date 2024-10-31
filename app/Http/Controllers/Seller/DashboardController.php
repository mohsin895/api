<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StockInfo;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function getsellerProductOrderDetails(Request $request)
    {
        // $shopInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)->first();
        $totalOrder=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
        $totalProduct=Product::where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
        $totalSellingPrice=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('sell_price');
        $totalSellingQty=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('quantity');
        $totalBuyingCost = StockInfo::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('sell_price');  
        $totalBuyingQty = StockInfo::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('quantity');                  
       $totalRevinue=($totalSellingPrice*$totalSellingQty) - ($totalBuyingCost*$totalBuyingQty);
       $totalSellingCommission=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->sum('commission');
       $totalPendingOrder=OrderItem::where('item_status',9)->where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
       $totalCancelledOrder=OrderItem::where('item_status',8)->where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
       $query=OrderItem::with('orderInfo','orderInfo.customerInfo','productInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->whereMonth('created_at',Carbon::now()->month);

       $recentOrder=$query->paginate($request->numOfData);
        $data=[
            'totalOrder'=>$totalOrder,
            'totalProduct'=>$totalProduct,
            'totalRevinue'=>$totalRevinue,
            'totalSellingCommission'=>$totalSellingCommission,
            'totalPendingOrder'=>$totalPendingOrder,
            'totalCancelledOrder'=>$totalCancelledOrder,
            'recentOrder'=>$recentOrder,
        ];

       

        return response()->json($data,200);
    }

    public function getOrderList(Request $request)
	{
		$orderList=OrderItem::with('orderInfo','orderInfo.customerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)
                           
							->orderBy('id','desc')->limit(10)->get();
		return response()->json($orderList,200);
	}

    public function getData()
    {
            $totalCompleteOrder=OrderItem::where('item_status',7)->where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
            $totalOrder=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
            $totalCancelledOrder=OrderItem::where('item_status',8)->where('seller_id',Auth::guard('seller-api')->user()->id)->count('id');
        $data =[
            ['Name', 'Number of Order Statistic'],
            ['Total Order', $totalOrder],
            ['Total Complete Order', $totalCompleteOrder],
            ['Total Cancelled Order', $totalCancelledOrder],
            
      ];

        return response()->json($data);
    }

    public function getCompanyData()
    {
             $currentYear=date("Y");
            $currentYear1=date("Y")-1;
            $currentYear2=date("Y")-2;
            $currentYear3=date("Y")-3;

            $currentYearTotalSellingPrice=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->whereYear('created_at',Carbon::now()->year)->sum('sell_price');
            $currentYearTotalSellingPrice1=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->whereYear('created_at',Carbon::now()->year - 1)->sum('sell_price');
            $currentYearTotalSellingPrice2=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->whereYear('created_at',Carbon::now()->year - 2)->sum('sell_price');
            $currentYearTotalSellingPrice3=OrderItem::where('seller_id',Auth::guard('seller-api')->user()->id)->whereYear('created_at',Carbon::now()->year - 3)->sum('sell_price');
            
        $data =[
            ['Year', 'Sales', 'Expenses', 'Profit'],
        ["$currentYear3",$currentYearTotalSellingPrice3, 400, 200],
      
        [$currentYear2, $currentYearTotalSellingPrice2, 460, 250],
        [$currentYear1, $currentYearTotalSellingPrice1, 1120, 300],
        [$currentYear, $currentYearTotalSellingPrice, 540, 4550]
            
      ];

        return response()->json($data);
    }

   
}
