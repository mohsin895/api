<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Country;
use App\Models\Thana;
use App\Models\Union;

class AreaController extends Controller
{
	public function getCountryList(Request $request)
	{
			$dataList=Country::whereNull('deleted_at')
									->where('status',1)
										->orderBy('name','asc')
											->get();
										
			return response()->json($dataList,200);
	}
   public function getDivisionList(Request $request)
   {
   		$dataList=Division::whereNull('deleted_at')
   								->where('status',1)
   									->orderBy('name','asc')
   										->get();

   		return response()->json($dataList,200);
   }
   public function getDistrictList(Request $request)
   {
   		$dataList=District::whereNull('deleted_at')
   								->where('status',1)
   									->orderBy('name','asc')
   										->get();
   									
   		return response()->json($dataList,200);
   }
   public function getThanaList(Request $request)
   {
   		$dataList=Thana::whereNull('deleted_at')
   								->where('status',1)
   									->orderBy('name','asc')
   										->get();
   									
   		return response()->json($dataList,200);
   }
   public function getUnionList(Request $request)
   {
   		$dataList=Union::whereNull('deleted_at')
   								->where('status',1)
   									->orderBy('name','asc')
   										->get();
   									
   		return response()->json($dataList,200);
   }
}
