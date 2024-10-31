<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Models\Shop;
use App\Models\VoucherDiscount;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WiseList;
use App\Models\CustomerCare;
use App\Models\GeneralSetting;
use App\Models\Following;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\EmailVerifyOtp;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{

	
    public function getOrderDetails(Request $request)
    {
    	 $orderInfo=Order::with(['addressInfo','addressInfo.unionInfo','addressInfo.thanaInfo','addressInfo.districtInfo','orderItems','orderItems.productInfo','orderItems.orderItemReviewInfo'])->where('id',$request->dataId)->first();
		$orderItem=OrderItem::where('order_id',$orderInfo->id)->where('customer_status',1)->get();
		$totalPrice=0;
		$deliveryCharge=0;
		$discount=0;
		foreach($orderItem as $item){
			$totalPrice +=$item->sell_price ;
			$deliveryCharge +=$item->delivery_charge;
			$discount +=$item->discount;
		}
		$totalAmount=$totalPrice + $deliveryCharge;
    	if(!empty($orderInfo)) {
    		$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'orderInfo'=>$orderInfo,
					'totalPrice'=>$totalPrice,
					'deliveryCharge'=>$deliveryCharge,
					'totalAmount'=>$totalAmount,
					'discount'=>$discount,
					
                ];
    	}
    	else{
    		$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'orderInfo'=>$orderInfo,
                ];
    	}

    	return response()->json($responseData,200); 
    }
    public function getSelectedAddressInfo(Request $request)
    {
    
        if(Auth::guard('customer-api')->check()){
               
                $dataInfo=CustomerAddress::with('unionInfo','thanaInfo','districtInfo')
                                            ->where('id',$request->dataId)
                                                ->first();

                if (!empty($dataInfo)) {

                   $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>null,
                            'errMsg'=>null,
                            'dataInfo'=>$dataInfo,
                        ];

                    return response()->json($responseData,200);
                }
                else{
                    
                     $responseData=[
                                'errMsgFlag'=>true,
                                'msgFlag'=>false,
                                'msg'=>null,
                                'errMsg'=>'Data Not Found.',  
                            ];
                        
                    return response()->json($responseData,200);
                }
            }
            else{

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Unauthorized Request.Please Sigin First.',
                ];

                return response()->json($responseData,200);
            }
     
    }

   public function getLastAddress(Request $request)
   {

            if(Auth::guard('customer-api')->check()){

                $lastAddress=CustomerAddress::where('customer_id',Auth::guard('customer-api')->user()->id)
                                                ->where('status',1)
                                                    ->orderBy('updated_at','DESC')
                                                        ->first();
                if(!empty($lastAddress)){

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'An Address Found.',
                        'errMsg'=>null,
                        'lastAddressId'=>$lastAddress->id,
                    ];

                    return response()->json($responseData,200); 
                }
                else{
                    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'No Address Found.',
                    
                    ];

                    return response()->json($responseData,200); 
                }

                
            }
            else{

               $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Not Signed Yet.',
                ];

                return response()->json($responseData,200); 
            }
        
   }
	
	public function updateAddress(Request $request)
	{
		$dataInfo=CustomerAddress::find($request->dataId);

		if (!empty($dataInfo)) {

			$dataInfo->name=$request->name;

			$dataInfo->phone=$request->phone;

			$dataInfo->address=$request->address;

			$dataInfo->district_id=$request->district;

			$dataInfo->thana_id=$request->thana;

			$dataInfo->union_id=$request->union;

			$dataInfo->updated_at=Carbon::now();

			// $dataInfo->status=1;

			if($dataInfo->save()){
				$responseData=[
		                    'errMsgFlag'=>false,
		                    'msgFlag'=>true,
		                    'msg'=>'Address Updated Successfully.',
		                    'errMsg'=>null,
		                ];
			}
			else{
				$responseData=[
		                    'errMsgFlag'=>true,
		                    'msgFlag'=>false,
		                    'msg'=>null,
		                    'errMsg'=>'Failed To Add Address.',
		                ];
			}

		}
		else{
			$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Requested Data Not Found.',
                ];
		}

		return response()->json($responseData);
	}
	public function getAddressInfo(Request $request)
	{
		$dataInfo=CustomerAddress::find($request->dataId);

		if (!empty($dataInfo)) {
			$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataInfo'=>$dataInfo,
                ];
		}
		else{
			$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Requested Data Not Found.',
                ];
		}
		return response()->json($responseData,200);
	}
	public function passwordChange(Request $request)
	{
		$dataInfo=Customer::find(Auth::guard('customer-api')->user()->id);

		if(!empty($dataInfo)) {

			if(Hash::check($request->oldPassword,$dataInfo->password)){

				if($request->newPassword==$request->conPassword){

					$dataInfo->password=Hash::make($request->newPassword);

					$dataInfo->updated_at=Carbon::now();

					if($dataInfo->save()){
						$responseData=[
		                    'errMsgFlag'=>false,
		                    'msgFlag'=>true,
		                    'msg'=>'Password Changed Successfully.',
		                    'errMsg'=>null,
		                ];
					}
					else{
						$responseData=[
		                    'errMsgFlag'=>true,
		                    'msgFlag'=>false,
		                    'msg'=>null,
		                    'errMsg'=>"Failed To Change Password.Please Try Again.",
		                ];
					}
				}
				else{
					$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>"Confirm Password Doesn't Match",
	                ];
				}
			}
			else{
				$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>"Old Password Doesn't Match.",
	                ];
			}
		}
		else{
			$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>'Requested User Information Not Found.',
	                ];
		}

		return response()->json($responseData,200);
	}
	public function addAddress(Request $request)
	{
		$dataInfo=new CustomerAddress();

		$dataInfo->customer_id=Auth::guard('customer-api')->user()->id;

		$dataInfo->name=$request->name;

		$dataInfo->phone=$request->phone;

		$dataInfo->address=$request->address;

		$dataInfo->district_id=$request->district;

		$dataInfo->thana_id=$request->thana;

		$dataInfo->union_id=$request->union;

		$dataInfo->created_at=Carbon::now();

		$dataInfo->status=1;

		if($dataInfo->save()){
			$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>'Address Added Successfully.',
	                    'errMsg'=>null,
	                ];
		}
		else{
			$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>'Failed To Add Address.',
	                ];
		}

		return response()->json($responseData,200);
	}

	public function logout(Request $request)
	{
		Auth::guard('customer')->logout();

		$responseData=[
				'errMsgFlag'=>false,
				'msgFlag'=>true,
				'errMsg'=>null,
				'msg'=>"Singout Successfully Done.",
			];

		return response()->json($responseData,200);
	}
	public function getOrderList(Request $request)
	{
		$orderList=Order::with('OrderhopInfo','OrderhopInfo.orderProduct','OrderhopInfo.orderProduct.orderStockInfo','OrderhopInfo.orderProduct.orderStockInfo.orderItemReviewInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo.sizeInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo.sizeVariantInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo.colorInfo','OrderhopInfo.orderProduct.productInfo','OrderhopInfo.sellerInfo.shopInfo','statusInfo')->whereNull('deleted_at')
						->where('customer_id',Auth::guard('customer-api')->user()->id)
							->orderBy('id','desc')
								->paginate(10);

		return response()->json($orderList,200);
	}

    public function getOrderListPaymentType(Request $request)
	{
		$orderList=Order::with('OrderhopInfo','OrderhopInfo.orderProduct','OrderhopInfo.orderProduct.orderStockInfo','OrderhopInfo.orderProduct.orderStockInfo.orderItemReviewInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo.sizeInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo.sizeVariantInfo','OrderhopInfo.orderProduct.orderStockInfo.stockInfo.colorInfo','OrderhopInfo.orderProduct.productInfo','OrderhopInfo.sellerInfo.shopInfo','statusInfo')->whereNull('deleted_at')
						->where('customer_id',Auth::guard('customer-api')->user()->id)
							->orderBy('id','desc')
								->where('payment_type', 0)->get();

		return response()->json($orderList,200);
	}
    public function addWishList(Request $request)
	{

		DB::beginTransaction();
        try{

			$dataInfo=WiseList::where('product_id',$request->productId)->where('customer_id',Auth::guard('customer-api')->user()->id)->first();

            if(empty($dataInfo)){


				$dataInfo = new WiseList();
				$dataInfo->product_id = $request->productId;
				$dataInfo->customer_id = Auth::guard('customer-api')->user()->id;
				
				$dataInfo->save();

                if($dataInfo->save())
                {
                  

                
                 
                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Add Product.',
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
                                'errMsg'=>'Failed To Save Product.Please Try Again.',
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
                            'errMsg'=>'Product already exists in wish list.',
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

	public function getWiseList(Request $request)
	{
		$wishList=WiseList::with('productInfo')
						->where('customer_id',Auth::guard('customer-api')->user()->id)
							->orderBy('id','desc')
								->paginate(50);

		return response()->json($wishList,200);
	}

	public function getWiseListCount(Request $request)
	{
		$wishListCount=WiseList::where('customer_id',Auth::guard('customer-api')->user()->id)
							->count('id');
	$data =[
		'wishListCount'=>$wishListCount,
		
	];


	return response()->json($data,200);
	}

	public function deleteWiseList(Request $request)
	{
		$dataInfo=WiseList::find($request->dataId);

							
         $dataInfo->delete();
		 $responseData=[
			'errMsgFlag'=>true,
			'msgFlag'=>true,
			'msg'=>'Wise List Delete Successfully.',
			'errMsg'=>null,
			
		];
		return response()->json($responseData,200);
	}
	public function getCustomerAddresses(Request $request)
	{
		$addresses=CustomerAddress::with('unionInfo','thanaInfo','districtInfo')->whereNull('deleted_at')
									->where('customer_id',Auth::guard('customer-api')->user()->id)
										->get();

		$responseData=[
			'errMsgFlag'=>false,
				'msgFlag'=>true,
				'errMsg'=>null,
				'msg'=>null,
				'addresses'=>$addresses
		];

		return response()->json($responseData,200);
	}
	public function getPurchaseHistory(Request $request)
	{
		$totalPurchase=Order::select(DB::raw('sum(price) as totalPurchase'))->where('customer_id',Auth::guard('customer-api')->user()->id)
								->where('is_delivered',1)
									->first();

		$last7Days=Order::select(DB::raw('sum(price) as totalPurchase'))->where('customer_id',Auth::guard('customer-api')->user()->id)
								->where('is_delivered',1)
									->whereDate('created_at','>=',Carbon::today()->sub('7 days'))
									->first();

		$last30Days=Order::select(DB::raw('sum(price) as totalPurchase'))->where('customer_id',Auth::guard('customer-api')->user()->id)
								->where('is_delivered',1)
									->whereDate('created_at','>=',Carbon::today()->sub('30 days'))
									->first();

		$responseData=[
				'errMsgFlag'=>false,
				'msgFlag'=>true,
				'errMsg'=>null,
				'msg'=>null,
				'purchaseHistory'=>[
					'totalPurchase'=>$totalPurchase['totalPurchase'],
					'last7Days'=>$last7Days['totalPurchase'],
					'last30Days'=>$last30Days['totalPurchase'],
				]
		];

		return response()->json($responseData,200);
	}
	public function getCustomerInfo()
	{
		$userInfo=Customer::find(Auth::guard('customer-api')->user()->id);

		// $response=[
		// 	'id'=>$userInfo->id,
		// 	'name'=>$userInfo->name,
		// 	'avatar'=>$userInfo->avatar,
		// 	'phone'=>$userInfo->phone,
		// 	'email'=>$userInfo->email,
		// ];

		$responseData=[
				'errMsgFlag'=>false,
				'msgFlag'=>true,
				'msg'=>null,
				'errMsg'=>null,
                'userInfo'=>$userInfo,
				'customerInfo'=>[
					'id'=>$userInfo->id,
					'name'=>$userInfo->name,
					'avatar'=>$userInfo->avatar,
					'phone'=>$userInfo->phone,
					'email'=>$userInfo->email,
					'dob'=>$userInfo->dob,
					'created_at'=>$userInfo->created_at,
					'last_login'=>$userInfo->last_login,
					'email_verify'=>$userInfo->email_verify,
                    'is_verify'=>$userInfo->is_verify,
				],
			];

		return response()->json($responseData,200);
	}

	public function updateCustomerInfo(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Customer::find(Auth::guard('customer-api')->user()->id);

            if(!empty($dataInfo)) {
                if(isset($request->avatar) && !is_null($request->file('avatar')))
                {      
                   
                    $image=$request->file('avatar');
                      
                        $imageName = $image->getClientOriginalName();
                           if (!Storage::disk('public')->exists('profile')) {
                               Storage::disk('public')->makeDirectory('profile');
                           }
                          
                          
                       Storage::disk('public')->put('profile/', $image);
                       
                       if(!is_null($imageName)){
                          
                           $path ='/storage/app/public/profile/'.$image->hashName();
   
                           $dataInfo->avatar=$path;
                       }
                   }
              
                $dataInfo->name=$request->name;
            


                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Profile Updated Successfully.',
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
                        'errMsg'=>'Failed To Update Profile.Please Try Again.',
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
                        'errMsg'=>'Requested Data No Found.',
                     ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

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


 

    public function verifyOtp(Request $request)
       {
    	
            // $otp = 1234;
			$otp=$request->otp1.$request->otp2.$request->otp3.$request->otp4;
	 		$customerInfo=Customer::where('otp',$otp)->first();
		
    		if(!empty($customerInfo))
    		{
    				
                Auth::guard('customer')->login($customerInfo);
			$token = $customerInfo->createToken(uniqid())->accessToken;
	

		return response()->json([
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'msg'=>'Login Successfully',
            'errMsg'=>null,
            'token'=>$token,
            'customerInfo'=>$this->getLoginUserInfo(),
		]);
	
    			
    		}
    		else
    		{
				return response()->json([
					        'errMsgFlag'=>true,
							'msgFlag'=>false,
							'msg'=>null,
							'errMsg'=>'You Enter Wrong Otp, Please use correct opt or Try again.',
					
				]);
    		}
    }

	public function cancelOrder(Request $request)
	{

		DB::beginTransaction();
        try{

			$dataInfo=OrderItem::where('id',$request->dataId)->first();

            if(!empty($dataInfo)){


				$dataInfo->customer_status = 0;
		
				
				$dataInfo->save();

                if($dataInfo->save())
                {
                  

                
                 
                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Cancel Order.',
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
                                'errMsg'=>'Failed To Save Product.Please Try Again.',
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
                            'errMsg'=>'Something went to wrong.',
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

	public function notVerifyInfo(Request $request)
	{
	

            $isCustomerExist=Customer::where('email',trim($request->email))
            ->orWhere('phone',trim($request->phone))->where('block',1)->first();
            

            if(empty($isCustomerExist)){

            
             return response()->json($isCustomerExist,200);

             }
             
	}

	public function message(Request $request)
	{
		DB::beginTransaction();
        try{

     
			$dataInfo=new CustomerCare();

			$dataInfo->name=$request->name;
			$dataInfo->email=$request->email;
			$dataInfo->message=$request->message;

			$dataInfo->status=2;

			$dataInfo->created_at=Carbon::now();

                   

                    if($dataInfo->save()){

                        
                        DB::commit();

                        

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Your Message Send Successfully.',
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
                                            'errMsg'=>'Failed To Send Message.',
                                        ];

                            return response()->json($responseData,200);
                    }

            
           
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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

    public function verifyCurrentPhone(Request $request)
    {
      
        DB::beginTransaction();
        try{

            $isCustomerExist=Customer::Where('phone',Auth::guard('customer-api')->user()->phone)->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isCustomerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isCustomerExist->where('phone',trim($request->phone));

            // $isCustomerExist=$isCustomerExist->first();


            if(($isCustomerExist)){

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    $isCustomerExist->phone=trim(Auth::guard('customer-api')->user()->phone);

                 
                    $isCustomerExist->remember_token=Hash::make($otp);

                 
                    $isCustomerExist->created_at=Carbon::now();

                   

                    if($isCustomerExist->save()){

                        
                        DB::commit();

                        $phone=GeneralController::phoneNumberPrefix(trim(Auth::guard('customer-api')->user()->phone));
               
						$message="Your OTP IS:".$otp." For Verify Phone In loyel.com.bd";

						try {
              
                            GeneralController::sendSMS($phone,$message);
							$response = 'success';
						} catch (\Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Please Verify Your Phone.',
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
                                            'errMsg'=>'Failed To Add Customer Infomation.',
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
                            'errMsg'=>'Customer Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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

    public function smsVerifyCurrentPhone(Request $request)
	{
		$customerInfo=Customer::Where('phone',Auth::guard('customer-api')->user()->phone)
										->first();

		if (!empty($customerInfo)) {
			if(Hash::check($request->smsCode,$customerInfo->remember_token)){
				
				$customerInfo->remember_token=null;
				
				$customerInfo->updated_at=Carbon::now();

				$customerInfo->is_verify=1;
				
				$customerInfo->status=1;

				if($customerInfo->save()){

					$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>'Your Phone is Verified Successfully.',
	                    'errMsg'=>null,
	                ];

				}
				else{
					$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>'Failed To Verify Otp.Please Try Again.',
	                ];
				}
			}
			else{
				$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Invalid Otp.Please Enter Valid Otp.',
                ];
			}
		}
		else{
			$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Customer Information Not Found.',
                ];
		}

		return response()->json($responseData,200);
	}

    public function updatePhone(Request $request)
    {
      
        DB::beginTransaction();
        try{

            $isCustomerExist=Customer::Where('phone',trim($request->newPhone))->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isCustomerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isCustomerExist->where('phone',trim($request->phone));

            // $isCustomerExist=$isCustomerExist->first();


            if(empty($isCustomerExist)){
                $updatePhone=Customer::Where('phone',Auth::guard('customer-api')->user()->phone)->first();

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    $updatePhone->phone=$request->newPhone;

                 
                    $updatePhone->remember_token=Hash::make($otp);

                 
                    $updatePhone->created_at=Carbon::now();

                   

                    if($updatePhone->save()){

                        
                        DB::commit();

                        $phone=GeneralController::phoneNumberPrefix(trim($request->newPhone));
               
						$message="Your OTP IS:".$otp." For Verify Phone In loyel.com.bd";

					 
						try {
              
							GeneralController::sendSMS($phone,$message);
							$response = 'success';
						} catch (\Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Please Verify Your Phone.',
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
                                            'errMsg'=>'Failed To Add Customer Infomation.',
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
                            'errMsg'=>'Customer Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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

    public function addNewEmail(Request $request)
    {
      
        DB::beginTransaction();
        try{

            $isCustomerExist=Customer::Where('email',trim($request->email))->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isCustomerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isCustomerExist->where('phone',trim($request->phone));

            // $isCustomerExist=$isCustomerExist->first();


            if(empty($isCustomerExist)){
                $addNewEmail=Customer::Where('phone',Auth::guard('customer-api')->user()->phone)->first();

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    $addNewEmail->email=trim($request->email);

                 
                    $addNewEmail->remember_token=Hash::make($otp);

                 
                    $addNewEmail->created_at=Carbon::now();

                   

                    if($addNewEmail->save()){

                        
                        DB::commit();
                       

                   

                        $toEmail    =   $request->email;
                        $data       =   array(
                            "title"=>"Welcome loyel.com.bd",
                            "message"    =>  "Your OTP IS:".$otp." For Verify Email In loyel.com.bd"
                        );
                
                        // pass dynamic message to mail class
                        Mail::to($toEmail)->send(new EmailVerifyOtp($data));
						try {
              
						
							$response = 'success';
						} catch (\Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Please Verify Your Email.',
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
                                            'errMsg'=>'Failed To Add New Email Infomation.',
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
                            'errMsg'=>'Email Already Exists.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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

    public function updateEmail(Request $request)
    {
      
        DB::beginTransaction();
        try{

            $isCustomerExist=Customer::Where('email',trim($request->newEmail))->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isCustomerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isCustomerExist->where('phone',trim($request->phone));

            // $isCustomerExist=$isCustomerExist->first();


            if(empty($isCustomerExist)){
                $updateEmail=Customer::Where('email',Auth::guard('customer-api')->user()->email)->first();

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    $updateEmail->email=$request->newEmail;

                 
                    $updateEmail->remember_token=Hash::make($otp);

                 
                    $updateEmail->created_at=Carbon::now();

                   

                    if($updateEmail->save()){

                        
                        DB::commit();

                   

                     
						try {
              
                            $toEmail   = $request->newEmail;
                            $data       =   array(
                                "title"=>"Loyel.com.bd",
                                "message"    =>  "Your OTP IS:".$otp." For Verify Update Email In loyel.com.bd"
                            );
                    
                            // pass dynamic message to mail class
                            Mail::to($toEmail)->send(new EmailVerifyOtp($data));
							$response = 'success';
						} catch (Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Please Verify Your Phone.',
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
                                            'errMsg'=>'Failed To Add Customer Infomation.',
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
                            'errMsg'=>'Customer Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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
    public function verifyEmail(Request $request)
	{
		$customerInfo=Customer::Where('email',Auth::guard('customer-api')->user()->email)
										->first();

		if (!empty($customerInfo)) {
			if(Hash::check($request->smsCode,$customerInfo->remember_token)){
				
				$customerInfo->remember_token=null;
				
				$customerInfo->updated_at=Carbon::now();

				$customerInfo->email_verify=1;
				
				$customerInfo->status=1;

				if($customerInfo->save()){

					$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>'Your Email is Verified Successfully.',
	                    'errMsg'=>null,
	                ];

				}
				else{
					$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>'Failed To Verify Otp.Please Try Again.',
	                ];
				}
			}
			else{
				$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Invalid Otp.Please Enter Valid Otp.',
                ];
			}
		}
		else{
			$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Customer Information Not Found.',
                ];
		}

		return response()->json($responseData,200);
	}
    
    public function sendVerifyEmail(Request $request)
    {
      
        DB::beginTransaction();
        try{

            $isCustomerExist=Customer::Where('email',trim(Auth::guard('customer-api')->user()->email))->first();
            
          


            if(($isCustomerExist)){
              

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                 
                    $isCustomerExist->remember_token=Hash::make($otp);

                 
                    $isCustomerExist->created_at=Carbon::now();

                   

                    if($isCustomerExist->save()){

                        
                        DB::commit();

                   

                        $toEmail    =   Auth::guard('customer-api')->user()->email;
                        $data       =   array(
                            "title"=>"Loyel.com.bd",
                            "message"    =>  "Your OTP IS:".$otp." For Verify Email In loyel.com.bd"
                        );
                
                        // pass dynamic message to mail class
                        Mail::to($toEmail)->queue(new EmailVerifyOtp($data));
						try {
              
						
							$response = 'success';
						} catch (\Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Please Verify Your Email.',
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
                                            'errMsg'=>'Failed To Add New Email Infomation.',
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
                            'errMsg'=>'Email Already Exists.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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
   


    public function followStore(Request $request)
    {
    	DB::beginTransaction();
        try{
            $store=Following::where('customer_id',$request->customerId)->where('shop_id',$request->shopId)->first();
            if(!empty($store)){
                $dataInfo=Following::where('customer_id',$request->customerId)->where('shop_id',$request->shopId)->first();
                $dataInfo->deleted_at=NULL;
                $dataInfo->created_at=Carbon::now();

            }else{

                $dataInfo=new Following();

                $dataInfo->customer_id=$request->customerId;
    
                $dataInfo->shop_id=$request->shopId;
    
                $dataInfo->created_at=Carbon::now();
            }

        

            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Follower Add Successfully.',
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
                    'errMsg'=>'Failed To Add Following.Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

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

    
    public function followingStore(Request $request)
    {
    	 $storeInfo=Following::with('shopInfo')
    	 					->where('customer_id',Auth::guard('customer-api')->user()->id)->whereNull('deleted_at')
    	 						->get();

                                 return response()->json($storeInfo,200);

    }


    public function unFollowingStore(Request $request)
    {
    	DB::beginTransaction();
        try{

            $dataInfo=Following::where('customer_id',Auth::guard('customer-api')->user()->id)->where('shop_id',$request->shopId)->first();

            $dataInfo->deleted_at=Carbon::now();

            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Unfollowing Successfully.',
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
                    'errMsg'=>'Failed To Unfollowing.Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

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


    public function getVoucherList(Request $request)
	{
		$wishList=VoucherDiscount::where('status',1)->orderBy('id','desc')
								->paginate(50);

		return response()->json($wishList,200);
	}


    public function followingInfo(Request $request)
    {
    	$storeInfo=Shop::where('slug','like','%'.$request->slug.'%')->first();
        if(!empty($storeInfo)){
            $followerInfo=Following::where('shop_id',$storeInfo->id)->where('customer_id',Auth::guard('customer-api')->user()->id)->whereNull('deleted_at')->first();
		}
       


		$data=[
			'errMsgFlag'=>false,
			'msgFlag'=>true,
			'msg'=>true,
			'errMsg'=>true,
            'storeInfo'=>$storeInfo,
            'followerInfo'=>$followerInfo,

		];

		return response()->json($data,200);
    }

    public function getVoucher(Request $request){
        $voucherList = VoucherDiscount::where('seller_id', $request->dataId)
        ->where('startAt', '<=', Carbon::now())
        ->where('endAt', '>=', Carbon::now())
        ->where('status',1)
        ->orderBy('id', 'desc')
        ->get();

		return response()->json($voucherList,200);
    }
}