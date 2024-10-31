<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerRefundable;

class CustomerRefundController extends Controller
{
    public function getRefundList(Request $request) {
        $query =CustomerRefundable::with('orderItem','orderItem.orderInfo','orderItem.productInfo','orderItem.orderInfo.customerInfo','returnCauseType')->whereNull('deleted_at');


    $dataList=$query->paginate($request->numOfData);

    return response()->json($dataList);
        
    }
}
