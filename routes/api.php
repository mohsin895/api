<?php

use Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//globalController start
Route::get('get/category/list',[App\Http\Controllers\Frontend\GlobalController::class,'loadCategory']);
Route::get('get/color/list',[App\Http\Controllers\Frontend\GlobalController::class,'loadColor']);
Route::get('get/size/list',[App\Http\Controllers\Frontend\GlobalController::class,'loadSize']);
//global Controller end

//landing page api start

Route::get('home/page',[App\Http\Controllers\Frontend\HomeController::class,'index']);

Route::get('get/right/banner/list',[App\Http\Controllers\Frontend\HomeController::class,'loadRightBanner']);
Route::get('get/seo/data/list',[App\Http\Controllers\Frontend\HomeController::class,'loadSeoData']);
Route::get('get/shockingDeal/list',[App\Http\Controllers\Frontend\HomeController::class,'getShockingDealList']);
Route::get('get/brand/list',[App\Http\Controllers\Frontend\HomeController::class,'getBrandList']);
Route::get('get/flashSale/product',[App\Http\Controllers\Frontend\HomeController::class,'getFlashSaleProduct']);
Route::get('get/latest/product',[App\Http\Controllers\Frontend\HomeController::class,'getLatestProduct']);
Route::get('get/featured/product',[App\Http\Controllers\Frontend\HomeController::class,'getFeaturedProduct']);
Route::get('get/banner/list/first',[App\Http\Controllers\Frontend\HomeController::class,'getBannerListFirst']);
Route::get('get/random/dual/category',[App\Http\Controllers\Frontend\HomeController::class,'getRandomDualCategory']);
Route::get('get/today/deal/product',[App\Http\Controllers\Frontend\HomeController::class,'getTodayDealProduct']);
Route::get('get/recent/viewed/product',[App\Http\Controllers\Frontend\HomeController::class,'getRecentViewedProduct']);
Route::get('get/banner/list/second',[App\Http\Controllers\Frontend\HomeController::class,'getBannerListSecond']);
Route::get('get/most/viewed/product',[App\Http\Controllers\Frontend\HomeController::class,'getMostViewedProduct']);
Route::get('get/slider/list',[App\Http\Controllers\Frontend\HomeController::class,'getSliderList']);
//landing page api end
//product Controller Start 

//products Detail page start
Route::get('product/details',[App\Http\Controllers\Frontend\ProductController::class,'getProductInfo']);
Route::get('product/review',[App\Http\Controllers\Frontend\ProductController::class,'getProductInfoReview']);
Route::get('product/related/product',[App\Http\Controllers\Frontend\ProductController::class,'getProductInfoRelatedProduct']);
//product Details page end

