<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\CartRule;
use App\Models\StockInfo;
use App\Models\CartProduct;
use App\Models\ProductQuantity;
use App\Models\IgnoreCartRule;
use App\Models\VoucherDisount;
use App\Models\DeliveryRules;
use Carbon\Carbon;
use Exception;
use DB;
use Auth;
use Cart;


class CartRulesController extends Controller
{
     public function updateDeliveryRules(Request $request)
    {
       DB::beginTransaction();
        try{

            $cartRuleInfo=DeliveryRules::find($request->dataId);

            if(!empty($cartRuleInfo)) {

                $startAt= $request->startDate.' '.$request->startTime;

                $endAt= $request->endDate.' '.$request->endTime;

               

                $cartRuleInfo->name=$request->name;

                $cartRuleInfo->details=$request->details;

                $cartRuleInfo->isCityWise=$request->isCityWise;

                $cartRuleInfo->isInsideDhaka=$request->isInsideDhaka;

                $cartRuleInfo->isAreaWise=$request->isAreaWise;

                $cartRuleInfo->isprice_wise=$request->isPriceWise;

                $cartRuleInfo->rules_for=$request->rulesFor;

                $cartRuleInfo->discount=$request->discount;

                $cartRuleInfo->price_required=$request->priceRequired;

                $cartRuleInfo->start_at=$startAt;

                $cartRuleInfo->end_at=$endAt;

                if(isset($request->areaId) && !is_null($request->areaId)){
                    $array=[];
                    foreach ($request->get('areaId') as $key => $value) {
                        array_push($array, [
                                            'areaId'=>$request->areaId[$key],
                                            'deliveryCharge'=>$request->deliveryCharge[$key],
                                            'areaName'=>$request->areaName[$key],
                                            'insideDhaka'=>$request->insideDhaka[$key],
                                        ]);
                    }
                    $cartRuleInfo->offeredAreas=json_encode($array);
                    //json_encode(array_combine($request->areaId,$request->deliveryCharge));
                }

                $cartRuleInfo->status=$request->status;

                $cartRuleInfo->created_at=Carbon::now();

                if($cartRuleInfo->save()){
                    
                    // $dataId=$dataInfo->id;

                    // $tableName='brands';

                    // $userId=1;

                    // $userType=1;

                    // $dataType=2;

                    // $comment='Brand Updated By ';
                    // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();
                        
                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Delivery Rules Updated Successfully.',
                                'errMsg'=>null,
                            ];
                            // dd("okay");
                        return response()->json($responseData,200);
                   
                }
                else{
                        DB::rollBack();
                        
                        $responseData=[
                                'errMsgFlag'=>true,
                                'msgFlag'=>false,
                                'msg'=>null,
                                'errMsg'=>'Failed To Store Delivery Rules.',
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

            GeneralController::storeSystemErrorLog($err,"CartRulesController@updateDeliveryRules");

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
    public function editDeliveryRulesInfo(Request $request)
    {
        $dataInfo=DeliveryRules::where('id',$request->dataId)
                                        ->first();

        if (!empty($dataInfo)) {

           $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>null,
                            'errMsg'=>null,
                            'dataInfo'=>$dataInfo,
                        ];

            return response()->json($responseData,200);
        }
        else
        {
           $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Data Not Found.',
                        ];

            return response()->json($responseData,200); 
        }
    }
    public function deleteDeliveryRules(Request $request)
    {
       DB::beginTransaction();
        try{

                $success=0;

                $fail=0;

                foreach ($request->get('dataIds') as $key => $value) {

                    
                    $dataInfo=DeliveryRules::find($value);

                    $dataInfo->status=0;

                    $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save())
                    {
                        $dataId=$dataInfo->id;

                        $tableName='delivery_rules';

                        $userId=1;

                        $userType=1;

                        $dataType=0;

                        $comment='Delivery Rules Deleted By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                    
                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        $success++;
                    }
                    else
                    {
                        $fail++;
                    }
                    
                }

                DB::commit();

                $responseData=[
                            
                            'errMsgFlag'=>($fail>0) ? true:false,

                            'msgFlag'=>($success>0) ? true:false,
                            
                            'msg'=>$success." Data Deleted Successfully.",
                            
                            'errMsg'=>$fail." Failed To Delete.",
                ];

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"CartRulesController@deleteDeliveryRules");
            
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
    public function changeDeliveryRulesStatus(Request $request)
    {
       DB::beginTransaction();
        try{
            
            $dataInfo=DeliveryRules::find($request->dataId);

            if (!empty($dataInfo)) {

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    // $dataId=$dataInfo->id;

                    // $tableName='brands';

                    // $userId=1;

                    // $userType=1;

                    // $dataType=2;

                    // $comment='Brand Updated By ';
                    // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Delivery Rules Status Changed Successfully.',
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
                                    'errMsg'=>'Failed To Change Delivery Rules Status.',
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
                                'errMsg'=>'Data Not Found.',
                            ];

                return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"CartRulesController@changeDeliveryRulesStatus");

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
    public function getDeliveryRulesList(Request $request)
    {
       $query=DeliveryRules::where('status','!=',0)
                                    ->latest();

         if(isset($request->searchKey) && !is_null($request->searchKey))
                $query->where('name','like',$request->searchKey.'%')->orWhere('details','like',$request->searchKey);

        $dataList=$query->paginate($request->numOfData);

        // return view('welcome');      
        return response()->json($dataList,200);
    }
    public function storeDeliveryRules(Request $request)
    {

        DB::beginTransaction();
        try{

            $startAt= $request->startDate.' '.$request->startTime;

            $endAt= $request->endDate.' '.$request->endTime;

            $cartRuleInfo=new DeliveryRules();

           

            $cartRuleInfo->name=$request->name;

            $cartRuleInfo->details=$request->details;

            $cartRuleInfo->isCityWise=$request->isCityWise;

            $cartRuleInfo->isInsideDhaka=$request->isInsideDhaka;

            $cartRuleInfo->isAreaWise=$request->isAreaWise;

            $cartRuleInfo->isprice_wise=$request->isPriceWise;

            $cartRuleInfo->rules_for=$request->rulesFor;

            $cartRuleInfo->discount=$request->discount;

            $cartRuleInfo->price_required=$request->priceRequired;

            $cartRuleInfo->start_at=$startAt;

            $cartRuleInfo->end_at=$endAt;

            if(isset($request->areaId) && !is_null($request->areaId)){
                $array=[];
                foreach ($request->get('areaId') as $key => $value) {
                    array_push($array, [
                                        'areaId'=>$request->areaId[$key],
                                        'deliveryCharge'=>$request->deliveryCharge[$key],
                                        'areaName'=>$request->areaName[$key],
                                        'insideDhaka'=>$request->insideDhaka[$key],
                                    ]);
                }
                $cartRuleInfo->offeredAreas=json_encode($array);
                //json_encode(array_combine($request->areaId,$request->deliveryCharge));
            }

            $cartRuleInfo->status=$request->status;

            $cartRuleInfo->created_at=Carbon::now();

            if($cartRuleInfo->save()){
                
                // $dataId=$dataInfo->id;

                // $tableName='brands';

                // $userId=1;

                // $userType=1;

                // $dataType=2;

                // $comment='Brand Updated By ';
                // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
            
                // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                DB::commit();
                    
                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Delivery Rules Stored Successfully.',
                            'errMsg'=>null,
                        ];
                        // dd("okay");
                    return response()->json($responseData,200);
               
            }
            else{
                    DB::rollBack();
                    
                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Store Delivery Rules.',
                        ];
                        
                    return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"CartRulesController@storeDeliveryRules");

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
    public static function getFreeProduct($cart,$choosedProducts)
    {
        $indexes=[];

        $takenRules=(session()->has('takenRules')) ? session()->get('takenRules'):[];

        $restrictedRules=(session()->has('restrictedRules')) ? session()->get('restrictedRules'):[];

        // $restrictedRules=IgnoreCartRule::where('status',1)
        //                                     ->whereIn('cartRuleId',$takenRules)
        //                                         ->pluck('ignoreCartRuleId')
        //                                             ->toArray();
                                            
      $freeProducts=CartProduct::whereHas('ruleInfoForAddToCart', function ($q) {
                                        $q->where('status',1) 
                                                ->where('startAt','<=',Carbon::now())
                                                    ->where('endAt','>=',Carbon::now())
                                                        ->whereIn('rulesFor',[0,2])
                                                            ->orderBy('priceRequired','DESC');

                                  })
                                    ->with(['ruleInfoForAddToCart'=>function($q) {
                                                $q->orderBy('priceRequired','DESC');
                                    }])//->where('appId',Auth::guard('admin')->user()->appId)
                                                    ->where('type',3)
                                                        ->where('status',1)
                                                            // ->whereIn('productId',$choosedProducts)
                                                                // ->whereNotIn('ruleId',$restrictedRules)
                                                                    ->orderBy('id','DESC')
                                                                         ->get();

        // dd($freeProducts);
         $totalPrice=array_sum(array_column($cart, 'totalPrice'));
         $totalDiscount=array_sum(array_column($cart, 'discount'));

         $nextCartIndex=count($cart);

        foreach ($freeProducts as $key => $freeProductInfo){

            $freeProductQuantity=1;
         
            if(!in_array($freeProductInfo->ruleInfoForAddToCart->id, session()->get('restrictedRules'))){

                if($freeProductInfo->ruleInfoForAddToCart->isPriceWise==1 && $freeProductInfo->ruleInfoForAddToCart->isProductRequired==1){

                   if($freeProductInfo->ruleInfoForAddToCart->priceRequired<=($totalPrice-$totalDiscount)){

                         $requiredProductInfos=CartProduct::where('type',1)
                                                                ->where('status',1)
                                                                    ->where('rule_id',$freeProductInfo->rule_id)
                                                                        ->get();

                        $requiredProductFlag=false;

                        foreach($requiredProductInfos as $key => $requiredProductInfo) {

                              if(!in_array($requiredProductInfo->productId, array_column($cart, 'productId'))){

                                $requiredProductFlag=false;

                                break;

                              }
                              else{

                                $requiredProductIndex=array_search($requiredProductInfo->productId,array_column($cart,'productId'));

                                if($requiredProductInfo->quantity>$cart[$requiredProductIndex]['quantity']){

                                    $requiredProductFlag=false;

                                    break;

                                }
                                else
                                    $requiredProductFlag=true;

                              }     
                        }

                         // if($freeProductInfo->apply_index>1){
                         if($requiredProductFlag && $cart[$requiredProductIndex]['quantity']>=1){

                                // $freeProductIndex=array_search($freeProductIndex->productId,array_column($cart,'productId'));

                                if($freeProductInfo->quantity>=$freeProductInfo->apply_index){

                                    $freeProductQuantity=(int)(($totalPrice-$totalDiscount)/$freeProductInfo->ruleInfoForAddToCart->priceRequired);

                                    $freeProductQuantity=(int)($freeProductQuantity/$freeProductInfo->apply_index);

                                }
                                else
                                    $requiredProductFlag=false;
                                
                        }
                        if($requiredProductFlag){

                            $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                            $productInfo=StockInfo::where('productId',$freeProductInfo->product_id)
                                                            
                                                                ->where('status',1)
                                                                    ->orderBy('id','DESC')
                                                                        ->first();


                             if(!empty($productInfo)){

                                if(!is_null($productInfo->productImage))
                                    $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                else
                                    $productImage=null;

                                    if($cart[$requiredProductIndex]['productId']==$freeProductInfo->product_id){
                                        $quantity=($quantity>($productInfo->quantity-$cart[$requiredProductIndex]['quantity'])) ? $productInfo->quantity-$cart[$requiredProductIndex]['quantity']:$quantity;
                                    }
                                    else{
                                        $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;
                                    }

                                    if ($quantity>0) {
                                        
                                          $freeCart=[
                                                    'productQuantityId'=>$productInfo->id,
                                                    'productId'=>$freeProductInfo->product_id,
                                                    'quantity'=>$quantity,
                                                    'rate'=>$productInfo->stockInfo->sellPrice,
                                                    'price'=>0,
                                                    'discount'=>0,//$cartInfo->attributes->discount,
                                                    'discountFlag'=>false,//$cartInfo->attributes->discount,
                                                    'isFreeProduct'=>true,
                                                    'colorId'=>$productInfo->colorId,
                                                    'sizeId'=>$productInfo->sizeId,
                                                    'quantityType'=>$productInfo->productInfo->quantityType,
                                                    'name'=>$productInfo->productInfo->name,
                                                    'size'=>$productInfo->sizeInfo->size,
                                                    'color'=>$productInfo->colorInfo->color,
                                                    'productImage'=>$productImage,
                                                    'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                                    'totalPrice'=>0,
                                                ];

                                            array_push($cart, $freeCart);

                                            array_push($takenRules, $freeProductInfo->rule_id);

                                            session()->put('takenRules',$freeProductInfo->rule_id);

                                            $restrictedRules=IgnoreCartRule::where('status',1)
                                                        ->whereIn('cartRuleId',$takenRules)
                                                            ->pluck('ignoreCartRuleId')
                                                                ->toArray();

                                            session()->put('restrictedRules',$restrictedRules);

                                    }
                                }

                        }
                   }
                }
                else
                {
                    if($freeProductInfo->ruleInfoForAddToCart->isPriceWise==1){

                         if($freeProductInfo->ruleInfoForAddToCart->priceRequired<=($totalPrice-$totalDiscount)){

                                // $freeProductIndex=array_search($freeProductInfo->product_id,array_column($cart,'productId'));
                                
                                
                                // dump($cart[$freeProductIndex]['quantity']);

                                 if($freeProductInfo->quantity>=1){

                                    if($freeProductInfo->quantity>=$freeProductInfo->apply_index){
                                       
                                         $freeProductQuantity=(int)(($totalPrice-$totalDiscount)/$freeProductInfo->ruleInfoForAddToCart->priceRequired);


                                         $freeProductQuantity=(int)($freeProductQuantity/$freeProductInfo->apply_index);

                                         // dump($cart[$freeProductIndex]['quantity']);

                                        $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                                        $productInfo=StockInfo::where('product_id',$freeProductInfo->product_id)
                                                                            ->where('status',1)
                                                                                ->orderBy('id','DESC')
                                                                                    ->first();


                                        if(!empty($productInfo)){

                                            if(!is_null($productInfo->productImage))
                                                $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                            else
                                                $productImage=null;
                                        
                                            $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;

                                            if ($quantity>0) {
                                                
                                                  $freeCart=[
                                                            'productQuantityId'=>$productInfo->id,
                                                            'productId'=>$freeProductInfo->product_id,
                                                            'quantity'=>$quantity,
                                                            'rate'=>$productInfo->stockInfo->sellPrice,
                                                            'price'=>0,
                                                            'discount'=>0,//$cartInfo->attributes->discount,
                                                            'discountFlag'=>false,//$cartInfo->attributes->discount,
                                                            'isFreeProduct'=>true,
                                                            'colorId'=>$productInfo->colorId,
                                                            'sizeId'=>$productInfo->sizeId,
                                                            'quantityType'=>$productInfo->productInfo->quantityType,
                                                            'name'=>$productInfo->productInfo->name,
                                                            'size'=>$productInfo->sizeInfo->size,
                                                            'color'=>$productInfo->colorInfo->color,
                                                            'productImage'=>$productImage,
                                                            'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                                            'totalPrice'=>0,
                                                        ];

                                                    array_push($cart, $freeCart);

                                                    array_push($takenRules, $freeProductInfo->rule_id);

                                                    session()->put('takenRules',$freeProductInfo->rule_id);

                                                    $restrictedRules=IgnoreCartRule::where('status',1)
                                                                ->whereIn('cartRuleId',$takenRules)
                                                                    ->pluck('ignoreCartRuleId')
                                                                        ->toArray();

                                                    session()->put('restrictedRules',$restrictedRules);
                                                
                                            }

                                        }
                                        
                                     }
            
                                }
                                else
                                {
                                    $freeProductQuantity=(int)(($totalPrice-$totalDiscount)/$freeProductInfo->ruleInfoForAddToCart->priceRequired);
                                   

                                    $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                                    $productInfo=StockInfo::where('product_id',$freeProductInfo->product_id)
                                                                    
                                                                        ->where('status',1)
                                                                            ->orderBy('id','DESC')
                                                                                ->first();

                                    if(!empty($productInfo)){

                                        if(!is_null($productInfo->productImage))
                                            $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                        else
                                            $productImage=null;

                                        $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;

                                        if ($quantity>0) {
                                            
                                              $freeCart=[
                                                        'productQuantityId'=>$productInfo->id,
                                                        'productId'=>$freeProductInfo->product_id,
                                                        'quantity'=>$quantity,
                                                        'rate'=>$productInfo->stockInfo->sellPrice,
                                                        'price'=>0,
                                                        'discount'=>0,//$cartInfo->attributes->discount,
                                                        'discountFlag'=>false,//$cartInfo->attributes->discount,
                                                        'isFreeProduct'=>true,
                                                        'colorId'=>$productInfo->colorId,
                                                        'sizeId'=>$productInfo->sizeId,
                                                        'quantityType'=>$productInfo->productInfo->quantityType,
                                                        'name'=>$productInfo->productInfo->name,
                                                        'size'=>$productInfo->sizeInfo->size,
                                                        'color'=>$productInfo->colorInfo->color,
                                                        'productImage'=>$productImage,
                                                        'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                                        'totalPrice'=>0,
                                                    ];

                                                array_push($cart, $freeCart);

                                                array_push($takenRules, $freeProductInfo->rule_id);

                                                session()->put('takenRules',$freeProductInfo->rule_id);

                                                $restrictedRules=IgnoreCartRule::where('status',1)
                                                            ->whereIn('cartRuleId',$takenRules)
                                                                ->pluck('ignoreCartRuleId')
                                                                    ->toArray();

                                                session()->put('restrictedRules',$restrictedRules);
                                            
                                        }
                                    }
                                }

                         }
                    }
                    else
                        if($freeProductInfo->ruleInfoForAddToCart->isProductRequired==1){

                                $requiredProductInfos=CartProduct::where('type',1)
                                                                        ->where('status',1)
                                                                            ->where('ruleId',$freeProductInfo->rule_id)
                                                                                ->get();

                                $requiredProductFlag=false;

                                foreach($requiredProductInfos as $key => $requiredProductInfo){

                                      if(!in_array($requiredProductInfo->productId, array_column($cart, 'productId'))){

                                        $requiredProductFlag=false;

                                        break;

                                      }
                                      else{

                                        $requiredProductIndex=array_search($requiredProductInfo->productId,array_column($cart,'productId'));

                                        if($requiredProductInfo->quantity>$cart[$requiredProductIndex]['quantity']){

                                            $requiredProductFlag=false;

                                            break;

                                        }
                                        else
                                            $requiredProductFlag=true;

                                      }     
                                }

                                // $freeProductIndex=array_search($freeProductInfo->product_id,array_column($cart,'productId'));

                                  if($freeProductInfo->quantity>=1 && $requiredProductFlag){

                                    
                                        if($freeProductInfo->quantity>=$freeProductInfo->apply_index){

                                            $freeProductQuantity=(int)($freeProductInfo->quantity/$freeProductInfo->apply_index);

                                        }
                                        else
                                            $requiredProductFlag=false;
                                        
                                    }

                                if($requiredProductFlag){
                                    
                                    // dd($freeProductQuantity);

                                    $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                                    $productInfo=StockInfo::where('productId',$freeProductInfo->product_id)
                                                                    
                                                                        ->where('status',1)
                                                                            ->orderBy('id','DESC')
                                                                                ->first();

                                    if(!empty($productInfo)){

                                        if(!is_null($productInfo->productImage))
                                          $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                        else
                                          $productImage=null;

                                       if($cart[$requiredProductIndex]['productId']==$freeProductInfo->product_id){
                                            $quantity=($quantity>($productInfo->quantity-$cart[$requiredProductIndex]['quantity'])) ? $productInfo->quantity-$cart[$requiredProductIndex]['quantity']:$quantity;
                                        }
                                        else{
                                            $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;
                                        }

                                        if ($quantity>0) {
                                            
                                              $freeCart=[
                                                        'productQuantityId'=>$productInfo->id,
                                                        'productId'=>$freeProductInfo->product_id,
                                                        'quantity'=>$quantity,
                                                        'rate'=>$productInfo->stockInfo->sellPrice,
                                                        'price'=>0,
                                                        'discount'=>0,//$cartInfo->attributes->discount,
                                                        'discountFlag'=>false,//$cartInfo->attributes->discount,
                                                        'isFreeProduct'=>true,
                                                        'colorId'=>$productInfo->colorId,
                                                        'sizeId'=>$productInfo->sizeId,
                                                        'quantityType'=>$productInfo->productInfo->quantityType,
                                                        'name'=>$productInfo->productInfo->name,
                                                        'size'=>$productInfo->sizeInfo->size,
                                                        'color'=>$productInfo->colorInfo->color,
                                                        'productImage'=>$productImage,
                                                        'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                                        'totalPrice'=>0,
                                                    ];

                                                array_push($cart, $freeCart);

                                                array_push($takenRules, $freeProductInfo->rule_id);

                                                session()->put('takenRules',$freeProductInfo->rule_id);

                                                $restrictedRules=IgnoreCartRule::where('status',1)
                                                            ->whereIn('cartRuleId',$takenRules)
                                                                ->pluck('ignoreCartRuleId')
                                                                    ->toArray();

                                                session()->put('restrictedRules',$restrictedRules);
                                            
                                        }
                               }

                            }
                        }
                        else{
                                // $freeProductIndex=array_search($freeProductInfo->product_id,array_column($cart,'productId'));

                                //  if($cart[$freeProductIndex]['quantity']>1){

                                //     if($cart[$freeProductIndex]['quantity']>=$freeProductInfo->apply_index){
                                       
                                //         $freeProductQuantity=(int)($cart[$freeProductIndex]['quantity']/$freeProductInfo->apply_index);

                                //         $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                                //         $productInfo=StockInfo::where('productId',$freeProductInfo->product_id)
                                //                                         
                                //                                             ->where('status',1)
                                //                                                 ->orderBy('id','DESC')
                                //                                                  ->first();


                                //     if(!empty($productInfo)){

                                //             if(!is_null($productInfo->productImage))
                                //                 $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                //             else
                                //                 $productImage=null;
                                    
                                //          $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;

                                //         if ($quantity>0) {
                                            
                                //               $freeCart=[
                                //                         'productQuantityId'=>$productInfo->id,
                                //                         'productId'=>$freeProductInfo->product_id,
                                //                         'quantity'=>$quantity,
                                //                         'rate'=>$productInfo->stockInfo->sellPrice,
                                //                         'price'=>0,
                                //                         'discount'=>0,//$cartInfo->attributes->discount,
                                //                         'discountFlag'=>false,//$cartInfo->attributes->discount,
                                //                         'isFreeProduct'=>true,
                                //                         'colorId'=>$productInfo->colorId,
                                //                         'sizeId'=>$productInfo->sizeId,
                                //                         'quantityType'=>$productInfo->productInfo->quantityType,
                                //                         'name'=>$productInfo->productInfo->name,
                                //                         'size'=>$productInfo->sizeInfo->size,
                                //                         'color'=>$productInfo->colorInfo->color,
                                //                         'productImage'=>$productImage,
                                //                         'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                //                         'totalPrice'=>0,
                                //                     ];

                                //                 array_push($cart, $freeCart);

                                //                 array_push($takenRules, $freeProductInfo->rule_id);

                                //                 session()->put('takenRules',$freeProductInfo->rule_id);

                                //                 $restrictedRules=IgnoreCartRule::where('status',1)
                                //                             ->whereIn('cartRuleId',$takenRules)
                                //                                 ->pluck('ignoreCartRuleId')
                                //                                     ->toArray();

                                //                 session()->put('restrictedRules',$restrictedRules);
                                            
                                //         }

                                //     }
                                    
                                //  }

                                $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                                $productInfo=StockInfo::where('productId',$freeProductInfo->product_id)
                                                                
                                                                    ->where('status',1)
                                                                        ->orderBy('id','DESC')
                                                                            ->first();

                                if(!empty($productInfo)){

                                    if(!is_null($productInfo->productImage))
                                         $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                    else
                                        $productImage=null;

                                    $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;

                                    if ($quantity>0) {
                                        
                                          $freeCart=[
                                                    'productQuantityId'=>$productInfo->id,
                                                    'productId'=>$freeProductInfo->product_id,
                                                    'quantity'=>$quantity,
                                                    'rate'=>$productInfo->stockInfo->sellPrice,
                                                    'price'=>0,
                                                    'discount'=>0,//$cartInfo->attributes->discount,
                                                    'discountFlag'=>false,//$cartInfo->attributes->discount,
                                                    'isFreeProduct'=>true,
                                                    'colorId'=>$productInfo->colorId,
                                                    'sizeId'=>$productInfo->sizeId,
                                                    'quantityType'=>$productInfo->productInfo->quantityType,
                                                    'name'=>$productInfo->productInfo->name,
                                                    'size'=>$productInfo->sizeInfo->size,
                                                    'color'=>$productInfo->colorInfo->color,
                                                    'productImage'=>$productImage,
                                                    'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                                    'totalPrice'=>0,
                                                ];

                                            array_push($cart, $freeCart);

                                            array_push($takenRules, $freeProductInfo->rule_id);

                                            session()->put('takenRules',$freeProductInfo->rule_id);

                                            $restrictedRules=IgnoreCartRule::where('status',1)
                                                        ->whereIn('cartRuleId',$takenRules)
                                                            ->pluck('ignoreCartRuleId')
                                                                ->toArray();

                                            session()->put('restrictedRules',$restrictedRules);
                                        
                                    
                                }
        
                            }
                            else
                            {

                                $quantity=(int)($freeProductQuantity*$freeProductInfo->quantity);

                                $productInfo=StockInfo::where('productId',$freeProductInfo->product_id)
                                                                
                                                                    ->where('status',1)
                                                                        ->orderBy('id','DESC')
                                                                            ->first();

                                if(!empty($productInfo)){

                                    if(!is_null($productInfo->productImage))
                                         $productImage=$productInfo->productImage->baseUrl.$productInfo->productImage->productImage;
                                    else
                                        $productImage=null;

                                    $quantity=($quantity>$productInfo->quantity) ? $productInfo->quantity:$quantity;

                                    if ($quantity>0) {
                                        
                                          $freeCart=[
                                                    'productQuantityId'=>$productInfo->id,
                                                    'productId'=>$freeProductInfo->product_id,
                                                    'quantity'=>$quantity,
                                                    'rate'=>$productInfo->stockInfo->sellPrice,
                                                    'price'=>0,
                                                    'discount'=>0,//$cartInfo->attributes->discount,
                                                    'discountFlag'=>false,//$cartInfo->attributes->discount,
                                                    'isFreeProduct'=>true,
                                                    'colorId'=>$productInfo->colorId,
                                                    'sizeId'=>$productInfo->sizeId,
                                                    'quantityType'=>$productInfo->productInfo->quantityType,
                                                    'name'=>$productInfo->productInfo->name,
                                                    'size'=>$productInfo->sizeInfo->size,
                                                    'color'=>$productInfo->colorInfo->color,
                                                    'productImage'=>$productImage,
                                                    'buyRate'=>$productInfo->stockInfo->purchasePrice,
                                                    'totalPrice'=>0,
                                                ];

                                            array_push($cart, $freeCart);

                                            array_push($takenRules, $freeProductInfo->rule_id);

                                            session()->put('takenRules',$freeProductInfo->rule_id);

                                            $restrictedRules=IgnoreCartRule::where('status',1)
                                                        ->whereIn('cartRuleId',$takenRules)
                                                            ->pluck('ignoreCartRuleId')
                                                                ->toArray();

                                            session()->put('restrictedRules',$restrictedRules);
                                        
                                    }
                                }
                            }
                        }
                }
            }
        }

        session()->put('takenRules',$takenRules);

        return $cart;
    }
    public static function getInvoiceDiscount($cart,$choosedProducts)
    {
        $takenRules=(session()->has('takenRules')) ? session()->get('takenRules'):[];

        $restrictedRules=[];

        // $restrictedRules=IgnoreCartRule::where('status',1)
        //                                     ->whereIn('cartRuleId',$takenRules)
        //                                         ->pluck('ignoreCartRuleId')
        //                                             ->toArray();

        $restrictedRules=(session()->has('restrictedRules')) ? session()->get('restrictedRules'):[];

       $cartRuleInfos=CartRule::with('cartProductInfosForAddToCart')
                                    ->where('status',1)
                                        ->where('isInvoiceDiscount',1) 
                                                ->where('startAt','<=',Carbon::now())
                                                    ->where('endAt','>=',Carbon::now())
                                                        ->whereIn('rulesFor',[1,2])
                                                            ->whereNotIn('id',$restrictedRules)
                                                                ->orderBy('id','DESC')
                                                                    ->get();

        $invoiceDiscount=0;

        $totalPrice=array_sum(array_column($cart, 'totalPrice'));

        $totalDiscount=array_sum(array_column($cart, 'discount'));

        // dump("totalPrice=>".$totalPrice);

        foreach ($cartRuleInfos as $key => $cartRuleInfo) {

           // dump($cartRuleInfo);

           if(in_array($cartRuleInfo->id, session()->get('restrictedRules'))){
            
               if($cartRuleInfo->isproduct_required==1 && $cartRuleInfo->isprice_wise==1){

                    if($cartRuleInfo->price_required<=($totalPrice-$totalDiscount)){

                        $requiredProductFlag=true;

                        foreach($cartRuleInfo->cartProductInfosForAddToCart as $key => $requiredProductInfo) {

                            if($requiredProductInfo->type==1){

                                 if(!in_array($requiredProductInfo->productId, array_column($cart, 'productId'))){
                                   
                                    $requiredProductFlag=false;

                                    break;

                                  }
                                  else{
                                    
                                    $requiredProductIndex=array_search($requiredProductInfo->productId,array_column($cart,'productId'));

                                    if($requiredProductInfo->quantity>$cart[$requiredProductIndex]['quantity']){

                                        $requiredProductFlag=false;

                                        break;
                                    }
                                    else
                                        $requiredProductFlag=true;

                                  } 

                            }   
                        }

                        if($requiredProductFlag){

                            $invoiceDiscount+=$cartRuleInfo->total_discount;

                            array_push($takenRules, $cartRuleInfo->id);

                            session()->put('takenRules',$cartRuleInfo->id);

                            $restrictedRules=IgnoreCartRule::where('status',1)
                                                                ->whereIn('cartRuleId',$takenRules)
                                                                    ->pluck('ignoreCartRuleId')
                                                                        ->toArray();

                            session()->put('restrictedRules',$restrictedRules);

                        }
                    }
               }
               else{

                    if($cartRuleInfo->isproduct_required==1){

                         $requiredProductFlag=true;

                        foreach($cartRuleInfo->cartProductInfosForAddToCart as $key => $requiredProductInfo) {

                            if($requiredProductInfo->type==1){

                                 if(!in_array($requiredProductInfo->productId, array_column($cart, 'productId'))){
                                   
                                    $requiredProductFlag=false;

                                    break;

                                  }
                                  else{
                                    
                                    $requiredProductIndex=array_search($requiredProductInfo->productId,array_column($cart,'productId'));

                                    if($requiredProductInfo->quantity>$cart[$requiredProductIndex]['quantity']){

                                        $requiredProductFlag=false;

                                        break;
                                    }
                                    else
                                        $requiredProductFlag=true;
                                  } 

                            }   
                        }

                        if($requiredProductFlag){

                            $invoiceDiscount+=$cartRuleInfo->total_discount;

                            array_push($takenRules, $cartRuleInfo->id);

                             session()->put('takenRules',$cartRuleInfo->id);

                            $restrictedRules=IgnoreCartRule::where('status',1)
                                                                ->whereIn('cartRuleId',$takenRules)
                                                                    ->pluck('ignoreCartRuleId')
                                                                        ->toArray();

                            session()->put('restrictedRules',$restrictedRules);
                        }

                    }
                    if($cartRuleInfo->isprice_wise==1){

                         if($cartRuleInfo->price_required<=($totalPrice-$totalDiscount)){

                              $invoiceDiscount+=$cartRuleInfo->total_discount;

                              array_push($takenRules, $cartRuleInfo->id);

                              session()->put('takenRules',$cartRuleInfo->id);

                            $restrictedRules=IgnoreCartRule::where('status',1)
                                                                ->whereIn('cartRuleId',$takenRules)
                                                                    ->pluck('ignoreCartRuleId')
                                                                        ->toArray();

                            session()->put('restrictedRules',$restrictedRules);
                         }

                    }
               }
            }

        }

       session()->put('takenRules',$takenRules);

       session()->put("invoiceDiscount",$invoiceDiscount);

    }
    public static function getProductDiscount($cart,$choosedProducts)
    {
      
        $indexes=[];

        $takenRules=(session()->has('takenRules')) ? session()->get('takenRules'):[];

        $restrictedRules=[];

        // $restrictedRules=IgnoreCartRule::where('status',1)
        //                                     ->whereIn('cartRuleId',$takenRules)
        //                                         ->pluck('ignoreCartRuleId')
        //                                             ->toArray();

        $restrictedRules=(session()->has('restrictedRules')) ? session()->get('restrictedRules'):[];

         $discountProducts=CartProduct::whereHas('ruleInfoForAddToCart', function ($q) {
                                        $q->where('status',1) 
                                                ->where('startAt','<=',Carbon::now())
                                                    ->where('endAt','>=',Carbon::now())
                                                        ->whereIn('rulesFor',[1,2]);

                                  })
                                    ->with('ruleInfoForAddToCart')
                                                    ->where('type',2)
                                                        ->where('status',1)
                                                            ->whereIn('productId',$choosedProducts)
                                                                // ->whereNotIn('ruleId',$restrictedRules)
                                                                    ->orderBy('id','DESC')
                                                                         ->get();

        $totalPrice=array_sum(array_column($cart, 'totalPrice'));

        $totalDiscount=array_sum(array_column($cart, 'discount'));

        foreach ($discountProducts as $key => $discountProductInfo) {

            $discount=0;

            $index=array_search($discountProductInfo->productId,array_column($cart,'productId'));

            array_push($indexes, $index);

            if($discountProductInfo->ruleInfoForAddToCart->isProductDiscount==1){

                if($discountProductInfo->ruleInfoForAddToCart->isProductRequired==1 && $discountProductInfo->ruleInfoForAddToCart->isProductRequired==1 ){

                    if($discountProductInfo->ruleInfoForAddToCart->priceRequired<=($totalPrice-$totalDiscount)){

                        $requiredProductInfos=CartProduct::where('type',1)
                                                                ->where('status',1)
                                                                    ->where('ruleId',$discountProductInfo->ruleId)
                                                                        ->get();

                        $requiredProductFlag=false;

                        foreach($requiredProductInfos as $key => $requiredProductInfo) {

                              if(!in_array($requiredProductInfo->productId, array_column($cart, 'productId'))){

                                $requiredProductFlag=false;

                                break;

                              }
                              else{

                                $requiredProductIndex=array_search($requiredProductInfo->productId,array_column($cart,'productId'));

                                if($requiredProductInfo->quantity>$cart[$requiredProductIndex]['quantity']){

                                    $requiredProductFlag=false;

                                    break;

                                }
                                else
                                    $requiredProductFlag=true;
                              }     
                        }

                        if($requiredProductFlag){

                            $qty=(int)$cart[$index]['quantity']/$discountProductInfo->applyIndex;

                            $discount=$qty*$discountProductInfo->discount;

                            // if($discountProductInfo->applyIndex)

                            $discountCart=[
                                            'productQuantityId'=>$cart[$index]['productQuantityId'],
                                            'productId'=>$cart[$index]['productId'],
                                            'quantity'=>$cart[$index]['quantity'],
                                            'rate'=>$cart[$index]['rate'],
                                            'price'=>$cart[$index]['rate'],
                                            'discount'=>$cart[$index]['discount']+$discount,//$cartInfo->attributes->discount,
                                            'discountFlag'=>true,//$cartInfo->attributes->discount,
                                            'isFreeProduct'=>$cart[$index]['isFreeProduct'],
                                            'colorId'=>$cart[$index]['colorId'],
                                            'sizeId'=>$cart[$index]['sizeId'],
                                            'quantityType'=>$cart[$index]['quantityType'],
                                            'name'=>$cart[$index]['name'],
                                            'size'=>$cart[$index]['size'],
                                            'color'=>$cart[$index]['color'],
                                            'productImage'=>$cart[$index]['productImage'],
                                            'buyRate'=>$cart[$index]['buyRate'],
                                            'hasSizeVarity'=>$cart[$index]['hasSizeVarity'],
                                            'hasColorVarity'=>$cart[$index]['hasColorVarity'],
                                            'totalPrice'=>$cart[$index]['totalPrice'],
                                        ];

                            $cart[$index]= $discountCart;

                            array_push($takenRules, $discountProductInfo->ruleId);

                            session()->put('takenRules',$discountProductInfo->ruleId);

                            $restrictedRules=IgnoreCartRule::where('status',1)
                                        ->whereIn('cartRuleId',$takenRules)
                                            ->pluck('ignoreCartRuleId')
                                                ->toArray();

                            session()->put('restrictedRules',$restrictedRules);
                        }
                    }
                }
                else{

                        if($discountProductInfo->ruleInfoForAddToCart->isProductRequired==1){

                                $requiredProductInfos=CartProduct::where('type',1)
                                                                        ->where('status',1)
                                                                            ->where('ruleId',$discountProductInfo->ruleId)
                                                                                ->get();

                                $requiredProductFlag=false;

                                foreach($requiredProductInfos as $key => $requiredProductInfo) {

                                      if(!in_array($requiredProductInfo->productId, array_column($cart, 'productId'))){

                                        $requiredProductFlag=false;

                                        break;

                                      }
                                      else{

                                        $requiredProductIndex=array_search($requiredProductInfo->productId,array_column($cart,'productId'));

                                        if($requiredProductInfo->quantity>$cart[$requiredProductIndex]['quantity']){

                                            $requiredProductFlag=false;

                                            break;

                                        }
                                        else
                                            $requiredProductFlag=true;

                                      }     
                                }

                                if($requiredProductFlag){

                                    $qty=(int)$cart[$index]['quantity']/$discountProductInfo->applyIndex;

                                    $discount=$qty*$discountProductInfo->discount;

                                    $discountCart=[
                                                    'productQuantityId'=>$cart[$index]['productQuantityId'],
                                                    'productId'=>$cart[$index]['productId'],
                                                    'quantity'=>$cart[$index]['quantity'],
                                                    'rate'=>$cart[$index]['rate'],
                                                    'price'=>$cart[$index]['rate'],
                                                    'discount'=>$cart[$index]['discount']+$discount,//$cartInfo->attributes->discount,
                                                    'discountFlag'=>true,//$cartInfo->attributes->discount,
                                                    'isFreeProduct'=>$cart[$index]['isFreeProduct'],
                                                    'colorId'=>$cart[$index]['colorId'],
                                                    'sizeId'=>$cart[$index]['sizeId'],
                                                    'quantityType'=>$cart[$index]['quantityType'],
                                                    'name'=>$cart[$index]['name'],
                                                    'size'=>$cart[$index]['size'],
                                                    'color'=>$cart[$index]['color'],
                                                    'productImage'=>$cart[$index]['productImage'],
                                                    'buyRate'=>$cart[$index]['buyRate'],
                                                    'hasSizeVarity'=>$cart[$index]['hasSizeVarity'],
                                                    'hasColorVarity'=>$cart[$index]['hasColorVarity'],
                                                    'totalPrice'=>$cart[$index]['totalPrice'],
                                                ];

                                    $cart[$index]= $discountCart;

                                    array_push($takenRules, $discountProductInfo->ruleId);

                                    session()->put('takenRules',$discountProductInfo->ruleId);

                                    $restrictedRules=IgnoreCartRule::where('status',1)
                                                ->whereIn('cartRuleId',$takenRules)
                                                    ->pluck('ignoreCartRuleId')
                                                        ->toArray();

                                    session()->put('restrictedRules',$restrictedRules);
                                }
                        }
                        else
                            if($discountProductInfo->ruleInfoForAddToCart->isProductRequired==1){

                                if($discountProductInfo->ruleInfoForAddToCart->priceRequired<=($totalPrice-$totalDiscount)){

                                    $qty=(int)$cart[$index]['quantity']/$discountProductInfo->applyIndex;

                                    $discount=$qty*$discountProductInfo->discount;

                                    // if($discountProductInfo->applyIndex)

                                    $discountCart=[
                                                    'productQuantityId'=>$cart[$index]['productQuantityId'],
                                                    'productId'=>$cart[$index]['productId'],
                                                    'quantity'=>$cart[$index]['quantity'],
                                                    'rate'=>$cart[$index]['rate'],
                                                    'price'=>$cart[$index]['rate'],
                                                    'discount'=>$cart[$index]['discount']+$discount,//$cartInfo->attributes->discount,
                                                    'discountFlag'=>true,//$cartInfo->attributes->discount,
                                                    'isFreeProduct'=>$cart[$index]['isFreeProduct'],
                                                    'colorId'=>$cart[$index]['colorId'],
                                                    'sizeId'=>$cart[$index]['sizeId'],
                                                    'quantityType'=>$cart[$index]['quantityType'],
                                                    'name'=>$cart[$index]['name'],
                                                    'size'=>$cart[$index]['size'],
                                                    'color'=>$cart[$index]['color'],
                                                    'productImage'=>$cart[$index]['productImage'],
                                                    'buyRate'=>$cart[$index]['buyRate'],
                                                    'hasSizeVarity'=>$cart[$index]['hasSizeVarity'],
                                                    'hasColorVarity'=>$cart[$index]['hasColorVarity'],
                                                    'totalPrice'=>$cart[$index]['totalPrice'],
                                                ];

                                     $cart[$index]= $discountCart;

                                     array_push($takenRules, $discountProductInfo->ruleId);

                                     session()->put('takenRules',$discountProductInfo->ruleId);

                                    $restrictedRules=IgnoreCartRule::where('status',1)
                                                ->whereIn('cartRuleId',$takenRules)
                                                    ->pluck('ignoreCartRuleId')
                                                        ->toArray();

                                    session()->put('restrictedRules',$restrictedRules);

                                }
                            }
                            else
                            {
                                 $qty=(int)$cart[$index]['quantity']/$discountProductInfo->applyIndex;

                                    $discount=$qty*$discountProductInfo->discount;

                                    // if($discountProductInfo->applyIndex)

                                    $discountCart=[
                                                    'productQuantityId'=>$cart[$index]['productQuantityId'],
                                                    'productId'=>$cart[$index]['productId'],
                                                    'quantity'=>$cart[$index]['quantity'],
                                                    'rate'=>$cart[$index]['rate'],
                                                    'price'=>$cart[$index]['rate'],
                                                    'discount'=>$cart[$index]['discount']+$discount,//$cartInfo->attributes->discount,
                                                    'discountFlag'=>true,//$cartInfo->attributes->discount,
                                                    'isFreeProduct'=>$cart[$index]['isFreeProduct'],
                                                    'colorId'=>$cart[$index]['colorId'],
                                                    'sizeId'=>$cart[$index]['sizeId'],
                                                    'quantityType'=>$cart[$index]['quantityType'],
                                                    'name'=>$cart[$index]['name'],
                                                    'size'=>$cart[$index]['size'],
                                                    'color'=>$cart[$index]['color'],
                                                    'productImage'=>$cart[$index]['productImage'],
                                                    'buyRate'=>$cart[$index]['buyRate'],
                                                    'hasSizeVarity'=>$cart[$index]['hasSizeVarity'],
                                                    'hasColorVarity'=>$cart[$index]['hasColorVarity'],
                                                    'totalPrice'=>$cart[$index]['totalPrice'],
                                                ];

                                $cart[$index]= $discountCart;

                                array_push($takenRules, $discountProductInfo->ruleId);

                                session()->put('takenRules',$discountProductInfo->ruleId);

                                $restrictedRules=IgnoreCartRule::where('status',1)
                                            ->whereIn('cartRuleId',$takenRules)
                                                ->pluck('ignoreCartRuleId')
                                                    ->toArray();

                                session()->put('restrictedRules',$restrictedRules);
                            }

                }
            }
        }

        session()->put('takenRules',$takenRules);
        
        return $cart;

    }
    public static function getCartInfos()
    {
        $customCart=[];

        $productIds=[];

        // $userId=(Auth::guard('admin')->check()) ? 'Admin-'.Auth::guard('admin')->user()->id:'Admin-0';

        $userId=(Auth::guard('staff-api')->check()) ? 'Admin-'.Auth::guard('staff-api')->user()->id:'Admin-0';

        $cartInfos=Cart::session($userId)->getContent();

        $totalCartValue=Cart::session($userId)->getTotal();

        session()->put('takenRules',[]);

        session()->put('restrictedRules',[]);

        foreach ($cartInfos as $key => $cartInfo) {

           // dump($cartInfo->attributes) ;
            if($cartInfo->attributes->isFreeProduct==false){

                $cart=[
                        'productQuantityId'=>explode("=>",$cartInfo->id)[0],
                        'productId'=>$cartInfo->attributes->productId,
                        'quantity'=>$cartInfo->quantity,
                        'rate'=>$cartInfo->attributes->rate,
                        'price'=>$cartInfo->price,
                        'totalPrice'=>$cartInfo->attributes->totalPrice,
                        'discount'=>$cartInfo->attributes->discount,
                        'discountFlag'=>$cartInfo->attributes->discountFlag,
                        'isFreeProduct'=>$cartInfo->attributes->isFreeProduct,
                        'colorId'=>$cartInfo->attributes->colorId,
                        'sizeId'=>$cartInfo->attributes->sizeId,
                        'quantityType'=>$cartInfo->attributes->quantityType,
                        'name'=>$cartInfo->name,
                        'size'=>$cartInfo->attributes->size,
                        'color'=>$cartInfo->attributes->color,
                        'productImage'=>$cartInfo->attributes->productImage,
                        'buyRate'=>$cartInfo->attributes->buyRate,
                        'hasSizeVarity'=>$cartInfo->attributes->hasSizeVarity,
                        'hasColorVarity'=>$cartInfo->attributes->hasColorVarity,
                    ];

             array_push( $customCart,$cart);

             array_push( $productIds,$cartInfo->attributes->productId);

            }
        }


        $customCart=CartRulesController::getProductDiscount($customCart,$productIds);

        $customCart=CartRulesController::getFreeProduct($customCart,$productIds);

        CartRulesController::getInvoiceDiscount($customCart,$productIds);

        return $customCart;
       
    }

    public function updateCartRules(Request $request)
    {
         // dd($request->all());
        DB::beginTransaction();
        try{


            $cartRuleInfo=CartRule::find($request->dataId);

            if(!empty($cartRuleInfo)){

                    $startAt= $request->startDate.' '.$request->startTime;

                    $endAt= $request->endDate.' '.$request->endTime;

                    $deleteFlag=CartProduct::where('rule_id',$request->dataId)->delete();

                    // $ignoreCartRuleDeleteFlag=IgnoreCartRule::where('cartRuleId',$request->dataId)->delete();

                    $falseCount=0;

                    //

                    $cartRuleInfo->name=$request->name;

                    $cartRuleInfo->details=$request->details;

                    $cartRuleInfo->isproduct_wise=$request->isProductWise;

                    $cartRuleInfo->isprice_wise=$request->isPriceWise;

                    $cartRuleInfo->isproduct_required=$request->isProductRequired;

                    $cartRuleInfo->isproduct_discount=$request->isProductDiscount;

                    $cartRuleInfo->isinvoice_discount=$request->isInvoiceDiscount;

                    $cartRuleInfo->isfree_product=$request->isFreeProduct;

                    $cartRuleInfo->rules_for=$request->rulesFor;

                    $cartRuleInfo->total_discount=(isset($request->invoiceDiscount) && $request->invoiceDiscount>0) ? $request->invoiceDiscount:0;

                    $cartRuleInfo->price_required=(isset($request->priceRequired) && $request->priceRequired>0) ? $request->priceRequired:0;

                    $cartRuleInfo->start_at=$startAt;

                    $cartRuleInfo->end_at=$endAt;

                    // $cartRuleInfo->applyOnOther=$request->applyOnOther;

                   // $cartRuleInfo->is_restricted=$request->isRestricted;

                    $cartRuleInfo->status=$request->status;

                    $cartRuleInfo->updated_at=Carbon::now();

                    // dd($cartRuleInfo);

                    if($cartRuleInfo->save()){
                        
                        // $dataId=$dataInfo->id;

                    // $tableName='brands';

                    // $userId=1;

                    // $userType=1;

                    // $dataType=2;

                    // $comment='Brand Updated By ';
                    // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        if($request->isFreeProduct==1){
                            $flag=$this->storeFreeProducts($request,$cartRuleInfo->id);
                            if(!$flag)
                                $falseCount++;
                        }
                        if($request->isProductRequired==1){
                            $flag=$this->storeRequiredProducts($request,$cartRuleInfo->id);
                            if(!$flag)
                                $falseCount++;
                        }
                        if($request->isProductDiscount==1){
                            $flag=$this->storeDiscountProducts($request,$cartRuleInfo->id);
                            if(!$flag)
                                $falseCount++;
                        }

                        if($request->isRestricted==1){
                            $flag=$this->storeRestrictedCartInfo($request,$cartRuleInfo->id);
                            if(!$flag)
                                $falseCount++;
                        }

                        if($falseCount==0){

                            DB::commit();
                            
                            $responseData=[
                                    'errMsgFlag'=>false,
                                    'msgFlag'=>true,
                                    'msg'=>'Cart Rules Updated Successfully',
                                    'errMsg'=>null,
                                ];
                                // dd("okay");
                            return response()->json($responseData,200);
                        }
                        else{

                            DB::rollBack();
                            
                            $responseData=[
                                    'errMsgFlag'=>true,
                                    'msgFlag'=>false,
                                    'msg'=>null,
                                    'errMsg'=>'Failed To Update Cart Rules.',
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
                                    'errMsg'=>'Failed To Update Cart Rules.',
                                ];
                                
                            return response()->json($responseData,200);
                    }
                }
                else{
                      $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Data Not Found.',
                    ];

                     return response()->json($responseData,200);  
                }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"CartRulesController@updateCartRules");

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
    public function editCartRulesInfo(Request $request)
    {
        $dataInfo=CartRule::with('cartProductInfos','cartProductInfos.productInfo')
                                 ->where('id',$request->dataId)
                                        ->first();

        if (!empty($dataInfo)) {

           $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>null,
                            'errMsg'=>null,
                            'dataInfo'=>$dataInfo,
                        ];

            return response()->json($responseData,200);
        }
        else
        {
           $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Data Not Found.',
                        ];

            return response()->json($responseData,200); 
        }
    }
    public function deleteCartRules(Request $request)
    {
        DB::beginTransaction();

        try{

                $success=0;

                $fail=0;

                foreach ($request->get('dataIds') as $key => $value) {

                    
                    $dataInfo=CartRule::find($value);

                    $dataInfo->status=0;

                    $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save())
                    {
                        // $dataId=$dataInfo->id;

                    // $tableName='brands';

                    // $userId=1;

                    // $userType=1;

                    // $dataType=2;

                    // $comment='Brand Updated By ';
                    // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        $success++;
                    }
                    else
                    {
                        $fail++;
                    }
                    
                }

                DB::commit();

                $responseData=[
                            
                            'errMsgFlag'=>($fail>0) ? true:false,

                            'msgFlag'=>($success>0) ? true:false,
                            
                            'msg'=>$success." Data Deleted Successfully.",
                            
                            'errMsg'=>$fail." Failed To Delete.",
                ];

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"CartRulesController@deleteCartRules");
            
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
    public function changeCartRulesStatus(Request $request)
    {
        DB::beginTransaction();
        try{
            $dataInfo=CartRule::find($request->dataId);
            if (!empty($dataInfo)) {

                $dataInfo->status=$request->status;
                $dataInfo->updated_at=Carbon::now();
                if($dataInfo->save()){

                    // $dataId=$dataInfo->id;

                    // $tableName='brands';

                    // $userId=1;

                    // $userType=1;

                    // $dataType=2;

                    // $comment='Brand Updated By ';
                    // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                    DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Cart Rules Status Changed Successfully.',
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
                                    'errMsg'=>'Failed To Change Cart Rules Status.',
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
                                'errMsg'=>'Data Not Found.',
                            ];

                return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"CartRulesController@changeCartRulesStatus");

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
    
    public function getActiveCartRulesList(Request $request)
    {
        $dataList=CartRule::with('cartProductInfos')
                                ->where('status',1)
                                    ->get();

        return response()->json($dataList,200);     
    }

    public function getCartRulesList(Request $request)
    {
        $query=CartRule::with('cartProductInfos')
                                ->where('status','!=',0)
                                    ->latest();

         if(isset($request->searchKey) && !is_null($request->searchKey))
                $query->where('name','like',$request->searchKey.'%')->orWhere('details','like',$request->searchKey);

        $dataList=$query->paginate($request->numOfData);

        // return view('welcome');      
        return response()->json($dataList,200);     
    }
    public function storeFreeProducts($request,$ruleId)
    {
        $success=0;
        $fail=0;
        foreach ($request->get('freeProductIds') as $key => $value) {

            $freeProductInfo=new CartProduct();

            $freeProductInfo->rule_id=$ruleId;

            $freeProductInfo->product_id=$value;

            $freeProductInfo->quantity=$request->freeQuantity[$key];

            $freeProductInfo->apply_index=$request->freeApplyOn[$key];

            $freeProductInfo->discount=0;

            $freeProductInfo->type=3;

            $freeProductInfo->created_at=Carbon::now();

            if($freeProductInfo->save())
                $success++;
            else
                $fail++;
            
        }
        if($fail>0)
            return false;
        else
            return true;
    }
    public function storeDiscountProducts($request,$ruleId)
    {
        $success=0;

        $fail=0;

        foreach ($request->get('discountProductIds') as $key => $value) {

            $discountProductInfo=new CartProduct();

            $discountProductInfo->rule_id=$ruleId;

            $discountProductInfo->product_id=$value;

            $discountProductInfo->discount=$request->productDiscount[$key];

            $discountProductInfo->apply_index=$request->discountApplyOn[$key];

            $discountProductInfo->type=2;

            $discountProductInfo->created_at=Carbon::now();

            if($discountProductInfo->save())
                $success++;
            else
                $fail++;
            
        }
        if($fail>0)
            return false;
        else
            return true;
    }
    public function storeRequiredProducts($request,$ruleId)
    {
        $success=0;

        $fail=0;

        foreach ($request->get('requiredProductIds') as $key => $value) {


            $requiredProductInfo=new CartProduct();

            $requiredProductInfo->rule_id=$ruleId;

            $requiredProductInfo->product_id=$value;

            $requiredProductInfo->quantity=$request->requiredProductQuantity[$key];

            $requiredProductInfo->discount=0;

            $requiredProductInfo->type=1;

            $requiredProductInfo->created_at=Carbon::now();

            if($requiredProductInfo->save())
                $success++;
            else
                $fail++;
            
        }
        if($fail>0)
            return false;
        else
            return true;
    }
    public function storeRestrictedCartInfo($request,$cartId)
    {
       $success=0;

        $fail=0;

        foreach ($request->get('cartRuleIds') as $key => $value) {

            $restrictedCartInfo=new IgnoreCartRule();

            $restrictedCartInfo->cartRuleId=$cartId;

            $restrictedCartInfo->ignoreCartRuleId=$value;

            $restrictedCartInfo->created_at=Carbon::now();

            if($restrictedCartInfo->save())
                $success++;
            else
                $fail++;
            
        }
        if($fail>0)
            return false;
        else
            return true;
    }
    public function storeCartRules(Request $request)
    {
          // dd($request->all());
        DB::beginTransaction();
        try{

            $falseCount=0;

            $startAt= $request->startDate.' '.$request->startTime;

            $endAt= $request->endDate.' '.$request->endTime;

            $cartRuleInfo=new CartRule();

           

            $cartRuleInfo->name=$request->name;

            $cartRuleInfo->details=$request->details;

            $cartRuleInfo->isproduct_wise=$request->isProductWise;

            $cartRuleInfo->isprice_wise=$request->isPriceWise;

            $cartRuleInfo->isproduct_required=$request->isProductRequired;

            $cartRuleInfo->isproduct_discount=$request->isProductDiscount;

            $cartRuleInfo->isinvoice_discount=$request->isInvoiceDiscount;

            $cartRuleInfo->isfree_product=$request->isFreeProduct;

            $cartRuleInfo->rules_for=$request->rulesFor;

            $cartRuleInfo->total_discount=$request->invoiceDiscount;

            $cartRuleInfo->price_required=$request->priceRequired;

            $cartRuleInfo->start_at=$startAt;

            $cartRuleInfo->end_at=$endAt;

            // $cartRuleInfo->applyOnOther=$request->applyOnOther;

            // $cartRuleInfo->is_restricted=$request->isRestricted;

            $cartRuleInfo->status=$request->status;

            $cartRuleInfo->created_at=Carbon::now();

            if($cartRuleInfo->save()){
                
                // $dataId=$dataInfo->id;

                    // $tableName='brands';

                    // $userId=1;

                    // $userType=1;

                    // $dataType=2;

                    // $comment='Brand Updated By ';
                    // // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    // GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);
                
                    // // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                if($request->isFreeProduct==1){
                    $flag=$this->storeFreeProducts($request,$cartRuleInfo->id);
                    if(!$flag)
                        $falseCount++;
                }
                if($request->isProductRequired==1){
                    $flag=$this->storeRequiredProducts($request,$cartRuleInfo->id);
                    if(!$flag)
                        $falseCount++;
                }
                if($request->isProductDiscount==1){
                    $flag=$this->storeDiscountProducts($request,$cartRuleInfo->id);
                    if(!$flag)
                        $falseCount++;
                }
                // if($request->isRestricted==1){
                //     $flag=$this->storeRestrictedCartInfo($request,$cartRuleInfo->id);
                //     if(!$flag)
                //         $falseCount++;
                // }
                if($falseCount==0){

                    DB::commit();
                    
                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Cart Rules Added Successfully',
                            'errMsg'=>null,
                        ];
                        // dd("okay");
                    return response()->json($responseData,200);
                }
                else{

                    DB::rollBack();
                    
                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Cart Rules.',
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
                            'errMsg'=>'Failed To Add Cart Rules.',
                        ];
                        
                    return response()->json($responseData,200);
            }

        }
        catch(Exception $err){

            DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"CartRulesController@storeCartRules");

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
    public function voucherCodeIsExists(Request $request)
    {
       $voucherInfo=VoucherDisount::where('promoCode',$request->promoCode)
                                        ->where('status','!=',0)
                                            ->first();
        if (empty($voucherInfo)) {
            
            $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Voucher  Code Can Be Used.',
                    'errMsg'=>null,
                ];

            return response()->json($responseData,200);
        }
        else{
            
            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Voucher Code Already Exists.Please Try Another Voucher Code.',
                ];

            return response()->json($responseData,200);
        }
    } 

