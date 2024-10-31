<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\ReturnPolicy;
use App\Models\Curior;
use App\Models\FinancialAccount;
use App\Models\CustomerRefundable;
use Carbon\Carbon;
use Exception;
use DB;


class ReturnOrderController extends Controller
{
    public function getReturnOrder(Request $request) {
        $dataInfo=OrderItem::with('productInfo','stockInfo','stockInfo.sizeInfo','stockInfo.colorInfo')->where('id',$request->dataId)->first();
        if (!empty($dataInfo)) {
			$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataInfo'=>$dataInfo,
                 
                ];
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

    public function getReturnPolicy(Request $request) {
    
        $dataInfo=ReturnPolicy::where('status',1)->whereNull('deleted_at')->get();
        if (!empty($dataInfo)) {
			$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataInfo'=>$dataInfo,
                 
                ];
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

    public function getCurior(Request $request) {
    
        $dataInfo=Curior::where('status',1)->whereNull('deleted_at')->get();
        if (!empty($dataInfo)) {
			$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataInfo'=>$dataInfo,
                 
                ];
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

    public function getFinancialAccount(Request $request) {
    
        $dataInfo=FinancialAccount::where('status',1)->whereNull('deleted_at')->get();
        if (!empty($dataInfo)) {
			$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataInfo'=>$dataInfo,
                 
                ];
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


    public function sendRefundProduct(Request $request){
        DB::beginTransaction();
        try{

            $refundData=CustomerRefundable::where('orderItem_id',$request->dataId)->first();
            if(empty($refundData)){

                
                $dataInfo=new CustomerRefundable();
                $dataInfo->orderItem_id=$request->dataId;
                $dataInfo->curior_id=$request->curiorName;
                if($request->returnType==2){
                    $dataInfo->financial_account_id=$request->financialAccountName;
                }
              
                $dataInfo->return_type=$request->returnType;
                $dataInfo->description=$request->description;
                $dataInfo->created_at=Carbon::now();
    
                if($dataInfo->save()){
    
                    DB::commit();
    
    
                    
                }
                else{
    
                     DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Next.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                }

            }else{

                $deleteRefundData=CustomerRefundable::where('orderItem_id',$request->dataId)->delete();
                $dataInfo=new CustomerRefundable();
                $dataInfo->orderItem_id=$request->dataId;
                $dataInfo->curior_id=$request->curiorName;
                if($request->returnType==2){
                    $dataInfo->financial_account_id=$request->financialAccountName;
                }
                $dataInfo->return_type=$request->returnType;
                $dataInfo->returnCauseId=$request->returnCauseId;
                $dataInfo->description=$request->description;
                $dataInfo->created_at=Carbon::now();
    
                if($dataInfo->save()){
    
                    DB::commit();
    
    
                    
                }
                else{
    
                     DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Next.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                }

            }
           

        }catch(Exception $er){
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

    public function getRefundProduct(Request $request){
        $dataInfo=OrderItem::with('productInfo','stockInfo','stockInfo.sizeInfo','stockInfo.colorInfo','refundItem','refundItem.curiorType','refundItem.financialAccountType','refundItem.returnCauseType')->where('id',$request->dataId)->first();
        if (!empty($dataInfo)) {
			$responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataInfo'=>$dataInfo,
                 
                ];
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

    public function confirmRefundProduct(Request $request){
        DB::beginTransaction();
        try{

            $dataInfo=CustomerRefundable::where('orderItem_id',$request->dataId)->first();
            if(!empty($dataInfo)){
                $dataInfo->accountNumber=$request->accountNumber;
      
                $dataInfo->created_at=Carbon::now();
    
                if($dataInfo->save()){
    
                    DB::commit();
                    
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Return  Successfully.',
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
                        'errMsg'=>'Failed To Next.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                }

            }else{
    
                     DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Next.Please Try Again.',
                     ];
    
                    return response()->json($responseData,200);
                

            }
           

        }catch(Exception $er){
            DB::rollBack();

            GeneralController::storeSystemErrorLog($er,"Backend\SizeController@addSize");

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
