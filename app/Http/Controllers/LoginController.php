<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Customer;
use App\Models\Seller;
use App\Models\SmsSetting;
use APp\Models\GeneralSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{

	
	
	public function sellerLogin(Request $request)
	{
		
    		if(is_numeric($request->userName))
    			$userData=['phone'=>$request->userName,'password'=>$request->password];
    		else
    			$userData=['email'=>$request->userName,'password'=>$request->password];

    		$userInfo=Seller::where('email',$request->userName)
    							->orWhere('phone',$request->userName)
    								->where('status','!=',0)
    								->where('is_verify',1)
    									->first();
    		if(!empty($userInfo))
    		{
    			if($userInfo->status==1)
    			{
    				
    				if(Hash::check($request->password,$userInfo->password))
    				{
    					if(Auth::guard('staff')->attempt($userData))
  						{
  							return redirect()->route('backend.dashboard');
  						}
    					else
    					{
    						Session::flash('errmsg','Cardentail Failed.Please Try Again.');

    						return redirect()->back()->withInput();
    					}
    				}
    				else
    				{
    					Session::flash('errmsg','Wrong Password.Please Enter Valid Password.');
    					return redirect()->back()->withInput();
    				}
    			}
    			else
    			{
    				Session::flash('errmsg','Your Account Has Been Temporary Deactived.');
    				return redirect()->back()->withInput();
    			}
    		}
    		else
    		{
    			Session::flash('errmsg','Inavald User Email Or Phone No.');
    			return redirect()->back()->withInput();
    		}
    	
	}

    public function staffLogin(Request $request)
    {
    	if(is_numeric($request->userName))
    			$userData=['phone'=>$request->userName,'password'=>$request->password];
    		else
    			$userData=['email'=>$request->userName,'password'=>$request->password];

    		$userInfo=Staff::where('email',$request->userName)
    							->orWhere('phone',$request->userName)
    								->where('status','!=',0)
    									->first();
    		if(!empty($userInfo))
    		{
    			if($userInfo->status==1)
    			{
    				
    				if(Hash::check($request->password,$userInfo->password))
    				{
    					if(Auth::guard('staff')->attempt($userData))
  						{
							// $token = $userInfo->createToken(uniqid())->accessToken;
						

  							// return response()->json([
							// 	'status' => true,
							// 	'token_type' => 'bearer',
							// 	'token' => $token,
							// 	'message' => 'Admin login successfully'
							// ]);

					 //$otp=substr(str_shuffle('0123456789'), 0,4);
			 $otp=1234;
			 $userInfo->otp =$otp;
			 $userInfo->save();

			 $message="Your Otp IS:".$otp." For Login In loyel.com.bd";
			 $phone=GeneralController::phoneNumberPrefix(trim($request->userName));
				
				  try {
		
					//GeneralController::sendSMS($phone,$message);
					  $response = 'success';
				  } catch (\Exception $exception) {
					  $response = 'error';
				  }

				  return response()->json([
								'status' => true,
							
								'message' => 'Admin login successfully'
							]);
  						}
    					else
    					{
    						return response()->json([
								'status' => false,
								'token' => '',
								'message' => 'Invalid credentials'
							]);
    					}
    				}
    				else
    				{
						return response()->json([
							'status' => false,
							'token' => '',
							'message' => 'Wrong Password.Please Enter Valid Password.'
						]);
    				}
    			}
    			else
    			{
					return response()->json([
						'status' => false,
						'token' => '',
						'message' => 'Your Account Has Been Temporary Deactived.'
					]);
    			}
    		}
    		else
    		{
				return response()->json([
					'status' => false,
					'token' => '',
					'message' => 'Inavald User Email Or Phone No.'
				]);
    		}
    }

	public function verifyOtp(Request $request)
       {
    	
            // $otp = 1234;
			$otp=$request->otp1.$request->otp2.$request->otp3.$request->otp4;
	 		$userInfo=Staff::where('otp',$otp)->first();
		
    		if(!empty($userInfo))
    		{
    				

			$token = $userInfo->createToken(uniqid())->accessToken;
	

		return response()->json([
			'status' => true,
			'token_type' => 'bearer',
			'token' => $token,
			'message' => 'Admin login successfully'
		]);
	
    			
    		}
    		else
    		{
				return response()->json([
					'status' => false,
					'token' => '',
					'message' => 'You Enter Wrong Otp, Please use correct opt or Try again.'
				]);
    		}
    }

	public function resetPassword(Request $request) {
		$userInfo = Staff::where('email',$request->userName)
							->orWhere('phone',$request->userName)
								->where('status','!=',0)
									->first();
		if(!empty($userInfo))
		{
			$is_updated = Staff::where('id', $userInfo->id)->update([
				'password' => Hash::make($request->password)
			]);

			if ($is_updated) {
				return response()->json([
					'status' => true,
					'message' => 'Your password successfully recovered'
				]);
			}
		}
		else
		{
			return response()->json([
				'status' => false,
				'token' => '',
				'message' => 'Inavald User Email Or Phone No.'
			]);
		}
	}
	public function staffLoginPassword(Request $request)
    {
        if(is_numeric($request->userName))
        $userData=['phone'=>$request->userName];
    else
        $userData=['email'=>$request->userName];
     $staff=Staff::where('email',trim($request->userName))
                        ->orWhere('phone',strtolower(trim($request->userName)))
                            ->first();
     if(!empty($staff)){
        if(is_numeric($request->userName)){
            // $otp=substr(str_shuffle('0123456789'), 0,6);
			   $otp=123456;

                  $staff->password=Hash::make($otp);
                   $staff->save();

                   $message="Your Password IS:".$otp." For Login In loyel.com.bd";
                   $phone=GeneralController::phoneNumberPrefix(trim($request->userName));
					
						try {
              
							//GeneralController::sendSMS($phone,$message);
							$response = 'success';
						} catch (\Exception $exception) {
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

            $staff->password=Hash::make($otp);
            $staff->save();
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
            } catch (\Exception $exception) {
                $response = 'error';
            }

            $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Your New Password Send Your Mobile Number.',
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


	public function logout()
{
    Auth::guard('staff')->logout();

    return response()->json(['message' => 'Logout successful'], 200);
}
}
