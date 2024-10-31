<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Models\SizeAttribute;
use Illuminate\Http\Request;
use App\Models\Size;
use Carbon\Carbon;
use Exception;
use DB;

class SizeAttributeController extends Controller
{
    
    public function addSizeAttribute(Request $request)
    {
    	DB::beginTransaction();
        try{

            foreach($request->attribute as $key=>$attribute){
                $dataInfo = new SizeAttribute();
                $dataInfo->size_id=$request->sizeId;
                $dataInfo->attribute=$request->attribute[$key];
                $dataInfo->save();
                $dataInfo->created_at=Carbon::now();
            }


            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Size Attribute Added Successfully.',
                    'errMsg'=>null,
                 ];

                return response()->json($responseData,200);
            }
            else{

                 DB::rollBack();

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Add Size Attribute .Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\SizeController@addSize");

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

    public function updateSizeAttribute(Request $request)
    {
    	DB::beginTransaction();
        try{
       
            foreach($request->attribute as $key=>$attribute){
                $dataInfo = SizeAttribute::where('id',$request->attributeId[$key])->first();
                $dataInfo->attribute=$request->attribute[$key];
                $dataInfo->save();
                $dataInfo->created_at=Carbon::now();
            }


            if($dataInfo->save()){

                DB::commit();

                $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Size Attribute Added Successfully.',
                    'errMsg'=>null,
                 ];

                return response()->json($responseData,200);
            }
            else{

                 DB::rollBack();

                $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Add Size Attribute .Please Try Again.',
                 ];

                return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

          

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

    public function getSizeAttributeList(Request $request)
    {
    	$query=Size::with('attributeInfo')->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->label) && !is_null($request->label))
            $query->where('label','like',$request->label.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }

    public function getSizeAttributeInfo(Request $request)
    {
       $dataInfo=Size::with('attributeInfo')->find($request->dataId);

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

    public function deleteSizeAttribute(Request $request)
    {
    	$dataInfo = SizeAttribute::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Size Delete successfully',
            ];

        }else{
             $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Something wrong, plese try again',

             ];
        }
        return response()->json($responseData,200);
    }

    public function getActiveSizeAttributeList(Request $request)
    {
    	$query=SizeAttribute::whereNull('deleted_at');

        if(isset($request->variantSize) && $request->variantSize!='')
            $query->where('size_id',$request->variantSize);

        $dataList=$query->orderBy('id','desc')->get();
        
        return response()->json($dataList,200);
    }
}
