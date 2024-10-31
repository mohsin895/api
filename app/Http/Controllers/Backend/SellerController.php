<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Shop;
use Carbon\Carbon;
use Exception;
use DB;
use Hash;
use Storage;


class SellerController extends Controller
{
    
    public function updateSeller(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Seller::find($request->dataId);

            if(!empty($dataInfo)){

                    $password=substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0,6);

                  
                    $dataInfo=Seller::find($request->dataId);
                    $dataInfo->f_name=$request->fname;
                    $dataInfo->l_name=$request->lname;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);
                    $dataInfo->password=Hash::make($request->password);

                    // $dataInfo->password=Hash::make($password);

                    $dataInfo->social_id=null;

                    $dataInfo->dob=$request->dob;

                    // $dataInfo->is_verify=1;

                    // $dataInfo->status=1;
                   

                     if(isset($request->photo) && !is_null($request->file('photo')))
                         {
                            $image=$request->file('photo');

                             // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                             $imageName =str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('seller')) {
                                Storage::disk('public')->makeDirectory('seller');
                            }

                           // $note_img = Image::make($image)->resize(400, 400)->stream();

                          


                            Storage::disk('public')->put('seller/', $image);
                            if(!is_null($imageName)){
                            $path = '/storage/app/public/seller/'.$image->hashName();

                            $dataInfo->avatar=$path;
                            }
                         }
                   

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $shopInfo= Shop::where('seller_id',$request->dataId)->first();

                        $shopInfo->shop_name=$request->shop_name;
                        $shopInfo->phone=$request->shop_phone;
                        $shopInfo->email=$request->shop_email;
                        $shopInfo->address=$request->address;
                        $shopInfo->facebook=$request->facebook;
                        $shopInfo->youtube=$request->youtube;
                        $shopInfo->instagram=$request->instagram;
                        $shopInfo->twitter=$request->twitter;
                        $shopInfo->slug=Str::slug($request->shop_name.'-'.$request->shop_phone.'-'.$dataInfo->name.'-'.$dataInfo->phone, '-');
                        $shopInfo->trade_license_no=$request->trade_license_no;
                        $shopInfo->shop_description=$request->shop_description;
                        $shopInfo->seller_id=$dataInfo->id;

                        if(isset($request->shop_logo) && !is_null($request->file('shop_logo')))
                        {
                           $image=$request->file('shop_logo');

                            // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                           if (!Storage::disk('public')->exists('seller')) {
                               Storage::disk('public')->makeDirectory('seller');
                           }

                          // $note_img = Image::make($image)->resize(400, 400)->stream();

                           $note_img = Image::make($image)->stream();

                               Storage::disk('public')->makeDirectory('seller');
                           Storage::disk('public')->put('seller/' . $imageName, $note_img);

                           $path = '/storage/app/public/seller/'.$imageName;

                           $shopInfo->shop_logo=$path;
                        }
                      
                           if(isset($request->shop_photo) && !is_null($request->file('shop_photo')))
                           {
                              $image=$request->file('shop_photo');
   
                               // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                               $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                              if (!Storage::disk('public')->exists('seller')) {
                                  Storage::disk('public')->makeDirectory('seller');
                              }
   
                             // $note_img = Image::make($image)->resize(400, 400)->stream();
   
                              $note_img = Image::make($image)->stream();
   
                                  Storage::disk('public')->makeDirectory('seller');
                              Storage::disk('public')->put('seller/' . $imageName, $note_img);
   
                              $path = '/storage/app/public/seller/'.$imageName;
   
                              $shopInfo->shop_photo=$path;
                           }
                          


                              if(isset($request->shop_banner) && !is_null($request->file('shop_banner')))
                              {
                                 $image=$request->file('shop_banner');
      
                                  // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                  $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                 if (!Storage::disk('public')->exists('seller')) {
                                     Storage::disk('public')->makeDirectory('seller');
                                 }
      
                                // $note_img = Image::make($image)->resize(400, 400)->stream();
      
                                 $note_img = Image::make($image)->stream();
      
                                     Storage::disk('public')->makeDirectory('seller');
                                 Storage::disk('public')->put('seller/' . $imageName, $note_img);
      
                                 $path = '/storage/app/public/seller/'.$imageName;
      
                                 $shopInfo->shop_banner=$path;
                              }
                             
                           if(isset($request->trade_license) && !is_null($request->file('trade_license')))
                           {
                              $image=$request->file('trade_license');
   
                               // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                               $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                              if (!Storage::disk('public')->exists('seller')) {
                                  Storage::disk('public')->makeDirectory('seller');
                              }
   
                             // $note_img = Image::make($image)->resize(400, 400)->stream();
   
                              $note_img = Image::make($image)->stream();
   
                                  Storage::disk('public')->makeDirectory('seller');
                              Storage::disk('public')->put('seller/' . $imageName, $note_img);
   
                              $path = '/storage/app/public/seller/'.$imageName;
   
                              $shopInfo->trade_license=$path;
                           }
                          
                       
                              if(isset($request->seller_nid_frontend) && !is_null($request->file('seller_nid_frontend')))
                              {
                                 $image=$request->file('seller_nid_frontend');
      
                                  // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                  $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                 if (!Storage::disk('public')->exists('seller')) {
                                     Storage::disk('public')->makeDirectory('seller');
                                 }
      
                                // $note_img = Image::make($image)->resize(400, 400)->stream();
      
                                 $note_img = Image::make($image)->stream();
      
                                     Storage::disk('public')->makeDirectory('seller');
                                 Storage::disk('public')->put('seller/' . $imageName, $note_img);
      
                                 $path = '/storage/app/public/seller/'.$imageName;
      
                                 $shopInfo->seller_nid_frontend=$path;
                              }
                            

                                 if(isset($request->seller_nid_backend) && !is_null($request->file('seller_nid_backend')))
                                 {
                                    $image=$request->file('seller_nid_backend');
         
                                     // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
         
                                     $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
         
                                    if (!Storage::disk('public')->exists('seller')) {
                                        Storage::disk('public')->makeDirectory('seller');
                                    }
         
                                   // $note_img = Image::make($image)->resize(400, 400)->stream();
         
                                    $note_img = Image::make($image)->stream();
         
                                        Storage::disk('public')->makeDirectory('seller');
                                    Storage::disk('public')->put('seller/' . $imageName, $note_img);
         
                                    $path = '/storage/app/public/seller/'.$imageName;
         
                                    $shopInfo->seller_nid_backend=$path;
                                 }
                               
                          $shopInfo->save();

                        $dataId=$dataInfo->id;

                        $tableName='sellers';

                        $userId=1;

                        $userType=1;

                        $dataType=2;

                        $comment='Seller Updated By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Seller Information Updated Successfully.',
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
                                            'errMsg'=>'Failed To Update Seller Infomation.',
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
                            'errMsg'=>'Requested Data Not Found.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\SellerController@updateSeller");
            
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
    public function getSellerInfo(Request $request)
    {
       $dataInfo=Seller::with('shopInfo')->find($request->dataId);

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
    public function getSellerInfoDetails(Request $request)
    {
       $dataInfo=Seller::with('shopInfo','productInfo','reviewInfo','reviewInfo.customerInfo')->find($request->dataId);

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

    public function getSellerInfoDetailsProduct(Request $request)
    {
       $query=Product::withSum('orderItems', 'quantity')->withSum('stockItems', 'quantity')->with('shopInfo','brandInfo','megaCategory','subCategory','normalCategory', 'stockInfo', 'productImages','stockSingleInfo')->where('seller_id',$request->dataId);
       $query->orderBy('id','desc');
            
       $dataList=$query->paginate($request->numOfData);
       if(!empty($dataList)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataList'=>$dataList
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataList'=>$dataList
          ];
       }

       return response()->json($responseData,200);
    }
    public function addSeller(Request $request)
    {
    	DB::beginTransaction();
        try{

            $isSellerExist=Seller::where('email',trim($request->email))
            ->orWhere('phone',trim($request->phone))->first();
            if(empty($isSellerExist)){
                    $dataInfo=new Seller();

                    $dataInfo->f_name=$request->fname;
                    $dataInfo->l_name=$request->lname;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                    $dataInfo->password=Hash::make($request->password);

                    $dataInfo->social_id=null;

                    $dataInfo->dob=$request->dob;

                    $dataInfo->is_verify=1;

                    $dataInfo->status=1;

                     if(isset($request->photo) && !is_null($request->file('photo')))
                         {
                            $image=$request->file('photo');

                             // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                             $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('seller')) {
                                Storage::disk('public')->makeDirectory('seller');
                            }

                           // $note_img = Image::make($image)->resize(400, 400)->stream();

                            $note_img = Image::make($image)->stream();

                                Storage::disk('public')->makeDirectory('seller');
                            Storage::disk('public')->put('seller/' . $imageName, $note_img);

                            $path = '/storage/app/public/seller/'.$imageName;

                            $dataInfo->avatar=$path;
                         }
                        

                         $dataInfo->created_at=Carbon::now();
                         $dataInfo->save();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){
                        $shopInfo=new Shop();

                        $shopInfo->shop_name=$request->shop_name;
                        $shopInfo->phone=$request->shop_phone;
                        $shopInfo->email=$request->shop_email;
                        $shopInfo->address=$request->address;
                        $shopInfo->facebook=$request->facebook;
                        $shopInfo->youtube=$request->youtube;
                        $shopInfo->instagram=$request->instagram;
                        $shopInfo->twitter=$request->twitter;
                        $shopInfo->slug=Str::slug($request->shop_name.'-'.$request->shop_phone.'-'.$dataInfo->name.'-'.$dataInfo->phone, '-');
                        $shopInfo->trade_license_no=$request->trade_license_no;
                        $shopInfo->shop_description=$request->shop_description;
                        $shopInfo->seller_id=$dataInfo->id;

                        if(isset($request->shop_logo) && !is_null($request->file('shop_logo')))
                        {
                           $image=$request->file('shop_logo');

                            // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                           if (!Storage::disk('public')->exists('seller')) {
                               Storage::disk('public')->makeDirectory('seller');
                           }

                          // $note_img = Image::make($image)->resize(400, 400)->stream();

                           $note_img = Image::make($image)->stream();

                               Storage::disk('public')->makeDirectory('seller');
                           Storage::disk('public')->put('seller/' . $imageName, $note_img);

                           $path = '/storage/app/public/seller/'.$imageName;

                           $shopInfo->shop_logo=$path;
                        }
                      
                           if(isset($request->shop_photo) && !is_null($request->file('shop_photo')))
                           {
                              $image=$request->file('shop_photo');
   
                               // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                               $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                              if (!Storage::disk('public')->exists('seller')) {
                                  Storage::disk('public')->makeDirectory('seller');
                              }
   
                             // $note_img = Image::make($image)->resize(400, 400)->stream();
   
                              $note_img = Image::make($image)->stream();
   
                                  Storage::disk('public')->makeDirectory('seller');
                              Storage::disk('public')->put('seller/' . $imageName, $note_img);
   
                              $path = '/storage/app/public/seller/'.$imageName;
   
                              $shopInfo->shop_photo=$path;
                           }
                         


                              if(isset($request->shop_banner) && !is_null($request->file('shop_banner')))
                              {
                                 $image=$request->file('shop_banner');
      
                                  // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                  $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                 if (!Storage::disk('public')->exists('seller')) {
                                     Storage::disk('public')->makeDirectory('seller');
                                 }
      
                                // $note_img = Image::make($image)->resize(400, 400)->stream();
      
                                 $note_img = Image::make($image)->stream();
      
                                     Storage::disk('public')->makeDirectory('seller');
                                 Storage::disk('public')->put('seller/' . $imageName, $note_img);
      
                                 $path = '/storage/app/public/seller/'.$imageName;
      
                                 $shopInfo->shop_banner=$path;
                              }
                            
                           if(isset($request->trade_license) && !is_null($request->file('trade_license')))
                           {
                              $image=$request->file('trade_license');
   
                               // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                               $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
   
                              if (!Storage::disk('public')->exists('seller')) {
                                  Storage::disk('public')->makeDirectory('seller');
                              }
   
                             // $note_img = Image::make($image)->resize(400, 400)->stream();
   
                              $note_img = Image::make($image)->stream();
   
                                  Storage::disk('public')->makeDirectory('seller');
                              Storage::disk('public')->put('seller/' . $imageName, $note_img);
   
                              $path = '/storage/app/public/seller/'.$imageName;
   
                              $shopInfo->trade_license=$path;
                           }
                          
                       
                       
                              if(isset($request->seller_nid_frontend) && !is_null($request->file('seller_nid_frontend')))
                              {
                                 $image=$request->file('seller_nid_frontend');
      
                                  // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                  $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
      
                                 if (!Storage::disk('public')->exists('seller')) {
                                     Storage::disk('public')->makeDirectory('seller');
                                 }
      
                                // $note_img = Image::make($image)->resize(400, 400)->stream();
      
                                 $note_img = Image::make($image)->stream();
      
                                     Storage::disk('public')->makeDirectory('seller');
                                 Storage::disk('public')->put('seller/' . $imageName, $note_img);
      
                                 $path = '/storage/app/public/seller/'.$imageName;
      
                                 $shopInfo->seller_nid_frontend=$path;
                              }
                            

                                 if(isset($request->seller_nid_backend) && !is_null($request->file('seller_nid_backend')))
                                 {
                                    $image=$request->file('seller_nid_backend');
         
                                     // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();
         
                                     $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();
         
                                    if (!Storage::disk('public')->exists('seller')) {
                                        Storage::disk('public')->makeDirectory('seller');
                                    }
         
                                   // $note_img = Image::make($image)->resize(400, 400)->stream();
         
                                    $note_img = Image::make($image)->stream();
         
                                        Storage::disk('public')->makeDirectory('seller');
                                    Storage::disk('public')->put('seller/' . $imageName, $note_img);
         
                                    $path = '/storage/app/public/seller/'.$imageName;
         
                                    $shopInfo->seller_nid_backend=$path;
                                 }
                                 
                          $shopInfo->save();

                        $dataId=$dataInfo->id;

                        $tableName='sellers';

                        $userId=1;

                        $userType=1;

                        $dataType=1;

                        $comment='Seller Added By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Seller Information Added Successfully.',
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
                                            'errMsg'=>'Failed To Add Seller Infomation.',
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
                                'errMsg'=>'Seller Already Registered.',
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
    public function getSellerList(Request $request)
    {

        if($request->dataId==1){
          
            $query=Seller::with('shopInfo')->orderBy('id','desc');
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
                $query->where('id',$request->sellerId);
    
            if(isset($request->sellerName) && !is_null($request->sellerName))
                $query->where('f_name','like',$request->sellerName.'%');
    
            if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
                $query->where('phone',$request->sellerPhone);
           
                if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
                $query->where('email',$request->sellerEmail);
           
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==2){
            $query=Seller::with('shopInfo')->where(['status'=>1,'is_verify'=>0])->whereNull('deleted_at')->orderBy('id','desc');
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
            $query->where('id',$request->sellerId);

        if(isset($request->sellerName) && !is_null($request->sellerName))
            $query->where('f_name','like',$request->sellerName.'%');

        if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
            $query->where('phone',$request->sellerPhone);
       
            if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
            $query->where('email',$request->sellerEmail);
 
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==3){
            $query=Seller::with('shopInfo')->where(['is_verify'=>1])->whereNull('deleted_at')->orderBy('id','desc')->where('is_verify',1)->whereNull('deleted_at');
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
            $query->where('id',$request->sellerId);

        if(isset($request->sellerName) && !is_null($request->sellerName))
            $query->where('f_name','like',$request->sellerName.'%');

        if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
            $query->where('phone',$request->sellerPhone);
       
            if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
            $query->where('email',$request->sellerEmail);
 
               
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==4){
            $query=Seller::with('shopInfo')->where(['status'=>0])->whereNull('deleted_at')->orderBy('id','desc')->where('status',2);
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
            $query->where('id',$request->sellerId);

        if(isset($request->sellerName) && !is_null($request->sellerName))
            $query->where('f_name','like',$request->sellerName.'%');

        if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
            $query->where('phone',$request->sellerPhone);
       
            if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
            $query->where('email',$request->sellerEmail);
 
    
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==5){
            $query=Seller::with('shopInfo')->where(['block'=>1])->whereNull('deleted_at')->orderBy('id','desc');
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
            $query->where('id',$request->sellerId);

        if(isset($request->sellerName) && !is_null($request->sellerName))
            $query->where('f_name','like',$request->sellerName.'%');

        if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
            $query->where('phone',$request->sellerPhone);
       
            if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
            $query->where('email',$request->sellerEmail);
 
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==6){
            $query=Seller::with('shopInfo')->orderBy('id','desc')->where('deleted_at','!=',NULL);
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
            $query->where('id',$request->sellerId);

        if(isset($request->sellerName) && !is_null($request->sellerName))
            $query->where('f_name','like',$request->sellerName.'%');

        if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
            $query->where('phone',$request->sellerPhone);
       
            if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
            $query->where('email',$request->sellerEmail);
 
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }
        else{
          
            $query=Seller::with('shopInfo')->where(['status'=>1,'is_verify'=>0])->whereNull('deleted_at')->orderBy('id','desc');
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
                $query->where('id',$request->sellerId);
    
            if(isset($request->sellerName) && !is_null($request->sellerName))
                $query->where('f_name','like',$request->sellerName.'%');
    
            if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
                $query->where('phone',$request->sellerPhone);
           
                if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
                $query->where('email',$request->sellerEmail);
 
    
            
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }
    

        $newSellerTotal=Seller::where(['status'=>1,'is_verify'=>0])->whereNull('deleted_at')->count('id');
        $inactiveTotalSeller=Seller::where(['status'=>0])->whereNull('deleted_at')->count('id');
        $deleteTotalSeller=Seller::where('deleted_at','!=', NULL)->count('id');
        $blockedTotalSeller=Seller::where(['block'=>1])->whereNull('deleted_at')->count('id');
        $verifyTotalSeller=Seller::where(['is_verify'=>1])->whereNull('deleted_at')->count('id');
        $TotalSeller=Seller::count('id');
   
        $data=[
            'dataList'=>$dataList,
            'newSellerTotal'=>$newSellerTotal,
            'TotalSeller'=>$TotalSeller,
            'inactiveTotalSeller'=>$inactiveTotalSeller,
            'deleteTotalSeller'=>$deleteTotalSeller,
     
            'verifyTotalSeller'=>$verifyTotalSeller,
            'blockedTotalSeller'=>$blockedTotalSeller,
         
        ];

        return response()->json($data,200);


    	// $query=Seller::with('shopInfo')->whereNull('deleted_at')->orderBy('id','desc');
        
        // if(isset($request->status) && !is_null($request->status))
        //     $query->where('status',$request->status);

        // if(isset($request->name) && !is_null($request->name))
        //     $query->where('name','like',$request->name.'%');

        // if(isset($request->phone) && !is_null($request->phone))
        //     $query->where('phone','like',$request->phone.'%');

        // if(isset($request->email) && !is_null($request->email))
        //     $query->where('email','like',$request->email.'%');

        // $dataList=$query->paginate($request->numOfData);

        // return response()->json($dataList);
    }
    public function getActiveSellerList(Request $request)
    {
    	$dataList=Seller::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeSellerStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Seller::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='sellers';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->name.' Seller Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->name.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Seller Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Seller Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\SellerController@changeSellerStatus",$err);
            
            DB::commit();

            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.'
            ];

            return response()->json($responseData,200);
        }
    }
    public function verifySeller(Request $request)
    {
        DB::beginTransaction();

        try{
                $dataInfo=Seller::find($request->dataId);

                $dataInfo->is_verify=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $shop=Shop::where('seller_id',$dataInfo->id)->first();
                    $shop->is_verify=$dataInfo->is_verify;
                    $shop->save();
                    $dataId=$dataInfo->id;

                    $tableName='sellers';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->name.' Seller Verified By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->name.' Seller Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Seller Verified Successfully.',
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
                            'errMsg'=>'Failed To Verify Seller.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\SellerController@verifySeller",$err);
            
            DB::commit();

            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.'
            ];

            return response()->json($responseData,200);
        }
    }

    public function blockSeller(Request $request)
    {
        DB::beginTransaction();

        try{
                $dataInfo=Seller::find($request->dataId);

                $dataInfo->block=$request->block;
                if($request->block==1){
                    $dataInfo->status=0; 
                }else{
                    $dataInfo->status=1;
                }

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $shop=Shop::where('seller_id',$dataInfo->id)->first();
                    $shop->block=$dataInfo->block;
                    $shop->save();
                    $dataId=$dataInfo->id;

                    $tableName='sellers';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->name.' Seller Verified By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->name.' Seller Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Seller Blocked Successfully.',
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
                            'errMsg'=>'Failed To Blocked Seller.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\SellerController@verifySeller",$err);
            
            DB::commit();

            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.'
            ];

            return response()->json($responseData,200);
        }
    }
    public function deleteSeller(Request $request)
    {
        $deleteSeller = Seller::find($request->dataId);
        $shop = Shop::where('seller_id',$deleteSeller->id)->first();
        if(!empty($shop)){
            $shop->deleted_at=Carbon::now();
        }
       
        $deleteSeller->deleted_at=Carbon::now();
        $deleteSeller->status=0;
        $deleteSeller->save();
        if($deleteSeller){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Seller Delete Successfully.',
                
        ];
        }else{
            
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete Banner.Please Try Again.',
           ];
        }
        return response()->json($responseData,200);
    }
}
