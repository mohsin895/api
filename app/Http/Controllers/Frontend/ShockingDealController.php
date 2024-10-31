<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShockingDeal;

class ShockingDealController extends Controller
{
    public function getShockingDealList(Request $request)
    {
    	$dataList=ShockingDeal::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
}
