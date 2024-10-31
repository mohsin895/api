<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\TopBanner;

class BannerController extends Controller
{
    public function getRandomLimitedBannerList(Request $request)
    {
    	$dataList=Banner::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->limit(2)
    								->get();

    	return response()->json($dataList,200);
    }
    public function getBannerList(Request $request)
    {
    	$dataList=Banner::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }

	public function getTopBannerList(Request $request)
    {
    	$dataList=TopBanner::
    					where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
}
