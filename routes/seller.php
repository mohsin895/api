<?php 



//Seller Panel Start
Route::post('user/send/message/seller', [App\Http\Controllers\Seller\SellerController::class,'sellerMessage']);
Route::get('seller/product/rating/review', [App\Http\Controllers\Seller\SellerController::class,'productRatingReview']);
Route::get('vandor/wise/slider', [App\Http\Controllers\Seller\SliderController::class,'vandorWiseSlider']);

Route::post('seller/otp/verify',[App\Http\Controllers\Seller\AuthController::class,'otpVerify']);
Route::post('seller/signup',[App\Http\Controllers\Seller\AuthController::class,'signup']);

Route::post('seller/send/password',[App\Http\Controllers\Seller\SellerController::class,'forgetPassword']);
Route::post('seller/login',[App\Http\Controllers\Seller\AuthController::class,'login']);
Route::get('seller/logout',[App\Http\Controllers\Seller\AuthController::class,'logout']);
Route::group(['prefix'=>'seller','as'=>'seller.','middleware'=>'SellerAuth'],function(){


Route::post('password/change',[App\Http\Controllers\Seller\SellerController::class,'passwordChange']);
Route::post('send/otp',[App\Http\Controllers\Seller\SellerController::class,'sendOtp']);
Route::post('verify/otp',[App\Http\Controllers\Seller\SellerController::class,'verifyOtp']);
Route::post('update/phone',[App\Http\Controllers\Seller\SellerController::class,'updatePhone']);
Route::post('verify/otp/phone',[App\Http\Controllers\Seller\SellerController::class,'verifyOtpPhone']);
Route::post('verify/otp/email',[App\Http\Controllers\Seller\SellerController::class,'verifyOtpEmail']);

Route::group(['prefix'=>'notification'],function(){  

	Route::get('/count',[App\Http\Controllers\Seller\NotificationController::class,'notificationCount']);
	Route::get('/list',[App\Http\Controllers\Seller\NotificationController::class,'notificationList']);
	Route::post('/update/all',[App\Http\Controllers\Seller\NotificationController::class,'notificationUpdateAll']);
});

	//Product Controller Start 
Route::group(['prefix'=>'product'],function(){  
		Route::post('/add',[App\Http\Controllers\Seller\ProductController::class,'addProduct']);
		Route::post('/update',[App\Http\Controllers\Seller\ProductController::class,'updateProduct']);
		Route::delete('delete/{id}',[App\Http\Controllers\Seller\ProductController::class,'deleteProduct']);
		Route::post('published',[App\Http\Controllers\Seller\ProductController::class,'changeProductPublished']);
		Route::post('status',[App\Http\Controllers\Seller\ProductController::class,'changeProductstatus']);
		Route::post('offer',[App\Http\Controllers\Seller\ProductController::class,'updateProductOffer']);
		Route::get('get/info',[App\Http\Controllers\Seller\ProductController::class,'getProductInfo']);
		
		Route::post('deleteAll',[App\Http\Controllers\Seller\ProductController::class,'deleteAll']);
		Route::post('publishedAll',[App\Http\Controllers\Seller\ProductController::class,'publishedAll']);
		Route::get('get/stock',[App\Http\Controllers\Seller\ProductController::class,'getStock']);
		Route::get('sizeAttribute/get/active/list', [App\Http\Controllers\Seller\ProductController::class,'getActiveSizeAttributeList']);
		Route::post('update/stock/price',[App\Http\Controllers\Seller\ProductController::class,'stockUpdateQuantityPrice']);
		Route::post('update/stock',[App\Http\Controllers\Seller\ProductController::class,'stockUpdateQuantity']);
		Route::delete('quantity/delete/{id}', [App\Http\Controllers\Seller\ProductController::class,'deleteProductQuantity']);
		Route::get('details/info', [App\Http\Controllers\Seller\ProductController::class,'getProductDetailInfo']);
	
	});

	Route::post('color/add',[App\Http\Controllers\Seller\ProductController::class,'addColor']);
	Route::post('color/check-unique-name',[App\Http\Controllers\Seller\ProductController::class,'checkUniqueName']);

	Route::post('size/add',[App\Http\Controllers\Seller\ProductController::class,'addSize']);

	Route::post('unit/add',[App\Http\Controllers\Seller\ProductController::class,'addUnit']);

	

	Route::get('get/color/list',[App\Http\Controllers\Seller\ProductController::class,'getColorList']);

	Route::get('get/size/list',[App\Http\Controllers\Seller\ProductController::class,'getSizeList']);

	Route::get('get/unit/list',[App\Http\Controllers\Seller\ProductController::class,'getUnitList']);

	

	Route::get('get/category/list',[App\Http\Controllers\Seller\ProductController::class,'getCategoryList']);
	Route::get('get/register/brand',[App\Http\Controllers\Seller\ProductController::class,'getRegisterBrandList']);

	Route::get('get/product/list',[App\Http\Controllers\Seller\ProductController::class,'getProductList']);

	Route::get('get/pc/list',[App\Http\Controllers\Seller\ProductController::class,'getShockingDealListPC']);
	Route::get('get/pc/list/rightBanner',[App\Http\Controllers\Seller\ProductController::class,'getRightSliderListPC']);


	//Product Controller End

	Route::post('update/shop/info',[App\Http\Controllers\Seller\SellerController::class,'updateShopInfo']);
	Route::post('financial/update',[App\Http\Controllers\Seller\SellerController::class,'updateFinanacialInfo']);
	Route::get('financial/get/info',[App\Http\Controllers\Seller\SellerController::class,'getFinanacialInfo']);
	Route::post('mobile/banking/update',[App\Http\Controllers\Seller\SellerController::class,'updateMobileBankingInfo']);
	Route::get('mobile/banking/get/info',[App\Http\Controllers\Seller\SellerController::class,'getMobileBankingInfo']);

	



	Route::get('get/info',[App\Http\Controllers\Seller\SellerController::class,'getSellerInfo']);

	Route::get('get/login/info',[App\Http\Controllers\Seller\SellerController::class,'getLoginSellerInfo']);
	Route::get('get/seller/info/edit',[App\Http\Controllers\Seller\SellerController::class,'getSellerInfoEdit']);
	Route::post('update/info',[App\Http\Controllers\Seller\SellerController::class,'updateSellerInfo']);

	Route::group(['prefix'=>'brand'],function(){
		Route::get('get/list',[App\Http\Controllers\Seller\BrandController::class,'getBrandList']);
		Route::post('add',[App\Http\Controllers\Seller\BrandController::class,'addBrand']);
		Route::get('get/pc/list',[App\Http\Controllers\Seller\BrandController::class,'getBrandPcList']);
		Route::get('get/info',[App\Http\Controllers\Seller\BrandController::class,'getBrandInfo']);
		Route::post('update',[App\Http\Controllers\Seller\BrandController::class,'updateBrand']);
		Route::get('get/search',[App\Http\Controllers\Seller\BrandController::class,'searchBrandList']);
	});

Route::group(['prefix'=>'store'],function(){
		Route::get('info',[App\Http\Controllers\Seller\StoreController::class,'getShopInfo']);
		Route::get('get/info',[App\Http\Controllers\Seller\StoreController::class,'getInfo']);
		Route::post('update',[App\Http\Controllers\Seller\StoreController::class,'updateShop']);
		Route::post('update/socialInfo',[App\Http\Controllers\Seller\StoreController::class,'updateSocialInfo']);
	});

//DashBoard Controller Start
		
		Route::get('orderProduct/details',[App\Http\Controllers\Seller\DashboardController::class,'getsellerProductOrderDetails']);
		Route::get('get/order/dashboard',[App\Http\Controllers\Seller\DashboardController::class,'getOrderList']);
		Route::get('get/order/data',[App\Http\Controllers\Seller\DashboardController::class,'getData']);
		Route::get('get/company/data',[App\Http\Controllers\Seller\DashboardController::class,'getCompanyData']);
		Route::get('get/monthly/company/data',[App\Http\Controllers\Seller\DashboardController::class,'getCompanyDataMonthly']);

		//DashboardController End
		
	

	Route::group(['prefix'=>'coupon'],function(){  
		Route::post('add/seller', [App\Http\Controllers\Seller\CouponCodeController::class,'addCouponCode']);

		Route::post('update', [App\Http\Controllers\Seller\CouponCodeController::class,'updateCouponCode']);

		Route::get('get/list', [App\Http\Controllers\Seller\CouponCodeController::class,'getCouponCodeList']);

		Route::get('get/active/list', [App\Http\Controllers\Seller\CouponCodeController::class,'getActiveCouponCodeList']);

		Route::get('get/info', [App\Http\Controllers\Seller\CouponCodeController::class,'getCouponCodeInfo']);

		Route::post('change/status', [App\Http\Controllers\Seller\CouponCodeController::class,'changeStatus']);

		Route::get('delete', [App\Http\Controllers\Seller\CouponCodeController::class,'deleteCouponCode']);
	
	});

	Route::group(['prefix'=>'slider'],function(){  
		Route::post('add/seller', [App\Http\Controllers\Seller\SliderController::class,'addSlider']);

		Route::post('update', [App\Http\Controllers\Seller\SliderController::class,'updateSlider']);

		Route::get('get/list', [App\Http\Controllers\Seller\SliderController::class,'getSliderList']);

		Route::get('get/active/list', [App\Http\Controllers\Seller\SliderController::class,'getActiveSliderList']);

		Route::get('get/info', [App\Http\Controllers\Seller\SliderController::class,'getSliderInfo']);

		Route::post('change/status', [App\Http\Controllers\Seller\SliderController::class,'changeSliderStatus']);

		Route::get('delete', [App\Http\Controllers\Seller\SliderController::class,'deleteSlider']);
	
	});
//Order Controller Start
    Route::get('get/order/list',[App\Http\Controllers\Seller\OrderController::class,'getOrderList']);
	
     Route::group(['prefix'=>'order'],function(){

		Route::get('details',[App\Http\Controllers\Seller\OrderController::class,'getOrderProductDetails']);
		Route::post('update/status',[App\Http\Controllers\Seller\OrderController::class,'singleProductUpdate']);
		Route::get('get/sale/list',[App\Http\Controllers\Seller\OrderController::class,'getOrderSaleList']);
		Route::get('print/single_product', [App\Http\Controllers\Seller\OrderController::class,'singleProductPrint']);
		Route::get('get/info', [App\Http\Controllers\Seller\OrderController::class,'getOrderInfo']);
		Route::get('get/item/info', [App\Http\Controllers\Seller\OrderController::class,'getOrderItemInfo']);
		
	});

	Route::group(['prefix'=>'review'],function(){
		Route::get('get/list',[App\Http\Controllers\Seller\ReviewController::class,'getReview']);

		Route::get('details',[App\Http\Controllers\Seller\ReviewController::class,'getOrderProductDetails']);
		Route::post('update/status',[App\Http\Controllers\Seller\ReviewController::class,'singleProductUpdate']);
		Route::get('get/sale/list',[App\Http\Controllers\Seller\ReviewController::class,'getOrderSaleList']);
		Route::get('print/single_product', [App\Http\Controllers\Seller\ReviewController::class,'singleProductPrint']);
		Route::get('get/info', [App\Http\Controllers\Seller\ReviewController::class,'getOrderInfo']);
		Route::get('get/item/info', [App\Http\Controllers\Seller\ReviewController::class,'getOrderItemInfo']);
		
	});

	//OrderController End

Route::group(['prefix'=>'payment'],function(){  
		Route::get('get/amount',[App\Http\Controllers\Seller\PaymentController::class,'getPayment']);
		Route::get('get/info',[App\Http\Controllers\Seller\PaymentController::class,'getPaymentInfo']);
		Route::post('send/request',[App\Http\Controllers\Seller\PaymentController::class,'RequestPayment']);
		Route::get('get/all/transcation',[App\Http\Controllers\Seller\PaymentController::class,'getAllTranscation']);
	
	});


});
