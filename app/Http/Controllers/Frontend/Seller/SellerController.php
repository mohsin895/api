<?php

namespace App\Http\Controllers\Frontend\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\SellerMessage;
use App\Models\Shop;
use App\Models\Review;
use App\Models\GeneralSetting;
use App\Models\SmsSetting;
use Auth;
use Hash;
use DB;
use Mail;
use Carbon\Carbon;

class SellerController extends Controller
{
   
	public function sellerMessage(Request $request)
    {

        DB::beginTransaction();
        try{
          
            $shop = Shop::where('id',$request->shopId)->first();
			$seller=Seller::where('id',$shop->seller_id)->first();
                   
                $dataInfo=new SellerMessage();

               

                $dataInfo->email=$request->email;
              

                $dataInfo->shop_id=$request->shopId;
				$dataInfo->seller_id=$seller->id;

                $dataInfo->message=$request->message;


                $dataInfo->status=0;

                $dataInfo->created_at=Carbon::now();
                
                if($dataInfo->save())
                {
                    

                   

                        DB::commit();

                        $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Successfully Send Message.',
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
                                'errMsg'=>'Failed To Send Message.Please Try Again.',
                        ];
                }
            
            

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
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

	public function productRatingReview(Request $request)
    {

          
           $shop=Shop::where('slug','like','%'.$request->slug.'%')->first();
			$seller=Seller::where('id',$shop->seller_id)->first();
            
                   
             $sellerProductReview=Review::with(['productInfo','customerInfo','sellerInfo','images','stockInfo', 'stockInfo.colorInfo'=>function($q) use($request){
				$q->select('color','color_code','id');
			},
			'stockInfo.sizeInfo'=>function($q) use($request){
				$q->select('size','id');
			},
			 ])->where('shop_id',$shop->id)->orderBy('id','desc')->get();
             $responseData=[
               
                'sellerProductReview'=>$sellerProductReview,
               
                
            ];


            return response()->json($responseData,200);
       
    }

	
}
