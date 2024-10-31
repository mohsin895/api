<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\SmsSetting;
use App\Models\GeneralSetting;
use App\Mail\EmailVerifyOtp;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
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
               
						$message="Your OTP IS:".$otp." For Verify Current Mobile Number In loyel.com.bd";

					    
						try {
              
                            GeneralController::sendSMS($phone,$message);
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
               
						$message="Your OTP IS:".$otp." For Verify Update mobile Number In loyel.com.bd";

						try {
              
                            GeneralController::sendSMS($phone,$message);
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
						} catch (Exception $exception) {
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
                        Mail::to($toEmail)->send(new EmailVerifyOtp($data));
						try {
              
						
							$response = 'success';
						} catch (Exception $exception) {
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
    public function forgetPassword(Request $request)
    {
        if(is_numeric($request->userName))
        $userData=['phone'=>$request->userName,'password'=>$request->password];
    else
        $userData=['email'=>$request->userName,'password'=>$request->password];
     $customerInfo=Customer::where('email',trim($request->userName))
                        ->orWhere('phone',strtolower(trim($request->userName)))
                            ->first();
     if(!empty($customerInfo)){
        if(is_numeric($request->userName)){
            $otp=substr(str_shuffle('0123456789'), 0,6);

              $customerInfo->password=Hash::make($otp);
              $customerInfo->save();

                   $message="Your Password IS:".$otp." For Login In loyel.com.bd";
                   $phone=GeneralController::phoneNumberPrefix(trim($request->userName));
					
						try {
              
                            GeneralController::sendSMS($phone,$message);
							$response = 'success';
						} catch (Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Your New password.',
                            'errMsg'=>null,
                        ];

                    return response()->json($responseData,200);

        }else{

            $otp= substr(str_shuffle('0123456789'), 0,6);
            $pass="Your Password IS:".$otp." For Login In loyel.com.bd";

            $customerInfo->password=Hash::make($otp);
            $customerInfo->save();
            $gs=GeneralSetting::first();
            $subject=$gs->shop_name;
            $email    = trim($request->userName);
            $messageData       =   array(
                "title"=>"Welcome loyel.com.bd",
                "pass"    =>$pass,
                'gs'=>$gs,
                'email' =>$email,
            );
    
            // pass dynamic message to mail class
            Mail::send('email.forgetPassword',$messageData,function($message) use($email,$subject){
                $message->to($email)->subject('For New Password from' .' '.$subject);
              });
            try {
  
            
                $response = 'success';
            } catch (Exception $exception) {
                $response = 'error';
            }

            $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Your New Password.',
                    'errMsg'=>null,
                ];

            return response()->json($responseData,200);

        }

     }else{
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Customer Information Not Found.',
        ];

        return response()->json($responseData,200);

     }
    }
    
}
