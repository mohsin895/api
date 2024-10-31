<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Shop;
use Auth;
use Carbon\Carbon;
use DB;
use Storage;


class StoreController extends Controller
{
    public function getInfo(Request $request)
    {
       $dataInfo=Shop::find($request->dataId);

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

    public function updateShop(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Shop::find($request->dataId);

            if(!empty($dataInfo)){

                    $password=substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0,6);

                  
                    $dataInfo=Shop::find($request->dataId);
                    $dataInfo->shop_name=$request->shop_name;
                    if(isset($request->shop_description) && !is_null($request->shop_description))
                    $dataInfo->shop_description=$request->shop_description;

                if(isset($request->trad_license_no) && !is_null($request->trad_license_no))
                    $dataInfo->trade_license_no=$request->trad_license_no;
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

                       $dataInfo->shop_logo=$path;
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

                          $dataInfo->shop_photo=$path;
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
  
                             $dataInfo->shop_banner=$path;
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

                          $dataInfo->trade_license=$path;
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
  
                             $dataInfo->seller_nid_frontend=$path;
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
     
                                $dataInfo->seller_nid_backend=$path;
                             }
                           
                     

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                     

                      
                         
                               

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
                                        'msg'=>'Shop Information Updated Successfully.',
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
        catch(\Exception $err){

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

    public function updateSocialInfo(Request $request)
  
    {
        DB::beginTransaction();
        try{

            $dataInfo=Shop::where('seller_id',Auth::guard('seller-api')->user()->id)->first();

            if(!empty($dataInfo)){

                
                           
                     
                $dataInfo->facebook=$request->facebook;
                $dataInfo->youtube=$request->youtube;
                $dataInfo->instagram=$request->instagram;
                $dataInfo->twitter=$request->twitter;
                $dataInfo->save();
                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

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
                                        'msg'=>'Social Information Updated Successfully.',
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
                                            'errMsg'=>'Failed To Update Social Infomation.',
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
        catch(\Exception $err){

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
  

    public function getShopInfo()
    {
       
        $dataInfo=Shop::with('shopUnionInfo','shopThanaInfo','shopDistrictInfo','warehouseUnionInfo','warehouseThanaInfo','warehouseDistrictInfo','returnUnionInfo','reeturnThanaInfo','returnDistrictInfo','sellerInfo')->where('seller_id',Auth::guard('seller-api')->user()->id)
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
            ];
            }
            return response()->json($responseData,200);
    }
}
