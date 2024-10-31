<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use App\Models\Seller;
use App\Models\SizeAttribute;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\StockInfo;
use App\Models\DeliveryCharge;
use App\Models\ShockingDeal;
use App\Models\Thana;
use App\Models\RightBanner;
use App\Models\Account;
use Auth;
use Exception;
use Carbon\Carbon;
use DB;
use Storage;


class ProductController extends Controller
{
    public $image_files = array();
    //upload product images

    public function getProductInfo(Request $request)
    {
       $dataInfo=Product::find($request->dataId);

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];
       }

       return response()->json($responseData,200);
    }

    public function addProduct(Request $request)
    {
        try{
            DB::beginTransaction();

            // $sellerInfo=Seller::with('shopInfo')
            //                     ->where('id',Auth::guard('seller-api')->user()->id)
            //                         ->first();

            $sellerInfo=Seller::with('shopInfo')
                              ->where('id',Auth::guard('seller-api')->user()->id)
                                   ->first();
           //  $sellerInfo = null;
            $quantity=0;

            $dataInfo=new Product();
            $dataInfo->category_id = $request->category_id;
            $dataInfo->subcategory_id = $request->subcategory_id;
            $dataInfo->sub_subcategory_id = $request->sub_subcategory_id;
            $dataInfo->name = $request->name;
            $dataInfo->added_by = 1;
            $dataInfo->staff_id = null;
            $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
            $dataInfo->shop_id = (isset($sellerInfo) && !is_null($sellerInfo->shopInfo)) ? $sellerInfo->shopInfo->id : null;
            $dataInfo->brand_id = $request->brand_id;
            $dataInfo->refundable = $request->refundable ? 1 : 0;
            $dataInfo->tags = $request->tags;
            $dataInfo->height = $request->height;
            $dataInfo->weight = $request->weight;
            $dataInfo->length = $request->length;
            $dataInfo->width = $request->width;
            $dataInfo->video_link = $request->video_link;
            $dataInfo->description = $request->description;
            $dataInfo->min_qty = $request->min_qty;
            $dataInfo->max_qty = $request->max_qty;
            $dataInfo->quantity = array_sum($request->quantity);
            $dataInfo->has_warranty = $request->has_warranty ? 1 : 0;
            $dataInfo->warranty_type = $request->warranty_type;
            $dataInfo->warranty_period = $request->warranty_period;
            $dataInfo->slug = uniqid();
            $dataInfo->sku = $request->sku;
            $dataInfo->has_discount = (isset($request->has_discount) && ($request->has_discount != 'false') && $request->has_discount != 'null') ? 1 : 0;
            if ($dataInfo->has_discount) {
                $dataInfo->discount = $request->discount;
                $dataInfo->discount_start = $request->discount_start_date;
                $dataInfo->discount_end = $request->discount_end_date;
                // $dataInfo->discount_start = $request->discount_start_date.' '.$request->discount_start_time;
                // $dataInfo->discount_end = $request->discount_end_date.' '.$request->discount_end_time;
            }
            $dataInfo->published = 0; //$request->published;

            // $dataInfo->is_b_to_b=$request->is_b_to_b;

            $dataInfo->is_b_to_c = 1;//$request->is_b_to_c;
            $dataInfo->status = 1;
            $dataInfo->created_at = Carbon::now();

            if(isset($request->thumbnail_img) && $request->thumbnail_img!='')
            {

                $image = $request->thumbnail_img;

                 $imageName = $this->nameGenerate($image);

                if (!Storage::disk('public')->exists('products')) {
                    Storage::disk('public')->makeDirectory('products');
                }

                $note_img = Image::make($image)->stream();
                Storage::disk('public')->put('products/' . $imageName, $note_img);
                $path = "/storage/app/public/products/".$imageName;
                $dataInfo->thumbnail_img=$path;
            }
        // return response()->json($dataInfo->save());
            
            if($dataInfo->save()){
                $pro_img = new ProductImage();
                $pro_img->product_id =$dataInfo->id;

                $pro_img->alt_name= $request->name;
                $pro_img->status=1;
                if(isset($request->thumbnail_img) && $request->thumbnail_img!='')
             {
 
                 $image = $request->thumbnail_img;
 
                  $imageName = $this->nameGenerate($image);
 
                 if (!Storage::disk('public')->exists('products')) {
                     Storage::disk('public')->makeDirectory('products');
                 }
 
                 $note_img = Image::make($image)->stream();
                 Storage::disk('public')->put('products/' . $imageName, $note_img);
                 $path = "/storage/app/public/products/".$imageName;
                 $pro_img->product_image=$path;
             }

             $pro_img->save();
          
                if($this->saveProductImage($request,$sellerInfo,$dataInfo) && $this->storeStock($request,$sellerInfo,$dataInfo) && $this->storeDeliveryCharge($request,$sellerInfo,$dataInfo)){
                 
                    DB::commit();
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>"Successfully Added Product.",
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
                }
                else{

                    DB::rollBack();

                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Product.',
                     ];

                    return response()->json($responseData,200);
                }
            }
            else{
                
                DB::rollBack();

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Add Product.',
                 ];

                return response()->json($responseData,200);
            }
        }
        catch(Exception $err){
           $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Something Went Wrong.Please Try Again.',
                 ];

            return response()->json($responseData,200); 
        }

       
    }

    public function updateProduct(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $sellerInfo=Seller::with('shopInfo')
            ->where('id',Auth::guard('seller-api')->user()->id)
                 ->first();
            $sellerInfo = null;
            $quantity=0;

            $dataInfo= Product::where('id',$request->dataId)->first();
            $dataInfo->category_id = $request->category_id;
            $dataInfo->subcategory_id = $request->subcategory_id;
            $dataInfo->sub_subcategory_id = $request->sub_subcategory_id;
            $dataInfo->name = $request->name;
            $dataInfo->added_by = 1;
            $dataInfo->staff_id = null;
            $dataInfo->height = $request->height;
            $dataInfo->weight = $request->weight;
            $dataInfo->length = $request->length;
            $dataInfo->width = $request->width;
            
             //$dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
            // $dataInfo->shop_id = (isset($sellerInfo) && !is_null($sellerInfo->shopInfo)) ? $sellerInfo->shopInfo->id : null;
            $dataInfo->brand_id = $request->brand_id;
            $dataInfo->refundable = $request->refundable ? 1 : 0;
            $dataInfo->tags = $request->tags;
            $dataInfo->product_type =  0;
            $dataInfo->video_link = $request->video_link;
            $dataInfo->description = $request->description;
            $dataInfo->min_qty = $request->min_qty;
            $dataInfo->max_qty = $request->max_qty;
            $dataInfo->quantity = array_sum($request->quantity);
            $dataInfo->has_warranty = $request->has_warranty ? 1 : 0;
            $dataInfo->warranty_type = $request->warranty_type;
            $dataInfo->warranty_period = $request->warranty_period;
            $dataInfo->slug = uniqid();
            $dataInfo->sku = $request->sku;
            $dataInfo->has_discount = (isset($request->has_discount) && ($request->has_discount != 'false') && $request->has_discount != 'null') ? 1 : 0;
            if ($dataInfo->has_discount) {
                $dataInfo->discount = $request->discount;
                $dataInfo->discount_start = $request->discount_start_date;
                $dataInfo->discount_end = $request->discount_end_date;
            
            }
            $dataInfo->published = 0; //$request->published;

            // $dataInfo->is_b_to_b=$request->is_b_to_b;

            $dataInfo->is_b_to_c = 1;//$request->is_b_to_c;
            $dataInfo->status = 1;

            $dataInfo->created_at = Carbon::now();

           
    
            
            if($dataInfo->save()){
             


               
                if( $this->updateStock($request,$sellerInfo,$dataInfo)){
                    DB::commit();
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>"Successfully Updated Product.",
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
                }
                else{

                    DB::rollBack();

                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Update Product.',
                     ];

                    return response()->json($responseData,200);
                }
            }
            else{
                
                DB::rollBack();

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Add Product.',
                 ];

                return response()->json($responseData,200);
            }
        }
        catch(Exception $err){
           $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Something Went Wrong.Please Try Again.',
                 ];

            return response()->json($responseData,200); 
        }
    }
    protected function nameGenerate($file)
    {
        $name = base64_encode(rand(10000, 99999) . time());
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        return strtolower($name) . '.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
    }
    public function productImages($image, $count)
    {
        if (isset($image)) {
         
            $imageName = $this->nameGenerate($image);
            if (!Storage::disk('public')->exists('products')) {
                Storage::disk('public')->makeDirectory('products');
            }
            
            $note_img = Image::make($image)->stream();
            Storage::disk('public')->put('products/' . $imageName, $note_img);
            $path = "/storage/app/public/products/".$imageName;
            array_push($this->image_files, $path);
        }
    }
    public function saveProductImage($request,$sellerInfo,$productInfo)
    {

        foreach ($request->images as $i => $image) {

            $this->productImages($image, $i);
       
             $productimage = new ProductImage;
            
       
              $productimage->product_image =$this->image_files[$i];
              $productimage->product_id = $productInfo->id;
              $productimage->status = 1;
              $productimage->save();

      }

       return  true;
   
    }
   
  
    public function storeStock($request,$sellerInfo,$productInfo)
    {
        foreach ($request->quantity as $key => $quantity) {
            $stockInfo=new StockInfo();
            $stockInfo->seller_id=1;
            $stockInfo->shop_id=1;
            $stockInfo->product_id=$productInfo->id;
            if(isset($sellerInfo)) $stockInfo->seller_id = $sellerInfo->id;
            $stockInfo->size_id=$request->size_id[$key];
            $stockInfo->color_id=$request->color_id[$key];
            $stockInfo->size_attribute_id=$request->size_attribute_id[$key];
            $stockInfo->quantity=$request->quantity[$key];
            $stockInfo->sell_price=$request->sell_price[$key];
            if($productInfo->has_discount == 1){
             $stockInfo->special_price=0;
            }else{
             $stockInfo->special_price=$request->special_price[$key];
            }
        
     
            $stockInfo->total_purches_price=$request->quantity[$key]*$request->sell_price[$key];
            $stockInfo->status=1;
            $stockInfo->created_at=Carbon::now();
            $stockInfo->save();
 
            if($stockInfo->save()){
             Account::where('product_id',$productInfo->id)->delete();
             $totalQty=StockInfo::where('product_id',$productInfo->id)->sum('quantity');
             $totalCost=StockInfo::where('product_id',$productInfo->id)->sum('total_purches_price');
             $account=new Account();
             $account->product_id=$productInfo->id;
             $account->total_qty=$totalQty;
             $account->debit=$totalCost;
             $account->seller_id=1;
             $account->save();
 
             $stockId=StockInfo::where('product_id',$productInfo->id)->first();
             $updateProductInfo=Product::where('id',$productInfo->id)->first();
             $updateProductInfo->sell_price=$stockId->sell_price;
             if($productInfo->has_discount == 1){
                 $updateProductInfo->special_price=0;
                }else{
                 $updateProductInfo->special_price=$stockId->special_price;
                }
 
             $updateProductInfo->save();
            }
        }
       return true;
    }

    public function updateStock($request,$sellerInfo,$productInfo)
    {
        // array_sum($request->quantity)
       //StockInfo::where('product_id', $productInfo->id)->delete();
       foreach ($request->quantity as $key => $quantity) {
           $stockInfo=StockInfo::where('id',$request->stockId[$key])->first();
           $stockInfo->seller_id=1;
           $stockInfo->shop_id=1;
           $stockInfo->product_id=$productInfo->id;
           if(isset($sellerInfo)) $stockInfo->seller_id = $sellerInfo->id;
           $stockInfo->size_id=$request->size_id[$key];
           $stockInfo->color_id=$request->color_id[$key];
           $stockInfo->size_attribute_id=$request->size_attribute_id[$key];
           $stockInfo->quantity=$request->quantity[$key];
           $stockInfo->sell_price=$request->sell_price[$key];
           if($productInfo->has_discount == 1){
            $stockInfo->special_price=0;
           }else{
            $stockInfo->special_price=$request->special_price[$key];
           }
           // $stockInfo->whole_sale_price=$request->whole_sale_price[$key];
           // $stockInfo->note=$request->note[$key];
           $stockInfo->total_purches_price=$request->quantity[$key]*$request->sell_price[$key];
           $stockInfo->status=1;
           $stockInfo->created_at=Carbon::now();
           $stockInfo->save();

           if($stockInfo->save()){
            Account::where('product_id',$productInfo->id)->delete();
            $totalQty=StockInfo::where('product_id',$productInfo->id)->sum('quantity');
            $totalCost=StockInfo::where('product_id',$productInfo->id)->sum('total_purches_price');
            $account=new Account();
            $account->product_id=$productInfo->id;
            $account->total_qty=$totalQty;
            $account->debit=$totalCost;
            $account->seller_id=1;
            $account->save();
            
            $stockId=StockInfo::where('product_id',$productInfo->id)->first();
            $updateProductInfo=Product::where('id',$productInfo->id)->first();
            $updateProductInfo->sell_price=$stockId->sell_price;
            if($productInfo->has_discount == 1){
                $updateProductInfo->special_price=0;
               }else{
                $updateProductInfo->special_price=$stockId->special_price;
               }

            $updateProductInfo->save();
           

            
           }
       }
       return true;
    }
    public function storeDeliveryCharge($request,$sellerInfo,$productInfo)
    {
        $cities=Thana::whereNull('deleted_at')->get();
        DeliveryCharge::where('product_id', $productInfo->id)->delete();
       

            $deliveryCharge=new DeliveryCharge();

            $deliveryCharge->thana_id=1;

            $deliveryCharge->product_id=$productInfo->id;
           

                $deliveryCharge->interCityDeliveryCharge=60;

                $deliveryCharge->interCityDeliveryChargeMax=80; 

           

            $deliveryCharge->outCityDeliveryCharge=100;

           $deliveryCharge->outCityDeliveryChargeMax=120;
           

            $deliveryCharge->created_at=Carbon::now();

            $deliveryCharge->save();
            
 
        return true;
    }
    public function getProductList(Request $request)
    {
        $query=Product::with('shopInfo','deliveryCharge','brandInfo','megaCategory','subCategory','normalCategory', 'stockInfo', 'productImages','stockSingleInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name))
            $query->where('name','like',$request->name.'%');

        if(isset($request->seller) && !is_null($request->seller))
            $query->where('seller_id',$request->seller);

        if(isset($request->brand) && !is_null($request->brand))
            $query->where('brand_id',$request->brand);

        if(isset($request->published) && !is_null($request->published))
            $query->where('published',$request->published);

        if(isset($request->category) && !is_null($request->category)){
            $query->where(function($q) use($request){
                $q->where('category_id',$request->category)
                        ->orWhere('subcategory_id',$request->category)
                            ->orWhere('sub_subcategory_id',$request->category);
            });
        }

        $query->orderBy('id','desc');
        
        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
  
    public function deleteProduct($id)
    {
        $deleteProduct = Product::find($id);
        $stock = StockInfo::where('product_id',$deleteProduct->id)->get();
       
            foreach($stock as $stPro){
             
                $stPro->delete();
            }

       
            $deleteProduct->delete();
        if($deleteProduct) $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'msg'=> 'Product Deleted Successfully.',
                'errMsg'=> null,
            ];
        else $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete Product.',
             ];
        return response()->json($responseData,200);
    }

   

    public function changeProductPublished(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Product::find($request->dataId);

                $dataInfo->published=$request->published;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='brands';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Product Published Changed Successfully.',
                            'errMsg'=>null,
                        ];
                }
                else
                {
                   DB::rollBack();

                   $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Change Product Published.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Seller\ProductController@changeProductPublished",$err);
            
            DB::commit();

            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.'
            ];

            return response()->json($responseData,200);
        }
    }

    public function changeProductStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Product::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='brands';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Product Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Product Status Changed Successfully.',
                            'errMsg'=>null,
                        ];
                }
                else
                {
                   DB::rollBack();

                   $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Change Product Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
          
            DB::commit();

            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.'
            ];

            return response()->json($responseData,200);
        }
    }

    public function updateProductOffer(Request $request)
    {
        
    

            DB::beginTransaction();
            try{
    
                $dataInfo=Product::find($request->dataId);
    
                if(!empty($dataInfo)){
    
                    $dataInfo->discount=$request->discount;
                   $dataInfo->deal_type=$request->dealType;
                   $dataInfo->discount_type=$request->discount_type;
    
                    $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save())
                    {
                        $dataId=$dataInfo->id;
    
                        $tableName='products';
    
                        $userId=1;
    
                        $userType=1;
    
                        $dataType=2;
    
                        $comment='Product Updated By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;
    
                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                    
                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);
    
                        DB::commit();
    
                        $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Successfully Update Discount.',
                                    'errMsg'=>null,
                            ];
                    }
                    else
                    {
                        DB::rollBack();
                        $responseData=[
                                    'errMsgFlag'=>true,
                                    'msgFlag'=>false,
                                    'msg'=>null,
                                    'errMsg'=>'Failed To update Discount.Please Try Again.',
                            ];
                    }
                }
                else
                {
                    DB::rollBack();
    
                    $responseData=[
                                 'errMsgFlag'=>true,
                                'msgFlag'=>false,
                                'msg'=>null,
                                'errMsg'=>'Requested Data Not Found.',
                            ];
    
                }
    
                return response()->json($responseData,200);
             }
             catch(Exception $err)
            {
                DB::rollBack();
                
                GeneralController::storeSystemErrorLog($err,"Backend\RightBannerController@updateRightSlider");
                
                DB::commit();
                
                $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Something Went Wrong.Please Try Again.',
                    ];
    
                 return response()->json($responseData,200);
            }
        }
      
          

        public function addSize(Request $request)
        {
            DB::beginTransaction();
            try{
    
                $dataInfo=new Size();
    
                $dataInfo->size=$request->sizeLabel;
    
                $dataInfo->label=$request->sizeLabel;
    
                $dataInfo->status=1;
    
                $dataInfo->created_at=Carbon::now();
    
                if($dataInfo->save()){
    
                    DB::commit();
    
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Size Added Successfully.',
                        'errMsg'=>null,
                     ];
    
                    return response()->json($responseData,200);
                }
                else{
    
                     DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Size.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                }
    
            }
            catch(Exception $err){
    
                DB::rollBack();
    
                GeneralController::storeSystemErrorLog($err,"Frontend\SellerController@addSize");
    
                DB::commit();
    
                $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                     ];
    
                return response()->json($responseData,200);
            }
        }
        public function addColor(Request $request)
        {
            DB::beginTransaction();
            try{
    
                $dataInfo=new Color();
    
                $dataInfo->color=$request->colorName;
    
                $dataInfo->color_code=$request->colorCode;
    
                $dataInfo->status=1;
    
                $dataInfo->created_at=Carbon::now();
    
                if($dataInfo->save()){
    
                    DB::commit();
    
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Color Added Successfully.',
                        'errMsg'=>null,
                     ];
    
                    return response()->json($responseData,200);
                }
                else{
    
                     DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Color.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                }
    
            }
            catch(Exception $err){
    
                DB::rollBack();
    
                GeneralController::storeSystemErrorLog($err,"Frontend\SellerController@addColor");
    
                DB::commit();
    
                $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                     ];
    
                return response()->json($responseData,200);
            }
        }
        public function getColorList(Request $request)
        {
           $dataList=Color::whereNull('deleted_at')->where('status',1)->get();
            
            return response()->json($dataList,200); 
        }
        public function getSizeList(Request $request)
        {
            $dataList=Size::whereNull('deleted_at')->where('status',1)->get();
    
            return response()->json($dataList,200);
        }
        public function addUnit(Request $request)
        {
            DB::beginTransaction();
            try{
    
                $dataInfo=new Unit();
    
                $dataInfo->label=$request->unitLabel;
    
                $dataInfo->status=1;
    
                $dataInfo->created_at=Carbon::now();
    
                if($dataInfo->save()){
    
                    DB::commit();
    
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Unit Added Successfully.',
                        'errMsg'=>null,
                     ];
    
                    return response()->json($responseData,200);
                }
                else{
    
                     DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Unit.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                }
    
            }
            catch(Exception $err){
    
                DB::rollBack();
    
                GeneralController::storeSystemErrorLog($err,"Frontend\SellerController@addunit");
    
                DB::commit();
    
                $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                     ];
    
                return response()->json($responseData,200);
            }
        }
        public function addBrand(Request $request)
        {
            DB::beginTransaction();
            try{
                 $dataInfo=new Brand();
    
                 if(isset($request->brandLogo) && !is_null($request->file('brandLogo')))
                 {      
                    
                     $image=$request->file('brandLogo');
                       
                         $imageName = $image->getClientOriginalName();
                            if (!Storage::disk('public')->exists('brands')) {
                                Storage::disk('public')->makeDirectory('brands');
                            }
                           
                           
                        Storage::disk('public')->put('brands/', $image);
                        
                        if(!is_null($imageName)){
                           
                            $path ='/storage/app/public/brands/'.$image->hashName();
    
                            $dataInfo->logo=$path;
                        }
                    }
    
                    $dataInfo->name=$request->name;
    
                    $dataInfo->name_bd=$request->name_bd;
    
                    $dataInfo->slug=$request->slug;
    
                    $dataInfo->status=1;
                  
                    // $banner->status=$request->status;
    
                    $dataInfo->created_at=Carbon::now();
                    
                    if($dataInfo->save())
                    {
                        
    
                        DB::commit();
    
                        $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Successfully Added Brand.',
                                    'errMsg'=>null,
                            ];
                    }
                    else
                    {
                        DB::rollBack();
                        $responseData=[
                                    'errMsgFlag'=>true,
                                    'msgFlag'=>false,
                                    'msg'=>null,
                                    'errMsg'=>'Failed To Save Brand.Please Try Again.',
                            ];
                    }
                
    
                return response()->json($responseData,200);
             }
             catch(Exception $err)
            {
                DB::rollBack();
                
                GeneralController::storeSystemErrorLog($err,"Frontend\SellerController@addBrand");
                
                DB::commit();
                
                $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Something Went Wrong.Please Try Again.',
                    ];
    
                 return response()->json($responseData,200);
            }
        }
        public function getUnitList(Request $request)
        {
            $dataList=Unit::whereNull('deleted_at')->where('status',1)->orderBy('label','asc')->get();
    
            return response()->json($dataList,200);
        }
        public function getBrandList(Request $request)
        {
            $dataList=Brand::whereNull('deleted_at')->where('status',1)->orderBy('name','asc')->get();
    
            return response()->json($dataList,200);
        }
        public function getCategoryList(Request $request)
        {
            $dataList=Category::select('id','title','parent_id','status','look_type')
                                ->with(['subCategory'=>function($q) use($request){
                                            $q->select('id','title','parent_id','status','look_type')->where('status',1);
                                        },
                                        'subCategory.subCategory'=>function($q) use($request){
                                            $q->select('id','title','parent_id','status','look_type')->where('status',1);
                                        }
                                    ])->where('look_type',1)
                                            ->where('status',1)
                                                ->orderBy('title','asc')
                                                    ->get();
    
            return response()->json($dataList,200);
        }

        public function getShockingDealListPC(Request $request)
        {
            $dataList=ShockingDeal::inRandomOrder()
                            ->where('status',1)
                                ->whereNull('deleted_at')
                                    ->get();
    
            return response()->json($dataList,200);
        }
    
        public function getRightSliderListPC() {
            $dataList = RightBanner::where('status', 1)->get();
            return response()->json($dataList, 200);
        }
        public function getActiveSizeAttributeList(Request $request)
        {
            $query=SizeAttribute::whereNull('deleted_at');
    
            if(isset($request->variantSize) && $request->variantSize!='')
                $query->where('size_id',$request->variantSize);
    
            $dataList=$query->orderBy('id','desc')->get();
            
            return response()->json($dataList,200);
        }

}
