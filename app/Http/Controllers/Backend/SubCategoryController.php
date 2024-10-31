<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Exception;
use DB;
use Storage;

class SubCategoryController extends Controller
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

    public function getSubCategoryList(Request $request)
    {
        $query = Category::withCount('subSubCategoryCount','subCategoryProductsCount')->with('parentInfo')->where('look_type', 2)->orderBy('id', 'desc')->whereNull('deleted_at');
        // ;

        if (isset($request->categoryId) && !is_null($request->categoryId))
            $query->where('parent_id', $request->categoryId);


        if (isset($request->categoryNameSort) && !is_null($request->categoryNameSort))
            $query->where('title', 'like', $request->categoryNameSort . '%');

        $dataList = $query->paginate($request->numOfData);

        return response()->json($dataList);
    }

    public function addSubCategory(Request $request)
    {

        DB::beginTransaction();

        try {
            $category = Category::where('id', $request->parentCategory)->first();
            $dataInfo = new Category();

            $dataInfo->title = $request->categoryName;

            $dataInfo->commission = $request->commission;


            $dataInfo->meta_key = $request->metakeyWord;

            $dataInfo->meta_title = $request->metaTitle;

            $dataInfo->meta_details = $request->metaDescription;

            $dataInfo->parent_id = $request->parentCategory;
            $dataInfo->look_type = 2;

            $dataInfo->slug = Str::slug($category->title . '-' . $request->categoryName);

            $dataInfo->status = 1;


            if (isset($request->categoryLogo) && !is_null($request->file('categoryLogo'))) {



                $logo = $request->file('categoryLogo');


                $logoImageName = str_replace(' ', '_', $request->title) . "_" . uniqid() . "." . $logo->getClientOriginalExtension();

                if (!Storage::disk('public')->exists('category')) {
                    Storage::disk('public')->makeDirectory('category');
                }


                $logo_img = Image::make($logo)->stream();


                Storage::disk('public')->put('category/' . $logoImageName, $logo_img);


                $logoPath = '/storage/app/public/category/' . $logoImageName;

                $dataInfo->category_logo = $logoPath;

            }

            $dataInfo->created_at = Carbon::now();

            // $dataInfo->updated_at=Carbon::now();

            if ($dataInfo->save()) {


                $dataId = $dataInfo->id;

                $tableName = 'categories';

                $userId = 1;

                $userType = 1;

                $dataType = 1;

                $comment = 'Category Added By ';
                // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                GeneralController::storeSystemLog($tableName, $dataId, $comment, $userId, $userType, $dataType);

                // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                DB::commit();

                $responseData = [
                    'errMsgFlag' => false,
                    'msgFlag' => true,
                    'msg' => 'Successfully Added Subcategory.',
                    'errMsg' => null,
                ];
            } else {
                $responseData = [
                    'errMsgFlag' => true,
                    'msgFlag' => false,
                    'msg' => null,
                    'errMsg' => 'Something Went Wrong.Please Try Again.',
                ];
            }

            return response()->json($responseData, 200);
        } catch (Exception $err) {
            DB::rollBack();

            GeneralController::storeSystemErrorLog($err, "Backends\SubCategoryController@addSubCategory");

            DB::commit();

            $responseData = [
                'errMsgFlag' => true,
                'msgFlag' => false,
                'msg' => null,
                'errMsg' => 'Something Went Wrong.Please Try Again.',
            ];

            return response()->json($responseData, 200);
        }
    }
    public function updateSubCategory(Request $request)
    {
        DB::beginTransaction();

        try {
            $dataInfo = Category::find($request->dataId);

            if (!empty($dataInfo)) {

                $dataInfo->title = $request->categoryName;

                $dataInfo->commission = $request->commission;


                $dataInfo->meta_key = $request->metakeyWord;

                $dataInfo->meta_title = $request->metaTitle;

                $dataInfo->meta_details = $request->metaDescription;

                $dataInfo->parent_id = $request->parentCategory;
                $dataInfo->look_type = 2;


                if (isset($request->categoryLogo) && !is_null($request->file('categoryLogo'))) {



                    $logo = $request->file('categoryLogo');


                    $logoImageName = str_replace(' ', '_', $request->title) . "_" . uniqid() . "." . $logo->getClientOriginalExtension();

                    if (!Storage::disk('public')->exists('category')) {
                        Storage::disk('public')->makeDirectory('category');
                    }


                    $logo_img = Image::make($logo)->stream();


                    Storage::disk('public')->put('category/' . $logoImageName, $logo_img);


                    $logoPath = '/storage/app/public/category/' . $logoImageName;

                    $dataInfo->category_logo = $logoPath;

                }


                $dataInfo->updated_at = Carbon::now();

                // $dataInfo->updated_at=Carbon::now();

                if ($dataInfo->save()) {



                    $dataId = $dataInfo->id;

                    $tableName = 'categories';

                    $userId = 1;

                    $userType = 1;

                    $dataType = 2;

                    $comment = 'SubCategory Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName, $dataId, $comment, $userId, $userType, $dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData = [
                        'errMsgFlag' => false,
                        'msgFlag' => true,
                        'msg' => 'Successfully Updated Subcategory.',
                        'errMsg' => null,
                    ];
                } else {
                    DB::rollBack();
                    $responseData = [
                        'errMsgFlag' => true,
                        'msgFlag' => false,
                        'msg' => null,
                        'errMsg' => 'Failed To Update Subcategory.',
                    ];
                }
            } else {
                $responseData = [
                    'errMsgFlag' => true,
                    'msgFlag' => false,
                    'msg' => null,
                    'errMsg' => 'Requested Data Not Found.',
                ];
            }

            return response()->json($responseData, 200);
        } catch (Exception $err) {
            DB::rollBack();

            GeneralController::storeSystemErrorLog($err, "Backends\SubCategoryController@updateSubCategory");

            DB::commit();

            $responseData = [
                'errMsgFlag' => true,
                'msgFlag' => false,
                'msg' => null,
                'errMsg' => 'Something Went Wrong.Please Try Again.',
            ];

            return response()->json($responseData, 200);
        }
    }
    public function getSubCategoryInfo(Request $request)
    {
        $dataInfo = Category::find($request->dataId);

        if (!empty($dataInfo)) {
            $responseData = [
                'errMsgFlag' => false,
                'msgFlag' => true,
                'errMsg' => null,
                'msg' => null,
                'dataInfo' => $dataInfo
            ];
        } else {
            $responseData = [
                'errMsgFlag' => true,
                'msgFlag' => false,
                'errMsg' => 'Requested Data Not Found.',
                'msg' => null,
                'dataInfo' => $dataInfo
            ];
        }

        return response()->json($responseData, 200);
    }



    public function getSubCategoryListPC(Request $request)
    {
        $dataList = Category::select('id', 'title', 'parent_id', 'status', 'look_type')
            ->with([
                'subCategory' => function ($q) use ($request) {
                    $q->select('id', 'title', 'parent_id', 'status', 'look_type')->where('status', 1);
                },
                'subCategory.subCategory' => function ($q) use ($request) {
                    $q->select('id', 'title', 'parent_id', 'status', 'look_type')->where('status', 1);
                }
            ])->where('look_type', 1)
            ->where('status', 1)
            ->orderBy('title', 'asc')
            ->get();

        return response()->json($dataList, 200);
    }
    // public function getActiveSubCategoryList(Request $request)
    // {
    //     $dataList=Category::where('status',1)->orderBy('title','asc')->get();

    //     return response()->json($dataList,200);
    // }

    public function getActiveCategoryList(Request $request)
    {
        $dataList = Category::where('status', 1)->where('look_type', 1)->orderBy('id', 'asc')->get();

        return response()->json($dataList, 200);
    }
    public function changeSubCategoryStatus(Request $request)
    {
        DB::beginTransaction();

        try {
            $dataInfo = Category::find($request->dataId);

            $dataInfo->status = $request->status;

            $dataInfo->updated_at = Carbon::now();

            if ($dataInfo->save()) {
                $dataId = $dataInfo->id;

                $tableName = 'categories';

                $userId = 1;

                $userType = 1;

                $dataType = 2;

                $comment = $dataInfo->id . '=>' . $dataInfo->title . ' Category Status Changed By ';
                // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Category Status Changed By '.Auth::guard('staff-api')->user()->name;

                GeneralController::storeSystemLog($tableName, $dataId, $comment, $userId, $userType, $dataType);

                DB::commit();

                $responseData = [
                    'errMsgFlag' => false,
                    'msgFlag' => true,
                    'msg' => 'Category Status Changed Successfully.',
                    'errMsg' => null,
                ];
            } else {
                DB::rollBack();

                $responseData = [
                    'errMsgFlag' => true,
                    'msgFlag' => false,
                    'msg' => null,
                    'errMsg' => 'Failed To Change Category Status.'
                ];
            }

            return response()->json($responseData, 200);
        } catch (Exception $err) {
            DB::rollBack();

            GeneralController::storeSystemErrorLog("Backends\CategoryController@changeCategoryStatus", $err);

            DB::commit();

            $responseData = [
                'errMsgFlag' => true,
                'msgFlag' => false,
                'msg' => null,
                'errMsg' => 'Something Went Wrong.Please Try Again.'
            ];

            return response()->json($responseData, 200);
        }
    }

    public function deleteSubCategory($id)
    {

        $subsubcat = Category::where('parent_id', $id)->where('look_type', 3)->count('id');

        if ($subsubcat > 0) {
            $responseData = [
                'errMsgFlag' => true,
                'msgFlag' => false,
                'msg' => null,
                'errMsg' => 'Failed To Delete Subcategory.',
            ];

            return response()->json($responseData, 200);

        } else {
            $deleteSubCategory = Category::find($id)->delete();
            $deleteSubCategory->delete();
            if ($deleteSubCategory)
                $responseData = [
                    'errMsgFlag' => false,
                    'msgFlag' => true,
                    'msg' => 'Subcategory Deleted Successfully.',
                    'errMsg' => null,
                ];
            else
                $responseData = [
                    'errMsgFlag' => true,
                    'msgFlag' => false,
                    'msg' => null,
                    'errMsg' => 'Failed To Delete Subcategory.',
                ];

        }





    }
}