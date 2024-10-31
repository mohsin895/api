<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\TopBanner;
use Exception;
use Carbon\Carbon;
use Storage;
use DB;


class TopBannerController extends Controller
{
    public function updateTopBanner(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=TopBanner::find($request->dataId);

            if(!empty($dataInfo)){


                // $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->updated_at=Carbon::now();


                if(isset($request->topBanner) && !is_null($request->file('topBanner')))
                 {      
            
                     $image=$request->file('topBanner');
                       
                         $imageName = $image->getClientOriginalName();
                            if (!Storage::disk('public')->exists('topBanners')) {
                                Storage::disk('public')->makeDirectory('topBanners');
                            }
                           
                           
                        Storage::disk('public')->put('topBanners/', $image);
                        
                        if(!is_null($imageName)){
                           
                            $path ='/storage/app/public/topBanners/'.$image->hashName();

                            $dataInfo->base_url=env('APP_URL');

                            $dataInfo->top_banner_url=$path;
                        }
                    }
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='topBanners';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='TopBanner Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save TopBanner.',
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
                                'errMsg'=>'Failed To Save TopBanner.Please Try Again.',
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
            
            GeneralController::storeSystemErrorLog($err,"Backends\TopBannerController@updateTopBanner");
            
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
    public function getTopBannerInfo(Request $request)
    {
       $dataInfo=TopBanner::find($request->dataId);

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

    public function addTopBanner(Request $request)
    {
    	DB::beginTransaction();
        try{
             if(isset($request->topBanner) && !is_null($request->file('topBanner')))
             {      
                 $dataInfo=new topBanner();

                 $image=$request->file('topBanner');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('topBanners')) {
                            Storage::disk('public')->makeDirectory('topBanners');
                        }
                       
                       
                    Storage::disk('public')->put('topBanners/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/topBanners/'.$image->hashName();

                        $dataInfo->base_url=env('APP_URL');

                        $dataInfo->top_banner_url=$path;
                    }


                $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='topBanners';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='TopBanner Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save TopBanner.',
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
                                'errMsg'=>'Failed To Save TopBanner.Please Try Again.',
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
                            'errMsg'=>'Please Choose A TopBanner Image First.',
                        ];

            }

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\TopBannerController@addTopBanner");
            
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
    public function getTopBannerList(Request $request)
    {
    	$query=TopBanner::whereNull('deleted_at');
        
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
    public function getActiveTopBannerList(Request $request)
    {
    	$dataList=TopBanner::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeTopBannerStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
               $getAllBanner = TopBanner::where('id','!=',$request->dataId)->get();
                $dataInfo=TopBanner::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();
                foreach($getAllBanner as $banner){
                    $topBannerdata =TopBanner::find($banner->id);
                    $topBannerdata->status=2;
                    $topBannerdata->save();

                }

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='TopBanners';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' TopBanner Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'TopBanner Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change TopBanner Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\TopBannerController@changeTopBannerStatus",$err);
            
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
    public function deleteTopBanner(Request $request)
    {
        $deleteTopBanner = TopBanner::find($request->dataId);
        $deleteTopBanner->delete();
        if($deleteTopBanner){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'TopBanner TopBanner Successfully.',
                
        ];
        }else{
            
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete TopBanner.Please Try Again.',
           ];
        }
        return response()->json($responseData,200);

    	
    }
}
