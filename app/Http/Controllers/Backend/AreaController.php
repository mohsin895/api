<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\District;
use App\Models\Country;
use App\Models\Thana;
use App\Models\Union;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function updateUnion(Request $request)
    {
       DB::beginTransaction();
       try{

            $dataInfo=Union::find($request->dataId);

            if(!empty($dataInfo)) {

                // $dataInfo=new Union();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->thana_id=$request->thana;

                $dataInfo->url=$request->website;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='unions';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Union Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Updated Union Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Update Union Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
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

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@updateUnion");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Update Union Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function updateThana(Request $request)
    {
       DB::beginTransaction();
       try{

            $dataInfo=Thana::find($request->dataId);

            if(!empty($dataInfo)) {

                // $dataInfo=new Thana();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->district_id=$request->district;

                $dataInfo->url=$request->website;

                $dataInfo->inter_city=$request->interCity;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='thanas';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='Thana Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Updated Thana Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Update Thana Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
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

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@updateThana");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Update  Thana Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function updateDistrict(Request $request)
    {
      DB::beginTransaction();
       try{

            $dataInfo=District::find($request->dataId);

            if(!empty($dataInfo)) {

                // $dataInfo=new District();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->division_id=$request->division;

                $dataInfo->lat=$request->latitude;

                $dataInfo->lon=$request->longitude;

                $dataInfo->url=$request->website;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='districts';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment='District Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Updated District Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Update District Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
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

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@updateDistrict");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Update District Information.',
            ];

            return response()->json($responseData,200); 
       } 
    }
    public function updateDivision(Request $request)
    {
       DB::beginTransaction();
       try{

            $dataInfo=Division::find($request->dataId);

            if(!empty($dataInfo)) {

                // $dataInfo=new Division();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='divisions';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Division Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Updated Division Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Division Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
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

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@updateDivision");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Update Division Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function updateCountry(Request $request)
    {
       DB::beginTransaction();
       try{

            $dataInfo=Country::find($request->dataId);

            if(!empty($dataInfo)) {

                // $dataInfo=new Division();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='countries';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Country Updated By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Updated Country Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Country Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
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

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@updateCountry");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Update Country Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function getUnionInfo(Request $request)
    {
       $dataInfo=Union::find($request->dataId);

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

    public function getThanaInfo(Request $request)
    {
       $dataInfo=Thana::find($request->dataId);

       if(!empty($dataInfo)) {
          $responseData=[
                'errMsgFlag'=>false,
                'msgFlag'=>true,
                'errMsg'=>'Requested Data Not Found.',
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

    public function getDistrictInfo(Request $request)
    {
       $dataInfo=District::find($request->dataId);

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

    public function getDivisionInfo(Request $request)
    {
       $dataInfo=Division::find($request->dataId);

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

    public function getCountryInfo(Request $request)
    {
       $dataInfo=Country::find($request->dataId);

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


    public function addUnion(Request $request)
    {
    	DB::beginTransaction();
       try{

            $isAlreadyExist=Union::where('name',trim($request->name))
                                            ->where('status','!=',0)
                                                 ->where('thana_id',$request->thana)
                                                        ->first();

            if(empty($isAlreadyExist)) {

                $dataInfo=new Union();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->thana_id=$request->thana;

                $dataInfo->url=$request->website;

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='unions';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Union Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Added Union Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Union Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
              $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Union Already Exists.',
                    ];

             return response()->json($responseData,200); 

            }
        }
        catch(Exception $err){

           DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@addUnion");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Added Union Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function getUnionList(Request $request)
    {
    	$query=Union::with('thanaInfo','thanaInfo.districtInfo','thanaInfo.districtInfo.divisionInfo')             ->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%')
                        ->orWhere('name_bd','like',$request->name.'%');
            });
        }
        
        $query->orderBy('name','asc');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getActiveUnionList(Request $request)
    {
    	$dataList=Union::where('status',1)->orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeUnionStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Union::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='unions';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Union Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Union Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Union Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Union Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
           DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\AreaController@changeUnionStatus",$err);
            
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
    public function deleteUnion(Request $request)
    {
        $dataInfo= Union::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Union Delete Successfully.',
            ];

        }else{
          $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Union Delete Successfully.',
          ];  

        }
        return response()->json($responseData,200);
    	
    }
    public function addThana(Request $request)
    {
    	DB::beginTransaction();
       try{

            $isAlreadyExist=Thana::where('name',trim($request->name))
                                            ->where('status','!=',0)
                                                 ->where('district_id',$request->district)
                                                        ->first();

            if(empty($isAlreadyExist)) {

                $dataInfo=new Thana();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->district_id=$request->district;

                $dataInfo->url=$request->website;

                $dataInfo->inter_city=$request->interCity;

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='thanas';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Thana Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Added Thana Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Thana Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
              $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Thana Already Exists.',
                    ];

             return response()->json($responseData,200); 

            }
        }
        catch(Exception $err){

           DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@addThana");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Added  Thana Information.',
            ];

            return response()->json($responseData,200); 
       }     
    }
    public function getThanaList(Request $request)
    {

    	$query=Thana::with('districtInfo','districtInfo.divisionInfo')->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%')
                        ->orWhere('name_bd','like',$request->name.'%');
            });
        }

        $query->orderBy('name','asc');

        $dataList=$query->paginate($request->numOfData);


        return response()->json($dataList);
    }
    public function getThanaListPC(Request $request)
    {
            $dataList=Thana::whereNull('deleted_at')
                                    ->where('status',1)
                                        ->orderBy('name','asc')
                                            ->get();
                                        
            return response()->json($dataList,200);
    }
    public function getActiveThanaList(Request $request)
    {
    	$dataList=Thana::where('status',1)->orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeThanaStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Thana::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='thanas';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Thana Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Thana Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Thana Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Thana Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
           DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\AreaController@changeThanaStatus",$err);
            
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
    public function deleteThana(Request $request)
    {
    	$dataInfo=Thana::find($request->dataId);
        $dataInfo->delete();
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'District Delete successfully',
            ];
        }else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Something wrong, please try again.'
            ];
        }
        return response()->json($responseData,200);

    }
    public function addDistrict(Request $request)
    {
    	DB::beginTransaction();
       try{

            $isAlreadyExist=District::where('name',trim($request->name))
                                            ->where('status','!=',0)
                                                 ->where('division_id',$request->division)
                                                        ->first();

            if(empty($isAlreadyExist)) {

                $dataInfo=new District();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->division_id=$request->division;

                $dataInfo->lat=$request->latitude;

                $dataInfo->lon=$request->longitude;

                $dataInfo->url=$request->website;

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='districts';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='District Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Added District Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add District Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
              $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'District Already Exists.',
                    ];

             return response()->json($responseData,200); 

            }
        }
        catch(Exception $err){

           DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@addDistrict");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Added District Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function getDistrictList(Request $request)
    {
    	$query=District::with('divisionInfo')->whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%')
                        ->orWhere('name_bd','like',$request->name.'%');
            });
        }

        $query->orderBy('name','asc');

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getActiveDistrictList(Request $request)
    {
    	$dataList=District::where('status',1)->orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeDistrictStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=District::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='districts';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' District Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' District Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'District Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change District Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
           DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\AreaController@changeDistrictStatus",$err);
            
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
    public function deleteDistrict(Request $request)
    {
    	$dataInfo= District::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'District Delete successfully',
            ];
        }else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Something wrong, please try again.'
            ];
        }
        return response()->json($responseData,200);
    }
    public function addDivision(Request $request)
    {
    	DB::beginTransaction();
       try{

            $isAlreadyExist=Division::where('name',trim($request->name))
                                            ->where('status','!=',0)
                                                        ->first();

            if(empty($isAlreadyExist)) {

                $dataInfo=new Division();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='divisions';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Division Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Added Division Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Division Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
              $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Division Already Exists.',
                    ];

             return response()->json($responseData,200); 

            }
        }
        catch(Exception $err){

           DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@addDivision");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Added Division Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function getDivisionList(Request $request)
    {
    	$query=Division::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%')
                        ->orWhere('name_bd','like',$request->name.'%');
            });
        }

        $query->orderBy('name','asc');
        
        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getActiveDivisionList(Request $request)
    {
    	$dataList=Division::where('status',1)->orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeDivisionStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Division::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='divisions';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Division Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Division Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Division Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Division Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
           DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\AreaController@changeDivisionStatus",$err);
            
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
    public function deleteDivision(Request $request)
    {
    	$deleteDivision = Division::find($request->dataId);
        $deleteDivision->delete();
        if($deleteDivision){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Division Delete Successfully.',
                
        ];
        }else{
            
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete Division.Please Try Again.',
           ];
        }
        return response()->json($responseData,200);

    }

    public function addCountry(Request $request)
    {
    	DB::beginTransaction();
       try{

            $isAlreadyExist=Country::where('name',trim($request->name))
                                            ->where('status','!=',0)
                                                        ->first();

            if(empty($isAlreadyExist)) {

                $dataInfo=new Country();

                $dataInfo->name=$request->name;

                $dataInfo->bn_name=$request->bn_name;

                $dataInfo->status=1;

                $dataInfo->created_at=Carbon::now();

                if($dataInfo->save()){
                    
                    $dataId=$dataInfo->id;

                    $tableName='countries';

                    $userId=1;

                    $userType=1;

                    $dataType=1;

                    $comment='Country Added By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                   DB::commit();

                    $responseData=[
                                'errMsgFlag'=>false,
                                'msgFlag'=>true,
                                'msg'=>'Successfully Added Country Information.',
                                'errMsg'=>null,
                            ];

                     return response()->json($responseData,200); 

                }
                else{

                    $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Country Information.',
                        ];

                    return response()->json($responseData,200); 
                }
            }
            else
            {
              $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Country Already Exists.',
                    ];

             return response()->json($responseData,200); 

            }
        }
        catch(Exception $err){

           DB::rollBack();

            GeneralController::storeSystemErrorLog($err,"Backend\AreaController@addCountry");

           DB::commit();

            $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Failed To Added Country Information.',
            ];

            return response()->json($responseData,200); 
       }
    }
    public function getCountryList(Request $request)
    {
    	$query=Country::whereNull('deleted_at');
        
        if(isset($request->status) && !is_null($request->status))
            $query->where('status',$request->status);

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%')
                        ->orWhere('name_bd','like',$request->name.'%');
            });
        }

        $query->orderBy('name','asc');
        
        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function getActiveCountryList(Request $request)
    {
    	$dataList=Country::where('status',1)->orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeCountryStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Country::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='countrys';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Country Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Division Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                   DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Country Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Country Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
           DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\AreaController@changeCountryStatus",$err);
            
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
    public function deleteCountry(Request $request)
    {
    	$deleteDivision = Country::find($request->dataId);
        $deleteDivision->delete();
        if($deleteDivision){
            $responseData=[
                'errMsgFlag'=>true,
               'msgFlag'=>false,
               'msg'=>null,
               'errMsg'=>'Country Delete Successfully.',
                
        ];
        }else{
            
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To Delete Country.Please Try Again.',
           ];
        }
        return response()->json($responseData,200);

    }
}
