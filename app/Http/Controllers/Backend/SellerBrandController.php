<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Models\BrandRejection;
use App\Models\SellerBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerBrandController extends Controller
{
    public function getSellerBrandList(Request $request)
    {
    	$query=SellerBrand::with('brands','seller')->orderBy('id','desc');
        
    

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%');
                        
            });
        }

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }

    public function getSellerBrandInfo(Request $request)
    {
    	$dataList=SellerBrand::with('brands','seller','BrandDocuments','BrandRejections')->where('id',$request->dataId)->first();
        

        return response()->json($dataList);
    }

    public function brandRejected(Request $request){
        
        try{
            DB::beginTransaction();
            $brand=SellerBrand::where('id',$request->dataId)->first();
            $brand->rejacted=1;
            $brand->save();
            if(!empty($brand)){
                $brandRejection=new BrandRejection();
                $brandRejection->document_id=$request->dataId;
                $brandRejection->staff_id=1;
                $brandRejection->recommendation=$request->recommendation;
                $brandRejection->rejection_reason=$request->rejectedReason;
                $brandRejection->save();
                if($brandRejection->save()){
                   
                    DB::commit();
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Brand rejected succssfully.',
                        'errMsg'=>null,

                    ];

                }else{

                }
                return response()->json($responseData,200);

            }
        

        }catch(\Exception $err){
            DB::rollBack();DB::commit();
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Something went wrong.Pleasae try Again.'

            ];
            return response()->json($responseData,200);
        }

    }

    public function changeProductPublished(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=SellerBrand::find($request->dataId);

                $dataInfo->approved=$request->published;
                if($dataInfo->approved==1){
                    $dataInfo->rejacted=0;
                  

                }

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
                            'msg'=>'Product Published Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Product Published.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(\Exception $err)
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

}
