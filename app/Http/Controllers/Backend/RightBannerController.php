<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\RightBanner;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use Storage;
use DB;

class RightBannerController extends Controller
{
    
    public function updateRightSlider(Request $request)
    {
        
        DB::beginTransaction();
        try{

            $dataInfo=RightBanner::find($request->dataId);

            if(!empty($dataInfo)){

                $dataInfo->title=$request->title;

                $dataInfo->slug=Str::slug($request->title, '-');

          

                $dataInfo->updated_at=Carbon::now();


                if(isset($request->slider) && !is_null($request->file('slider')))
                 {      
            
                     $image=$request->file('slider');
                       
                         $imageName = $image->getClientOriginalName();
                            if (!Storage::disk('public')->exists('rightSlider')) {
                                Storage::disk('public')->makeDirectory('rightSlider');
                            }
                           
                           
                            $imageResize = Image::make($image)
                            ->resize(60, 60, function ($constraint) {
                                $constraint->aspectRatio(); 
                                $constraint->upsize();      
                            })
                            ->stream();
                        Storage::disk('public')->put('rightSlider/', $image, $imageResize);
                        
                        if(!is_null($imageName)){
                           
                            $path ='/storage/app/public/rightSlider/'.$image->hashName();

                          

                            $dataInfo->image=$path;
                        }
                    }
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='sliders';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Slider Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Update Slider.',
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
                                'errMsg'=>'Failed To Save Slider.Please Try Again.',
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
            
            GeneralController::storeSystemErrorLog($err,"Backend\RightBannerController@updateRightSlider");
            
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
    public function getRightSliderInfo(Request $request)
    {
       $dataInfo=RightBanner::find($request->dataId);

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

    public function addRightSlider(Request $request)
    {

        DB::beginTransaction();
        try{
             if(isset($request->slider) && !is_null($request->file('slider')))
             {      
                 $image=$request->file('slider');

             $imageName = str_replace(' ', '-', $request->title). "-" . Carbon::now()->format('d-m-Y') . "." . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('rightSlider')) {
                Storage::disk('public')->makeDirectory('rightSlider');
            }

          
            $imageResize = Image::make($image)
            ->resize(60, 60, function ($constraint) {
                $constraint->aspectRatio(); 
                $constraint->upsize();      
            })
            ->stream();
        Storage::disk('public')->put('rightSlider/', $image, $imageResize);

            $path ='/storage/app/public/rightSlider/'.$imageName;

                   
                $dataInfo=new RightBanner();

             

                $dataInfo->image=$path;

                $dataInfo->title=$request->title;
                $dataInfo->slug=Str::slug($request->title, '-');

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='sliders';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Slider Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Successfully Save Slider.',
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
                                'errMsg'=>'Failed To Update Save Slider.Please Try Again.',
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
                            'errMsg'=>'Please Choose A Slider Image First.',
                        ];

            }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backend\RightBannerController@addRightSlider");
            
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
    public function getRightSliderList(Request $request)
    {
    	$query=RightBanner::orderBy('id','desc');
        
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

    public function getRightSliderListPC() {
        $dataList = RightBanner::where('status', 1)->get();
        return response()->json($dataList, 200);
    }

    public function getActiveRightSliderList(Request $request)
    {
    	$dataList=RightBanner::where('status',1)->orderBy('id','desc')->get();

        return response()->json($dataList,200);
    }
    public function changeRightSliderStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=RightBanner::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='Sliders';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Slider Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Slider Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Slider Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Slider Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\RightBannerController@changeRightSliderStatus",$err);
            
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
    public function deleteRightSlider(Request $request)
    {
    	$dataInfo = RightBanner::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Slider Delete Successfully .',

            ];

        }else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To delete Banner, Try Again.'
            ];

        }
        return response()->json($responseData,200);
    }
}
