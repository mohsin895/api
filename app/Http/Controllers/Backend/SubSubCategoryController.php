<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Models\Product;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class SubSubCategoryController extends Controller
{
    public function checkUniqueName(Request $request)
    {
        $title = $request->input('categoryName');
        $categoryId = $request->input('categoryId');
        $exists = Category::where('parent_id', $categoryId)->where('title', $title)->count('id');
        if ($exists > 0) {
            $responseData = [
                'errMsgFlag' => true,
                'msgFlag' => false,
                'msg' => null,
                'errMsg' => ' Name already Exists.Please Write Unique Name',
            ];
            return response()->json($responseData, 200);

        } else {

           

        }
        
    }

    public function checkUniqueNameEdit(Request $request)
    {
        $category = Category::where('look_type', 2)->get();
        foreach ($category as $cat) {
            $existsCat = Category::where('id', $request->dataId)->first();
            if ($cat->id != $existsCat->id) {
                $title = $request->input('categoryName');
                $categoryId = $request->input('categoryId');
                $exists = Category::where('parent_id', $categoryId)->where('title', $title)->count('id');
                if ($exists > 0) {
                    $responseData = [
                        'errMsgFlag' => true,
                        'msgFlag' => false,
                        'msg' => null,
                        'errMsg' => ' Name already Exists.Please Write Unique Name',
                    ];

                    return response()->json($responseData, 200);
                }


            }
        }


    }
    public function getSubSubCategoryList(Request $request)
    {
        $query=Category::withCount('normalCategoryProductsCount')->with('parentInfo','subcatInfo')->where('look_type',3)->orderBy('id','desc')->whereNull('deleted_at');
                            // ->where('look_type',1);
        
        if(isset($request->categoryId) && !is_null($request->categoryId))
            $query->where('sub_cat_id',$request->categoryId);
            if(isset($request->subcategoryId) && !is_null($request->subcategoryId))
            $query->where('parent_id',$request->subcategoryId);

        if(isset($request->categoryNameSort) && !is_null($request->categoryNameSort))
            $query->where('title','like',$request->categoryNameSort.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }

    public function updateSubSubCategory(Request $request)
    {
            DB::beginTransaction();

       try{
        $catId = Category::where('id',$request->category)->first();
            $dataInfo=Category::find($request->dataId);

            if(!empty($dataInfo)) {
              
              $dataInfo->title=$request->categoryName;

                $dataInfo->commission=$request->commission;

                $dataInfo->look_type=3;
                $dataInfo->parent_id = $request->subcategory;
                $dataInfo->sub_cat_id = $request->category;
                $dataInfo->catId = $catId->id;
                if(!empty($request->subcategory) && intval($request->subcategory)) 
                $dataInfo->parent_id = $request->subcategory;
             
                $dataInfo->catId = $catId->id;
                // $dataInfo->parent_id=(isset($request->parentCategory) && is_null($request->parentCategory)) ? $request->parentCategory:null;

                 $dataInfo->commission=$request->commission;

                $dataInfo->meta_key=$request->metaDescription;

                $dataInfo->meta_title=$request->metaDescription;

                $dataInfo->meta_details=$request->metaDescription;

                $dataInfo->slug=Str::slug($request->categoryName);

             


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
    public function getSubSubCategoryInfo(Request $request)
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

    public function addSubSubCategory(Request $request)
    {
        
        DB::beginTransaction();

       try{
        $catId = Category::where('id',$request->category)->first();
            $dataInfo=new Category();

            $dataInfo->title=$request->categoryName;

            $dataInfo->commission=$request->commission;

            $dataInfo->look_type=3;


            $dataInfo->parent_id = $request->subcategory;
            $dataInfo->sub_cat_id = $request->category;
            $dataInfo->catId = $catId->id;

            $dataInfo->meta_key=$request->metaDescription;

            $dataInfo->meta_title=$request->metaDescription;

            $dataInfo->meta_details=$request->metaDescription;

            $dataInfo->slug=strtolower(Str::slug($request->categoryName));

            $dataInfo->status=1;


            $dataInfo->created_at=Carbon::now();

            // $dataInfo->updated_at=Carbon::now();

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
                        'msg'=>'Successfully Added Sub Subcategory.',
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
 
    public function getSubSubCategoryListPC(Request $request)
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
    public function getActiveCategoryList(Request $request)
    {
        $dataList=Category::where('status',1)->where('look_type',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }

    public function getActiveSubCategoryList(Request $request)
    {
        $query=Category::where('status',1)->where('look_type',2);

        if(isset($request->category) && $request->category!='')
        $query->where('parent_id',$request->category);

    $dataList=$query->orderBy('id','asc')->get();

    return response()->json($dataList,200);

    }

    public function getSortingCategoryList(Request $request)
    {
        $dataList=Category::where('status',1)->where('look_type',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }

    public function getSortingSubCategoryList(Request $request)
    {
        $query=Category::where('status',1)->where('look_type',2);

        if(isset($request->categoryId) && $request->categoryId!='')
        $query->where('parent_id',$request->categoryId);

    $dataList=$query->orderBy('id','asc')->get();

    return response()->json($dataList,200);

    }
    public function changeSubSubCategoryStatus(Request $request)
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
   
    public function deleteSubSubCategory($id)
    {
        
        $product = Product::where('sub_subcategory_id',$id)->count('id');

        if($product >0){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete Sub Subcategory.',
            ];
   
        return response()->json($responseData,200);

        }else{
            $deleteSubCategory = Category::find($id)->delete();
             $deleteSubCategory->delete();
      $responseData=[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'msg'=> 'Sub Subcategory Deleted Successfully.',
            'errMsg'=> null,
            'product'=>$product,
        ];
        return response()->json($responseData,200);
                
            }
               
        

        
    }
}