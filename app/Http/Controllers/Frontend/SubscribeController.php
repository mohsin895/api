<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Carbon\Carbon;
use Exception;
use DB;

class SubscribeController extends Controller
{
    public function subscriber(Request $request)
	{
		DB::beginTransaction();
        try{

            $isCustomerExist=Subscription::whereNull('deleted_at');
            


            if(isset($request->email) && !is_null($request->email))
                $isCustomerExist->where('email',trim($request->email));

            $isCustomerExist=$isCustomerExist->first();


            if(empty($isCustomerExist)){



                    $dataInfo=new Subscription();

            

                    $dataInfo->email=$request->email;


                    $dataInfo->status=1;

                    

                    $dataInfo->created_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                      

                        DB::commit();

                

                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'You are now a memebr.',
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
                                            'errMsg'=>'Failed To Subscribe.',
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
                            'errMsg'=>'Email Already Registered.',
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
}
