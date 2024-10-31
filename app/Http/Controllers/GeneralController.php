<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemErrorLog;
use App\Models\DataEntryHistory;
use App\Models\SmsSetting;
use App\Models\Product;
use Carbon\Carbon;

class GeneralController extends Controller
{
  public function test(Request $request)
  {
    $query=Product::with('shopInfo','brandInfo','megaCategory','subCategory','normalCategory')
                        ->whereNull('deleted_at')
                            ->where('seller_id',12);
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name))
            $query->where('name','like',$request->name.'%');

        if(isset($request->brand) && !is_null($request->brand))
            $query->where('brand_id',$request->brand);

        if(isset($request->published) && !is_null($request->published))
            $query->where('published',$request->published);

        if(isset($request->category) && !is_null($request->category)){
            $query->where(function($q) use($request){
                $q->where('category_id',$request->category)
                        ->orWhere('subcategory_id',$request->category)
                            ->orWhere('sub_subcategory_id',$request->category);
            });
        }

        $query->orderBy('name','asc');
        
        $dataList=$query->paginate($request->numOfData);

        return view('welcome',compact('dataList'));
  }
    public static function sendSMS($phone,$message)
    {

      $gs=SmsSetting::find(1);
      $api_key=$gs->nonMaskingApiKey;
      $client_id=$gs->nonMaskingClientId;
      $sender_id="8809617609942";


      $message = urlencode($message);
      $sender_id = urlencode($sender_id);
      $url = "https://api.smsq.global/api/v2/SendSMS?ApiKey=$api_key&ClientId=$client_id&SenderId=$sender_id&Message=$message&MobileNumbers=$phone&Is_Unicode=true";
      // dd($url);
      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_URL, $url);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_NOBODY, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_exec($ch);
      return true; 
    }
   
  
    public static function phoneNumberPrefix($phone)
    {
        $number=null;
         if(substr($phone,0,2)!='88')
            $number.='88'.$phone;
          else
            $number.=$phone;
          return $number;
    }
     public static function storeSystemLog($tableName,$dataId,$optional,$userId,$userType,$dataType)
    {
        $dataInfo=new DataEntryHistory();

        $dataInfo->data_table=$tableName;

        $dataInfo->data_id=$dataId;

        $dataInfo->type=$dataType;

        $dataInfo->user_type=$userType;

        $dataInfo->user_id=$userId;

        $dataInfo->optional=$optional;

        $dataInfo->created_at=Carbon::now();

        if($dataInfo->save())
            return true;
        else
            return false;

    }
    public static function storeSystemErrorLog($controller,$error)
    {
    	if(strlen($error)>5000)
    		$error=substr($error, 0,2000);

    	$dataInfo=new SystemErrorLog();

    	$dataInfo->controller=$controller;

    	$dataInfo->error=$error;

    	$dataInfo->created_at=Carbon::now();

    	if($dataInfo->save())
    		return true;
    	else
    		return false;
    }
}