Route::get('category/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'categoryWiseProduct']);
Route::get('subCategory/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'subCategoryWiseProduct']);
Route::get('combo/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'comboWiseProduct']);
Route::get('combo/wise/product/sort',[App\Http\Controllers\Frontend\ProductController::class,'comboWiseProductSort']);
Route::get('most/view/product',[App\Http\Controllers\Frontend\ProductController::class,'mostViewProduct']);
Route::get('recent/view/product',[App\Http\Controllers\Frontend\ProductController::class,'recentViewProduct']);
Route::get('latest/product',[App\Http\Controllers\Frontend\ProductController::class,'latestProduct']);
Route::get('vandor/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'vandorWiseProduct']);
Route::post('supper/product/districtWise',[App\Http\Controllers\Frontend\ProductController::class,'supperProduct']);

Route::get('brand/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'brandWiseProduct']);
Route::get('supper/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'supperWiseProduct']);
Route::get('wholesale/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'wholesaleWiseProduct']);
Route::get('feature/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'featureWiseProduct']);
Route::get('todayDeal/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'todayDealWiseProduct']);
Route::get('flashSale/product',[App\Http\Controllers\Frontend\ProductController::class,'flashSaleProduct']);
Route::get('shockingDeal/wise/product',[App\Http\Controllers\Frontend\ProductController::class,'shockinDealWiseProduct']);

//productController End
//category page api start 



//category page api end

Route::get('size/attribute',[App\Http\Controllers\Frontend\ProductController::class,'sizeAttribute']);

Route::post('user/subscriber',[App\Http\Controllers\Frontend\SubscribeController::class,'subscriber']);
Route::get('user/search',[App\Http\Controllers\Frontend\HomeController::class,'search']);
Route::get('flashSale/time',[App\Http\Controllers\Frontend\HomeController::class,'flashSaleTime']);
Route::post('sent/order/sms',[App\Http\Controllers\Frontend\OrderController::class,'sentOrderSms']);
Route::post('get/delivery/charge/discount',[App\Http\Controllers\Frontend\OrderController::class,'getDeliveryChargeDiscount']);


// Route::post('product/add/to/cart',[App\Http\Controllers\Frontend\OrderController::class,'addToCart']);

Route::get('product/view',[App\Http\Controllers\Frontend\HomeController::class,'productView']);
Route::post('product/size/price',[App\Http\Controllers\Frontend\HomeController::class,'sizeWisePrice']);


Route::get('get/main/category',[App\Http\Controllers\Frontend\CategoryController::class,'getMainCategory']);
Route::get('get/sub/category',[App\Http\Controllers\Frontend\CategoryController::class,'getSubCategory']);

Route::get('get/random/right/slider',[App\Http\Controllers\Frontend\HomeController::class,'getRightBannerList']);



Route::get('get/category/top',[App\Http\Controllers\Frontend\HomeController::class,'getCategoryTop']);


Route::get('get/random/brand/list',[App\Http\Controllers\Frontend\HomeController::class,'getRandomLimitedBrandList']);
Route::get('get/seller/list',[App\Http\Controllers\Frontend\HomeController::class,'getSellerList']);
Route::get('get/random/seller/list',[App\Http\Controllers\Frontend\HomeController::class,'getRandomLimitedSellerList']);
Route::get('get/shop/list',[App\Http\Controllers\Frontend\HomeController::class,'getShopList']);
Route::get('get/random/shop/list',[App\Http\Controllers\Frontend\HomeController::class,'getRandomLimitedShopList']);
Route::get('get/random/banner/list',[App\Http\Controllers\Frontend\HomeController::class,'getRandomLimitedBannerList']);

Route::get('get/premium/packge/list',[App\Http\Controllers\Frontend\HomeController::class,'getPremiumPackge']);
Route::get('get/topbanner/list',[App\Http\Controllers\Frontend\BannerController::class,'getTopBannerList']);

Route::get('get/division/list',[App\Http\Controllers\Frontend\AreaController::class,'getDivisionList']);
Route::get('get/district/list',[App\Http\Controllers\Frontend\AreaController::class,'getDistrictList']);
Route::get('get/country/list',[App\Http\Controllers\Frontend\AreaController::class,'getCountryList']);
Route::get('get/thana/list',[App\Http\Controllers\Frontend\AreaController::class,'getThanaList']);
Route::get('get/union/list',[App\Http\Controllers\Frontend\AreaController::class,'getUnionList']);
Route::get('get/setting/info',[App\Http\Controllers\Frontend\SettingController::class,'getInformation']);
Route::get('get/seo/info',[App\Http\Controllers\Frontend\SettingController::class,'getSeoInformation']);

//Frontend Category Page Start 

Route::get('get/filter',[App\Http\Controllers\Frontend\CategoryController::class,'getFilterList']);
//frontend Category page End


Route::post('staff/admin/login', [App\Http\Controllers\LoginController::class,'staffLogin']);
Route::post('staff/admin/verify/otp', [App\Http\Controllers\LoginController::class,'verifyOtp']);
Route::post('staff/admin/get/password', [App\Http\Controllers\LoginController::class,'staffLoginPassword']);
Route::get('staff/admin/get/setting/info',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'getInformation']);

Route::post('staff/admin/reset-password', [App\Http\Controllers\LoginController::class,'resetPassword']);

Route::group(['prefix'=>'staff','as'=>'staff.','middleware'=>'StaffAuth'],function(){
Route::post('/logout', [App\Http\Controllers\LoginController::class,'logout']);
Route::get('/notification',[App\Http\Controllers\Backend\NotificationController::class,'notificationCount']);
Route::get('/offer/supper/list',[App\Http\Controllers\Backend\OfferController::class,'offerSupper']);

Route::group(['prefix'=>'dashboard'],function(){

Route::get('get/card/info',[App\Http\Controllers\Backend\DashboardController::class,'getCardInfo']);
Route::get('get/order/list',[App\Http\Controllers\Backend\DashboardController::class,'getOrderList']);
Route::get('get/order/data',[App\Http\Controllers\Backend\DashboardController::class,'getData']);
Route::get('get/company/data',[App\Http\Controllers\Backend\DashboardController::class,'getCompanyData']);
Route::get('get/monthly/company/data',[App\Http\Controllers\Backend\DashboardController::class,'getCompanyDataMonthly']);
});

	//Category  Start 
Route::group(['prefix'=>'category'],function(){

		Route::post('get/list',[App\Http\Controllers\Backend\CategoryController::class,'getCategoryList']);
		Route::post('change/status',[App\Http\Controllers\Backend\CategoryController::class,'changeCategoryStatus']);
		Route::post('change/top',[App\Http\Controllers\Backend\CategoryController::class,'changeCategoryTop']);
		Route::delete('delete/{id}',[App\Http\Controllers\Backend\CategoryController::class,'deleteCategory']);
		Route::post('check-unique-name',[App\Http\Controllers\Backend\CategoryController::class,'checkUniqueName']);
		Route::post('add',[App\Http\Controllers\Backend\CategoryController::class,'addCategory']);
		Route::post('check-unique-name/edit',[App\Http\Controllers\Backend\CategoryController::class,'checkUniqueNameEdit']);
		Route::get('get/info',[App\Http\Controllers\Backend\CategoryController::class,'getCategoryInfo']);
		Route::post('update',[App\Http\Controllers\Backend\CategoryController::class,'updateCategory']);
		Route::get('get/pc/list',[App\Http\Controllers\Backend\CategoryController::class,'getCategoryListPC']);//for product Page
		Route::post('priroty/decrement',[App\Http\Controllers\Backend\CategoryController::class,'prirotyDecrement']);
		Route::post('priroty/increment',[App\Http\Controllers\Backend\CategoryController::class,'prirotyIncrement']);

		// Route::get('get/active/list',[App\Http\Controllers\Backend\CategoryController::class,'getActiveCategoryList']);

		
		
	    

		
	});

	Route::group(['prefix'=>'subCategory'],function(){

		Route::get('get/list',[App\Http\Controllers\Backend\SubCategoryController::class,'getSubCategoryList']);
		Route::post('change/status',[App\Http\Controllers\Backend\SubCategoryController::class,'changeSubCategoryStatus']);
		Route::delete('delete/{id}',[App\Http\Controllers\Backend\SubCategoryController::class,'deleteSubCategory']);
		Route::get('get/active/category/list',[App\Http\Controllers\Backend\SubCategoryController::class,'getActiveCategoryList']);
		Route::post('add',[App\Http\Controllers\Backend\SubCategoryController::class,'addSubCategory']);
		Route::post('update',[App\Http\Controllers\Backend\SubCategoryController::class,'updateSubCategory']);
		Route::get('get/pc/list',[App\Http\Controllers\Backend\SubCategoryController::class,'getSubCategoryListPC']);//for product Page
		 Route::get('get/info',[App\Http\Controllers\Backend\SubCategoryController::class,'getSubCategoryInfo']);
		 Route::post('check-unique-name',[App\Http\Controllers\Backend\SubCategoryController::class,'checkUniqueName']);
		 Route::post('check-unique-name/edit',[App\Http\Controllers\Backend\SubCategoryController::class,'checkUniqueNameEdit']);
	 

		
	});
	Route::group(['prefix'=>'subsubcategory'],function(){

		Route::get('get/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getSubSubCategoryList']);
		Route::post('change/status',[App\Http\Controllers\Backend\SubSubCategoryController::class,'changeSubSubCategoryStatus']);
		Route::delete('delete/{id}',[App\Http\Controllers\Backend\SubSubCategoryController::class,'deleteSubSubCategory']);
		Route::get('get/active/category/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getActiveCategoryList']);
		Route::get('get/active/subcategory/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getActiveSubCategoryList']);
		Route::post('add',[App\Http\Controllers\Backend\SubSubCategoryController::class,'addSubSubCategory']);
		Route::post('update',[App\Http\Controllers\Backend\SubSubCategoryController::class,'updateSubSubCategory']);
		Route::get('get/pc/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getSubSubCategoryListPC']);//for product Page
		Route::get('get/info',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getSubSubCategoryInfo']);
		Route::get('get/sorting/category/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getSortingCategoryList']);
		Route::get('get/sorting/subcategory/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getSortingSubCategoryList']);
		Route::post('check-unique-name',[App\Http\Controllers\Backend\SubSubCategoryController::class,'checkUniqueName']);
		Route::post('check-unique-name/edit',[App\Http\Controllers\Backend\SubSubCategoryController::class,'checkUniqueNameEdit']);
		// Route::get('get/active/list',[App\Http\Controllers\Backend\SubSubCategoryController::class,'getSubSubCategoryListPC']);

		
		
		
	  

		
	});

	//Category End

	//Brand Start 
Route::group(['prefix'=>'brand'],function(){

		Route::post('add',[App\Http\Controllers\Backend\BrandController::class,'addBrand']);

		Route::post('update',[App\Http\Controllers\Backend\BrandController::class,'updateBrand']);

		Route::post('get/list',[App\Http\Controllers\Backend\BrandController::class,'getBrandList']);

		Route::get('get/pc/list',[App\Http\Controllers\Backend\BrandController::class,'getBrandListPC']);

		Route::get('get/active/list',[App\Http\Controllers\Backend\BrandController::class,'getActiveBrandList']);

		Route::get('get/info',[App\Http\Controllers\Backend\BrandController::class,'getBrandInfo']);

		Route::post('change/status',[App\Http\Controllers\Backend\BrandController::class,'changeBrandStatus']);

		Route::get('delete',[App\Http\Controllers\Backend\BrandController::class,'deleteBrand']);

		Route::post('check-unique-name',[App\Http\Controllers\Backend\BrandController::class,'checkUniqueName']);

		Route::post('check-unique-name/edit',[App\Http\Controllers\Backend\BrandController::class,'checkUniqueNameEdit']);

		

		Route::group(['prefix'=>'seller'],function(){
			Route::post('get/list',[App\Http\Controllers\Backend\SellerBrandController::class,'getSellerBrandList']);
			Route::get('get/info',[App\Http\Controllers\Backend\SellerBrandController::class,'getSellerBrandInfo']);
			Route::post('rejected',[App\Http\Controllers\Backend\SellerBrandController::class,'brandRejected']);
			Route::post('published', [App\Http\Controllers\Backend\SellerBrandController::class,'changeProductPublished']);
		});

	});

	//Brand End

	//Premium Packge Start 
Route::group(['prefix'=>'premiumPackge'],function(){

	Route::post('add',[App\Http\Controllers\Backend\PremiumPackgeController::class,'addPackge']);

	Route::post('update',[App\Http\Controllers\Backend\PremiumPackgeController::class,'updatePackge']);

	Route::get('get/list',[App\Http\Controllers\Backend\PremiumPackgeController::class,'getPackgeList']);

	Route::get('get/pc/list',[App\Http\Controllers\Backend\PremiumPackgeController::class,'getPackgeListPC']);

	Route::get('get/active/list',[App\Http\Controllers\Backend\PremiumPackgeController::class,'getActivePackgeList']);

	Route::get('get/info',[App\Http\Controllers\Backend\PremiumPackgeController::class,'getPackgeInfo']);

	Route::post('change/status',[App\Http\Controllers\Backend\PremiumPackgeController::class,'changePackgeStatus']);

	Route::post('delete',[App\Http\Controllers\Backend\PremiumPackgeController::class,'deletePackge']);

});

//Premium Packge End

Route::group(['prefix'=>'report'],function(){

		Route::get('stock/report', [App\Http\Controllers\Backend\ReportController::class,'getStockInfo']);

		Route::get('sales/report', [App\Http\Controllers\Backend\ReportController::class,'getSalesReport']);
	});

	
	//mohsin sikder

	Route::post('update/shipping/charge',[App\Http\Controllers\Backend\ShippinChargeController::class,'shippingCharge']);
	Route::get('get/shipping/charge/info',[App\Http\Controllers\Backend\ShippinChargeController::class,'shippingChargeInfo']);
	Route::post('update/general/setting',[App\Http\Controllers\Backend\SettingController::class,'general_setting']);
	Route::get('get/general/setting/info',[App\Http\Controllers\Backend\SettingController::class,'general_setting_info']);
	Route::post('update/seo/setting',[App\Http\Controllers\Backend\SettingController::class,'seo_setting']);
	Route::get('get/seo/setting/info',[App\Http\Controllers\Backend\SettingController::class,'seo_setting_info']);
	 Route::get('get/admin/setting/info',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'getInformation']);
	Route::get('get/admin/setting/email/info',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'getEmailInformation']);
	Route::post('update/admin/setting/email',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'general_setting']);
	Route::get('get/admin/setting/sms/info',[App\Http\Controllers\Backend\SmsSettingController::class,'getSmsInformation']);
	Route::post('update/admin/setting/sms',[App\Http\Controllers\Backend\SmsSettingController::class,'general_setting_sms']);
	Route::get('get/admin/setting/footer/info',[App\Http\Controllers\Backend\SettingController::class,'getFooterInformation']);
	Route::post('update/admin/setting/footer',[App\Http\Controllers\Backend\SettingController::class,'general_setting_footer']);
	Route::get('get/admin/setting/auth/info',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'getGoogleAuthInfo']);
	Route::post('update/admin/setting/auth',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'google_auth_setting']);
	Route::get('get/admin/setting/facebook/auth/info',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'getFacebookAuthInfo']);
	Route::post('update/admin/setting/facebook/auth',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'facebook_auth_setting']);
	Route::get('get/admin/setting/pusher/info',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'getPusherInfo']);
	Route::post('update/admin/setting/pusher',[App\Http\Controllers\Backend\AdminPanelSetupController::class,'pusher_setting']);
	Route::post('update/general/setting/sms/reg/otp',[App\Http\Controllers\Backend\SettingController::class,'changeSmsRegOtp']);
	Route::post('update/general/setting/sms/update/phone/otp',[App\Http\Controllers\Backend\SettingController::class,'changeSmsUpdatePhoneOtp']);
	Route::post('update/general/setting/sms/current/phone/otp',[App\Http\Controllers\Backend\SettingController::class,'changeSmsCurrentPhoneOtp']);
	Route::post('update/general/setting/sms/forget/password/otp',[App\Http\Controllers\Backend\SettingController::class,'changeSmsForgetPasswordOtp']);
	Route::post('update/general/setting/sms/order/otp',[App\Http\Controllers\Backend\SettingController::class,'changeSmsOrderOtp']);
	Route::post('update/general/setting/email/reg/otp',[App\Http\Controllers\Backend\SettingController::class,'changeEmailRegOtp']);
	Route::post('update/general/setting/email/update/phone/otp',[App\Http\Controllers\Backend\SettingController::class,'changeEmailUpdatePhoneOtp']);
	Route::post('update/general/setting/email/current/phone/otp',[App\Http\Controllers\Backend\SettingController::class,'changeEmailCurrentPhoneOtp']);
	Route::post('update/general/setting/email/forget/password/otp',[App\Http\Controllers\Backend\SettingController::class,'changeEmailForgetPasswordOtp']);
	Route::post('update/general/setting/email/order/otp',[App\Http\Controllers\Backend\SettingController::class,'changeEmailOrderOtp']);
	Route::post('update/general/setting/user/server/down',[App\Http\Controllers\Backend\SettingController::class,'userPanelDown']);
	Route::post('update/general/setting/seller/server/down',[App\Http\Controllers\Backend\SettingController::class,'sellerPanelDown']);
	//mohsin sikder

