<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;

class VendorController extends Controller
{
    public function getVandorList(Request $request)
    {
    	$dataList=Shop::with('sellerInfo')->inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
}
