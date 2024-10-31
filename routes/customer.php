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