Route::group(['prefix'=>'unit'],function(){

		Route::post('add',[App\Http\Controllers\Backend\UnitController::class,'addUnit']);

		Route::post('update',[App\Http\Controllers\Backend\UnitController::class,'updateUnit']);

		Route::get('get/list',[App\Http\Controllers\Backend\UnitController::class,'getUnitList']);

		Route::get('get/pc/list',[App\Http\Controllers\Backend\UnitController::class,'getUnitListPC']);

		Route::get('get/active/list',[App\Http\Controllers\Backend\UnitController::class,'getActiveUnitList']);

		Route::get('get/info',[App\Http\Controllers\Backend\UnitController::class,'getUnitInfo']);

		Route::post('change/status',[App\Http\Controllers\Backend\UnitController::class,'changeUnitStatus']);

		Route::get('delete',[App\Http\Controllers\Backend\UnitController::class,'deleteUnit']);
	});

	Route::group(['prefix'=>'union'],function(){

		Route::post('add', [App\Http\Controllers\Backend\AreaController::class,'addUnion']);

		Route::post('update', [App\Http\Controllers\Backend\AreaController::class,'updateUnion']);

		Route::post('get/list', [App\Http\Controllers\Backend\AreaController::class,'getUnionList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\AreaController::class,'getActiveUnionList']);

		Route::get('get/info', [App\Http\Controllers\Backend\AreaController::class,'getUnionInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\AreaController::class,'changeUnionStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\AreaController::class,'deleteUnion']);
	});

	Route::group(['prefix'=>'thana'],function(){

		Route::post('add', [App\Http\Controllers\Backend\AreaController::class,'addThana']);

		Route::post('update', [App\Http\Controllers\Backend\AreaController::class,'updateThana']);

		Route::post('get/list', [App\Http\Controllers\Backend\AreaController::class,'getThanaList']);
		Route::get('get/pc/list', [App\Http\Controllers\Backend\AreaController::class,'getThanaListPC']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\AreaController::class,'getActiveThanaList']);

		Route::get('get/info', [App\Http\Controllers\Backend\AreaController::class,'getThanaInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\AreaController::class,'changeThanaStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\AreaController::class,'deleteThana']);
	});

	Route::group(['prefix'=>'district'],function(){

		Route::post('add', [App\Http\Controllers\Backend\AreaController::class,'addDistrict']);

		Route::post('update', [App\Http\Controllers\Backend\AreaController::class,'updateDistrict']);

		Route::post('get/list', [App\Http\Controllers\Backend\AreaController::class,'getDistrictList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\AreaController::class,'getActiveDistrictList']);

		Route::get('get/info', [App\Http\Controllers\Backend\AreaController::class,'getDistrictInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\AreaController::class,'changeDistrictStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\AreaController::class,'deleteDistrict']);
	});

	Route::group(['prefix'=>'division'],function(){

		Route::post('add', [App\Http\Controllers\Backend\AreaController::class,'addDivision']);

		Route::post('update', [App\Http\Controllers\Backend\AreaController::class,'updateDivision']);

		Route::post('get/list', [App\Http\Controllers\Backend\AreaController::class,'getDivisionList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\AreaController::class,'getActiveDivisionList']);

		Route::get('get/info', [App\Http\Controllers\Backend\AreaController::class,'getDivisionInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\AreaController::class,'changeDivisionStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\AreaController::class,'deleteDivision']);
	});

	Route::group(['prefix'=>'country'],function(){

		Route::post('add', [App\Http\Controllers\Backend\AreaController::class,'addCountry']);

		Route::post('update', [App\Http\Controllers\Backend\AreaController::class,'updateCountry']);

		Route::post('get/list', [App\Http\Controllers\Backend\AreaController::class,'getCountryList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\AreaController::class,'getActiveCountryList']);

		Route::get('get/info', [App\Http\Controllers\Backend\AreaController::class,'getCountryInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\AreaController::class,'changeCountryStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\AreaController::class,'deleteCountry']);
	});

	Route::group(['prefix'=>'customer'],function(){

		Route::post('add', [App\Http\Controllers\Backend\CustomerController::class,'addCustomer']);

		Route::post('update', [App\Http\Controllers\Backend\CustomerController::class,'updateCustomer']);

		Route::get('get/list', [App\Http\Controllers\Backend\CustomerController::class,'getCustomerList']);
		Route::get('get/info/details', [App\Http\Controllers\Backend\CustomerController::class,'getCustomerDetails']);
		Route::get('get/active/list', [App\Http\Controllers\Backend\CustomerController::class,'getActiveCustomerList']);

		Route::get('get/info', [App\Http\Controllers\Backend\CustomerController::class,'getCustomerInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\CustomerController::class,'changeCustomerStatus']);

		Route::post('verify', [App\Http\Controllers\Backend\CustomerController::class,'verifyCustomer']);
		Route::post('block', [App\Http\Controllers\Backend\CustomerController::class,'blockCustomer']);

		Route::post('delete', [App\Http\Controllers\Backend\CustomerController::class,'deleteCustomer']);
	});


	Route::group(['prefix'=>'refund'],function(){


		Route::post('get/list', [App\Http\Controllers\Backend\CustomerRefundController::class,'getRefundList']);

	});

	Route::group(['prefix'=>'seller'],function(){

		Route::post('add', [App\Http\Controllers\Backend\SellerController::class,'addSeller']);

		Route::post('update', [App\Http\Controllers\Backend\SellerController::class,'updateSeller']);

		Route::get('get/list', [App\Http\Controllers\Backend\SellerController::class,'getSellerList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\SellerController::class,'getActiveSellerList']);

		Route::get('get/info', [App\Http\Controllers\Backend\SellerController::class,'getSellerInfo']);
		Route::get('get/info/details', [App\Http\Controllers\Backend\SellerController::class,'getSellerInfoDetails']);
		Route::post('change/status', [App\Http\Controllers\Backend\SellerController::class,'changeSellerStatus']);
		Route::post('verify', [App\Http\Controllers\Backend\SellerController::class,'verifySeller']);
		Route::post('block', [App\Http\Controllers\Backend\SellerController::class,'blockSeller']);
		Route::get('get/info/details/product', [App\Http\Controllers\Backend\SellerController::class,'getSellerInfoDetailsProduct']);
		Route::get('delete', [App\Http\Controllers\Backend\SellerController::class,'deleteSeller']);
	});

	Route::group(['prefix'=>'supper'],function(){

	

		Route::get('/seller/get/list/verify', [App\Http\Controllers\Backend\OfferController::class,'getSellerList']);
		Route::post('/seller/send/supper/product', [App\Http\Controllers\Backend\OfferController::class,'sendSellerSupperProduct']);

	});

	Route::group(['prefix'=>'staff'],function(){

		Route::post('add', [App\Http\Controllers\Backend\StaffController::class,'addStaff']);

		Route::post('update', [App\Http\Controllers\Backend\StaffController::class,'updateStaff']);

		Route::post('get/list', [App\Http\Controllers\Backend\StaffController::class,'getStaffList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\StaffController::class,'getActiveStaffList']);

		Route::get('get/info', [App\Http\Controllers\Backend\StaffController::class,'getStaffInfo']);

		Route::get('get/active/role/list', [App\Http\Controllers\Backend\StaffController::class,'getActiveRoleList']);

		Route::post('change/status', [App\Http\Controllers\Backend\StaffController::class,'changeStaffStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\StaffController::class,'deleteStaff']);
		Route::get('permission/list', [App\Http\Controllers\Backend\StaffController::class,'getPermissionList']);
		Route::get('auth/info', [App\Http\Controllers\Backend\StaffController::class,'getAuthStaffList']);
		Route::get('auth/info/edit', [App\Http\Controllers\Backend\StaffController::class,'getStaffInfoEdit']);
		Route::post('password/change', [App\Http\Controllers\Backend\StaffController::class,'passwordChange']);
		Route::post('update/auth/info', [App\Http\Controllers\Backend\StaffController::class,'updateAuthStaffInfo']);
		
	});
	Route::group(['prefix'=>'roll'],function(){

		Route::post('add', [App\Http\Controllers\Backend\RollController::class,'addRoll']);

		Route::post('update', [App\Http\Controllers\Backend\RollController::class,'updateRoll']);

		Route::post('get/list', [App\Http\Controllers\Backend\RollController::class,'getRollList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\RollController::class,'getActiveRollList']);

		Route::get('get/info', [App\Http\Controllers\Backend\RollController::class,'getRollInfo']);

		Route::get('get/active/role/list', [App\Http\Controllers\Backend\RollController::class,'getActiveRoleList']);

		Route::post('change/status', [App\Http\Controllers\Backend\RollController::class,'changeRollStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\RollController::class,'deleteRoll']);
	});

	Route::group(['prefix'=>'permission'],function(){
			
		Route::post('add/or/remove', [App\Http\Controllers\Backend\PermissionController::class,'addOrRemovePermission']);
		Route::get("get/Rolepermission/list", [App\Http\Controllers\Backend\PermissionController::class,'getRoleWisePermissionList']);

	});

	

	Route::group(['prefix'=>'color'],function(){

		Route::post('add', [App\Http\Controllers\Backend\ColorController::class,'addColor']);

		Route::post('update', [App\Http\Controllers\Backend\ColorController::class,'updateColor']);

		Route::post('get/list', [App\Http\Controllers\Backend\ColorController::class,'getColorList']);
		Route::get('get/pc/list',  [App\Http\Controllers\Backend\ColorController::class,'getColorListPC']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\ColorController::class,'getActiveColorList']);

		Route::get('get/info', [App\Http\Controllers\Backend\ColorController::class,'getColorInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\ColorController::class,'changeColorStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\ColorController::class,'deleteColor']);

	});

	Route::group(['prefix'=>'size'],function(){

		Route::post('add', [App\Http\Controllers\Backend\SizeController::class,'addSize']);

		Route::post('update', [App\Http\Controllers\Backend\SizeController::class,'updateSize']);

		Route::post('get/list', [App\Http\Controllers\Backend\SizeController::class,'getSizeList']);

		Route::get('get/pc/list', [App\Http\Controllers\Backend\SizeController::class,'getSizeListPC']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\SizeController::class,'getActiveSizeList']);

		Route::get('get/info', [App\Http\Controllers\Backend\SizeController::class,'getSizeInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\SizeController::class,'changeSizeStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\SizeController::class,'deleteSize']);

	});


	Route::group(['prefix'=>'sizeAttribute'],function(){

		Route::post('add', [App\Http\Controllers\Backend\SizeAttributeController::class,'addSizeAttribute']);

		Route::post('update', [App\Http\Controllers\Backend\SizeAttributeController::class,'updateSizeAttribute']);

		Route::post('get/list', [App\Http\Controllers\Backend\SizeAttributeController::class,'getSizeAttributeList']);

		Route::get('get/pc/list', [App\Http\Controllers\Backend\SizeAttributeController::class,'getSizeAttributeListPC']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\SizeAttributeController::class,'getActiveSizeAttributeList']);

		Route::get('get/info', [App\Http\Controllers\Backend\SizeAttributeController::class,'getSizeAttributeInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\SizeAttributeController::class,'changeSizeAttributeStatus']);

		Route::post('delete', [App\Http\Controllers\Backend\SizeAttributeController::class,'deleteSizeAttribute']);
		

	});
	

	Route::group(['prefix'=>'slider'],function(){

		Route::post('add', [App\Http\Controllers\Backend\SliderController::class,'addSlider']);

		Route::post('update', [App\Http\Controllers\Backend\SliderController::class,'updateSlider']);

		Route::post('get/list', [App\Http\Controllers\Backend\SliderController::class,'getSliderList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\SliderController::class,'getActiveSliderList']);

		Route::get('get/info', [App\Http\Controllers\Backend\SliderController::class,'getSliderInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\SliderController::class,'changeSliderStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\SliderController::class,'deleteSlider']);

	});
	Route::group(['prefix'=>'supperSlider'],function(){

		Route::post('add', [App\Http\Controllers\Backend\SupperSliderController::class,'addSlider']);

		Route::post('update', [App\Http\Controllers\Backend\SupperSliderController::class,'updateSlider']);

		Route::post('get/list', [App\Http\Controllers\Backend\SupperSliderController::class,'getSliderList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\SupperSliderController::class,'getActiveSliderList']);

		Route::get('get/info', [App\Http\Controllers\Backend\SupperSliderController::class,'getSliderInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\SupperSliderController::class,'changeSliderStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\SupperSliderController::class,'deleteSlider']);

	});

	Route::group(['prefix'=>'rightSlider'],function(){

		Route::post('add', [App\Http\Controllers\Backend\RightBannerController::class,'addRightSlider']);

		Route::post('update', [App\Http\Controllers\Backend\RightBannerController::class,'updateRightSlider']);

		Route::post('get/list', [App\Http\Controllers\Backend\RightBannerController::class,'getRightSliderList']);

		Route::get('get/pc/list', [App\Http\Controllers\Backend\RightBannerController::class,'getRightSliderListPC']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\RightBannerController::class,'getActiveRightSliderList']);

		Route::get('get/info', [App\Http\Controllers\Backend\RightBannerController::class,'getRightSliderInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\RightBannerController::class,'changeRightSliderStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\RightBannerController::class,'deleteRightSlider']);

	});

	Route::group(['prefix'=>'shockingDeal'],function(){

		Route::post('add', [App\Http\Controllers\Backend\ShockingDeaController::class,'addShockingDeal']);

		Route::post('update', [App\Http\Controllers\Backend\ShockingDeaController::class,'updateShockingDeal']);

		Route::post('get/list', [App\Http\Controllers\Backend\ShockingDeaController::class,'getShockingDealList']);
		
		Route::get('get/pc/list', [App\Http\Controllers\Backend\ShockingDeaController::class,'getShockingDealListPC']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\ShockingDeaController::class,'getActiveShockingDealList']);

		Route::get('get/info', [App\Http\Controllers\Backend\ShockingDeaController::class,'getShockingDealInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\ShockingDeaController::class,'changeShockingDealStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\ShockingDeaController::class,'deleteShockingDeal']);

	});
	Route::group(['prefix'=>'topBanner'],function(){

		Route::post('add',[App\Http\Controllers\Backend\TopBannerController::class,'addTopBanner']);

		Route::post('update',[App\Http\Controllers\Backend\TopBannerController::class,'updateTopBanner']);

		Route::post('get/list',[App\Http\Controllers\Backend\TopBannerController::class,'getTopBannerList']);
		
		Route::get('get/active/list',[App\Http\Controllers\Backend\TopBannerController::class,'getActiveTopBannerList']);

		Route::get('get/info',[App\Http\Controllers\Backend\TopBannerController::class,'getTopBannerInfo']);

		Route::post('change/status',[App\Http\Controllers\Backend\TopBannerController::class,'changeTopBannerStatus']);

		Route::get('delete',[App\Http\Controllers\Backend\TopBannerController::class,'deleteTopBanner']);

	});
	Route::group(['prefix'=>'banner'],function(){

		Route::post('add', [App\Http\Controllers\Backend\BannerController::class,'addBanner']);

		Route::post('update', [App\Http\Controllers\Backend\BannerController::class,'updateBanner']);

		Route::post('get/list', [App\Http\Controllers\Backend\BannerController::class,'getBannerList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\BannerController::class,'getActiveBannerList']);

		Route::get('get/info', [App\Http\Controllers\Backend\BannerController::class,'getBannerInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\BannerController::class,'changeBannerStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\BannerController::class,'deleteBanner']);

	});
	Route::group(['prefix'=>'returnPolicy'],function(){

		Route::post('add', [App\Http\Controllers\Backend\ReturnPolicyController::class,'addPolicy']);

		Route::post('update', [App\Http\Controllers\Backend\ReturnPolicyController::class,'updatePolicy']);

		Route::post('get/list', [App\Http\Controllers\Backend\ReturnPolicyController::class,'getPolicyList']);

		Route::get('get/info', [App\Http\Controllers\Backend\ReturnPolicyController::class,'getPolicyInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\ReturnPolicyController::class,'changePolicyStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\ReturnPolicyController::class,'deletePolicy']);

	});
	Route::group(['prefix'=>'warehouse'],function(){

		Route::post('add', [App\Http\Controllers\Backend\WarehouseController::class,'addWarehouse']);

		Route::post('update', [App\Http\Controllers\Backend\WarehouseController::class,'updateWarehouse']);

		Route::post('get/list', [App\Http\Controllers\Backend\WarehouseController::class,'getWarehouseList']);

		Route::get('get/info', [App\Http\Controllers\Backend\WarehouseController::class,'getWarehouseInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\WarehouseController::class,'changeWarehouseStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\WarehouseController::class,'deleteWarehouse']);

	});

	Route::group(['prefix'=>'curior'],function(){

		Route::post('add', [App\Http\Controllers\Backend\CuriorController::class,'addCurior']);

		Route::post('update', [App\Http\Controllers\Backend\CuriorController::class,'updateCurior']);

		Route::post('get/list', [App\Http\Controllers\Backend\CuriorController::class,'getCuriorList']);

		Route::get('get/info', [App\Http\Controllers\Backend\CuriorController::class,'getCuriorInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\CuriorController::class,'changeCuriorStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\CuriorController::class,'deleteCurior']);

	});
	Route::group(['prefix'=>'financialAccount'],function(){

		Route::post('add', [App\Http\Controllers\Backend\FinancialAccountController::class,'addFinancialAccount']);

		Route::post('update', [App\Http\Controllers\Backend\FinancialAccountController::class,'updateFinancialAccount']);

		Route::post('get/list', [App\Http\Controllers\Backend\FinancialAccountController::class,'getFinancialAccountList']);

		Route::get('get/info', [App\Http\Controllers\Backend\FinancialAccountController::class,'getFinancialAccountInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\FinancialAccountController::class,'changeFinancialAccountStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\FinancialAccountController::class,'deleteFinancialAccount']);

	});
	Route::group(['prefix'=>'product'],function(){

		Route::post('add', [App\Http\Controllers\Backend\ProductController::class,'addProduct']);

		Route::post('update', [App\Http\Controllers\Backend\ProductController::class,'updateProduct']);
		Route::post('restore', [App\Http\Controllers\Backend\ProductController::class,'restore']);
		Route::get('get/list', [App\Http\Controllers\Backend\ProductController::class,'getProductList']);
	

		Route::get('get/active/list', [App\Http\Controllers\Backend\ProductController::class,'getActiveProductList']);

		 Route::get('details/info', [App\Http\Controllers\Backend\ProductController::class,'getProductInfo']);
		 Route::get('get/stock',[App\Http\Controllers\Backend\ProductController::class,'getStock']);

		 Route::post('/upload-images', [App\Http\Controllers\Backend\ProductController::class,'uploadImages']);
		Route::post('change/status', [App\Http\Controllers\Backend\ProductController::class,'changeProductStatus']);
		Route::delete('delete/{id}', [App\Http\Controllers\Backend\ProductController::class,'deleteProduct']);
		Route::delete('quantity/delete/{id}', [App\Http\Controllers\Backend\ProductController::class,'deleteProductQuantity']);
		Route::post('published', [App\Http\Controllers\Backend\ProductController::class,'changeProductPublished']);
		Route::post('bToB', [App\Http\Controllers\Backend\ProductController::class,'changeProductBToB']);
		Route::post('bToC', [App\Http\Controllers\Backend\ProductController::class,'changeProductBToC']);
		Route::post('rejected',[App\Http\Controllers\Backend\ProductController::class,'productRejected']);
		Route::post('suspended',[App\Http\Controllers\Backend\ProductController::class,'suspendedProduct']);
		Route::post('deleteAll',[App\Http\Controllers\Backend\ProductController::class,'deleteAll']);
		Route::post('publishedAll',[App\Http\Controllers\Backend\ProductController::class,'publishedAll']);
		Route::post('update/stock',[App\Http\Controllers\Backend\ProductController::class,'stockUpdateQuantity']);
		Route::post('update/stock/price',[App\Http\Controllers\Backend\ProductController::class,'stockUpdateQuantityPrice']);
	});

	Route::group(['prefix'=>'cart/rules'],function(){

		Route::get('active/list', [App\Http\Controllers\Backend\CartRulesController::class,'getActiveCartRulesList']);

		Route::get('get/list', [App\Http\Controllers\Backend\CartRulesController::class,'getCartRulesList']);

		Route::get('info/status/change', [App\Http\Controllers\Backend\CartRulesController::class,'changeCartRulesStatus']);

		Route::post('info/delete', [App\Http\Controllers\Backend\CartRulesController::class,'deleteCartRules']);

		Route::get('info/edit/', [App\Http\Controllers\Backend\CartRulesController::class,'editCartRulesInfo']);
		
		Route::post('store', [App\Http\Controllers\Backend\CartRulesController::class,'storeCartRules']);

		Route::post('update', [App\Http\Controllers\Backend\CartRulesController::class,'updateCartRules']);

	});
	Route::group(['prefix'=>'product/delivery/charge'],function(){

		Route::get('active/list', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'getActiveDeliveryRulesList']);

		Route::post('get/list', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'getDeliveryChargeList']);

		Route::post('change/status', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'changeDeliveryChargeStatus']);

		Route::post('delete', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'deleteDeliveryCharge']);

		Route::get('get/info/', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'getDeliveryChargeInfo']);
		Route::get('get/active/category/list',[App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'getActiveCategoryList']);
		Route::get('get/active/subcategory/list',[App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'getActiveSubCategoryList']);
		Route::get('get/active/subsubcategory/list',[App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'getActiveSubSubCategoryList']);

		Route::post('add', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'addDeliveryCharge']);

		Route::post('update', [App\Http\Controllers\Backend\ProductDeliveryChargeController::class,'updateDeliveryCharge']);

	});	
	Route::group(['prefix'=>'delivery/rules'],function(){

		Route::get('active/list', [App\Http\Controllers\Backend\CartRulesController::class,'getActiveDeliveryRulesList']);

		Route::get('get/list', [App\Http\Controllers\Backend\CartRulesController::class,'getDeliveryRulesList']);

		Route::get('info/status/change', [App\Http\Controllers\Backend\CartRulesController::class,'changeDeliveryRulesStatus']);

		Route::post('info/delete', [App\Http\Controllers\Backend\CartRulesController::class,'deleteDeliveryRules']);

		Route::get('info/edit/', [App\Http\Controllers\Backend\CartRulesController::class,'editDeliveryRulesInfo']);

		Route::post('store', [App\Http\Controllers\Backend\CartRulesController::class,'storeDeliveryRules']);

		Route::post('update', [App\Http\Controllers\Backend\CartRulesController::class,'updateDeliveryRules']);

	});	

	Route::group(['prefix'=>'order'],function(){

		Route::post('add', [App\Http\Controllers\Backend\OrderController::class,'addOrder']);

		Route::post('update', [App\Http\Controllers\Backend\OrderController::class,'updateOrder']);

		Route::get('get/list', [App\Http\Controllers\Backend\OrderController::class,'getOrderList']);
		Route::post('get/item/list', [App\Http\Controllers\Backend\OrderController::class,'getOrderItemList']);

		//Route::get('get/active/list', [App\Http\Controllers\Backend\OrderController::class,'getActiveOrderList']);

		Route::get('get/info', [App\Http\Controllers\Backend\OrderController::class,'getOrderInfo']);
		Route::get('get/item/info', [App\Http\Controllers\Backend\OrderController::class,'getOrderItemInfo']);
		Route::get('print/invoice', [App\Http\Controllers\Backend\OrderController::class,'printInvoice']);
		Route::post('change/status', [App\Http\Controllers\Backend\OrderController::class,'changeOrderStatus']);
		Route::post('delete/single_product', [App\Http\Controllers\Backend\OrderController::class,'singleProductDelete']);
		Route::get('show/single_product', [App\Http\Controllers\Backend\OrderController::class,'singleProductShow']);
		Route::get('print/single_product', [App\Http\Controllers\Backend\OrderController::class,'singleProductPrint']);
		Route::post('update/single_product', [App\Http\Controllers\Backend\OrderController::class,'singleProductUpdate']);

		
		Route::post('delete', [App\Http\Controllers\Backend\OrderController::class,'deleteOrder']);

	});

	Route::group(['prefix'=>'subscriber'],function(){

	

		Route::post('get/list', [App\Http\Controllers\Backend\SubscriberController::class,'getSubscriberList']);

		Route::post('send/message', [App\Http\Controllers\Backend\SubscriberController::class,'sendMessage']);


		Route::post('change/status', [App\Http\Controllers\Backend\SubscriberController::class,'changeSubscriberStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\SubscriberController::class,'deleteSubscriber']);
	});

	Route::group(['prefix'=>'coupon'],function(){

		Route::post('add', [App\Http\Controllers\Backend\CouponCodeController::class,'addCouponCode']);

		Route::post('update', [App\Http\Controllers\Backend\CouponCodeController::class,'updateCouponCode']);

		Route::post('get/list', [App\Http\Controllers\Backend\CouponCodeController::class,'getCouponCodeList']);
		Route::post('get/list/staff', [App\Http\Controllers\Backend\CouponCodeController::class,'getCouponCodeListStaff']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\CouponCodeController::class,'getActiveCouponCodeList']);

		Route::get('get/info', [App\Http\Controllers\Backend\CouponCodeController::class,'getCouponCodeInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\CouponCodeController::class,'changeStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\CouponCodeController::class,'deleteCouponCode']);

	});
	Route::group(['prefix'=>'flashSale'],function(){

		Route::post('add', [App\Http\Controllers\Backend\FlashSaleController::class,'addFlashSale']);

		Route::post('update', [App\Http\Controllers\Backend\FlashSaleController::class,'updateFlashSale']);

		Route::post('get/list', [App\Http\Controllers\Backend\FlashSaleController::class,'getFlashSaleList']);

		Route::get('get/active/list', [App\Http\Controllers\Backend\FlashSaleController::class,'getActiveFlashSaleList']);

		Route::get('get/info', [App\Http\Controllers\Backend\FlashSaleController::class,'getFlashSaleInfo']);

		Route::post('change/status', [App\Http\Controllers\Backend\FlashSaleController::class,'changeStatus']);

		Route::get('delete', [App\Http\Controllers\Backend\FlashSaleController::class,'deleteFlashSale']);

	});
	Route::group(['prefix'=>'payment'],function(){

		Route::get('get/list', [App\Http\Controllers\Backend\PaymentController::class,'getSellerRequestPayment']);
		Route::get('get/info', [App\Http\Controllers\Backend\PaymentController::class,'getPaymentInfo']);
		Route::post('update/status', [App\Http\Controllers\Backend\PaymentController::class,'updatePaymentStatus']);

	

	});
	Route::group(['prefix'=>'message'],function(){

		Route::post('get/list', [App\Http\Controllers\Backend\CustomerMessageController::class,'getMessageList']);
		Route::get('get/info', [App\Http\Controllers\Backend\CustomerMessageController::class,'getMessageInfo']);
		Route::post('update/status', [App\Http\Controllers\Backend\CustomerMessageController::class,'updatePaymentStatus']);
		Route::get('delete', [App\Http\Controllers\Backend\CustomerMessageController::class,'delete']);

	

	});

	//ERP
Route::group(['prefix'=>'v1'], function(){
	Route::group(['prefix'=>'erp'], function(){
		Route::post('/setting/insert/update', [App\Http\Controllers\ERP\GeneralSettingController::class, 'generalSetting']);
		Route::post('/setting/notification', [App\Http\Controllers\ERP\GeneralSettingController::class, 'notification']);
		Route::get('/setting/info', [App\Http\Controllers\ERP\GeneralSettingController::class, 'generalSettingInfo']);
		Route::post('/update/email',[App\Http\Controllers\ERP\Auth\LoginController::class,'updateEmail']);
		Route::post('/update/password',[App\Http\Controllers\ERP\Auth\LoginController::class,'passwordChange']);
		Route::group(['prefix'=>'department'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\DepartmentController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\DepartmentController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\DepartmentController::class, 'dataInfoDelete']);
			Route::post('/designation/delete', [App\Http\Controllers\ERP\HRM\DepartmentController::class, 'deleteDesignation']);
		});
		Route::group(['prefix'=>'branch'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\BranchController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\BranchController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\BranchController::class, 'dataInfoDelete']);
			
		});
		Route::group(['prefix'=>'employee'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\EmployeeController::class,'index']);
			Route::get('/get/info', [App\Http\Controllers\ERP\HRM\EmployeeController::class,'getEmployeeInfo']);
			Route::get('/get/department/list', [App\Http\Controllers\ERP\HRM\EmployeeController::class,'getDepartment']);
			Route::get('/get/designation/list/{id}', [App\Http\Controllers\ERP\HRM\EmployeeController::class,'getDesignation']);
			Route::get('/get/branch/list', [App\Http\Controllers\ERP\HRM\EmployeeController::class,'getBranch']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\EmployeeController::class, 'dataInfoAddOrUpdate']);
			Route::post('/add/salary', [App\Http\Controllers\ERP\HRM\EmployeeController::class, 'addSalary']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\EmployeeController::class, 'dataInfoDelete']);
			Route::post('/salary/delete', [App\Http\Controllers\ERP\HRM\EmployeeController::class, 'salaryDelete']);
		});



		Route::group(['prefix'=>'award'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\AwardController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\AwardController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\AwardController::class, 'dataInfoDelete']);
			Route::get('/get/employee/list', [App\Http\Controllers\ERP\HRM\AwardController::class,'getEmployee']);
		});
		Route::group(['prefix'=>'expense'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\ExpenseController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\ExpenseController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\ExpenseController::class, 'dataInfoDelete']);
			
		});
		Route::group(['prefix'=>'noticeboard'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\NoticeboardController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\NoticeboardController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\NoticeboardController::class, 'dataInfoDelete']);
			
		});

		Route::group(['prefix'=>'leaveType'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\LeaveTypeController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\LeaveTypeController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\LeaveTypeController::class, 'dataInfoDelete']);
			
		});
		Route::group(['prefix'=>'leaveApplication'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\LeaveApplicationController::class,'index']);
	        Route::post('/status/update', [App\Http\Controllers\ERP\HRM\LeaveApplicationController::class, 'updateStatus']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\LeaveApplicationController::class, 'dataInfoDelete']);
			
		});

		Route::group(['prefix'=>'holydays'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\HolydayController::class,'index']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\HolydayController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\HolydayController::class, 'dataInfoDelete']);
			
		});
		Route::group(['prefix'=>'payroll'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\PayrollsController::class,'index']);
			Route::get('/get/employee/info', [App\Http\Controllers\ERP\HRM\PayrollsController::class,'getEmployeeInfo']);
			Route::get('/get/employee/list', [App\Http\Controllers\ERP\HRM\PayrollsController::class,'getEmployee']);
			Route::get('/get/info', [App\Http\Controllers\ERP\HRM\PayrollsController::class,'getPayrollInfo']);
	        Route::post('/insert', [App\Http\Controllers\ERP\HRM\PayrollsController::class, 'dataInfoAdd']);
			Route::post('/update', [App\Http\Controllers\ERP\HRM\PayrollsController::class, 'dataInfoUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\PayrollsController::class, 'dataInfoDelete']);
			
		});
		Route::group(['prefix'=>'attendance'], function(){
			Route::get('/get/list', [App\Http\Controllers\ERP\HRM\AttendanceController::class,'index']);
			Route::get('/mark/list', [App\Http\Controllers\ERP\HRM\AttendanceController::class,'markAttendance']);
			Route::get('/get/employee/list', [App\Http\Controllers\ERP\HRM\AttendanceController::class,'getEmployee']);
	        Route::post('/insert/update', [App\Http\Controllers\ERP\HRM\AttendanceController::class, 'dataInfoAddOrUpdate']);
			Route::post('/delete', [App\Http\Controllers\ERP\HRM\AttendanceController::class, 'dataInfoDelete']);
			Route::post('/update/employee', [App\Http\Controllers\ERP\HRM\AttendanceController::class, 'updateEmployeeAttendanceInfo']);
			
		});

		Route::group(['prefix'=>'dashboard'], function(){
			Route::get('/get/info', [App\Http\Controllers\ERP\HRM\DashboardController::class,'dashboardInfo']);
			
			
		});


	});

	

});


	//ERP
});


	//ERP
