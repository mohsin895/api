<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\GeneralSetting;
use App\Models\Shop;
use App\Models\Review;
use App\Models\SmsSetting;
use App\Models\Financial;
use App\Models\MobileBanking;
use App\Models\SellerMessage;
use App\Mail\EmailVerifyOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{

    
    public function getLoginSellerInfo()
    {
        
        $dataInfo=Seller::where('id',Auth::guard('seller-api')->user()->id)
        ->whereNull('deleted_at')
            ->first();
            if(!empty($dataInfo)) {
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
            'errMsg'=>null,
            'dataInfo'=>$dataInfo,
            ];
            }
            return response()->json($responseData,200);
    }

    public function getSellerInfoEdit(Request $request)
    {
       $dataInfo=Seller::find($request->dataId);

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];
       }

       return response()->json($responseData,200);
    }

    public function updateSellerInfo(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Seller::find($request->dataId);

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
              
                $dataInfo->f_name=$request->f_name;
                $dataInfo->l_name=$request->l_name;
                $dataInfo->address=$request->address;
                $dataInfo->city=$request->city;


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
        catch(\Exception $err){

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

  
    public function updateFinanacialInfo(Request $request)
    {
        DB::beginTransaction();
        try{
            $sellerInfo=Seller::with('shopInfo')
            ->where('id',Auth::guard('seller-api')->user()->id)
                 ->first();
            $dataInfo=Financial::where('seller_id',$request->dataId)->first();

            if(!empty($dataInfo)) {
                if(isset($request->check) && !is_null($request->file('check')))
                {      
                   
                    $image=$request->file('check');
                      
                        $imageName = $image->getClientOriginalName();
                           if (!Storage::disk('public')->exists('profile')) {
                               Storage::disk('public')->makeDirectory('profile');
                           }
                          
                          
                       Storage::disk('public')->put('profile/', $image);
                       
                       if(!is_null($imageName)){
                          
                           $path ='/storage/app/public/profile/'.$image->hashName();
   
                           $dataInfo->check=$path;
                       }
                   }
                   $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
                   $dataInfo->type_of_id=$request->typeOfId;
                   $dataInfo->tin_number=$request->tinNumber;
                   $dataInfo->id_number=$request->IdNumber;
                   $dataInfo->routing_number=$request->routingNumber;
                   $dataInfo->account_number=$request->bankAccountNumber;
                   $dataInfo->branch_name=$request->branchName;
                   $dataInfo->bank_account_name=$request->bankAccountName;
                   $dataInfo->bank_name=$request->bankName;
                   $dataInfo->swiftCode=$request->swiftCode;


                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Financial Info Updated Successfully.',
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
                        'errMsg'=>'Failed To Update Financial Info.Please Try Again.',
                     ];

                    return response()->json($responseData,200);
                }
            
            }
            else{
                
             $sellerInfo=Seller::with('shopInfo')
             ->where('id',Auth::guard('seller-api')->user()->id)
                  ->first();
                $dataInfo=new Financial();

                if(isset($request->check) && !is_null($request->file('check')))
                {      
                   
                    $image=$request->file('check');
                      
                        $imageName = $image->getClientOriginalName();
                           if (!Storage::disk('public')->exists('profile')) {
                               Storage::disk('public')->makeDirectory('profile');
                           }
                          
                          
                       Storage::disk('public')->put('profile/', $image);
                       
                       if(!is_null($imageName)){
                          
                           $path ='/storage/app/public/profile/'.$image->hashName();
   
                           $dataInfo->check=$path;
                       }
                   }
                 $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
                $dataInfo->type_of_id=$request->typeOfId;
                $dataInfo->tin_number=$request->tinNumber;
                $dataInfo->	id_number=$request->IdNumber;
                $dataInfo->routing_number=$request->routingNumber;
                $dataInfo->account_number=$request->bankAccountNumber;
                $dataInfo->branch_name=$request->branchName;
                $dataInfo->bank_account_name=$request->bankAccountName;
                $dataInfo->bank_name=$request->bankName;


                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();
                $dataInfo->save();
                DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Financial Info Added Successfully.',
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
            }
        }
        catch(\Exception $err){

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

    public function getFinanacialInfo(Request $request)
    {
       $dataInfo=Financial::where('seller_id',Auth::guard('seller-api')->user()->id)->first();

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];
       }

       return response()->json($responseData,200);
    }

    
    public function updateMobileBankingInfo(Request $request)
    {
        DB::beginTransaction();
        try{
            $sellerInfo=Seller::with('shopInfo')
            ->where('id',Auth::guard('seller-api')->user()->id)
                 ->first();
            $dataInfo=MobileBanking::where('seller_id',$request->dataId)->first();

            if(!empty($dataInfo)) {
               
                   $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
                   $dataInfo->account_name=$request->accountName;
                   $dataInfo->acount_number=$request->accountNumber;
                   $dataInfo->type=$request->typeAccount;


                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Financial Info Updated Successfully.',
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
                        'errMsg'=>'Failed To Update Financial Info.Please Try Again.',
                     ];

                    return response()->json($responseData,200);
                }
            
            }
            else{
                
             $sellerInfo=Seller::with('shopInfo')
             ->where('id',Auth::guard('seller-api')->user()->id)
                  ->first();
                $dataInfo=new MobileBanking();

                 $dataInfo->seller_id = isset($sellerInfo) ? $sellerInfo->id : null;
                $dataInfo->account_name=$request->accountName;
                $dataInfo->acount_number=$request->accountNumber;
                $dataInfo->	type=$request->typeAccount;
 


                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();
                $dataInfo->save();
                DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Financial Info Added Successfully.',
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
            }
        }
        catch(\Exception $err){

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

    public function getMobileBankingInfo(Request $request)
    {
       $dataInfo=MobileBanking::where('seller_id',Auth::guard('seller-api')->user()->id)->first();

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataInfo'=>$dataInfo
          ];
       }

       return response()->json($responseData,200);
    }
    public function updateShopInfo(Request $request)
    {
        try{
                $dataInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)
                                ->whereNull('deleted_at')
                                    ->first();
                                        
                if(!empty($dataInfo)) {
                   
                        if(isset($request->shop_name) && !is_null($request->shop_name))
                            $dataInfo->shop_name=$request->shop_name;

                        if(isset($request->shop_description) && !is_null($request->shop_description))
                            $dataInfo->shop_description=$request->shop_description;

                        if(isset($request->trad_license_no) && !is_null($request->trad_license_no))
                            $dataInfo->trade_license_no=$request->trad_license_no;

                        if(isset($request->email) && !is_null($request->email))
                            $dataInfo->email=$request->email;

                        if(isset($request->phone) && !is_null($request->phone))
                            $dataInfo->phone=$request->phone;

                        if(isset($request->facebook) && !is_null($request->facebook))
                            $dataInfo->facebook=$request->facebook;

                        if(isset($request->twitter) && !is_null($request->twitter))
                            $dataInfo->twitter=$request->twitter;

                        if(isset($request->youtube) && !is_null($request->youtube))
                            $dataInfo->youtube=$request->youtube;

                        if(isset($request->instagram) && !is_null($request->instagram))
                            $dataInfo->instagram=$request->instagram;

                            if(isset($request->shopAddress) && !is_null($request->shopAddress))
                            $dataInfo->shopAddress=$request->shopAddress;
                            if(isset($request->returnAddress) && !is_null($request->returnAddress))
                            $dataInfo->returnAddress=$request->returnAddress;
                            if(isset($request->warehouseAddress) && !is_null($request->warehouseAddress))
                            $dataInfo->warehouseAddress=$request->warehouseAddress;
                            if(isset($request->shopDistrict) && !is_null($request->shopDistrict))
                            $dataInfo->shopDistrictId=$request->shopDistrict;
                            if(isset($request->returnDistrict) && !is_null($request->returnDistrict))
                            $dataInfo->returnDistrictId=$request->returnDistrict;
                            if(isset($request->warehouseDistrict) && !is_null($request->warehouseDistrict))
                            $dataInfo->warehouseDistrictId=$request->warehouseDistrict;
                            if(isset($request->shopThana) && !is_null($request->shopThana))
                            $dataInfo->shopUpazalaId=$request->shopThana;
                            if(isset($request->returnThana) && !is_null($request->returnThana))
                            $dataInfo->returnUpazalaId=$request->returnThana;
                            if(isset($request->warehouseThana) && !is_null($request->warehouseThana))
                            $dataInfo->warehouseUpazalaId=$request->warehouseThana;
                            if(isset($request->shopUnion) && !is_null($request->shopUnion))
                            $dataInfo->shopUnionId=$request->shopUnion;
                            if(isset($request->returnUnion) && !is_null($request->returnUnion))
                            $dataInfo->returnUnionId=$request->returnUnion;
                            if(isset($request->warehouseUnion) && !is_null($request->warehouseUnion))
                            $dataInfo->warehouseUnionId=$request->warehouseUnion;

                        if(isset($request->shop_logo) && !is_null($request->file('shop_logo')))
                        {

                            $image=$request->file('shop_logo');

                             $imageName =  uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('shop')) {
                                Storage::disk('public')->makeDirectory('shop');
                            }

                            $note_img = Image::make($image)->stream();

                            Storage::disk('public')->put('shop/' . $imageName, $note_img);

                            $path = '/storage/app/public/shop/'.$imageName;

                            $dataInfo->shop_logo=$path;
                        }

                        if(isset($request->shop_photo) && !is_null($request->file('shop_photo')))
                        {

                            $image=$request->file('shop_photo');

                             $imageName =  uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('shop')) {
                                Storage::disk('public')->makeDirectory('shop');
                            }

                            $note_img = Image::make($image)->stream();

                            Storage::disk('public')->put('shop/' . $imageName, $note_img);

                            $path = '/storage/app/public/shop/'.$imageName;

                            $dataInfo->shop_photo=$path;
                        }

                        if(isset($request->shop_banner) && !is_null($request->file('shop_banner')))
                        {

                            $image=$request->file('shop_banner');

                             $imageName =  uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('shop')) {
                                Storage::disk('public')->makeDirectory('shop');
                            }

                            $note_img = Image::make($image)->stream();

                            Storage::disk('public')->put('shop/' . $imageName, $note_img);

                            $path = '/storage/app/public/shop/'.$imageName;

                            $dataInfo->shop_banner=$path;
                        }

                        if(isset($request->trade_license) && !is_null($request->file('trade_license')))
                        {

                            $image=$request->file('trade_license');

                             $imageName =  uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('shop')) {
                                Storage::disk('public')->makeDirectory('shop');
                            }

                            $note_img = Image::make($image)->stream();

                            Storage::disk('public')->put('shop/' . $imageName, $note_img);

                            $path = '/storage/app/public/shop/'.$imageName;

                            $dataInfo->trade_license=$path;
                        }

                    if($dataInfo->save()){

                        $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Shop Information Updated Successfully.',
                            'errMsg'=>null,
                         ];
                    }
                    else{
                        $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Update Information.Please Try Again.',
                         ];
                    }

                }
                else{
                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Requested Data Not Found.Please Try Again.',
                         ];
                }
                return response()->json($responseData,200);
        }
        catch(\Exception $err){
            
            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Something Went Wrong.Please Try Again.',
                 ];

            return response()->json($responseData,200);
        }
    }

    public function passwordChange(Request $request)
	{
		$dataInfo=Seller::find(Auth::guard('seller-api')->user()->id);

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

    public function forgetPassword(Request $request)
    {
        if(is_numeric($request->userName))
        $userData=['phone'=>$request->userName,'password'=>$request->password];
    else
        $userData=['email'=>$request->userName,'password'=>$request->password];
     $sellerInfo=Seller::where('email',trim($request->userName))
                        ->orWhere('phone',strtolower(trim($request->userName)))
                            ->first();
     if(!empty($sellerInfo)){
        if(is_numeric($request->userName)){
            $otp=substr(str_shuffle('0123456789'), 0,6);

                  $sellerInfo->password=Hash::make($otp);
                   $sellerInfo->save();

                   $message="Your Password IS:".$otp." For Login In loyel.com.bd";
                   $phone=GeneralController::phoneNumberPrefix(trim($request->userName));
				
						try {
              
                            GeneralController::sendSMS($phone,$message);
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

            $sellerInfo->password=Hash::make($otp);
            $sellerInfo->save();
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


    public function getSellerInfo()
    {
        $userInfo=Seller::find(Auth::guard('seller-api')->user()->id);

        // $response=[
        //  'id'=>$userInfo->id,
        //  'name'=>$userInfo->name,
        //  'avatar'=>$userInfo->avatar,
        //  'phone'=>$userInfo->phone,
        //  'email'=>$userInfo->email,
        // ];

        $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'msg'=>null,
                'errMsg'=>null,
                'sellerInfo'=>[
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
                ],
            ];

        return response()->json($responseData,200);
    }

    public function sellerMessage(Request $request)
    {

        DB::beginTransaction();
        try{
          
            $shop = Shop::where('id',$request->shopId)->first();
			$seller=Seller::where('id',$shop->seller_id)->first();
                   
                $dataInfo=new SellerMessage();

               

                $dataInfo->email=$request->email;
              

                $dataInfo->shop_id=$request->shopId;
				$dataInfo->seller_id=$seller->id;

                $dataInfo->message=$request->message;


                $dataInfo->status=0;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    

                   

                        DB::commit();

                        $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Successfully Send Message.',
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
                                'errMsg'=>'Failed To Send Message.Please Try Again.',
                        ];
                }
            
            

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
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                ];

             return response()->json($responseData,200);
        }
    }

	public function productRatingReview(Request $request)
    {

          
           $shop=Shop::where('slug','like','%'.$request->slug.'%')->first();
			$seller=Seller::where('id',$shop->seller_id)->first();
            
                   
             $sellerProductReview=Review::with(['productInfo','customerInfo','sellerInfo','images','stockInfo', 'stockInfo.colorInfo'=>function($q) use($request){
				$q->select('color','color_code','id');
			},
			'stockInfo.sizeInfo'=>function($q) use($request){
				$q->select('size','id');
			},
			 ])->where('shop_id',$shop->id)->orderBy('id','desc')->get();
             $responseData=[
               
                'sellerProductReview'=>$sellerProductReview,
               
                
            ];


            return response()->json($responseData,200);
       
    }
   

    public function sendOtp(Request $request)
    {
      
        DB::beginTransaction();
        try{

     

            $isSellerExist=Seller::where('email',trim($request->email))
            ->orWhere('phone',trim($request->phone))->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isSellerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isSellerExist->where('phone',trim($request->phone));

            // $isSellerExist=$isSellerExist->first();


            if(($isSellerExist)){

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    // $isSellerExist->phone=trim($request->phone);

                    // $isSellerExist->email=trim($request->email);
                    $isSellerExist->remember_token=Hash::make($otp);

                 
                    $isSellerExist->created_at=Carbon::now();

                   

                    if($isSellerExist->save()){
                        if(!empty($request->phone)){
                            DB::commit();

                            $phone=GeneralController::phoneNumberPrefix(trim($request->phone));
                   
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

                        }else{
                            DB::commit();

                   

                            $toEmail    =   $request->email;
                            $data       =   array(
                                "title"=>"Loyel.com.bd",
                                "message"    =>  "Your OTP IS:".$otp." For Verify Email In loyel.com.bd"
                            );
                    
                            // pass dynamic message to mail class
                            Mail::to($toEmail)->send(new EmailVerifyOtp($data));
                           
    
                            $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Please Verify Your Email.',
                                    'errMsg'=>null,
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
        catch(\Exception $err){

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

    public function verifyOtp(Request $request)
	{
		$seller=Seller::find(Auth::guard('seller-api')->user()->id);

		if (!empty($seller)) {
			if(Hash::check($request->otp,$seller->remember_token)){
				
				$seller->remember_token=null;
				
				$seller->updated_at=Carbon::now();

				if($seller->save()){

					$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>' Verified Successfully.',
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

            $isSellerExist=Seller::where('email',trim($request->newEmail))
            ->orWhere('phone',trim($request->newPhone))->first();
            
          

            // if(isset($request->email) && !is_null($request->email))
            //     $isSellerExist->where('email',trim($request->email));

			// 	if(isset($request->phone) && !is_null($request->phone))
            //     $isSellerExist->where('phone',trim($request->phone));

            // $isSellerExist=$isSellerExist->first();


            if(empty($isSellerExist)){
                if(!empty($request->newPhone)){
                    $updatePhone=Seller::Where('phone',$request->phone)->first();

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    $updatePhone->phone=$request->newPhone;
                    $updatePhone->phone_verify =0;
                 
                    $updatePhone->remember_token=Hash::make($otp);

                 
                    $updatePhone->created_at=Carbon::now();
                    $updatePhone->save();
                   

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
                                'msg'=>'Please Verify Your new Mobile Number.',
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
                                            'errMsg'=>'Failed To Add Seller Phone.',
                                        ];

                            return response()->json($responseData,200);
                    }
                    
                }else{
                    $updateEmail=Seller::Where('email',$request->email)->first();

                    $otp=substr(str_shuffle('0123456789'), 0,6);

                
                    $updateEmail->email=$request->newEmail;
                    $updateEmail->email_verify =0;
                 
                    $updateEmail->remember_token=Hash::make($otp);

                 
                    $updateEmail->created_at=Carbon::now();
                    $updateEmail->save();
                   

                    if($updateEmail->save()){


                        DB::commit();

                   

                        $toEmail    =   $request->newEmail;
                        $data       =   array(
                            "title"=>"Loyel.com.bd",
                            "message"    =>  "Your OTP IS:".$otp." For Verify Email In loyel.com.bd"
                        );
                
                        // pass dynamic message to mail class
                        Mail::to($toEmail)->send(new EmailVerifyOtp($data));
                       

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
                                            'errMsg'=>'Failed To Add Seller Email.',
                                        ];

                            return response()->json($responseData,200);
                    }
                }
              

            }
            else{
                    DB::rollBack();

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Seller Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(\Exception $err){

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

    public function verifyOtpPhone(Request $request)
	{
		$seller=Seller::find(Auth::guard('seller-api')->user()->id);

		if (!empty($seller)) {
			if(Hash::check($request->otp,$seller->remember_token)){
				
				$seller->remember_token=null;
                $seller->phone_verify=1;
				$seller->updated_at=Carbon::now();

				if($seller->save()){

					$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>' Verified Successfully.',
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
    public function verifyOtpEmail(Request $request)
	{
		$seller=Seller::find(Auth::guard('seller-api')->user()->id);

		if (!empty($seller)) {
			if(Hash::check($request->otp,$seller->remember_token)){
				
				$seller->remember_token=null;
				$seller->email_verify=1;
				$seller->updated_at=Carbon::now();

				if($seller->save()){

					$responseData=[
	                    'errMsgFlag'=>false,
	                    'msgFlag'=>true,
	                    'msg'=>' Verified Successfully.',
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

}
