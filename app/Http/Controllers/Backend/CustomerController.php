<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function updateCustomer(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Customer::find($request->dataId);

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

                            if (Storage::disk('public')->exists('customer')) {
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
   public function getCustomerInfo(Request $request)
    {
       $dataInfo=Customer::find($request->dataId);

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

    public function getCustomerDetails(Request $request)
    {
       $dataInfo=Customer::with('reviewInfo')->find($request->dataId);

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
    public function getSellerInfoDetailsProduct(Request $request)
    {
       $query=Order::where('customer_id',$request->dataId);
       $query->orderBy('id','desc');
            
       $dataList=$query->paginate($request->numOfData);
       if(!empty($dataList)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>null,
                'msg'=>null,
                'dataList'=>$dataList
          ];  
       }
       else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'errMsg'=>'Requested Data Not Found.',
                'msg'=>null,
                'dataList'=>$dataList
          ];
       }

       return response()->json($responseData,200);
    }
    public function addCustomer(Request $request)
    {
    	DB::beginTransaction();
        try{

            $isCustomerExist=Customer::whereNull('deleted_at');
            
            if(isset($request->phone) && !is_null($request->phone))
                $isCustomerExist->where('phone',trim($request->phone));

            if(isset($request->email) && !is_null($request->email))
                $isCustomerExist->where('email',trim($request->email));

            $isCustomerExist=$isCustomerExist->first();


            if(empty($isCustomerExist)){

                    // $password=substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0,6);

                    $dataInfo=new Customer();

                    $dataInfo->name=$request->name;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                    $dataInfo->password=Hash::make($request->password);

                    $dataInfo->social_id=null;

                    $dataInfo->dob=$request->dob;

                    $dataInfo->is_verify=1;

                    $dataInfo->status=1;

                     if(isset($request->photo) && !is_null($request->file('photo')))
                         {
                            $image=$request->file('photo');

                             // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                             $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            if (Storage::disk('public')->exists('customer')) {
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

                    $dataInfo->created_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='customers';

                        $userId=1;

                        $userType=1;

                        $dataType=1;

                        $comment='Customer Added By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Customer Information Added Successfully.',
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
                                            'errMsg'=>'Failed To Add Customer Infomation.',
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
                            'errMsg'=>'Customer Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\CustomerController@addCustomer");
            
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
    public function getCustomerList(Request $request)
    {

        
        if($request->dataId==1){
          
            $query=Customer::orderBy('id','desc');
        
          
    
            if(isset($request->customerName) && !is_null($request->customerName))
                $query->where('name','like',$request->customerName.'%');
    
            if(isset($request->customerPhone) && !is_null($request->customerPhone))
                $query->where('phone',$request->customerPhone);
           
                if(isset($request->customerEmail) && !is_null($request->customerEmail))
                $query->where('email',$request->customerEmail);
           
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==2){
            $query=Customer::whereNull('deleted_at')->orderBy('id','desc')->whereDate('created_at', Carbon::now());
        
            

        if(isset($request->customerName) && !is_null($request->customerName))
            $query->where('name','like',$request->customerName.'%');

        if(isset($request->customerPhone) && !is_null($request->customerPhone))
            $query->where('phone',$request->customerPhone);
       
            if(isset($request->customerEmail) && !is_null($request->customerEmail))
            $query->where('email',$request->customerEmail);
 
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==3){
            $query=Customer::whereNull('deleted_at')->orderBy('id','desc')->where('is_verify',1)->whereNull('deleted_at');
        
            

        if(isset($request->customerName) && !is_null($request->customerName))
            $query->where('name','like',$request->customerName.'%');

        if(isset($request->customerPhone) && !is_null($request->customerPhone))
            $query->where('phone',$request->customerPhone);
       
            if(isset($request->customerEmail) && !is_null($request->customerEmail))
            $query->where('email',$request->customerEmail);
 
               
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==4){
            $query=Customer::whereNull('deleted_at')->orderBy('id','desc')->where('status',2);
        
            

        if(isset($request->customerName) && !is_null($request->customerName))
            $query->where('name','like',$request->customerName.'%');

        if(isset($request->customerPhone) && !is_null($request->customerPhone))
            $query->where('phone',$request->customerPhone);
       
            if(isset($request->customerEmail) && !is_null($request->customerEmail))
            $query->where('email',$request->customerEmail);
 
    
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }elseif($request->dataId==5){
            $query=Customer::orderBy('id','desc')->where(['block'=>1])->whereNull('deleted_at');
        
            

        if(isset($request->customerName) && !is_null($request->customerName))
            $query->where('name','like',$request->customerName.'%');

        if(isset($request->customerPhone) && !is_null($request->customerPhone))
            $query->where('phone',$request->customerPhone);
       
            if(isset($request->customerEmail) && !is_null($request->customerEmail))
            $query->where('email',$request->customerEmail);
 
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }
        elseif($request->dataId==6){
            $query=Customer::orderBy('id','desc')->where('deleted_at','!=',NULL);
        
            

        if(isset($request->customerName) && !is_null($request->customerName))
            $query->where('name','like',$request->customerName.'%');

        if(isset($request->customerPhone) && !is_null($request->customerPhone))
            $query->where('phone',$request->customerPhone);
       
            if(isset($request->customerEmail) && !is_null($request->customerEmail))
            $query->where('email',$request->customerEmail);
 
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }
        else{
          
            $query=Customer::whereNull('deleted_at')->orderBy('id','desc')->whereDate('created_at', Carbon::now());
        
          
    
            if(isset($request->customerName) && !is_null($request->customerName))
                $query->where('name','like',$request->customerName.'%');
    
            if(isset($request->customerPhone) && !is_null($request->customerPhone))
                $query->where('phone',$request->customerPhone);
           
                if(isset($request->customerEmail) && !is_null($request->customerEmail))
                $query->where('email',$request->customerEmail);
 
    
            
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

        }
    

        $totalNewCustomer=Customer::where('status', '=', 0)->orWhere('status', '=', 1)->whereDate('created_at', Carbon::now())->whereNull('deleted_at')->count('id');
        $totalVerifyCustomer=Customer::where(['status'=>1,'is_verify'=>1])->whereNull('deleted_at')->count('id');
        $totalInactiveCustomer=Customer::where(['status'=>2])->whereNull('deleted_at')->count('id');
        $totalDeleteCustomer=Customer::where('deleted_at','!=', NULL)->count('id');
        $totalBlockedCustomer=Customer::where(['block'=>1])->whereNull('deleted_at')->count('id');
        $TotalCustomer=Customer::count('id');
   
        $data=[
            'dataList'=>$dataList,
            'totalNewCustomer'=>$totalNewCustomer,
            'TotalCustomer'=>$TotalCustomer,
            'totalVerifyCustomer'=>$totalVerifyCustomer,
            'totalInactiveCustomer'=>$totalInactiveCustomer,
            'totalDeleteCustomer'=>$totalDeleteCustomer,
            'totalBlockedCustomer'=>$totalBlockedCustomer,

         
        ];

        return response()->json($data,200);
    	// $query=Customer::whereNull('deleted_at')->orderBy('id','desc');
        
        // if(isset($request->status) && !is_null($request->status))
        //     $query->where('status',$request->status);

        // if(isset($request->name) && !is_null($request->name))
        //     $query->where('name','like',$request->name.'%');

        // if(isset($request->phone) && !is_null($request->phone))
        //     $query->where('phone','like',$request->phone.'%');

        // if(isset($request->email) && !is_null($request->email))
        //     $query->where('email','like',$request->email.'%');

        // $dataList=$query->paginate($request->numOfData);

        // return response()->json($dataList);
    }
    public function getActiveCustomerList(Request $request)
    {
    	$dataList=Customer::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeCustomerStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Customer::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='customers';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->name.' Customer Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->name.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Customer Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Customer Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CustomerController@changeCustomerStatus",$err);
            
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
    public function verifyCustomer(Request $request)
    {
        DB::beginTransaction();

        try{
                $dataInfo=Customer::find($request->dataId);

                $dataInfo->is_verify=$request->is_verify;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='customers';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Customer Verified By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Customer Verified Successfully.',
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
                            'errMsg'=>'Failed To Verify Customer.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CustomerController@verifyCustomer",$err);
            
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
    public function blockCustomer(Request $request)
    {
        DB::beginTransaction();

        try{
                $dataInfo=Customer::find($request->dataId);

                $dataInfo->block=$request->block;
                if($request->block==1){
                    $dataInfo->status=0;
                }else{
                    $dataInfo->status=1;
                }

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='customers';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Customer Verified By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();
                        if($request->block==1){
                            $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Customer Blocked Successfully.',
                                'errMsg'=>null,
                            ];

                        }else{
                            $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Customer Unblocked Successfully.',
                                'errMsg'=>null,
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
                            'errMsg'=>'Failed To Blocked Customer.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\CustomerController@verifyCustomer",$err);
            
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
    public function deleteCustomer(Request $request)
    {
        $dataInfo = Customer::find($request->dataId);
        $dataInfo->status=2;
        $dataInfo->block=1;
        $dataInfo->deleted_at=Carbon::now();
        $dataInfo->save();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Customer Delete Successfully.',
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