Route::post('staff/v1/erp/login',[App\Http\Controllers\ERP\Auth\LoginController::class,'login']);

Route::group(['prefix'=>'employee','as'=>'employee.','middleware'=>'EmployeeAuth'],function(){
	Route::group(['prefix'=>'v1'], function(){
		Route::group(['prefix'=>'erp'], function(){
			Route::get('/dashboard/info',[App\Http\Controllers\ERP\Employee\DashboardController::class,'dashboard'] );
			Route::get('/employee/info',[App\Http\Controllers\ERP\Employee\DashboardController::class,'employeeInfo'] );
			Route::post('/update/password', [App\Http\Controllers\ERP\Auth\LoginController::class, 'updatePassword']);
			Route::group(['prefix'=>'leave'], function(){
				Route::get('/get/list', [App\Http\Controllers\ERP\Employee\LeaveController::class,'index']);
				Route::post('/insert/update', [App\Http\Controllers\ERP\Employee\LeaveController::class, 'dataInfoAddOrUpdate']);
				Route::post('/multiple/insert/update', [App\Http\Controllers\ERP\Employee\LeaveController::class, 'dataInfoAddOrUpdateMultiple']);
				Route::post('/delete', [App\Http\Controllers\ERP\Employee\LeaveController::class, 'dataInfoDelete']);
				Route::get('/get/leaveType/list', [App\Http\Controllers\ERP\Employee\LeaveController::class,'leaveType']);
			});
			Route::group(['prefix'=>'expense'], function(){
				Route::get('/get/list', [App\Http\Controllers\ERP\Employee\ExpenseController::class,'index']);
				Route::post('/insert/update', [App\Http\Controllers\ERP\Employee\ExpenseController::class, 'dataInfoAddOrUpdate']);
				Route::post('/delete', [App\Http\Controllers\ERP\Employee\ExpenseController::class, 'dataInfoDelete']);
				
			});
			Route::group(['prefix'=>'attendance'], function(){
				Route::get('/get/list', [App\Http\Controllers\ERP\Employee\AttendanceController::class,'index']);
				Route::post('/clock/in', [App\Http\Controllers\ERP\Employee\AttendanceController::class, 'clockIn']);
				Route::post('/clock/out', [App\Http\Controllers\ERP\Employee\AttendanceController::class, 'clockOut']);
				
			});

		});
	});
});


	//ERP

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

