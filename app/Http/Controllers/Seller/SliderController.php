<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\SellerSlider;
use App\Models\Shop;
use App\Models\Seller;
use Carbon\Carbon;
use Auth;
use Storage;
use DB;

class SliderController extends Controller
{
    public function updateSlider(Request $request)
    {
        
        DB::beginTransaction();
        try{

            $dataInfo=SellerSlider::find($request->dataId);

            if(!empty($dataInfo)){

                $dataInfo->title=$request->title;
                $dataInfo->seller_id=Auth::guard('seller-api')->user()->id;

                $dataInfo->description=$request->description;

                $dataInfo->target_url=$request->targetUrl;

              

                // $dataInfo->status=1;
              
                // $banner->status=$request->status;

                $dataInfo->updated_at=Carbon::now();


                if(isset($request->slider) && !is_null($request->file('slider')))
                 {      
            
                     $image=$request->file('slider');
                       
                         $imageName = $image->getClientOriginalName();
                            if (!Storage::disk('public')->exists('sliders')) {
                                Storage::disk('public')->makeDirectory('sliders');
                            }
                           
                           
                        Storage::disk('public')->put('sliders/', $image);
                        
                        if(!is_null($imageName)){
                           
                            $path ='/storage/app/public/sliders/'.$image->hashName();

             

                            $dataInfo->slider_url=$path;
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
         catch(\Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backend\SliderController@updateSlider");
            
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
    public function getSliderInfo(Request $request)
    {
       $dataInfo=SellerSlider::find($request->dataId);

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

    public function addSlider(Request $request)
    {

        DB::beginTransaction();
        try{
             if(isset($request->slider) && !is_null($request->file('slider')))
             {      
                 $image=$request->file('slider');

             $imageName = str_replace(' ', '-', $request->title). "-" . Carbon::now()->format('d-m-Y') . "." . $image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('slider')) {
                Storage::disk('public')->makeDirectory('slider');
            }

            $note_img = Image::make($image)->stream();

            Storage::disk('public')->put('slider/' . $imageName, $note_img);

            $path ='/storage/app/public/slider/'.$imageName;

                   
                $dataInfo=new SellerSlider();

                // $dataInfo->base_url=env('APP_URL');

                $dataInfo->slider_url=$path;
                $dataInfo->seller_id=Auth::guard('seller-api')->user()->id;

                $dataInfo->title=$request->title;

                $dataInfo->description=$request->description;

                $dataInfo->target_url=$request->targetUrl;

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
        catch(\Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backend\SliderController@addSlider");
            
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
    public function getSliderList(Request $request)
    {
    	$query=SellerSlider::where('seller_id',Auth::guard('seller-api')->user()->id)->whereNull('deleted_at')->orderBy('id','desc');
        
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
    public function getActiveSliderList(Request $request)
    {
    	$dataList=SellerSlider::where('seller_id',Auth::guard('seller-api')->user()->id)->where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeSliderStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=SellerSlider::find($request->dataId);

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
        catch(\Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\SliderController@changeSliderStatus",$err);
            
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
    public function deleteSlider(Request $request)
    {
    	$dataInfo = SellerSlider::find($request->dataId);
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

    public function vandorWiseSlider(Request $request)
    {
    	$vandorInfo=Shop::where('slug','like','%'.$request->slug.'%')->first();
        $sellerId= Seller::where('id',$vandorInfo->seller_id)->first();

    	$dataList=SellerSlider::where('seller_id',$sellerId->id)->where('status',1)->get();
		// if(!empty($vandorInfo)){
            
		// 	$query->where(function($q) use($vandorInfo){
		// 		$q->where('seller_id',$vandorInfo->id);
					
		// 	});
		// }

		// $dataList=$query->get();


    	

		return response()->json($dataList,200);
    }
}
