<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockInfo;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getSalesReport(Request $request)
    {
    	$query=OrderItem::with(['shopInfo'=>function($q) use($request){
    				$q->select('id','shop_name');
    			}])->select(DB::raw('shop_id,sum(quantity) as totalQuantity, sum(quantity*sell_rate) as totalSellPrice, sum(commission) as totalCommission'))
    			->where('status',1)
    				->groupBy('shop_id');

    	if(isset($request->startDate) && isset($request->endDate)){

    		if(!is_null($request->startDate) && !is_null($request->endDate)){
    		
                $query->whereBetween(DB::raw('date(created_at)'),[$request->startDate,$request->endDate]);
    		
    		}
    		elseif (!is_null($request->startDate)) {
    			
                $query->whereDate('created_at',$request->startDate);

    		}
    		elseif(!is_null($request->endDate)) {
    			
    			 $query->whereDate('created_at','<=',$request->endDate);

    		}
    	}
    	elseif(isset($request->startDate) && !is_null($request->startDate)){

    		$query->whereDate('created_at',$request->startDate);

    	}
    	elseif (isset($request->endDate) && !is_null($request->endDate)) {

    		$query->whereDate('created_at','<=',$request->endDate);

    	}

    	if(isset($request->shopId) && !is_null($request->shopId))
    		$query->where('shop_id',$request->shopId);

    	$dataList=$query->paginate($request->numOfData);

    	return response()->json($dataList,200);
    }

    public function getStockInfo(Request $request)
    {
    	$query=StockInfo::with(['shopInfo'=>function($q) use($request){
    				$q->select('id','shop_name');
    			}])->select(DB::raw('shop_id,sum(quantity) as totalQuantity, sum(quantity*sell_price) as totalSellPrice'))
    			->where('status',1)
    				->groupBy('shop_id');

    	if(isset($request->startDate) && isset($request->endDate)){

    		if(!is_null($request->startDate) && !is_null($request->endDate)){
    		
                $query->whereBetween(DB::raw('date(created_at)'),[$request->startDate,$request->endDate]);
    		
    		}
    		elseif (!is_null($request->startDate)) {
    			
                $query->whereDate('created_at',$request->startDate);

    		}
    		elseif(!is_null($request->endDate)) {
    			
    			 $query->whereDate('created_at','<=',$request->endDate);

    		}
    	}
    	elseif(isset($request->startDate) && !is_null($request->startDate)){

    		$query->whereDate('created_at',$request->startDate);

    	}
    	elseif (isset($request->endDate) && !is_null($request->endDate)) {

    		$query->whereDate('created_at','<=',$request->endDate);

    	}

    	if(isset($request->shopId) && !is_null($request->shopId))
    		$query->where('shop_id',$request->shopId);

    	$dataList=$query->paginate($request->numOfData);

    	return response()->json($dataList,200);
    }
}
