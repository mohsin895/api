<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PremiumPackge;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Slider;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Banner;
use App\Models\Product;
use App\Models\StockInfo;
use App\Models\RightBanner;
use App\Models\FlashSale;
use App\Models\ProductView;
use App\Models\ShockingDeal;
use App\Models\MetaContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

	public function index(Request $request) {
		$dataList=Slider::where('status',1)
		->select('slider_url','status','id','deleted_at','target_url')
		->whereNull('deleted_at')
		->orderBy('id','desc')
			->get();
	
	
			return response()->json($dataList,200);
		
	}
	   public function loadSeoData(Request $request) {
	
		$dataList  = MetaContent::select('image','description','title')->first();	
		
	
			return response()->json($dataList,200);
		
	}
	
	public function loadRightBanner(Request $request) {
		
		$dataList=RightBanner::select('image','slug','status','title')->inRandomOrder()
		->where('status',1)
		
				->limit(4)
					->get();

	
			return response()->json($dataList,200);
		
	}

	public function getShockingDealList(Request $request)
    {
    	$dataList=ShockingDeal::inRandomOrder()
		                ->select('status','deleted_at','slug','shockingDeal_url')
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }

	public function getBrandList(Request $request)
    {
    	$dataList=Brand::inRandomOrder()
		               ->select('status','deleted_at','logo','slug','name')
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }

	public function getFlashSaleProduct(Request $request)
	{
		$dataList=Product::select('status','deleted_at','published','total_view','endDate','name','sell_price','slug','special_price','startDate','thumbnail_img')->where('status',1)
									->whereNull('deleted_at')
										->where('published',1)
											->orderBy('total_view','desc')
												->limit(10)
													->get();

		return response()->json($dataList,200);
	}

	public function getLatestProduct(Request $request)
	{
		$date =Carbon::today()->subDays(7);
		// $dataList=Product::with(['stockInfo'=>function($q) use($request){
		// 						$q->select('product_id','size_id','color_id','quantity','sell_price','whole_sale_price')->where('status',1);
		// 					}])
		// 					->where('status',1)
		// 						->whereNull('deleted_at')
		// 							->where('published',1)
		// 								->orderBy('id','desc')
		// 								->where('created_at','>=',$date)
		// 									->limit(10)
		// 										->get();

		$dataList=Product::select('status','deleted_at','published','created_at','endDate','name','sell_price','slug','special_price','startDate','thumbnail_img')->where('status',1)
			->whereNull('deleted_at')
				->where('published',1)
					->orderBy('id','desc')
					->where('created_at','>=',$date)
						->limit(10)
							->get();

		return response()->json($dataList,200);
	}

	public function getFeaturedProduct(Request $request)
	{
		$dataList=Product::select('status','deleted_at','published','created_at','endDate','name','sell_price','slug','special_price','startDate','thumbnail_img')->where('status',1)
									->whereNull('deleted_at')
										->where('published',1)
											->orderBy('total_view','desc')
												->limit(10)
													->get();

		return response()->json($dataList,200);
	}

	public function getBannerListFirst(Request $request)
    {
    	$dataList=Banner::inRandomOrder()
		       ->select('status','deleted_at','banner_url','slug')
					->where('status',1)
					->whereNull('deleted_at')
							->limit(2)
						->get();

    	return response()->json($dataList,200);
    }

	public function getRandomDualCategory(Request $request)
    {
        $dataList=Category::with(['categoryProducts'=>function($q) use($request){
                                $q->whereNull('deleted_at')
                                    ->where('status',1)
                                        ->where('published',1)
                                            ->inRandomOrder()
                                                ->limit(10);
                            },'categoryProducts.stockInfo',
                            'singleSubCategory'=>function($q) use($request){
                                $q->whereNull('deleted_at')
                                    ->where('status',1)
                                        ->inRandomOrder();
                            },
                            'singleSubCategory.subCategoryProducts'=>function($q) use($request){
                                $q->whereNull('deleted_at')
                                    ->where('status',1)
                                        ->where('published',1)
                                            ->inRandomOrder()
                                                ->limit(10);
                            },'singleSubCategory.subCategoryProducts.stockInfo'

                            ])
                            ->where('look_type',1)
                                ->where('status',1)
                                    ->whereNull('deleted_at')
                                        ->inRandomOrder()
                                            ->limit(3)
                                                ->get();

         // return view('welcome',compact('dataList'));
        return response()->json($dataList,200);
    }
	public function getTodayDealProduct(Request $request)
	{
		$dataList=Product::select('status','deleted_at','published','created_at','endDate','name','sell_price','slug','special_price','startDate','thumbnail_img')->where('status',1)
									->whereNull('deleted_at')
										->where('published',1)
											->orderBy('total_view','desc')
												->limit(10)
													->get();

		return response()->json($dataList,200);
	}

	public function getRecentViewedProduct(Request $request)
	{
		$dataList=Product::select('status','deleted_at','published','created_at','endDate','name','sell_price','slug','special_price','startDate','thumbnail_img')->where('status',1)
									->whereNull('deleted_at')
										->where('published',1)
													->get();

		return response()->json($dataList,200);
	}
	public function getBannerListSecond(Request $request)
    {
    	$dataList=Banner::inRandomOrder()
		          ->select('status','deleted_at','banner_url','slug')
					->where('status',1)
					->whereNull('deleted_at')
							->limit(2)
						->get();

    	return response()->json($dataList,200);
    }
	public function getMostViewedProduct(Request $request)
	{
		$dataList=Product::select('status','deleted_at','published','created_at','endDate','name','sell_price','slug','special_price','startDate','thumbnail_img')->where('status',1)
									->whereNull('deleted_at')
										->where('published',1)
											->orderBy('total_view','desc')
												->limit(10)
													->get();

		return response()->json($dataList,200);
	}




   


	
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
		           ->select('status','deleted_at','banner_url','slug')
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
	 public function getRandomLimitedBrandList(Request $request)
    {
    	$dataList=Brand::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->limit(10)
    								->get();

    	return response()->json($dataList,200);
    }
   
	

	public function getCategoryTop(Request $request)
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
										->where('top',1)
    										->orderBy('title','asc')
    											->get();

    	return response()->json($dataList,200);
    }
	public function getSliderList(Request $request)
    {
    	$dataList=Slider::where('status',1)
    						->whereNull('deleted_at')
							->orderBy('id','desc')
    							->get();

    	return response()->json($dataList,200);
    }

     public function getRandomLimitedShopList(Request $request)
    {
    	$dataList=Shop::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							
    								->get();

    	return response()->json($dataList,200);
    }
	
    public function getShopList(Request $request)
    {
    	$dataList=Shop::inRandomOrder()
    					->where('status',1)
						->where('is_verify',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
    public function getRandomLimitedSellerList(Request $request)
    {
    	$dataList=Seller::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->limit(10)
    								->get();

    	return response()->json($dataList,200);
    }
    public function getSellerList(Request $request)
    {
    	$dataList=Seller::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
	public function search(Request $request)
    {
		$query = $request->input('q');

		$results = DB::table('products')
		  ->where('name', 'like', '%'.$query.'%')
		  ->get();
	  
		return response()->json($results);
    }

	public function flashSaleTime(Request $request)
	{
		$flashsaletime = FlashSale::latest()->first();
		
		if(!empty($flashsaletime)){
			$endTimeDate=$flashsaletime->endDate->toDateString() .' '. $flashsaletime->endTime;
		}else{
			$endTimeDate=0;
		}

		$responseData=[
            'endTimeDate'=>$endTimeDate,
			
			
        ];

		return response()->json($responseData,200);
	}
	public function productView(Request $request)
	{
		$product=Product::where('slug','like','%'.$request->slug.'%')->first();
		if(!empty($product)){
		// 	$totalView = $product->total_view;
		//   $product->total_view=$totalView + 1;
		//    $product->save();

		$product->total_view=$product->total_view + 1;
		$product->save();
		$likeViewInfo=new ProductView();
		$likeViewInfo->product_id=$product->id;
		$likeViewInfo->user_ip=$request->ip();
		$likeViewInfo->view=1;
		$likeViewInfo->created_at=Carbon::now();
		$likeViewInfo->save();

		}
		
		return response()->json($product,200);
	}
	public function sizeWisePrice(Request $request){

		$product=Product::where('slug',$request->slug)->first();

		$stockInfo = StockInfo::where('product_id',$product->id)->where('size_attribute_id',$request->sizeAttribute)->first();

		return response()->json($stockInfo,200);

	}

	public function getPremiumPackge(Request $request)
    {
    	$dataList=PremiumPackge::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }


	public function sitemapGenerate()
    {
        $shopList = Shop::where('status', 1)->get();
        $subCategoryList = Category::where('status', 1)->get();
      

        return response()->view('sitemap', [
            'shopList' => $shopList,
            'subCategoryList' => $subCategoryList,
         
        ])->header('Content-Type', 'text/xml');
    }

	
}