   public function storeVoucherCodeInfo(Request $request)
   {
        DB::beginTransaction();

        try{

            $startAt= $request->startDate.' '.$request->startTime;

            $endAt= $request->endDate.' '.$request->endTime;

            $voucherInfo=new VoucherDisount();

            $voucherInfo->name=$request->name;

            $voucherInfo->promoCode=$request->promoCode;

            $voucherInfo->canBeUsed=$request->canBeUsed;

            $voucherInfo->available=$request->available;

            $voucherInfo->availableFor=$request->availableFor;

            $voucherInfo->isPriceRequired=$request->isPriceRequired;

            $voucherInfo->priceRequired=$request->priceRequired;

            $voucherInfo->isDiscountInPercent=$request->isDiscountInPercent;

            $voucherInfo->discountAmount=$request->discountAmount;

            $voucherInfo->status=1;

            $voucherInfo->startAt=$startAt;

            $voucherInfo->endAt=$endAt;
            
            $voucherInfo->created_at=Carbon::now();

            if($voucherInfo->save()){

                    DB::commit();
            
                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Voucher Code Added Successfully.',
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
                            'errMsg'=>'Failed To Add Voucher Code.Please Try Again.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,'CartRulesController@storeVoucherCodeInfo');
            
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
   public function getVoucherCodeList(Request $request)
   {
        $query=VoucherDisount::where('status','!=',0)
                                ->latest();   
        if (isset($request->searchKey) && !is_null($request->searchKey)) 
            $query->where('promoCode','like',$request->searchKey.'%');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList,200);
   }  
   public function changeVoucherCodeStatus(Request $request)
   {
       $dataInfo=VoucherDisount::find($request->dataId);

       if(!empty($dataInfo)) {
         
         $dataInfo->status=$request->status;

         $dataInfo->updated_at=Carbon::now();   

         if ($dataInfo->save()) {
           
            $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>'Voucher Status Changed Successfully.',
                    'errMsg'=>null,
                ];

            return response()->json($responseData,200);
         }
         else{

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Change Voucher Status.',
                ];

            return response()->json($responseData,200);
         }
       }
       else{

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Requested Data Not Found.',
                ];

            return response()->json($responseData,200);
       }
   }
   public function editVoucherCodeInfo(Request $request)
   {
      $dataInfo=VoucherDisount::find($request->dataId);

      if (!empty($dataInfo)) {

          $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'dataInfo'=>$dataInfo,
                    'errMsg'=>null,
                ];

        return response()->json($responseData,200);
      }
      else{
             $responseData=[
                                'errMsgFlag'=>true,
                                'msgFlag'=>false,
                                'msg'=>null,
                                'errMsg'=>'Requested Data Not Found.',
                            ];

            return response()->json($responseData,200);
      }
   }
   public function updateVoucherCodeInfo(Request $request)
   {
       DB::beginTransaction();

        try{
            $voucherInfo=VoucherDisount::find($request->dataId);

            if(!empty($voucherInfo)){
            
                $startAt= $request->startDate.' '.$request->startTime;

                $endAt= $request->endDate.' '.$request->endTime;

                // $voucherInfo->promoCode=$request->promoCode;

                 $voucherInfo->name=$request->name;

                $voucherInfo->canBeUsed=$request->canBeUsed;

                $voucherInfo->available=$request->available;

                $voucherInfo->availableFor=$request->availableFor;

                $voucherInfo->isPriceRequired=$request->isPriceRequired;

                $voucherInfo->priceRequired=$request->priceRequired;

                $voucherInfo->isDiscountInPercent=$request->isDiscountInPercent;

                $voucherInfo->discountAmount=$request->discountAmount;

                // $voucherInfo->status=1;

                $voucherInfo->startAt=$startAt;

                $voucherInfo->endAt=$endAt;
                
                $voucherInfo->updated_at=Carbon::now();

                if($voucherInfo->save()){

                        DB::commit();
                
                        $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Voucher Info Updated Successfully.',
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
                                'errMsg'=>'Failed To Update Voucher Code Info.Please Try Again.',
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
                            'errMsg'=>'Requested Data Not Found',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,'CartRulesController@updateVoucherCodeInfo');
            
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
   public function deleteVoucherCodeInfo(Request $request)
   {
       DB::beginTransaction();

        try{

                $success=0;

                $fail=0;

                foreach ($request->get('dataIds') as $key => $value) {

                    
                    $dataInfo=VoucherDisount::find($value);

                    $dataInfo->status=0;

                    $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save())
                    {
                        $success++;
                    }
                    else
                    {
                        $fail++;
                    }
                    
                }

                DB::commit();

                $responseData=[
                            
                            'errMsgFlag'=>($fail>0) ? true:false,

                            'msgFlag'=>($success>0) ? true:false,
                            
                            'msg'=>$success." Data Deleted Successfully.",
                            
                            'errMsg'=>$fail." Failed To Delete.",
                ];

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"CartRulesController@deleteVoucherCodeInfo");
            
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
