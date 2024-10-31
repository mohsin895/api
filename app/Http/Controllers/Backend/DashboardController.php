<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Seller;
use App\Models\StockInfo;
use App\Models\Order;
use App\Models\OrderItem;
use DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function getCardInfo(Request $request)
    {
    	$totalBrand=Brand::whereNull('deleted_at')
    							->where('status',1)
    								->count();

    	$totalCategory=Category::whereNull('deleted_at')
    							->where('status',1)
    								->count();

    	$totalSeller=Seller::whereNull('deleted_at')
    								->count();

    	$totalProduct=Product::whereNull('deleted_at')
    							->where('status',1)
    								->where('published',1)
    									->count();

    	$totalCustomer=Customer::whereNull('deleted_at')
    							->where('status',1)
    								->count();

    	$totalOrder=Order::whereNull('deleted_at')
							->count();

		$totalPendingOrder=Order::whereNull('deleted_at')
									->where('is_proccessing',1)
										->count();

		$totalConfirmOrder=Order::whereNull('deleted_at')
									->where('is_shipping',1)
										->count();

		$totalCancelOrder=Order::whereNull('deleted_at')
											->where('is_cancelled',1)
												->count();

		$totalDeliveredOrder=Order::whereNull('deleted_at')
											->where('is_delivered',1)
												->count();

		$totalSales=Order::select(DB::raw('(sum(price)-(sum(discount)+sum(invoice_discount)+sum(promo_discount))) as totalSale'))
										->whereNull('deleted_at')
											->where('is_delivered',1)
												->first();

		$totalDeliveryCharge=Order::select(DB::raw('sum(delivery_charge) as deliveryCharge'))
										->whereNull('deleted_at')
											->where('is_delivered',1)
												->first();
	 $topCategories = Category::withCount('products')->orderByDesc('products_count')->take(10)->get();
	  
	//    foreach($topCategory as $cat){
    //    $product = Product::where('category_id',$cat->id)->count('id');

	//    }

		// $totalCommission=Order::whereNull('deleted_at')
		// 									->where('is_proccessing',1)
		// 										->count();

		$data=[
			'topCategories'=>$topCategories,
			'totalBrand'=>$totalBrand,
			'totalCategory'=>$totalCategory,
			'totalSeller'=>$totalSeller,
			'totalProduct'=>$totalProduct,
			'totalCustomer'=>$totalCustomer,
			'totalOrder'=>$totalOrder,
			'totalConfirmOrder'=>$totalConfirmOrder,
			'totalPendingOrder'=>$totalPendingOrder,
			'totalCancelOrder'=>$totalCancelOrder,
			'totalDeliveredOrder'=>$totalDeliveredOrder,
			'totalDeliveryCharge'=>(int)$totalDeliveryCharge['deliveryCharge'],
			'totalSales'=>(int)$totalSales['totalSale'],
		];

		// return view('welcome',compact('data'));
		return response()->json($data,200);;
    }

	public function getOrderList(Request $request)
	{
		$totalOrderItem=Order::with('customerInfo','orderItems','orderItems.productInfo')->whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year)->orderBy('id','desc')->take(10)->get();
		
		$orderList=[
			'totalOrderItem'=>$totalOrderItem,
			

		];
		return response()->json($orderList,200);
	}

	public function getsellerProductOrderDetails(Request $request)
    {
        // $shopInfo=Shop::first();
        $totalOrder=OrderItem::count('id');
        $totalProduct=Product::count('id');
        $totalSellingPrice=OrderItem::sum('sell_price');
        $totalSellingQty=OrderItem::sum('quantity');
        $totalBuyingCost = StockInfo::sum('purchase_price');  
        $totalBuyingQty = StockInfo::sum('quantity');                  
       $totalRevinue=($totalSellingPrice*$totalSellingQty) - ($totalBuyingCost*$totalBuyingQty);
       $totalSellingCommission=OrderItem::sum('commission');
       $totalPendingOrder=OrderItem::where('item_status',9)->count('id');
       $totalCancelledOrder=OrderItem::where('item_status',8)->count('id');
        // $query=Order::with('statusInfo')->whereNull('deleted_at')
        //                 ->whereIn('id',$orderIds);
        $data=[
            'totalOrder'=>$totalOrder,
            'totalProduct'=>$totalProduct,
            'totalRevinue'=>$totalRevinue,
            'totalSellingCommission'=>$totalSellingCommission,
            'totalPendingOrder'=>$totalPendingOrder,
            'totalCancelledOrder'=>$totalCancelledOrder,
        ];

       

        return response()->json($data,200);
    }

   

    public function getData()
    {
            $totalCompleteOrder=OrderItem::where('item_status',7)->count('id');
            $totalOrder=OrderItem::count('id');
            $totalCancelledOrder=OrderItem::where('item_status',8)->count('id');
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

            $currentYearTotalSellingPrice=OrderItem::whereYear('created_at',Carbon::now()->year)->sum('sell_price');
            $currentYearTotalSellingPrice1=OrderItem::whereYear('created_at',Carbon::now()->year - 1)->sum('sell_price');
            $currentYearTotalSellingPrice2=OrderItem::whereYear('created_at',Carbon::now()->year - 2)->sum('sell_price');
            $currentYearTotalSellingPrice3=OrderItem::whereYear('created_at',Carbon::now()->year - 3)->sum('sell_price');
            
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