// Customer Panel Start

Route::post('customer/login',[App\Http\Controllers\Customer\AuthController::class,'login']);

Route::post('customer/otp/verify',[App\Http\Controllers\Customer\AuthController::class,'otpVerify']);
Route::post('customer/signup',[App\Http\Controllers\Customer\AuthController::class,'signup']);
Route::post('customer/forget/password',[App\Http\Controllers\Customer\AuthController::class,'forgetPassword']);

Route::post('customer/message',[App\Http\Controllers\Frontend\CustomerController::class,'message']);

Route::post('customer/follow/store',[App\Http\Controllers\Frontend\CustomerController::class,'followStore']);
Route::get('customer/logout',[App\Http\Controllers\Frontend\CustomerController::class,'logout']);
Route::get('customer/noverifyInfo',[App\Http\Controllers\Frontend\CustomerController::class,'notVerifyInfo']);


//   Route::post('customer/verify/otp',[App\Http\Controllers\Frontend\CustomerController::class,'verifyOtp']);

  Route::post('customer/order/track',[App\Http\Controllers\Frontend\OrderController::class,'getOrderTrackInfo']);
  Route::group(['prefix'=>'customer','as'=>'customer.','middleware'=>'CustomerAuth'],function(){


	Route::post('get/delivery/charge',[App\Http\Controllers\Frontend\OrderController::class,'getDeliveryCharge']);
	Route::get('get/total/payable',[App\Http\Controllers\Frontend\OrderController::class,'totalPayable']);
	Route::post('product/add/to/cart',[App\Http\Controllers\Frontend\CartController::class,'addToCart']);
	Route::get('cart/list/count',[App\Http\Controllers\Frontend\CartController::class,'getCartCount']);
	Route::get('get/cart/list',[App\Http\Controllers\Frontend\CartController::class,'getCartList']);
	Route::get('get/checkout/list',[App\Http\Controllers\Frontend\CartController::class,'getCheckOutList']);
	Route::post('update/qunatity/increase',[App\Http\Controllers\Frontend\CartController::class,'cartQuantityIncrease']);
	Route::post('update/qunatity/decrease',[App\Http\Controllers\Frontend\CartController::class,'cartQuantityDecrease']);
	Route::post('update/qunatity',[App\Http\Controllers\Frontend\CartController::class,'cartQuantityUpdate']);
	Route::post('update/check/uncheck/all',[App\Http\Controllers\Frontend\CartController::class,'checkUncheckAll']);
	Route::post('update/check/uncheck/seller',[App\Http\Controllers\Frontend\CartController::class,'checkUncheckSeller']);
	Route::post('update/check/uncheck/product',[App\Http\Controllers\Frontend\CartController::class,'checkUncheckProduct']);
	Route::post('update/check/uncheck/stock/cart',[App\Http\Controllers\Frontend\CartController::class,'checkUncheckStockInfo']);
	Route::post('remove/to/cart',[App\Http\Controllers\Frontend\CartController::class,'removeCartItem']);
	Route::post('remove/to/cart/stock/info',[App\Http\Controllers\Frontend\CartController::class,'removeCartStockInfo']);
	Route::post('get/store/voucher',[App\Http\Controllers\Frontend\CustomerController::class,'getVoucher']);
	Route::post('get/promo/discount',[App\Http\Controllers\Frontend\OrderController::class,'getPromoDiscount']);
	Route::get('get/google/info', [App\Http\Controllers\Frontend\CustomerController::class,'googleAuth']);

	Route::get('order/track/status',[App\Http\Controllers\Frontend\OrderController::class,'getOrderTrackStatus']);
	Route::post('order/placed',[App\Http\Controllers\Frontend\OrderController::class,'orderPlaced']);
	Route::post('order/placed/cod',[App\Http\Controllers\Frontend\OrderController::class,'orderPlacedCod']);
	Route::post('order/placed/online/payment',[App\Http\Controllers\Frontend\OrderController::class,'orderPlacedOnlinePayment']);
	Route::post('product/review',[App\Http\Controllers\Frontend\OrderController::class,'productReview']);
	Route::get('product/review/info',[App\Http\Controllers\Frontend\OrderController::class,'getReviewInfo']);
	Route::get('get/review/info',[App\Http\Controllers\Frontend\OrderController::class,'getCustomerReviewInfo']);
	Route::get('selected/address/info',[App\Http\Controllers\Frontend\CustomerController::class,'getSelectedAddressInfo']);

	Route::get('get/last/address',[App\Http\Controllers\Frontend\CustomerController::class,'getLastAddress']);
	Route::get('get/info',[App\Http\Controllers\Frontend\CustomerController::class,'getCustomerInfo']);

	Route::get('get/address/info',[App\Http\Controllers\Frontend\CustomerController::class,'getAddressInfo']);

	Route::post('password/change',[App\Http\Controllers\Frontend\CustomerController::class,'passwordChange']);
	Route::post('product/add/to/wish/list',[App\Http\Controllers\Frontend\CustomerController::class,'addWishList']);
	Route::post('order/cancel',[App\Http\Controllers\Frontend\CustomerController::class,'cancelOrder']);
	Route::get('wish/list/count',[App\Http\Controllers\Frontend\CustomerController::class,'getWiseListCount']);

	Route::post('address/add',[App\Http\Controllers\Frontend\CustomerController::class,'addAddress']);

	Route::post('address/update',[App\Http\Controllers\Frontend\CustomerController::class,'updateAddress']);

	Route::get('get/info',[App\Http\Controllers\Frontend\CustomerController::class,'getCustomerInfo']);

	Route::get('get/purchase/history',[App\Http\Controllers\Frontend\CustomerController::class,'getPurchaseHistory']);

	Route::get('get/addresses',[App\Http\Controllers\Frontend\CustomerController::class,'getCustomerAddresses']);
	Route::post('update/info',[App\Http\Controllers\Frontend\CustomerController::class,'updateCustomerInfo']);

	Route::get('get/order/list',[App\Http\Controllers\Frontend\CustomerController::class,'getOrderList']);
	Route::get('get/order/list/without/payment/type',[App\Http\Controllers\Frontend\CustomerController::class,'getOrderListPaymentType']);
	Route::get('get/wise/list',[App\Http\Controllers\Frontend\CustomerController::class,'getWiseList']);
	Route::get('get/voucher/list',[App\Http\Controllers\Frontend\CustomerController::class,'getVoucherList']);
	Route::get('delete/wise/list',[App\Http\Controllers\Frontend\CustomerController::class,'deleteWiseList']);
	Route::get('get/order/details',[App\Http\Controllers\Frontend\CustomerController::class,'getOrderDetails']);


    Route::post('send/verify/phone',[App\Http\Controllers\Frontend\CustomerController::class,'verifyCurrentPhone']);
    Route::post('sms/verify/phone',[App\Http\Controllers\Frontend\CustomerController::class,'smsVerifyCurrentPhone']);
    Route::post('update/phone',[App\Http\Controllers\Frontend\CustomerController::class,'updatePhone']);
    Route::post('add/new/email',[App\Http\Controllers\Frontend\CustomerController::class,'addNewEmail']);
    Route::post('verify/email',[App\Http\Controllers\Frontend\CustomerController::class,'verifyEmail']);
	Route::post('update/email',[App\Http\Controllers\Frontend\CustomerController::class,'updateEmail']);
    Route::post('send/verify/email',[App\Http\Controllers\Frontend\CustomerController::class,'sendVerifyEmail']);
    Route::get('get/order/refund/return',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'getReturnOrder']);
    Route::get('get/return/policy',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'getReturnPolicy']);
    Route::get('get/curior',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'getCurior']);
    Route::get('get/financial/account',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'getFinancialAccount']);
    Route::post('send/refund/product',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'sendRefundProduct']);
    Route::get('get/refund/product',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'getRefundProduct']);
    Route::post('confirm/refund/product',[App\Http\Controllers\Frontend\Customer\ReturnOrderController::class,'confirmRefundProduct']);
    Route::post('verify/email',[App\Http\Controllers\Frontend\CustomerController::class,'verifyEmail']);
    Route::post('send/verify/email',[App\Http\Controllers\Frontend\CustomerController::class,'sendVerifyEmail']);
	Route::get('get/following/store',[App\Http\Controllers\Frontend\CustomerController::class,'followingStore']);
	Route::post('unFollowing/store',[App\Http\Controllers\Frontend\CustomerController::class,'unFollowingStore']);

	Route::get('following/info',[App\Http\Controllers\Frontend\CustomerController::class,'followingInfo']);
});



