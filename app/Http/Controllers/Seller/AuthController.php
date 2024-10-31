<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\Seller;
use App\Models\SellerBrand;
use App\Models\Shop;
use App\Models\SmsSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
       FacadesDB::beginTransaction();
        try{

            $isSellerExist=Seller::where('email',trim($request->email))
            ->orWhere('phone',trim($request->phone))->first();
            
            // if(isset($request->phone) && !is_null($request->phone))
            //     $isSellerExist->where('phone',trim($request->phone));

            // if(isset($request->email) && !is_null($request->email))
            //     $isSellerExist->where('email',trim($request->email));

            // $isSellerExist=$isSellerExist->first();


            if(empty($isSellerExist)){

                     $otp=substr(str_shuffle('0123456789'), 0,4);
                    // $otp=1234;

                    $dataInfo=new Seller();

                    $dataInfo->f_name=$request->name;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                    $dataInfo->password=Hash::make($request->password);

                    $dataInfo->remember_token=Hash::make($otp);

                    $dataInfo->is_verify=0;
                    $dataInfo->phone_verify=0;
                    $dataInfo->status=2;

                 
                  $dataInfo->created_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                       if($dataInfo->save()){

                        $shopInfo=new Shop();

                        $shopInfo->shop_name=$request->name;

                        $shopInfo->email=strtolower(trim($request->email));

                        $shopInfo->phone=$request->phone;

                        $shopInfo->seller_id=$dataInfo->id;
                        $randNum=rand(100000,9999999);
                        $randNum1=rand(100000000,9999999999);
                        $shopInfo->slug=Str::slug($request->name.'-'.$randNum1.'-'.$dataInfo->f_name.'-'.$randNum);

                        $shopInfo->created_at=Carbon::now();

                        $shopInfo->save();

                        $sellerbrand= new SellerBrand();
                        $sellerbrand->seller_id=$dataInfo->id;
                        $sellerbrand->brand_id=1;
                        $sellerbrand->relationType=2;
                        $sellerbrand->approved=1;
                        $sellerbrand->rejacted=0;
                    

                        $sellerbrand->save();

                        FacadesDB::commit();

                        $to=GeneralController::phoneNumberPrefix(trim($request->phone));
               
                        $message="Your OTP IS:".$otp." For Registration In loyel.com.bd";

                        $gs=SmsSetting::find(1);
						$api_key=$gs->nonMaskingApiKey;
						$client_id=$gs->nonMaskingClientId;
						$sender_id="8809617609942";
						try {
              
                            $message = urlencode($message);
                            $sender_id = urlencode($sender_id);
                            $url = "https://api.smsq.global/api/v2/SendSMS?ApiKey=$api_key&ClientId=$client_id&SenderId=$sender_id&Message=$message&MobileNumbers=$to&Is_Unicode=true";
                            // dd($url);
                            $ch = curl_init();
                            curl_setopt ($ch, CURLOPT_URL, $url);
                            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
                            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_NOBODY, false);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_exec($ch);
							$response = 'success';
						} catch (Exception $exception) {
							$response = 'error';
						}

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Your Infomation Stored Successfully.Please Verify Your Account',
                                        'errMsg'=>null,
                                    ];

                        return response()->json($responseData,200);

                    }
                    else{
                            FacadesDB::rollBack();

                            $responseData=[
                                            'errMsgFlag'=>true,
                                            'msgFlag'=>false,
                                            'msg'=>null,
                                            'errMsg'=>'Failed To Signup.Please Try Again.',
                                        ];

                            return response()->json($responseData,200);
                    }

            }
            else{
                    FacadesDB::rollBack();

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Seller Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            FacadesDB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Seller\AuthController@signup");
            
            FacadesDB::commit();

            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
            ];

            return response()->json($responseData,200);
        }
    }

    public function otpVerify(Request $request)
    {
        $sellerInfo=Seller::where('email',strtolower(trim($request->email)))
                                    ->orWhere('phone',$request->phone)
                                        ->first();

        if (!empty($sellerInfo)) {

            $otp=$request->otp1.$request->otp2.$request->otp3.$request->otp4;
            if(Hash::check($otp,$sellerInfo->remember_token)){
                
                $sellerInfo->remember_token=null;
                
                $sellerInfo->updated_at=Carbon::now();

                 $sellerInfo->phone_verify=1;
                
                $sellerInfo->status=1;

                if($sellerInfo->save()){

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
                    'errMsg'=>'seller Information Not Found.',
                ];
        }

        return response()->json($responseData,200);
    }

    public function login(Request $request)
    {
        if(is_numeric($request->userName))
        $userData=['phone'=>$request->userName,'password'=>$request->password];
    else
        $userData=['email'=>$request->userName,'password'=>$request->password];
     $sellerInfo=Seller::where('email',$request->userName)
                        ->orWhere('phone',strtolower(trim($request->userName)))
                            ->first();

        if(!empty($sellerInfo)) {

            if($sellerInfo->status==1){
                if($sellerInfo->phone_verify ==1){
                    if (Hash::check(request()->password, $sellerInfo->password)) {
                
                        FacadesAuth::guard('seller')->login($sellerInfo);
    
                        $token = $sellerInfo->createToken(uniqid())->accessToken;
    
                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                
                                'errMsg'=>null,
                                'token'=>$token,
                                'sellerInfo'=>$this->getLoginUserInfo(),
                                'msg'=>"Login Successfully Done.",
                            ];
    
                        return response()->json($responseData,200);
                    }
                    else{
                        $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Wrong Password.Please Enter Right Password.',
                        ];
                    }

                }else{
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Your Mobile Number is not vrified.Please verify your Mobile Number.',
                    ];
                }

               
            }
            else{
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

    public function getLoginUserInfo()
    {
        
        $userInfo=Seller::find(FacadesAuth::guard('seller')->user()->id);

        $response=[
            'id'=>$userInfo->id,
            'f_name'=>$userInfo->f_name,
            'l_name'=>$userInfo->l_name,
            'address'=>$userInfo->address,
            'city'=>$userInfo->city,
            'country'=>$userInfo->country,
            'packge'=>$userInfo->packge,
            'avatar'=>$userInfo->avatar,
            'phone'=>$userInfo->phone,
            'email'=>$userInfo->email,
            'dob'=>$userInfo->dob,
            'is_verify'=>$userInfo->is_verify,
        ];
        return $response;
    }

    public function logout(Request $request)
    {
        FacadesAuth::guard('seller')->logout();

        $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>"Singout Successfully Done.",
            ];

        return response()->json($responseData,200);
    }

  

  

}