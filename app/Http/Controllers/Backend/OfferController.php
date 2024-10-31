<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\SupperProduct;
use App\Models\ShockingDeal;
use App\Models\RightBanner;
use DB;

class OfferController extends Controller
{
public function offerSupper(Request $request){
   
        $supperProduct = RightBanner::where('status', 1)->get();
        $offerProduct=ShockingDeal::whereNull('deleted_at')->get();
        $data=[
            'supperProduct'=>$supperProduct,
            'offerProduct'=>$offerProduct,
        ];
        return response()->json($data, 200);
   
}


    public function getSellerList(Request $request)
    {

       
            $query=Seller::with('shopInfo')->where(['is_verify'=>1])->whereNull('deleted_at')->orderBy('id','desc');
        
            if(isset($request->sellerId) && !is_null($request->sellerId))
            $query->where('id',$request->sellerId);

        if(isset($request->sellerName) && !is_null($request->sellerName))
            $query->where('f_name','like',$request->sellerName.'%');

        if(isset($request->sellerPhone) && !is_null($request->sellerPhone))
            $query->where('phone',$request->sellerPhone);
       
            if(isset($request->sellerEmail) && !is_null($request->sellerEmail))
            $query->where('email',$request->sellerEmail);
 
               
    
            $query->orderBy('id','desc');
            
            $dataList=$query->paginate($request->numOfData);

      
    

   
        $data=[
            'dataList'=>$dataList,
       
         
        ];

        return response()->json($data,200);

    }

    public function sendSellerSupperProduct(Request $request){

        DB::beginTransaction();

        try{
        $selectedProductsIds = json_decode($request->input('selectedSellerId'));
       
        foreach($selectedProductsIds as $sellerId){
            $supperProduct= new SupperProduct();
            $supperProduct->seller_id= $sellerId; 
            $supperProduct->supper_id=$request->supper_id;
            $supperProduct->save();
        }


      
        
        if($selectedProductsIds)
        {
          
            DB::commit();

            $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Product Published  Successfully.',
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
catch(Exception $err)
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
