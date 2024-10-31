<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get("email", [App\HTTP\Controllers\EmailConfigurationController::class, "composeEmail"])->name("email");
Route::post("compose-email", [App\HTTP\Controllers\EmailConfigurationController::class, "sendEmail"])->name("compose-email");
Route::get('test', [App\Http\Controllers\GeneralController::class,'test']);

Route::get('/sitemap.xml',[App\Http\Controllers\Frontend\HomeController::class,"sitemapGenerate"]);

// Route::get("online/payment", [App\Http\Controllers\Frontend\OrderController::class,'onlinePaymentSuccess']);
// Route::get("order/success/{orderId}", [App\Http\Controllers\Frontend\HomeController::class,'index'])->name('order.success.message');
// Route::get("order/fail/{orderId}", [App\Http\Controllers\Frontend\HomeController::class,'index'])->name('order.fail.message');
// Route::get("order/cancel/{orderId}", [App\Http\Controllers\Frontend\HomeController::class,'index'])->name('order.cancel.message');

Route::get("order/{orderId}/online/payment", [App\Http\Controllers\Frontend\OrderController::class,'onlinePayment']);

Route::post("online/payment/success", [App\Http\Controllers\Frontend\OrderController::class,'onlinePaymentSuccess']);

Route::post("online/payment/fail", [App\Http\Controllers\Frontend\OrderController::class,'onlinePaymentFail']);

Route::post("online/payment/cancel", [App\Http\Controllers\Frontend\OrderController::class,'onlinePaymentCancel']);

Route::get("/online/payment/ipn", [App\Http\Controllers\Frontend\OrderController::class,'onlinePaymentCancel']);

Route::get("pagenotfound", [App\Http\Controllers\Frontend\HomeController::class,'index'])->name('notfound');

Route::get("error", [App\Http\Controllers\Frontend\HomeController::class,'index'])->name('error');


Route::get('/auth/google',  [App\Http\Controllers\Customer\AuthController::class,'redirectToGoogle']);
Route::get('/auth/google/callback',  [App\Http\Controllers\Customer\AuthController::class,'handleGoogleCallback']);


Route::get('/payroll/download/{id}', [App\Http\Controllers\ERP\HRM\PayrollsController::class, 'downloadPDF'])->name('payroll.download');