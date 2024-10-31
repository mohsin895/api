<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PremiumPackge;
use Carbon\Carbon;
use Exception;
use DB;
use Storage;

class PremiumPackgeController extends Controller
{
    
    public function updatePackge(Request $request)
    {
        DB::beginTransaction();
        try{
             
             $dataInfo=PremiumPackge::find($request->dataId);

             if(!empty($dataInfo)){
                if(isset($request->packgeLogo) && !is_null($request->file('packgeLogo')))
             {      
                
                 $image=$request->file('packgeLogo');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('packges')) {
                            Storage::disk('public')->makeDirectory('packges');
                        }
                       
                       
                    Storage::disk('public')->put('packge/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/packge/'.$image->hashName();

                        $dataInfo->logo=$path;
                    }
                }

                $dataInfo->name=$request->name;

               
                $dataInfo->price=$request->price;

      

                $dataInfo->slug=Str::slug($request->name);

                // $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->updated_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='brands';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Brand Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Update Premium Packge.',
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
                                'errMsg'=>'Failed To Update Brand.Please Try Again.',
                        ];
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
             }

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\BrandController@updateBrand");
            
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
    public function getPackgeInfo(Request $request)
    {
       $dataInfo=PremiumPackge::find($request->dataId);

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

    public function addPackge(Request $request)
    {
    	
        DB::beginTransaction();
        try{
             $dataInfo=new PremiumPackge();

             if(isset($request->packgeLogo) && !is_null($request->file('packgeLogo')))
             {      
                
                 $image=$request->file('packgeLogo');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('packges')) {
                            Storage::disk('public')->makeDirectory('packges');
                        }
                       
                       
                    Storage::disk('public')->put('packge/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/packge/'.$image->hashName();

                        $dataInfo->logo=$path;
                    }
                }

                $dataInfo->name=$request->name;

                //
                $dataInfo->price=$request->price;



                $dataInfo->slug=Str::slug($request->name);;

                $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='brands';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Brand Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Save Premium Packge.',
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
                                'errMsg'=>'Failed To Save Premium Packge.Please Try Again.',
                        ];
                }
            

            return response()->json($responseData,200);
         }
         catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\BrandController@addBrand");
            
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
    public function getPackgeList(Request $request)
    {
    	$query=PremiumPackge::orderBy('id','desc')->whereNull('deleted_at');
        
    

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%');
                        
            });
        }

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getPackgeListPC(Request $request)
    {
        $dataList=PremiumPackge::whereNull('deleted_at')->where('status',1)->orderBy('id','asc')->get();

        return response()->json($dataList,200);
    }
    public function getActivePackgeList(Request $request)
    {
    	$dataList=PremiumPackge::where('status',1)->orderBy('id','asc')->get();

        return response()->json($dataList,200);
    }
    public function changePackgeStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=PremiumPackge::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='packges';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.'Premium Packge Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Premium Packge Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Premium Packge Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\BrandController@changeBrandStatus",$err);
            
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
    public function deletePackge(Request $request)
    {
    	$dataInfo= PremiumPackge::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Premium Packge Delete Successfully.',
            ];

        }else{

            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Somethigwrong, please try again',

            ];

        }

        return response()->json($responseData,200);
    }
}
