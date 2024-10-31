<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Banner;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function updateBanner(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Banner::find($request->dataId);

            if(!empty($dataInfo)){

                $dataInfo->title=$request->title;

                $dataInfo->description=$request->description;

                $dataInfo->slug=Str::slug($request->title);;

                $dataInfo->category_id=$request->category;

                // $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->updated_at=Carbon::now();


                if(isset($request->banner) && !is_null($request->file('banner')))
                 {      
            
                     $image=$request->file('banner');
                       
                         $imageName = $image->getClientOriginalName();
                            if (!Storage::disk('public')->exists('banners')) {
                                Storage::disk('public')->makeDirectory('banners');
                            }
                           
                           
                        Storage::disk('public')->put('banners/', $image);
                        
                        if(!is_null($imageName)){
                           
                            $path ='/storage/app/public/banners/'.$image->hashName();

                            $dataInfo->base_url=env('APP_URL');

                            $dataInfo->banner_url=$path;
                        }
                    }
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='banners';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Banner Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save Banner.',
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
                                'errMsg'=>'Failed To Save Banner.Please Try Again.',
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
                            'errMsg'=>'Requested Data Not Found.',
                        ];

            }

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\BannerController@updateBanner");
            
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
    public function getBannerInfo(Request $request)
    {
       $dataInfo=Banner::find($request->dataId);

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

    public function addBanner(Request $request)
    {
    	DB::beginTransaction();
        try{
             if(isset($request->banner) && !is_null($request->file('banner')))
             {      
                 $dataInfo=new Banner();

                 $image=$request->file('banner');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('banners')) {
                            Storage::disk('public')->makeDirectory('banners');
                        }
                       
                       
                    Storage::disk('public')->put('banners/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/banners/'.$image->hashName();

                        $dataInfo->base_url=env('APP_URL');

                        $dataInfo->banner_url=$path;
                    }

                $dataInfo->title=$request->title;

                $dataInfo->description=$request->description;

                $dataInfo->slug=Str::slug($request->title);

                $dataInfo->category_id=$request->category;

                $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='banners';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Banner Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save Banner.',
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
                                'errMsg'=>'Failed To Save Banner.Please Try Again.',
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
                            'errMsg'=>'Please Choose A Banner Image First.',
                        ];

            }

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\BannerController@addBanner");
            
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
    public function getBannerList(Request $request)
    {
    	$query=Banner::with('categoryInfo')->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->title) && !is_null($request->title)){
            $query->where(function($q) use($request){
                $q->where('title','like',$request->title.'%');
            });
        }

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getActiveBannerList(Request $request)
    {
    	$dataList=Banner::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeBannerStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Banner::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='banners';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Banner Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Banner Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\BannerController@changeBannerStatus",$err);
            
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
    public function deleteBanner(Request $request)
    {
        $deleteBanner = Banner::find($request->dataId);
        $deleteBanner->delete();
        if($deleteBanner){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Banner Delete Successfully.',
                
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
