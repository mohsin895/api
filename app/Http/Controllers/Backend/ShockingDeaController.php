<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\ShockingDeal;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;
use Storage;
use DB;

class ShockingDeaController extends Controller
{
    public function updateShockingDeal(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=ShockingDeal::find($request->dataId);

            if(!empty($dataInfo)){


                // $dataInfo->status=1;
              
                 $dataInfo->title=$request->title;
                 $dataInfo->slug=Str::slug($request->title, '-');

                $dataInfo->updated_at=Carbon::now();


                if(isset($request->shockingDeal) && !is_null($request->file('shockingDeal')))
                 {      
            
                     $image=$request->file('shockingDeal');
                       
                         $imageName = $image->getClientOriginalName();
                            if (!Storage::disk('public')->exists('shockingDeals')) {
                                Storage::disk('public')->makeDirectory('shockingDeals');
                            }
                           
                           
                        Storage::disk('public')->put('shockingDeals/', $image);
                        
                        if(!is_null($imageName)){
                           
                            $path ='/storage/app/public/shockingDeals/'.$image->hashName();

                            $dataInfo->base_url=env('APP_URL');

                            $dataInfo->shockingDeal_url=$path;
                        }
                    }
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='shockingDeals';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='ShockingDeal Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save ShockingDeal.',
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
                                'errMsg'=>'Failed To Save ShockingDeal.Please Try Again.',
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
            
            GeneralController::storeSystemErrorLog($err,"Backends\ShockingDeaController@updateShockingDeal");
            
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
    public function getShockingDealInfo(Request $request)
    {
       $dataInfo=ShockingDeal::find($request->dataId);

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

    public function addShockingDeal(Request $request)
    {
    	DB::beginTransaction();
        try{
             if(isset($request->shockingDeal) && !is_null($request->file('shockingDeal')))
             {      
                 $dataInfo=new ShockingDeal();

                 $image=$request->file('shockingDeal');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('shockingDeals')) {
                            Storage::disk('public')->makeDirectory('shockingDeals');
                        }
                       
                       
                    Storage::disk('public')->put('shockingDeals/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/shockingDeals/'.$image->hashName();

                        $dataInfo->base_url=env('APP_URL');

                        $dataInfo->shockingDeal_url=$path;
                    }


                $dataInfo->status=1;
              
                $dataInfo->title=$request->title;
                $dataInfo->slug=Str::slug($request->title, '-');

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='shockingDeals';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='ShockingDeal Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save ShockingDeal.',
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
                                'errMsg'=>'Failed To Save ShockingDeal.Please Try Again.',
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
                            'errMsg'=>'Please Choose A ShockingDeal Image First.',
                        ];

            }

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\ShockingDeaController@addShockingDeal");
            
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
    public function getShockingDealList(Request $request)
    {
    	$query=ShockingDeal::whereNull('deleted_at');
        
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

    public function getShockingDealListPC(Request $request)
    {
    	$dataList=ShockingDeal::inRandomOrder()
    					->where('status',1)
    						->whereNull('deleted_at')
    							->get();

    	return response()->json($dataList,200);
    }
    public function getActiveShockingDealList(Request $request)
    {
    	$dataList=ShockingDeal::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeShockingDealStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=ShockingDeal::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='ShockingDeals';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' ShockingDeal Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'ShockingDeal Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change ShockingDeal Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\ShockingDeaController@changeShockingDealStatus",$err);
            
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
    public function deleteShockingDeal(Request $request)
    {
        $deleteShockingDeal = ShockingDeal::find($request->dataId);
        $deleteShockingDeal->delete();
        if($deleteShockingDeal){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'ShockingDeal ShockingDeal Successfully.',
                
        ];
        }else{
            
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete ShockingDeal.Please Try Again.',
           ];
        }
        return response()->json($responseData,200);

    	
    }
}
