<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function getRandomLimitedBrandList(Request $request)
    {
    	$dataList=Brand::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->limit(10)
    								->get();

    	return response()->json($dataList,200);
    }
    public function getBrandListRandom(Request $request)
    {
    	$dataList=Brand::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
	
}
