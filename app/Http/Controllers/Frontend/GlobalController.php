<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;

class GlobalController extends Controller
{
    public function loadCategory(Request $request) {
		
		$dataList=Category::with(['subCategory'=>function($q) use($request){
				$q->where('status',1)->whereNull('deleted_at');
				},
				'subCategory.subCategory'=>function($q) use($request){
				$q->where('status',1)->whereNull('deleted_at');
				},
				])
				
				->where('status',1)
				->whereNull('deleted_at')
				->where('look_type',1)
				->orderBy('serial','asc')
					->get();

	
			return response()->json($dataList,200);
		
	}

    public function loadColor(Request $request) {
		
		$dataList=Color::where('status',1)
        ->whereNull('deleted_at')
            ->get();

	
			return response()->json($dataList,200);
		
	}

    public function loadSize(Request $request) {
		
		$dataList=Size::where('status',1)
        ->whereNull('deleted_at')
            ->get();

	
			return response()->json($dataList,200);
		
	}
}
