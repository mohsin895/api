<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;

class CategoryController extends Controller
{
    public function getMainCategory(Request $request)
    {
    	$dataList=Category::where('status',1)
    								->whereNull('deleted_at')
    									->where('look_type',1)
    										->get();

    	return response()->json($dataList,200);
    }

    public function getSubCategory(Request $request)
    {
    	$category=Category::where('status',1)
    								->whereNull('deleted_at')
                                    ->where('slug','like','%'.$request->slug.'%')
    										->first();

   $dataList=Category::where('parent_id',$category->id)->where('status',1)
    								->whereNull('deleted_at')
    									->where('look_type',1)
    										->get();

    	return response()->json($dataList,200);
    }

    public function getCategoryList(Request $request)
    {
        $dataList=Category::with(['subCategory'=>function($q) use($request){
                                    $q->where('status',1)->whereNull('deleted_at');
                                },
                                'subCategory.subCategory'=>function($q) use($request){
                                    $q->where('status',1)->whereNull('deleted_at');
                                },
                                'categoryImage'])
                                ->where('status',1)
                                    ->whereNull('deleted_at')
                                        ->where('look_type',1)
                                            ->get();

        return response()->json($dataList,200);
    }

    public function getFilterList(Request $request)
    {
    	$brandList=Brand::where('status',1)
    						->whereNull('deleted_at')
    							->get();

       $colorList=Color::where('status',1)
    						->whereNull('deleted_at')
    							->get();
                                
       $sizeList=Color::where('status',1)
       ->whereNull('deleted_at')
           ->get();

            $responseData=[
                'brandList'=>$brandList,
                'colorList'=>$colorList,
                'sizeList'=>$sizeList,
            
                
            ];


    	return response()->json($responseData,200);

     
    }
}
