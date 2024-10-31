<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\StockInfo;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\CartShop;
use Carbon\Carbon;
use App\Models\CartProductStockInfo;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
   
    public function addToCart(Request $request){
        try{
         

            $productInfo = Product::where('id',$request->productId)->first();
            $stockInfoId = StockInfo::where('product_id',$request->productId)->where('color_id',$request->colorCode)->where('size_attribute_id',$request->sizeAttributeCode)->first();
             $totalSellQuantity= OrderItem::where('stock_id',$stockInfoId->id)->sum('quantity');
             $sellQuantity =$stockInfoId->quantity - $totalSellQuantity;
             $customer_id=Auth::guard('customer-api')->user()->id;
            $cartCount = CartProduct::where('product_id',$request->productId)->where('customer_id',$customer_id)->count();
            $totalShop= CartShop::where('customer_id',$customer_id)->where('seller_id',$productInfo->seller_id)->count();

            if($totalShop >0){
                $shop= CartShop::where('customer_id',$customer_id)->where('seller_id',$productInfo->seller_id)->first();
                if(!empty($shop)){
                    $shop->check_uncheck=0;
                    $shop->save();
                    if($cartCount < 1 ){
                        if($sellQuantity <= $request->quantity  ){
                         
                              $responseData=[
                                'msgFlag'=>false,
                                'errMsgFlag'=>true,
                                'msg'=>null,
                                'errMsg'=>'Requested Quantity More Than Stock Quantity.',
                                'sellQuantity'=>$sellQuantity,
                                     
                                    ];
                          
                               return response()->json($responseData,200);
        
                        }else{
                            $cartProduct = new CartProduct();
                            $cartProduct->customer_id= $customer_id;
                            $cartProduct->cart_shop_id= $shop->id;
                            $cartProduct->seller_id= $productInfo->seller_id;
                            $cartProduct->product_id= $request->productId;
                            $cartProduct->stock_info_id= $stockInfoId->id;
                            $cartProduct->color= $request->colorCode;
                            $cartProduct->size= $stockInfoId->size_id;
                            $cartProduct->quantity= $request->quantity ;
                            $cartProduct->size_attribute_id=$request->sizeAttributeCode;
                            $cartProduct->save();
                            if($cartProduct->save()){
                                $stockInfo=new CartProductStockInfo();
                                $stockInfo->cart_id= $cartProduct->id;
                                $stockInfo->cart_shop_id= $shop->id;
                                $stockInfo->customer_id= $customer_id;
                                $stockInfo->seller_id= $productInfo->seller_id;
                                $stockInfo->stock_info_id= $stockInfoId->id;
                                $stockInfo->quantity= $request->quantity ;
                                $stockInfo->color_id= $request->colorCode;
                                $stockInfo->size_id= $stockInfoId->size_id;
                                $stockInfo->size_attribute_id=$request->sizeAttributeCode;
                                $stockInfo->save();
                              
                            
                               
        
                                $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Product Added To Cart Successfully ',
                                    'errMsg'=>null,
                                 
                                ];
        
                            }
                          
                  
                            
                          
                               return response()->json($responseData,200);
                        }
                       
        
                    }else if($cartCount > 0){
                        $cart = CartProduct::where('product_id',$request->productId)->where('customer_id',$customer_id)->first();
                        $cart->check_uncheck=0;
                        $cart->save();
                        if($sellQuantity <= $request->quantity  ){
                         
                            $responseData=[
                              'msgFlag'=>false,
                              'errMsgFlag'=>true,
                              'msg'=>null,
                              'errMsg'=>'Requested Quantity More Than Stock Quantity.',
                              'sellQuantity'=>$sellQuantity,
                                   
                                  ];
                        
                             return response()->json($responseData,200);
        
                      }else{
                        
                          if($cart){
                            $stockInfoId = StockInfo::where('id',$stockInfoId->id)->first();
                            $cartStockInfoId = CartProductStockInfo::where('stock_info_id',$stockInfoId->id)->first();
                            if($cartStockInfoId){
                                $responseData=[
                                    'errMsgFlag'=>true,
                                    'msgFlag'=>false,
                                    'msg'=>null,
                                    'errMsg'=>'Product already Exists.',
                                         
                                        ];
                                        return response()->json($responseData,200);
                            }else{
                                $stockInfo=new CartProductStockInfo();
                                $stockInfo->cart_id= $cart->id;
                                $stockInfo->customer_id= $customer_id;
                                $stockInfo->cart_shop_id= $shop->id;
                                $stockInfo->seller_id= $productInfo->seller_id;
                                $stockInfo->stock_info_id= $stockInfoId->id;
                                $stockInfo->quantity= $request->quantity ;
                                $stockInfo->color_id= $request->colorCode;
                                $stockInfo->size_id= $stockInfoId->size_id;
                                $stockInfo->size_attribute_id=$request->sizeAttributeCode;
                                $stockInfo->save();
                                $totalShop= CartShop::where('customer_id',$customer_id)->where('seller_id',$productInfo->seller_id)->count();
                                if($totalShop >0){
        
                                }else{
                                    $shop=new CartShop();
                                  
                                    $shop->customer_id= $customer_id;
                                    $shop->seller_id= $productInfo->seller_id;
                                    $shop->save();
                                }
          
                                $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Product Added To Cart Successfully ',
                                    'errMsg'=>null,
                                 
                                ];
                                return response()->json($responseData,200);
                            }
                             
        
                          }
                        
                
                          
                        
                           
                      }
        
        
                    }
                    
                    else{
                       
                      
              
                          $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Product already Exists.',
                                 
                                ];
                      
                           return response()->json($responseData,200);
        
                    }
                }

            }else{
                $shop=new CartShop();
                $shop->customer_id= $customer_id;
                $shop->seller_id= $productInfo->seller_id;
                $shop->save();
                if($shop->save()){
                    if($cartCount < 1 ){
                        if($sellQuantity <= $request->quantity  ){
                         
                              $responseData=[
                                'msgFlag'=>false,
                                'errMsgFlag'=>true,
                                'msg'=>null,
                                'errMsg'=>'Requested Quantity More Than Stock Quantity.',
                                'sellQuantity'=>$sellQuantity,
                                     
                                    ];
                          
                               return response()->json($responseData,200);
        
                        }else{
                            $cartProduct = new CartProduct();
                            $cartProduct->customer_id= $customer_id;
                            $cartProduct->cart_shop_id= $shop->id;
                            $cartProduct->seller_id= $productInfo->seller_id;
                            $cartProduct->product_id= $request->productId;
                            $cartProduct->stock_info_id= $stockInfoId->id;
                            $cartProduct->color= $request->colorCode;
                            $cartProduct->size= $stockInfoId->size_id;
                            $cartProduct->quantity= $request->quantity ;
                            $cartProduct->size_attribute_id=$request->sizeAttributeCode;
                            $cartProduct->save();
                            if($cartProduct->save()){
                                $stockInfo=new CartProductStockInfo();
                                $stockInfo->cart_id= $cartProduct->id;
                                $stockInfo->customer_id= $customer_id;
                                $stockInfo->cart_shop_id= $shop->id;
                                $stockInfo->seller_id= $productInfo->seller_id;
                                $stockInfo->stock_info_id= $stockInfoId->id;
                                $stockInfo->quantity= $request->quantity ;
                                $stockInfo->color_id= $request->colorCode;
                                $stockInfo->size_id= $stockInfoId->size_id;
                                $stockInfo->size_attribute_id=$request->sizeAttributeCode;
                                $stockInfo->save();
                              
                            
                               
        
                                $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Product Added To Cart Successfully ',
                                    'errMsg'=>null,
                                 
                                ];
        
                            }
                          
                  
                            
                          
                               return response()->json($responseData,200);
                        }
                       
        
                    }else if($cartCount > 0){
                        $cart = CartProduct::where('product_id',$request->productId)->where('customer_id',$customer_id)->first();
        
                        if($sellQuantity <= $request->quantity  ){
                         
                            $responseData=[
                              'msgFlag'=>false,
                              'errMsgFlag'=>true,
                              'msg'=>null,
                              'errMsg'=>'Requested Quantity More Than Stock Quantity.',
                              'sellQuantity'=>$sellQuantity,
                                   
                                  ];
                        
                             return response()->json($responseData,200);
        
                      }else{
                        
                          if($cart){
                            $stockInfoId = StockInfo::where('id',$stockInfoId->id)->first();
                            $cartStockInfoId = CartProductStockInfo::where('stock_info_id',$stockInfoId->id)->first();
                            if($cartStockInfoId){
                                $responseData=[
                                    'errMsgFlag'=>true,
                                    'msgFlag'=>false,
                                    'msg'=>null,
                                    'errMsg'=>'Product already Exists.',
                                         
                                        ];
                                        return response()->json($responseData,200);
                            }else{
                                $stockInfo=new CartProductStockInfo();
                                $stockInfo->cart_id= $cart->id;
                                $stockInfo->customer_id= $customer_id;
                                $stockInfo->cart_shop_id= $shop->id;
                                $stockInfo->seller_id= $productInfo->seller_id;
                                $stockInfo->stock_info_id= $stockInfoId->id;
                                $stockInfo->quantity= $request->quantity ;
                                $stockInfo->color_id= $request->colorCode;
                                $stockInfo->size_id= $stockInfoId->size_id;
                                $stockInfo->size_attribute_id=$request->sizeAttributeCode;
                                $stockInfo->save();
                                // $totalShop= CartShop::where('customer_id',$customer_id)->where('seller_id',$productInfo->seller_id)->count();
                                // if($totalShop >0){
        
                                // }else{
                                //     $shop=new CartShop();
                                //     $shop->cart_id= $cartProduct->id;
                                //     $shop->customer_id= $customer_id;
                                //     $shop->seller_id= $productInfo->seller_id;
                                //     $shop->save();
                                // }
          
                                $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Product Added To Cart Successfully ',
                                    'errMsg'=>null,
                                 
                                ];
                                return response()->json($responseData,200);
                            }
                             
        
                          }
                        
                
                          
                        
                      }
        
        
                    }
                    
                    else{
                       
                      
              
                          $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Product already Exists.',
                                 
                                ];
                      
                           return response()->json($responseData,200);
        
                    }
                }
            }
           
          
  
    
  
         }
          catch(Exception $err){
              
              GeneralController::storeSystemErrorLog($err,"Frontend\OrderController@addToCart");
  
            
  
              $responseData=[
                          'errMsgFlag'=>true,
                          'msgFlag'=>false,
                          'msg'=>null,
                          'errMsg'=>'Something Went Wrong.Please Try Again.',
                      
              ];
  
              return response()->json($responseData,200);
          }
    }
    public function getCartList(Request $request)
	{

        $cartList=CartShop::with('cartProductInfo','cartStockInfo','cartProductInfo.productInfo','cartProductInfo.cartInfo','cartProductInfo.cartInfo.stockInfo','cartProductInfo.cartInfo','cartProductInfo.cartInfo.sizeInfo','cartProductInfo.cartInfo.sizeVariantInfo','cartProductInfo.cartInfo.colorInfo','sellerInfo','sellerInfo.shopInfo')->where('customer_id', Auth::guard('customer-api')->user()->id)->get();
        $totalCartCheck=CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)->get();
        $countAllItem =CartProductStockInfo::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartStock =CartProductStockInfo::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartCheckInfoCount =CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartCheckInfo =CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)
        ->sum('quantity');
        $tatalAmount =0;
        foreach($totalCartCheck as $total){
           
            $stock=StockInfo::where('id',$total->stock_info_id)->first();
       
            $totalStockAmount =($stock->startDate<=Carbon::now() && $stock->endDate >=Carbon::now()) ? ($stock->special_price*$total->quantity ):($stock->sell_price*$total->quantity);
            $tatalAmount += $totalStockAmount;
     
        }

        $data =[
            'cartList'=>$cartList,
     
            'totalCartStock'=>$totalCartStock,
            'countAllItem'=>$countAllItem,
            'totalCartCheckInfo'=>$totalCartCheckInfo,
            'tatalAmount'=>$tatalAmount,
            'totalCartCheckInfoCount'=>$totalCartCheckInfoCount,
        ];
    
    
        return response()->json($data,200);
	}

    public function getCartListst(Request $request)
	{

        $totalShopCustomerCart=CartShop::with('cartProductInfo')->where('customer_id', Auth::guard('customer-api')->user()->id)->get();
		$sellerIds =CartProduct::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->distinct()
        ->pluck('seller_id');
        $countAllItem =CartProduct::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartStock =CartProductStockInfo::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartCheckInfo =CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
       
        
        $cartList = [];
        $totalShop=0;
        $totalShopCheck=0;
        $toalShopList=[];
     
        foreach ($sellerIds as $sellerId) {
            $sellerData = CartProduct::with('CartShop','productInfo','cartInfo.stockInfo','cartInfo','cartInfo.colorInfo','cartInfo.sizeInfo','cartInfo.sizeVariantInfo','sellerInfo','sellerInfo.shopInfo')
                ->where('customer_id', Auth::guard('customer-api')->user()->id)
                ->where('seller_id', $sellerId)
                ->get();
                $totalProductsForSeller = $sellerData->count();
                
            $cartList[$sellerId] = $sellerData;
            // $toalShopList[$sellerId] = $totalProductsForSeller;
            // $cartList[$sellerId] = [
            //     'cartProducts' => $sellerData,
            //     'totalProducts' => $totalProductsForSeller,
            // ];
         

        }	

        $totalCartCheck=CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)->get();
   $tatalAmount =0;
   foreach($totalCartCheck as $total){
      
       $stock=StockInfo::where('id',$total->stock_info_id)->first();
  
       $totalStockAmount =($stock->startDate<=Carbon::now() && $stock->endDate >=Carbon::now()) ? ($stock->special_price*$total->quantity ):($stock->sell_price*$total->quantity);
       $tatalAmount += $totalStockAmount;

   }



        $data =[
            'cartList'=>$cartList,
       
             'toalShopList'=>$toalShopList,
            'totalShopCheck'=>$totalShopCheck,
            'totalCartStock'=>$totalCartStock,
            'countAllItem'=>$countAllItem,
            'totalCartCheckInfo'=>$totalCartCheckInfo,
            'tatalAmount'=>$tatalAmount,
            'totalShopCustomerCart'=>$totalShopCustomerCart,
            
        ];
    
    
        return response()->json($data,200);
	}

	public function getCartCount(Request $request)
	{
		$cartListCount=CartProductStockInfo::where('customer_id',Auth::guard('customer-api')->user()->id)
							->count('id');
	$data =[
		'cartListCount'=>$cartListCount,
		
	];


	return response()->json($data,200);
	}

    public function cartQuantityIncrease(Request $request)
	{

		DB::beginTransaction();
        try{

			$dataInfo=CartProductStockInfo::where('id',$request->cartId)->first();

            if(!empty($dataInfo)){


				
				$dataInfo->quantity = $dataInfo->quantity + 1;
				
				
				$dataInfo->save();

                if($dataInfo->save())
                {
                 $this->customerDataUpdate($request);
                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Quantity Update.',
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
                                'errMsg'=>'Failed To Update Quantity.Please Try Again.',
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
                            'errMsg'=>'Product Not Found.',
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
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                ];

             return response()->json($responseData,200);
        }

	
	}

    public function cartQuantityDecrease(Request $request)
	{

		DB::beginTransaction();
        try{

			$dataInfo=CartProductStockInfo::where('id',$request->cartId)->first();

            if(!empty($dataInfo)){


				
				$dataInfo->quantity = $dataInfo->quantity - 1;
				
				
				$dataInfo->save();

                if($dataInfo->save())
                {
                    $this->customerDataUpdate($request);
                 
                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Quantity Update.',
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
                                'errMsg'=>'Failed To Update Quantity.Please Try Again.',
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
                            'errMsg'=>'Product Not Found.',
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
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                ];

             return response()->json($responseData,200);
        }

	
	}

    public function cartQuantityUpdate(Request $request)
	{

		DB::beginTransaction();
        try{

			$dataInfo=CartProductStockInfo::where('id',$request->cartId)->first();

            if(!empty($dataInfo)){


				
				$dataInfo->quantity = $request->quantity;
				
				
				$dataInfo->save();

                if($dataInfo->save())
                {
                  
                    $this->customerDataUpdate($request);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Quantity Update.',
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
                                'errMsg'=>'Failed To Update Quantity.Please Try Again.',
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
                            'errMsg'=>'Product Not Found.',
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
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                ];

             return response()->json($responseData,200);
        }

	
	}

    public function checkUncheckAll(Request $request){
        DB::beginTransaction();
        try{
            $customer_id=Auth::guard('customer-api')->user()->id;
			$dataInfo=CartProduct::where('customer_id',$customer_id)->get();
            foreach($dataInfo as $data){
                // $dataProduct=CartProduct::where('id',$data->id)->first();
                $productInfoCount = CartProductStockInfo::where('cart_id',$data->id)->count();
                if($request->dataId ==1){
                    
                    $data->check_uncheck=0;
                    $data->checkout_check_uncheck=0;
                    $data->save();
                
                      $productInfo = CartProductStockInfo::where('cart_id',$data->id)->get();
                      foreach($productInfo as $product){
                        $product->check_uncheck=0;
                      
                        $product->save();
                      }
                    
                      
                        $shopInfo = CartShop::where('customer_id',$customer_id)->get();
                        foreach($shopInfo as $shop){
                            $shop->discount_amount=NULL;
                            $shop->promo_id=NULL;
                           $shop->check_uncheck=0;
                          $shop->checkout_check_uncheck=0;
                          $shop->save();
                          
                        }
                       
                      
                }else{
                    $data->checkout_check_uncheck=$productInfoCount;
                    $data->check_uncheck=1;
                    $data->save();
                   
                      $productInfo = CartProductStockInfo::where('cart_id',$data->id)->get();
                      foreach($productInfo as $product){
                        $product->check_uncheck=1;
                      
                        $product->save();
                      }
                      $shopInfo = CartShop::where('customer_id',$customer_id)->get();
                      foreach($shopInfo as $shop){
                        $productInfoShopCount = CartProductStockInfo::where('cart_shop_id',$shop->id)->count();
                        $shop->check_uncheck=1;
                        $shop->discount_amount=NULL;
                        $shop->promo_id=NULL;
                       $shop->checkout_check_uncheck=$productInfoShopCount;
                     
                        $shop->save();
                      }

                }
               
              

            }

            $this->customerDataUpdate($request);

            DB::commit();

        $responseData = [
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Operation successful',
        ];

      

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
                       
                ];

             return response()->json($responseData,200);
        }

    }

    public function checkUncheckSeller(Request $request){
        DB::beginTransaction();
        try{
            $cartSeller=CartShop::where('id',$request->dataId)->first();
            $customer_id=Auth::guard('customer-api')->user()->id;
            $cartProduct=CartProduct::where('seller_id',$cartSeller->seller_id)->where('customer_id',$customer_id)->get();
            $dataInfoCountShop=CartProductStockInfo::where('cart_shop_id',$cartSeller->id)->count();
            if($cartSeller->check_uncheck==1){
                $cartSeller->check_uncheck=0;
                $cartSeller->discount_amount=NULL;
                $cartSeller->promo_id=NULL;
                $cartSeller->checkout_check_uncheck=$cartSeller->checkout_check_uncheck - $dataInfoCountShop;
                foreach($cartProduct as $product){
                    $dataInfoCount=CartProductStockInfo::where('cart_id',$product->id)->count();
                    $product->check_uncheck=0;
                    $product->checkout_check_uncheck=$product->checkout_check_uncheck - $dataInfoCount;
                    $product->save();
                }
            }else{
                $cartSeller->checkout_check_uncheck=$cartSeller->checkout_check_uncheck + $dataInfoCountShop;
                $cartSeller->check_uncheck=1;
                $cartSeller->discount_amount=NULL;
                $cartSeller->promo_id=NULL;
                foreach($cartProduct as $product){
                    $dataInfoCount=CartProductStockInfo::where('cart_id',$product->id)->count();
                    $product->check_uncheck=1;
                    $product->checkout_check_uncheck=$product->checkout_check_uncheck + $dataInfoCount;
                    $product->save();
                }
            }
            $cartSeller->discount_amount=NULL;
            $cartSeller->promo_id=NULL;
            $cartSeller->save();

          
			$dataInfo=CartProductStockInfo::where('seller_id',$cartSeller->seller_id)->where('customer_id',$customer_id)->get();
            foreach($dataInfo as $data){
            
                $cartSeller=CartShop::where('id',$request->dataId)->first();
                if($cartSeller->check_uncheck ==0){
                    $data->check_uncheck=0;
                  
                    $data->save();
                
                      
                }else{
                    $data->check_uncheck=1;
                  
                    $data->save();
                   

                }
               
              

            }
           $this->customerDataUpdate($request);

            DB::commit();

        $responseData = [
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Operation successful',
        ];

        return response()->json($responseData, 200);

 
         }
         catch(\Exception $err)
        {
            DB::rollBack();
            
           
            
            DB::commit();
            
            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                       
                ];

             return response()->json($responseData,200);
        }

    }

    public function checkUncheckProduct(Request $request){
        DB::beginTransaction();
        try{
            $customer_id=Auth::guard('customer-api')->user()->id;
            $cartProduct=CartProduct::where('customer_id',$customer_id)->where('product_id',$request->dataId)->first();
            $dataInfo=CartProductStockInfo::where('cart_id',$cartProduct->id)->get();
            $dataInfoCount=CartProductStockInfo::where('cart_id',$cartProduct->id)->count();
            $cartShop=CartShop::where('seller_id',$cartProduct->seller_id)->where('customer_id',$customer_id)->first();
            if($cartProduct->check_uncheck==1){
                $cartShop->check_uncheck=0;
           
                $cartShop->checkout_check_uncheck=$cartShop->checkout_check_uncheck + $dataInfoCount;
                $cartShop->save();
                foreach($dataInfo as $product){
                    $product->check_uncheck=0;
                    $product->save();
                }
                $cartProduct->checkout_check_uncheck=0;
                $cartProduct->check_uncheck=0;
            }else{
                $cartShop->check_uncheck=1;
           
                $cartShop->checkout_check_uncheck=$cartShop->checkout_check_uncheck + $dataInfoCount;
                $cartShop->save();
                foreach($dataInfo as $product){
                    $product->check_uncheck=1;
                    $product->save();
                }
                $cartProduct->checkout_check_uncheck=$dataInfoCount;
                $cartProduct->check_uncheck=1;
            }
            $cartProduct->save();
            // if($cartProduct->save()){
            //     $cartShop=CartShop::where('seller_id',$cartProduct->seller_id)->where('customer_id',$customer_id)->first();
              
            //     $cartShopProductCheck=CartProduct::where('seller_id',$cartShop->seller_id)->where('customer_id',$customer_id)->where('check_uncheck',1)->count();
            //     $cartShopProductCount=CartProduct::where('seller_id',$cartShop->seller_id)->where('customer_id',$customer_id)->count();
            //       if($cartShopProductCheck == $cartShopProductCount){
            //         $cartShop->check_uncheck=1;
            //         $cartShop->discount_amount=NULL;
            //         $cartShop->promo_id=NULL;
            //         $cartShop->checkout_check_uncheck=$cartShop->checkout_check_uncheck + $dataInfoCount;
            //         $cartShop->save();
            //       }else{
            //         $cartShop->check_uncheck=0;
            //         $cartShop->discount_amount=NULL;
            //         $cartShop->promo_id=NULL;
            //         $cartShop->checkout_check_uncheck=$cartShop->checkout_check_uncheck - $dataInfoCount;
            //         $cartShop->save();
            //       }
            // }

           $this->customerDataUpdate($request);
            DB::commit();

        $responseData = [
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Operation successful',
          
        ];

     

            return response()->json($responseData,200);
         }
         catch(\Exception $err)
        {
            DB::rollBack();
            
           
            
            DB::commit();
            
            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                       
                ];

             return response()->json($responseData,200);
        }

    }

    public function checkUncheckStockInfo(Request $request){
        DB::beginTransaction();
        try{
            $customer_id=Auth::guard('customer-api')->user()->id;
            $cartStockProduct=CartProductStockInfo::where('id',$request->dataId)->first();
            $shop=CartShop::where('id',$cartStockProduct->cart_shop_id)->first();
            if($cartStockProduct->check_uncheck==1){
              
                $shop->checkout_check_uncheck=$shop->checkout_check_uncheck - 1;
                $shop->check_uncheck=0;
                $shop->save();
                $cartStockProduct->check_uncheck=0;
            }else{
                $shop->checkout_check_uncheck=$shop->checkout_check_uncheck + 1;
                        $shop->check_uncheck=1;
                        $shop->save();
                $cartStockProduct->check_uncheck=1;
            }
            $cartStockProduct->save();
            if($cartStockProduct->save()){
                $cart=CartProduct::where('id',$cartStockProduct->cart_id)->first();
              
                $countCartStockInfoCheck=CartProductStockInfo::where('cart_id',$cart->id)->where('check_uncheck',1)->count();
                $countCartStockInfo=CartProductStockInfo::where('cart_id',$cart->id)->count();
                  if($countCartStockInfoCheck == $countCartStockInfo  ){
                    $cart->check_uncheck=1;
                    $cart->checkout_check_uncheck=$cart->checkout_check_uncheck + 1;
                    $cart->save();
                  }else{
                    $cart->check_uncheck=0;
                    $cart->checkout_check_uncheck=$cart->checkout_check_uncheck - 1;
                    $cart->save();
                  }
            }
            // if($cartStockProduct->save()){
            //     $shop=CartShop::where('id',$cartStockProduct->cart_shop_id)->first();
              
            //       $countCartProductCheck=CartProductStockInfo::where('cart_shop_id',$shop->id)->where('customer_id',$customer_id)->where('check_uncheck',1)->count();
            //        $countCartProduct=CartProductStockInfo::where('cart_shop_id',$shop->id)->where('customer_id',$customer_id)->count();

            //         if($countCartProductCheck == $countCartProduct){
            //             $shop->checkout_check_uncheck=$shop->checkout_check_uncheck + 1;
            //             $shop->check_uncheck=1;
            //             $shop->save();
            //           }else{
            //             $shop->check_uncheck=0;
            //             $shop->discount_amount=NULL;
            //             $shop->promo_id=NULL;
            //             $shop->checkout_check_uncheck=$shop->checkout_check_uncheck - 1;
            //             $shop->save();
            //           }
                
                  
            // }
            $this->customerDataUpdate($request);

            DB::commit();

        $responseData = [
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Operation successful',
          
        ];

     

            return response()->json($responseData,200);
         }
         catch(\Exception $err)
        {
            DB::rollBack();
            
           
            
            DB::commit();
            
            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                       
                ];

             return response()->json($responseData,200);
        }

    }


    public function getCheckout(Request $request)
	{
		$sellerIds =CartProduct::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->distinct()
        ->pluck('seller_id');
        $countAllItem =CartProduct::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartStock =CartProductStockInfo::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartCheckInfo =CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
       
        
        $cartList = [];
        $totalShop=0;
        $totalShopCheck=0;
        $toalShopList=[];
     
        foreach ($sellerIds as $sellerId) {
            $sellerData = CartProduct::with('CartShop','productInfo','cartInfo.stockInfo','cartInfo','cartInfo.colorInfo','cartInfo.sizeInfo','cartInfo.sizeVariantInfo','sellerInfo','sellerInfo.shopInfo')
                ->where('customer_id', Auth::guard('customer-api')->user()->id)
                ->where('seller_id', $sellerId)
                ->get();
                $totalProductsForSeller = $sellerData->count();
              
                
            $cartList[$sellerId] = $sellerData;
           
           
         

        }	

        $totalCartCheck=CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)->get();
        $tatalAmount =0;
        foreach($totalCartCheck as $total){
            
            $stock=StockInfo::where('id',$total->stock_info_id)->first();
        
            $totalStockAmount =($stock->startDate<=Carbon::now() && $stock->endDate >=Carbon::now()) ? ($stock->special_price*$total->quantity ):($stock->sell_price*$total->quantity);
            $tatalAmount += $totalStockAmount;

        }



        $data =[
            'cartList'=>$cartList,
       
             'toalShopList'=>$toalShopList,
            'totalShopCheck'=>$totalShopCheck,
            'totalCartStock'=>$totalCartStock,
            'countAllItem'=>$countAllItem,
            'totalCartCheckInfo'=>$totalCartCheckInfo,
            'tatalAmount'=>$tatalAmount,
            
        ];
    
    
        return response()->json($data,200);
	}




    public function removeCartItem(Request $request){
        try{
         

             $customer_id=Auth::guard('customer-api')->user()->id;
            $productInfo = CartProduct::where('id',$request->dataId)->where('customer_id',$customer_id)->first();
         
            if(!empty($productInfo)){

                $cartSeller=CartShop::where('seller_id',$productInfo->seller_id)->where('customer_id',$customer_id)->first();
                $checkProductInfo = CartProduct::where('seller_id',$productInfo->seller_id)->where('customer_id',$customer_id)->count();
                $cartStock=CartProductStockInfo::where('cart_id',$productInfo->id)->get();
                
                    foreach($cartStock as $cart){
                        $cart->delete();
                    }
                
                if($checkProductInfo <2){
                    $cartSeller=CartShop::where('seller_id',$productInfo->seller_id)->where('customer_id',$customer_id)->first();
                  
                 $cartSeller->delete();
                 
                }
                $productInfo->delete();
                // if(!empty($productInfo)){
                //     $cartStock=CartProductStockInfo::where('cart_id',$productInfo->id)->get();
                //     foreach($cartStock as $cart){
                //         $cart->delete();
                //     }
                   
                // }
                $this->customerDataUpdate($request);
                
              

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>' Cart Product Delete Successfully ',
                        'errMsg'=>null,
                     
                    ];
                 
                    
                       return response()->json($responseData,200);
                
               

            } else{
               
              
      
                  $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Product Not found !.',
                         
                        ];
              
                   return response()->json($responseData,200);

            }
          
  
    
  
         }
          catch(\Exception $err){
              
              GeneralController::storeSystemErrorLog($err,"Frontend\OrderController@addToCart");
  
            
  
              $responseData=[
                          'errMsgFlag'=>true,
                          'msgFlag'=>false,
                          'msg'=>null,
                          'errMsg'=>'Something Went Wrong.Please Try Again.',
                      
              ];
  
              return response()->json($responseData,200);
          }
    }


    public function removeCartStockInfo(Request $request)
    {
        try {
            $customer_id = Auth::guard('customer-api')->user()->id;
            
            $cartStockProduct = CartProductStockInfo::find($request->dataId);
            $this->customerDataUpdate($request);
            
            if (!$cartStockProduct) {
                return response()->json([
                    'errMsgFlag' => true,
                    'msgFlag' => false,
                    'msg' => null,
                    'errMsg' => 'Cart product not found.'
                ], 404);
            }
            
            $cartProduct = CartProduct::find($cartStockProduct->cart_id);
            
            if (!$cartProduct) {
                return response()->json([
                    'errMsgFlag' => true,
                    'msgFlag' => false,
                    'msg' => null,
                    'errMsg' => 'Related cart product not found.'
                ], 404);
            }
            
            $checkCartStock = CartProductStockInfo::where('cart_id', $cartProduct->id)->count();
            
            if ($checkCartStock < 2) {
                $cartSeller = CartShop::where('seller_id', $cartStockProduct->seller_id)
                                    ->where('customer_id', $customer_id)
                                    ->first();
                                    
                if ($cartSeller) {
                    $cartProductCount = CartProduct::where('cart_shop_id', $cartSeller->id)
                                                    ->where('customer_id', $customer_id)
                                                    ->count();
                                                    
                    if ($cartProductCount < 2) {
                        DB::beginTransaction();
                        
                        $cartProduct->delete();
                        $cartSeller->delete();
                        $cartStockProduct->delete();
                        
                        DB::commit();
                        
                        return response()->json([
                            'errMsgFlag' => false,
                            'msgFlag' => true,
                            'msg' => 'Cart product deleted successfully',
                            'errMsg' => null,
                        ], 200);
                    }else{
                        DB::beginTransaction();
                        
                        $cartProduct->delete();
                        $cartStockProduct->delete();
                        
                        DB::commit();
                        
                        return response()->json([
                            'errMsgFlag' => false,
                            'msgFlag' => true,
                            'msg' => 'Cart product deleted successfully',
                            'errMsg' => null,
                        ], 200);
                    }
                }
            } else {
                $cartStockProduct->delete();
                
                return response()->json([
                    'errMsgFlag' => false,
                    'msgFlag' => true,
                    'msg' => 'Cart product deleted successfully',
                    'errMsg' => null,
                ], 200);
            }
        } catch (\Exception $err) {
           
            
            return response()->json([
                'errMsgFlag' => true,
                'msgFlag' => false,
                'msg' => null,
                'errMsg' => 'Something went wrong. Please try again later.',
            ], 500);
        }

    }


    public function getCheckOutList(Request $request)
	{

        $cartTotalDiscount=CartShop::where('customer_id', Auth::guard('customer-api')->user()->id)->where('checkout_check_uncheck', '!=',0)->sum('discount_amount');

        $cartList=CartShop::with(['cartProductInfo'=>function($q) use($request){
            $q->where('checkout_check_uncheck', '!=',0)->get();
        },'cartStockInfo'=>function($q) use($request){
            $q->where('check_uncheck',1)->get();
        },
        'cartStockInfo.stockInfo'
        ,'cartProductInfo.productInfo'
        ,'cartProductInfo.cartInfo',
        'cartProductInfo.cartInfo.stockInfo',
        'cartProductInfo.cartInfo'=>function($q) use($request){
            $q->where('check_uncheck',1)->get();
        }
        ,'cartProductInfo.cartInfo.sizeInfo','cartProductInfo.cartInfo.sizeVariantInfo','cartProductInfo.cartInfo.colorInfo','sellerInfo','sellerInfo.shopInfo'])->where('customer_id', Auth::guard('customer-api')->user()->id)->where('check_uncheck',1)->get();
        $totalCartCheck=CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)->get();
        $countAllItem =CartProductStockInfo::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartStock =CartProductStockInfo::where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartCheckInfoCount =CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)
        ->count();
        $totalCartCheckInfo =CartProductStockInfo::where('check_uncheck',1)->where('customer_id', Auth::guard('customer-api')->user()->id)
        ->sum('quantity');
        $tatalAmount =0;
        $loyelDiscount=Customer::where('id',Auth::guard('customer-api')->user()->id)->sum('discount_amount');
        foreach($totalCartCheck as $total){
           
            $stock=StockInfo::where('id',$total->stock_info_id)->first();
       
            $totalStockAmount =($stock->startDate<=Carbon::now() && $stock->endDate >=Carbon::now()) ? ($stock->special_price*$total->quantity ):($stock->sell_price*$total->quantity);
            $tatalAmount += $totalStockAmount;
     
        }
      $discountShop=CartShop::where('customer_id', Auth::guard('customer-api')->user()->id)->where('checkout_check_uncheck', '!=',0)->get();
      $cartProductInfo=0;
      foreach($discountShop as $shop){
        $cartProductInfo =CartProductStockInfo::where('check_uncheck',1)->where('cart_shop_id', $shop->id)->get();
       
       
      }
    
        $data =[
            'cartList'=>$cartList,
            'cartProductInfo'=>$cartProductInfo,
            'totalCartStock'=>$totalCartStock,
            'loyelDiscount'=>$loyelDiscount,
            'countAllItem'=>$countAllItem,
            'totalCartCheckInfo'=>$totalCartCheckInfo,
            'tatalAmount'=>$tatalAmount,
            'totalCartCheckInfoCount'=>$totalCartCheckInfoCount,
            'cartTotalDiscount'=>$cartTotalDiscount,
        ];
    
    
        return response()->json($data,200);
	}


    public function customerDataUpdate($request){

        $loyelDiscount=Customer::where('id',Auth::guard('customer-api')->user()->id)->first();
        $loyelDiscount->promo_id=NULL;
        $loyelDiscount->discount_amount=NULL;
        $loyelDiscount->save();
        $userCartShop=CartShop::where('customer_id', Auth::guard('customer-api')->user()->id)->get();
            
        foreach($userCartShop as $shop){
           $userShop = CartShop::where('id',$shop->id)->first();
           $userShop->promo_id=NULL;
           $userShop->discount_amount=NULL;
           $userShop->save();
        }
    }
}
