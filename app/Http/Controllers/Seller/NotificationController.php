<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notificationCount()
    {
        $sellerNotification = Notification::where('seller_views_all', 1)->where('seller_id', Auth::guard('seller-api')->user()->id)->count();
        // event(new OrderNotification($notification));
        return response()->json($sellerNotification, 200);
    }

    public function notificationList()
    {
        $sellerNotification = Notification::with('orderItem.productInfo')->where('seller_id', Auth::guard('seller-api')->user()->id)->orderBy('id', 'desc')->get();
        // event(new OrderNotification($notification));
        return response()->json($sellerNotification, 200);
    }

    public function notificationUpdateAll(Request $request)
    {

        DB::beginTransaction();
        try {

            $dataInfo = Notification::where('seller_id', Auth::guard('seller-api')->user()->id)->get();

                foreach($dataInfo as $data){
                    
                    $notify = Notification::where('id',$data->id)->first();
                    $notify->seller_views_all=0;
                    $notify->save();
                }

            // $dataInfo->title = $request->title;
            // $dataInfo->seller_id = Auth::guard('seller-api')->user()->id;

            // $dataInfo->description = $request->description;

            // $dataInfo->target_url = $request->targetUrl;

            // $dataInfo->updated_at = Carbon::now();



         
           

                $tableName = 'notifications';

                $userId = 1;

                $userType = 1;

                $dataType = 2;

                $comment = 'Notification Updated By ';
                // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                // GeneralController::storeSystemLog($tableName, $dataId, $comment, $userId, $userType, $dataType);

                // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                DB::commit();

                $responseData = [
                    'errMsgFlag' => false,
                    'msgFlag' => true,
                    'msg' => 'Successfully Update Slider.',
                    'errMsg' => null,
                ];
            


            return response()->json($responseData, 200);
        } catch (\Exception $err) {
            DB::rollBack();

            GeneralController::storeSystemErrorLog($err, "Seller\NotificationController@updateSlider");

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
}
