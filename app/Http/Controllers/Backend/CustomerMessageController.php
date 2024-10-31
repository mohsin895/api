<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\CustomerCare;
use Carbon\Carbon;
use Exception;
use DB;
use Hash;
use Storage;

class CustomerMessageController extends Controller
{
    public function reply(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=CustomerCare::find($request->dataId);

            if(!empty($dataInfo)){

                    // $password=substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0,6);

                    $dataInfo->name=$request->name;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                   $dataInfo->password=Hash::make($request->password);

                    // $dataInfo->social_id=null;

                    $dataInfo->dob=$request->dob;

                    // $dataInfo->is_verify=1;

                    // $dataInfo->status=1;

                     if(isset($request->photo) && !is_null($request->file('photo')))
                         {
                            $image=$request->file('photo');

                             // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                             $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('customer')) {
                                Storage::disk('public')->makeDirectory('customer');
                            }

                           // $note_img = Image::make($image)->resize(400, 400)->stream();

                            $note_img = Image::make($image)->stream();

                                Storage::disk('public')->makeDirectory('customer');
                            Storage::disk('public')->put('customer/' . $imageName, $note_img);

                            $path = '/storage/app/public/customer/'.$imageName;

                            $dataInfo->avatar=$path;
                         }
                         else
                            $dataInfo->avatar='/storage/app/public/customer/defaultUser.png';

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='customers';

                        $userId=1;

                        $userType=1;

                        $dataType=2;

                        $comment='Customer Updated By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Customer Information Updated Successfully.',
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
                                            'errMsg'=>'Failed To Update Customer Infomation.',
                                        ];

                            return response()->json($responseData,200);
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

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@updateCustomer");
            
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
    public function getMessageInfo(Request $request)
    {
       $dataInfo=CustomerCare::find($request->dataId);

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
  
    public function getMessageList(Request $request)
    {
    	$query=CustomerCare::orderBy('id','desc');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name))
            $query->where('name','like',$request->name.'%');

        if(isset($request->phone) && !is_null($request->phone))
            $query->where('phone','like',$request->phone.'%');

        if(isset($request->email) && !is_null($request->email))
            $query->where('email','like',$request->email.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
   
  
    public function delete(Request $request)
    {
        $dataInfo = CustomerCare::find($request->dataId);
       
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Mesage Delete Successfully.',
            ];

        }else{

            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Somethigwrong, please try again',

            ];

        }
        return response()->json($responseData, 200);
    }
}
