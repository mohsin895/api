<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Brand;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function updateBrand(Request $request)
    {
        DB::beginTransaction();
        try{
             
             $dataInfo=Brand::find($request->dataId);

             if(!empty($dataInfo)){
                if(isset($request->brandLogo) && !is_null($request->file('brandLogo')))
             {      
                
                 $image=$request->file('brandLogo');
                   
                     $imageName = $image->getClientOriginalName();
                        if (!Storage::disk('public')->exists('banners')) {
                            Storage::disk('public')->makeDirectory('banners');
                        }
                       
                       
                    Storage::disk('public')->put('brand/', $image);
                    
                    if(!is_null($imageName)){
                       
                        $path ='/storage/app/public/brand/'.$image->hashName();

                        $dataInfo->logo=$path;
                    }
                }
                if(isset($request->brandBanner) && !is_null($request->file('brandBanner')))
                {      
                   
                    $image=$request->file('brandBanner');
                      
                        $imageName = $image->getClientOriginalName();
                           if (!Storage::disk('public')->exists('banners')) {
                               Storage::disk('public')->makeDirectory('banners');
                           }
                          
                          
                       Storage::disk('public')->put('brand/', $image);
                       
                       if(!is_null($imageName)){
                          
                           $path ='/storage/app/public/brand/'.$image->hashName();
   
                           $dataInfo->banner=$path;
                       }
                   }
                $dataInfo->name=$request->name;

               
                $dataInfo->meta_key_word=$request->metakeyWord;

                $dataInfo->meta_title=$request->metaTitle;
    
                $dataInfo->meta_description=$request->metaDescription;

                $dataInfo->slug=strtolower(Str::slug($request->name));

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
                                'msg'=>'Successfully Update Brand.',
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
    public function getBrandInfo(Request $request)
    {
       $dataInfo=Brand::find($request->dataId);

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

    public function addBrand(Request $request)
    {
    	
        DB::beginTransaction();
        try{
            $title=$request->input('name');
            $exists =Brand::where('name',$title)->first();
            if($exists){

                $brandInfo = Brand::where('id',$exists->id)->first();
                $brandInfo->staff_id=1;

                //
                $brandInfo->meta_key_word=$request->metakeyWord;

                $brandInfo->meta_title=$request->metaTitle;
    
                $brandInfo->meta_description=$request->metaDescription;

                // $brandInfo->slug=Str::slug($request->name);

                $brandInfo->status=1;
              
                // $banner->status=$request->status;

                $brandInfo->created_at=Carbon::now();

                
            
                
                if($brandInfo->save())
                {
                    $dataId=$brandInfo->id;

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
                                'msg'=>'Brand Already Exists.',
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
                                'errMsg'=>'Failed To Save Brand.Please Try Again.',
                        ];
                }

            }else{
                $dataInfo=new Brand();

                if(isset($request->brandLogo) && !is_null($request->file('brandLogo')))
                {      
                   
                    $image=$request->file('brandLogo');
                      
                        $imageName = $image->getClientOriginalName();
                           if (!Storage::disk('public')->exists('brands')) {
                               Storage::disk('public')->makeDirectory('brands');
                           }
                          
                          
                       Storage::disk('public')->put('brands/', $image);
                       
                       if(!is_null($imageName)){
                          
                           $path ='/storage/app/public/brands/'.$image->hashName();
   
                           $dataInfo->logo=$path;
                       }
                   }

                   if(isset($request->brandBanner) && !is_null($request->file('brandBanner')))
                   {      
                      
                       $image=$request->file('brandBanner');
                         
                           $imageName = $image->getClientOriginalName();
                              if (!Storage::disk('public')->exists('brands')) {
                                  Storage::disk('public')->makeDirectory('brands');
                              }
                             
                             
                          Storage::disk('public')->put('brands/', $image);
                          
                          if(!is_null($imageName)){
                             
                              $path ='/storage/app/public/brands/'.$image->hashName();
      
                              $dataInfo->banner=$path;
                          }
                      }

                   $dataInfo->name=$request->name;

                   $dataInfo->staff_id=1;
                   $dataInfo->addedBy=1;
                   $dataInfo->meta_key_word=$request->metakeyWord;
   
                   $dataInfo->meta_title=$request->metaTitle;
       
                   $dataInfo->meta_description=$request->metaDescription;
   
                   $dataInfo->slug=strtolower(Str::slug($request->name));
   
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
                                'msg'=>'Successfully Save Brand.',
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
                                'errMsg'=>'Failed To Save Brand.Please Try Again.',
                        ];
                }

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


    public function checkUniqueName(Request $request){
        $title=$request->input('name');
        $exists =Brand::where('name',$title)->exists();
        if($exists){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>' Name already Exists.Please Write Unique Name',
        ];

       
        }
        return response()->json($responseData,200);
    }

    public function checkUniqueNameEdit(Request $request){
        $category=Brand::get();
        foreach($category as $cat){
            $existsCat=Brand::where('id',$request->dataId)->first();
            if($cat->id != $existsCat){
                $title=$request->input('categoryName');
                $exists =Brand::where('title',$title)->exists();
                if($exists){
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>' Name already Exists.Please Write Unique Name',
                ];
        
                return response()->json($responseData,200);
                }
              

            }
        }
        
  
    }
    public function getBrandList(Request $request)
    {
    	$query=Brand::orderBy('id','desc')->where('staff_id',1)->whereNull('deleted_at');
        
    

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%');
                        
            });
        }

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }

   
    public function getBrandListPC(Request $request)
    {
        $dataList=Brand::whereNull('deleted_at')->where('status',1)->orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function getActiveBrandList(Request $request)
    {
    	$dataList=Brand::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeBrandStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Brand::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='brands';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Brand Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Brand Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Brand Status.'
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
    public function deleteBrand(Request $request)
    {
    	$dataInfo= Brand::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Brand Delete Successfully.',
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
