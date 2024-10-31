<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getCategoryList(Request $request)
    {
        $query=Category::withCount('subCategoryCount','parentInfoCount','categoryProductsCount')->where('look_type',1)->whereNull('deleted_at')->orderBy('serial','asc');
                            // ->where('look_type',1);
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->title) && !is_null($request->title))
            $query->where('title','like',$request->title.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }

    public function checkUniqueName(Request $request){
        $title=$request->input('categoryName');
        $exists =Category::where('title',$title)->exists();
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
        $category=Category::get();
        foreach($category as $cat){
            $existsCat=Category::where('id',$request->dataId)->first();
            if($cat->id != $existsCat){
                $title=$request->input('categoryName');
                $exists =Category::where('title',$title)->exists();
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

    public function addCategory(Request $request)
    {
        
        DB::beginTransaction();

       try{
      $cat = Category::where('look_type',1)->orderBy('serial','desc')->first();
            $dataInfo=new Category();

            $dataInfo->title=$request->categoryName;

            $dataInfo->commission=$request->commission;
   
            $dataInfo->serial=$cat->serial +1;
            $dataInfo->meta_key=$request->metakeyWord;

            $dataInfo->meta_title=$request->metaTitle;

            $dataInfo->meta_details=$request->metaDescription;

            $dataInfo->slug=Str::slug($request->categoryName);

            $dataInfo->status=1;

 

            if(isset($request->categoryBanner) && !is_null($request->file('categoryBanner')))
                 {
                   

                    $image= $request->file('categoryBanner');
                  

                     $imageName = str_replace(' ', '_', $request->title)."_". uniqid() . "." . $image->getClientOriginalExtension();

                    
                    if (!Storage::disk('public')->exists('category')) {
                        Storage::disk('public')->makeDirectory('category');
                    }

                    $note_img = Image::make($image)->stream();
                

                    Storage::disk('public')->put('category/' . $imageName, $note_img);
                

                    $path = '/storage/app/public/category/'.$imageName;
                 


                    $dataInfo->category_image=$path;
                  
                  
                 }

                 if(isset($request->categoryLogo) && !is_null($request->file('categoryLogo')))
                 {
                    

                 
                    $logo = $request->file('categoryLogo');

                   
                     $logoImageName = str_replace(' ', '_', $request->title)."_". uniqid() . "." . $logo->getClientOriginalExtension();

                    if (!Storage::disk('public')->exists('category')) {
                        Storage::disk('public')->makeDirectory('category');
                    }

                  
                    $logo_img = Image::make($logo)->stream();

           
                    Storage::disk('public')->put('category/' . $logoImageName, $logo_img);

                 
                    $logoPath = '/storage/app/public/category/'.$logoImageName;

                    $dataInfo->category_logo=$logoPath;
                   
                 }

            $dataInfo->created_at=Carbon::now();


            if($dataInfo->save()){
               

                $dataId=$dataInfo->id;

                $tableName='categories';

                $userId=1;

                $userType=1;

                $dataType=1;

                $comment='Category Added By ';
                // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                DB::commit();

                $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Successfully Added category.',
                        'errMsg'=>null,
                ];
            }
            else
            {
                 $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
                ];
            }

            return response()->json($responseData,200);
       }
       catch(Exception $err)
       {
            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backends\CategoryController@addCategory");

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

    public function getCategoryInfo(Request $request)
    {
       $dataInfo=Category::find($request->dataId);

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

    public function updateCategory(Request $request)
    {
         DB::beginTransaction();

       try{
            $dataInfo=Category::find($request->dataId);

            if(!empty($dataInfo)) {
              
              $dataInfo->title=$request->categoryName;

                $dataInfo->commission=$request->commission;

                $dataInfo->meta_key=$request->metakeyWord;

                $dataInfo->meta_title=$request->metaTitle;
    
                $dataInfo->meta_details=$request->metaDescription;

                $dataInfo->slug=Str::slug($request->categoryName);

                // $dataInfo->status=1;
                if(isset($request->categoryBanner) && !is_null($request->file('categoryBanner')))
                {
           

           

                   $image= $request->file('categoryBanner');

         

                    $imageName = str_replace(' ', '_', $request->title)."_". uniqid() . "." . $image->getClientOriginalExtension();

                   
                   if (!Storage::disk('public')->exists('category')) {
                       Storage::disk('public')->makeDirectory('category');
                   }

                   $note_img = Image::make($image)->stream();
               

                   Storage::disk('public')->put('category/' . $imageName, $note_img);
               

                   $path = '/storage/app/public/category/'.$imageName;
                


                   $dataInfo->category_image=$path;
                 
                 
                }

                if(isset($request->categoryLogo) && !is_null($request->file('categoryLogo')))
                {
                   

                
                   $logo = $request->file('categoryLogo');

                
                    $logoImageName = str_replace(' ', '_', $request->title)."_". uniqid() . "." . $logo->getClientOriginalExtension();

                   if (!Storage::disk('public')->exists('category')) {
                       Storage::disk('public')->makeDirectory('category');
                   }

                 
                   $logo_img = Image::make($logo)->stream();

          
                   Storage::disk('public')->put('category/' . $logoImageName, $logo_img);

                
                   $logoPath = '/storage/app/public/category/'.$logoImageName;

                   $dataInfo->category_logo=$logoPath;
                  
                }


                $dataInfo->updated_at=Carbon::now();

                // $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){
                    
                 

                    $dataId=$dataInfo->id;

                    $tableName='categories';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Category Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Successfully Updated Category.',
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
                            'errMsg'=>'Failed To Update Category.',
                    ];
                }
            }
            else{
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

            GeneralController::storeSystemErrorLog($err,"Backends\CategoryController@updateCategory");

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
  
   
 
    public function getCategoryListPC(Request $request)
    {
        $dataList=Category::select('id','title','parent_id','status','look_type')
                            ->with(['subCategory'=>function($q) use($request){
                                        $q->select('id','title','parent_id','status','look_type')->where('status',1);
                                    },
                                    'subCategory.subCategory'=>function($q) use($request){
                                        $q->select('id','title','parent_id','status','look_type')->where('status',1);
                                    }
                                ])->where('look_type',1)
                                        ->where('status',1)
                                            ->orderBy('title','asc')
                                                ->get();

        return response()->json($dataList,200);
    }
    // public function getActiveCategoryList(Request $request)
    // {
    //     $dataList=Category::where('status',1)->orderBy('title','asc')->get();

    //     return response()->json($dataList,200);
    // }
    public function changeCategoryStatus(Request $request)
    {
         DB::beginTransaction();

        try{
                $dataInfo=Category::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='categories';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Category Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Category Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CategoryController@changeCategoryStatus",$err);
            
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

    public function prirotyDecrement(Request $request)
    {
         DB::beginTransaction();

        try{
                $dataInfo=Category::find($request->dataId);

                $dataInfo->serial= $dataInfo->serial - 1;

                $dataInfo->save();

                if($dataInfo->save())
                {
                    $category = Category::where('look_type',1)->get();
                    foreach($category as $cat){
                     if($cat->serial == $dataInfo->serial){
                         if($cat->id != $dataInfo->id){
                             $cat->serial = $cat->serial +1;
                             $cat->save();
                             break;
                 
                         }
                        
                     }
                    }
                    $dataId=$dataInfo->id;

                    $tableName='categories';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Priroity Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Priroity  Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Priroity.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CategoryController@changeCategoryStatus",$err);
            
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
    public function prirotyIncrement(Request $request)
    {
         DB::beginTransaction();

        try{
              

                $dataInfo = Category::find($request->dataId);

             
                $dataInfo->serial += 1;
                
              
                $dataInfo->save();

                if($dataInfo->save())
                {
   $category = Category::where('look_type',1)->get();
   foreach($category as $cat){
    if($cat->serial == $dataInfo->serial){
        if($cat->id != $dataInfo->id){
            $cat->serial = $cat->serial -1;
            $cat->save();
            break;

        }
       
    }
   }
                 
                    $dataId=$dataInfo->id;

                    $tableName='categories';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Priroity Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Priroity Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Priroity.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CategoryController@changeCategoryStatus",$err);
            
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
    public function changeCategoryTop(Request $request)
    {
         DB::beginTransaction();

        try{
                $dataInfo=Category::find($request->dataId);

                $dataInfo->top=$request->top;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='categories';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Category Update Successfully.',
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
                            'errMsg'=>'Failed To Change Category Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CategoryController@changeCategoryStatus",$err);
            
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
    public function deleteCategory($id)
    {
        $deleteCategory = Category::find($id);
        $subcat = Category::where('parent_id',$id)->where('look_type',2)->count('id');
        if($subcat >0){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete Category.',
            ];
   
        return response()->json($responseData,200);

        }else{
            $filePathInStorage = $deleteCategory->category_image; 
             $extension=pathinfo($filePathInStorage,PATHINFO_EXTENSION);
  
             if (Storage::disk('public')->exists($extension)) {
                Storage::disk('public')->delete($extension);
               
                if (Storage::disk('public')->exists($extension)) {
                   
                } else {
                  
                }
            } else {
                
            }
               
          $deleteCategory->delete();
          if($deleteCategory) $responseData=[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'msg'=> 'Category Deleted Successfully.',
            'errMsg'=> null,
        ];
    else $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Failed To Delete Category.',
         ];
    return response()->json($responseData,200);

        }
    

             
            
          
       
        
    }
}