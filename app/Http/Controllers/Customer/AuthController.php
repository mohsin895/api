<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Mail\ForgetPassword;
use App\Mail\EmailVerifyOtp;
use App\Models\Customer;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Intervention\Image\Facades\Image;

class AuthController extends Controller
{
    public function getLoginUserInfo()
	{
		
		$userInfo=Customer::find(Auth::guard('customer')->user()->id);

		$response=[
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
		];
		return $response;
	}
    public function login(Request $request)
    {

		if(is_numeric($request->userName))
    			$userData=['phone'=>$request->userName,'password'=>$request->password];
    		else
    			$userData=['email'=>$request->userName,'password'=>$request->password];
    	$customerInfo=Customer::where('email',$request->userName)
    							->orWhere('phone',strtolower(trim($request->userName)))
    								->first();

    	if(!empty($customerInfo)) {
			if($customerInfo->block==2){
				if($customerInfo->status==1){

					if (Hash::check(request()->password, $customerInfo->password)) {
					
						Auth::guard('customer')->login($customerInfo);
							//   $userInfosave=Customer::find($customerInfo->id);
								$customerInfo->last_login=Carbon::now();
								$customerInfo->save();
	
						$token = $customerInfo->createToken(uniqid())->accessToken;
	
						$responseData=[
								'errMsgFlag'=>false,
								'msgFlag'=>true,
								'msg'=>'Login Successfully',
								'errMsg'=>null,
								'token'=>$token,
								'customerInfo'=>$this->getLoginUserInfo(),
							];
	
						
					}
					else{
						$responseData=[
							'errMsgFlag'=>true,
							'msgFlag'=>false,
							'msg'=>null,
							'errMsg'=>'Wrong Password.Please Enter Right Password.',
						];
					}
				}
				else{
					$responseData=[
						'errMsgFlag'=>true,
						'msgFlag'=>false,
						'msg'=>null,
						'errMsg'=>'Your Account InActive.Please Contact With an Admin.',
					];
				}

			}else{
				$responseData=[
					'errMsgFlag'=>true,
					'msgFlag'=>false,
					'msg'=>null,
					'errMsg'=>'Your Account Temporary Blocked.Please Contact With an Admin.',
				];
				
			}

    		
    	}
    	else{
    		$responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Invalid Username.Please Try Again.',
            	];
    	}

    	return response()->json($responseData,200);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    
    
    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect('https://loyel.com.bd/')->with('error', 'Google login failed');
        }
    
        // Check if the user exists by email
        $existingUser = Customer::where('email', $user->email)->first();
    
        if ($existingUser) {
            // If the user already exists, log them in
            Auth::guard('customer')->login($existingUser);
            $otp= substr(str_shuffle('0123456789'), 0,4);
            $existingUser->otp = $otp;
            $existingUser->save();

            $toEmail    =   $existingUser->email;
            $data       =   array(
                "title"=>"Welcome loyel.com.bd",
                "message"    =>  "Your OTP IS:".$otp." For Verify Email In loyel.com.bd"
            );

            echo "<script>window.localStorage.setItem('userData', '" . json_encode($otp) . "');</script>";
    
            // pass dynamic message to mail class
            Mail::to($toEmail)->send(new EmailVerifyOtp($data));
            try {
  
            
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        } else {
            // If the user doesn't exist, create a new user
            $otp= substr(str_shuffle('0123456789'), 0,4);
            $newUser = new Customer();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->password = Hash::make($otp);
            $newUser->google_id = $user->id;
            // $newUser->is_verify = 1;
            $newUser->email_verify = 1;
            $newUser->otp = $otp;
            $newUser->save();
            echo "<script>window.localStorage.setItem('userData', '" . json_encode($otp) . "');</script>";
            $toEmail    =   $newUser->email;
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
            
    
        }
    
        return redirect('https://loyel.com.bd/otp'); // Redirect to a dashboard or profile page
    }

    public function otpVerify(Request $request)
	{
		$customerInfo=Customer::Where('phone',$request->phone)
										->first();
      

		if (!empty($customerInfo)) {
            $otp=$request->otp1.$request->otp2.$request->otp3.$request->otp4;
			if(Hash::check($otp,$customerInfo->remember_token)){
				
				$customerInfo->remember_token=null;
				
				$customerInfo->updated_at=Carbon::now();

				$customerInfo->is_verify=1;
				
				$customerInfo->status=1;

				if($customerInfo->save()){

					$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>'Your Profile is Verified Successfully.',
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
	public function signup(Request $request)
	{
		DB::beginTransaction();
        try{

            $isCustomerExist=Customer::Where('phone',trim($request->phone))->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isCustomerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isCustomerExist->where('phone',trim($request->phone));

            // $isCustomerExist=$isCustomerExist->first();


            if(empty($isCustomerExist)){
                $gs=GeneralSetting::first();
                if($gs->send_reg_otp_sms=='yes'){
                    $otp=substr(str_shuffle('0123456789'), 0,4);

                }else{
                    $otp=1234;
                }

                   

                    $dataInfo=new Customer();

                    $dataInfo->name=$request->name;

                    // $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                    $dataInfo->password=Hash::make($request->password);

                    $dataInfo->social_id=null;

                    $dataInfo->remember_token=Hash::make($otp);

                    $dataInfo->is_verify=0;

                    $dataInfo->status=2;

                     if(isset($request->photo) && !is_null($request->file('photo')))
                         {
                            $image=$request->file('photo');

                             // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                             $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('customer')) {
                                Storage::disk('public')->makeDirectory('customer');
                            }

                           // $note_img = Image::make($image)->resize(400, 400)->stream();

                            $note_img = Image::make($image)->stream();

                                Storage::disk('public')->makeDirectory('customer');
                            Storage::disk('public')->put('customer/' . $imageName, $note_img);

                            $path = '/storage/app/public/customer/'.$imageName;

                            $dataInfo->avatar=$path;
                         }
                         else
                            $dataInfo->avatar='/storage/app/public/customer/defaultUser.png';

                    $dataInfo->created_at=Carbon::now();

                   

                    if($dataInfo->save()){

                        
                        DB::commit();

                        $phone=GeneralController::phoneNumberPrefix(trim($request->phone));
               
						$message="Your OTP IS:".$otp." For Registration In loyel.com.bd";
                      
                        if($gs->send_reg_otp_sms=='yes'){
                            try {
              
                                GeneralController::sendSMS($phone,$message);
                                $response = 'success';
                            } catch (Exception $exception) {
                                $response = 'error';
                            }

                        }

						

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Your Information Recored Successfully.Please Verify Your Account.',
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
            $data  =   array(
                "title"=>"Welcome loyel.com.bd",
                "pass"    =>$pass,
                "website"=>$gs->website,
                "shop_address"=>$gs->shop_address,
                "office_email"=>$gs->office_email,
                "shop_phone"=>$gs->shop_phone,
                "shop_logo"=>$gs->shop_logo,
               
                'gs'=>$gs,
                'email' =>$email,
            );
    
          
              Mail::to($email)->queue(new ForgetPassword($data));
          

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
