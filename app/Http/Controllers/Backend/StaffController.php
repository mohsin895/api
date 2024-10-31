<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Exception;
use Auth;
use DB;
use Hash;
use Storage;


class StaffController extends Controller
{
    public function updateStaff(Request $request)
    {
       DB::beginTransaction();
        try{

            $dataInfo=Staff::find($request->dataId);

            if(!empty($dataInfo)){

                    // $password=substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0,6);

                    // $dataInfo=new Staff();

                    $dataInfo->name=$request->name;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                   $dataInfo->password=Hash::make($request->password);

                    // $dataInfo->social_id=null;

                    $dataInfo->address=$request->address;

                    $dataInfo->role=$request->role;

                    // $dataInfo->status=1;
                    if(isset($request->photo) && !is_null($request->file('photo')))
                    {      
               
                        $image=$request->file('photo');
                          
                            $imageName = $image->getClientOriginalName();
                               if (!Storage::disk('public')->exists('staff')) {
                                   Storage::disk('public')->makeDirectory('staff');
                               }
                              
                              
                           Storage::disk('public')->put('staff/', $image);
                           
                           if(!is_null($imageName)){
                              
                               $path ='/storage/app/public/staff/'.$image->hashName();
   
                
   
                               $dataInfo->avatar=$path;
                           }
                       }

                 

                    $dataInfo->updated_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='staffs';

                        $userId=1;

                        $userType=1;

                        $dataType=2;

                        $comment='Staff Updated By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Staff Information Updated Successfully.',
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
                                            'errMsg'=>'Failed To Update Staff Infomation.',
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
            
            GeneralController::storeSystemErrorLog($err,"Backends\StaffController@updateStaff");
            
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
    public function getStaffInfo(Request $request)
    {
       $dataInfo=Staff::find($request->dataId);

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

    public function addStaff(Request $request)
    {
    	DB::beginTransaction();
        try{

            $isStaffExist=Staff::whereNull('deleted_at');
            
            if(isset($request->phone) && !is_null($request->phone))
                $isStaffExist->where('phone',trim($request->phone));

            if(isset($request->email) && !is_null($request->email))
                $isStaffExist->where('email',trim($request->email));

            $isStaffExist=$isStaffExist->first();


            if(empty($isStaffExist)){

                    $password=substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0,6);

                    $dataInfo=new Staff();

                    $dataInfo->name=$request->name;

                    $dataInfo->email=(isset($request->email) && !is_null($request->email)) ? strtolower(trim($request->email)):trim($request->phone).'@loyel.com.bd';

                    $dataInfo->phone=trim($request->phone);

                    $dataInfo->password=Hash::make($request->password);

                    $dataInfo->social_id=null;

                    $dataInfo->address=$request->address;

                    $dataInfo->role=$request->role;

                    $dataInfo->status=1;

                     if(isset($request->photo) && !is_null($request->file('photo')))
                         {
                            $image=$request->file('photo');

                             // $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $image->getClientOriginalExtension();

                             $imageName = str_replace(' ', '_',$request->name). "-" . uniqid() . "." . $image->getClientOriginalExtension();

                            if (!Storage::disk('public')->exists('staff')) {
                                Storage::disk('public')->makeDirectory('staff');
                            }

                           // $note_img = Image::make($image)->resize(400, 400)->stream();

                            $note_img = Image::make($image)->stream();

                                Storage::disk('public')->makeDirectory('staff');
                            Storage::disk('public')->put('staff/' . $imageName, $note_img);

                            $path = '/storage/app/public/staff/'.$imageName;

                            $dataInfo->avatar=$path;
                         }
                         else
                            $dataInfo->avatar='/storage/app/public/staff/defaultUser.png';

                    $dataInfo->created_at=Carbon::now();

                    // $dataInfo->updated_at=Carbon::now();

                    if($dataInfo->save()){

                        $dataId=$dataInfo->id;

                        $tableName='staffs';

                        $userId=1;

                        $userType=1;

                        $dataType=1;

                        $comment='Staff Added By ';
                        // $comment=$dataInfo->id.'=>'.$dataInfo->title.' Banner Status Changed By '.Auth::guard('staff-api')->user()->name;

                        GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                        // GeneralController::storeEntryHistory($table,$dataId,$dataType,$userType,$userId,$optional);

                        DB::commit();

                        $responseData=[
                                        'errMsgFlag'=>false,
                                        'msgFlag'=>true,
                                        'msg'=>'Staff Information Added Successfully.',
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
                                            'errMsg'=>'Failed To Add Staff Infomation.',
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
                            'errMsg'=>'Staff Already Registered.',
                        ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

            DB::rollBack();
            
            GeneralController::storeSystemErrorLog($err,"Backends\StaffController@addStaff");
            
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
    public function getStaffList(Request $request)
    {
    	$query=Staff::whereNull('deleted_at');
        
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
    public function getActiveStaffList(Request $request)
    {
    	$dataList=Staff::where('status',1)->orderBy('title','asc')->get();

        return response()->json($dataList,200);
    }
    public function getActiveRoleList(Request $request)
    {
        $dataList=Role::orderBy('name','asc')->get();

        return response()->json($dataList,200);
    }
    public function changeStaffStatus(Request $request)
    {
    	DB::beginTransaction();

        try{
                $dataInfo=Staff::find($request->dataId);

                $dataInfo->status=$request->status;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save())
                {
                    $dataId=$dataInfo->id;

                    $tableName='staffs';

                    $userId=1;

                    $userType=1;

                    $dataType=2;

                    $comment=$dataInfo->id.'=>'.$dataInfo->title.' Staff Status Changed By ';
                    // $comment=$dataInfo->id.'=>'.$dataInfo->name.' Color Status Changed By '.Auth::guard('staff-api')->user()->name;

                    GeneralController::storeSystemLog($tableName,$dataId,$comment,$userId,$userType,$dataType);

                    DB::commit();

                    $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>'Staff Status Changed Successfully.',
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
                            'errMsg'=>'Failed To Change Staff Status.'
                        ];
                }

            return response()->json($responseData,200);
        }
        catch(Exception $err)
        {
            DB::rollBack();
            
            GeneralController::storeSystemErrorLog("Backends\StaffController@changeStaffStatus",$err);
            
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
    public function deleteStaff(Request $request)
    {
    	$dataInfo = Staff::find($request->dataId);
        $dataInfo->delete();
        if($dataInfo){
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Staff Delete Successfully .',

            ];

        }else{
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Failed To delete Role, Try Again.'
            ];

        }
        return response()->json($responseData,200);
    }

    public function getPermissionList(Request $request)
    {
        $permissionList=Permission::where('status',1)->where('roleId',Auth::guard('staff-api')->user()->role)->pluck('permissionCode')->toArray();

        $permissionList=array_map('intval', $permissionList);

        return response()->json($permissionList,200);
    }

    public function getAuthStaffList(Request $request)
    {
        $staffList=Staff::where('status',1)->where('id',Auth::guard('staff-api')->user()->id)->first();

        return response()->json($staffList,200);
    }

    public function passwordChange(Request $request)
	{
		$dataInfo=Staff::find(Auth::guard('staff-api')->user()->id);

		if(!empty($dataInfo)) {

			if(Hash::check($request->oldPassword,$dataInfo->password)){

				if($request->newPassword==$request->conPassword){

					$dataInfo->password=Hash::make($request->newPassword);

					$dataInfo->updated_at=Carbon::now();

					if($dataInfo->save()){
						$responseData=[
		                    'errMsgFlag'=>false,
		                    'msgFlag'=>true,
		                    'msg'=>'Password Changed Successfully.',
		                    'errMsg'=>null,
		                ];
					}
					else{
						$responseData=[
		                    'errMsgFlag'=>true,
		                    'msgFlag'=>false,
		                    'msg'=>null,
		                    'errMsg'=>"Failed To Change Password.Please Try Again.",
		                ];
					}
				}
				else{
					$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>"Confirm Password Doesn't Match",
	                ];
				}
			}
			else{
				$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>"Old Password Doesn't Match.",
	                ];
			}
		}
		else{
			$responseData=[
	                    'errMsgFlag'=>true,
	                    'msgFlag'=>false,
	                    'msg'=>null,
	                    'errMsg'=>'Requested User Information Not Found.',
	                ];
		}

		return response()->json($responseData,200);
	}
    public function getStaffInfoEdit(Request $request)
    {
       $dataInfo=Staff::find($request->dataId);

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

    public function updateAuthStaffInfo(Request $request)
    {
        DB::beginTransaction();
        try{

            $dataInfo=Staff::find(Auth::guard('staff-api')->user()->id);

            if(!empty($dataInfo)) {
                if(isset($request->photo) && !is_null($request->file('photo')))
                {      
                   
                    $image=$request->file('photo');
                      
                        $imageName = $image->getClientOriginalName();
                           if (!Storage::disk('public')->exists('profile')) {
                               Storage::disk('public')->makeDirectory('profile');
                           }
                          
                          
                       Storage::disk('public')->put('profile/', $image);
                       
                       if(!is_null($imageName)){
                          
                           $path ='/storage/app/public/profile/'.$image->hashName();
   
                           $dataInfo->avatar=$path;
                       }
                   }
              
                $dataInfo->name=$request->name;
                $dataInfo->email=$request->email;
                $dataInfo->address=$request->address;
                $dataInfo->phone=$request->phone;


                // $dataInfo->status=1;

                $dataInfo->updated_at=Carbon::now();

                if($dataInfo->save()){

                    DB::commit();

                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>'Profile Updated Successfully.',
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
                        'errMsg'=>'Failed To Update Profile.Please Try Again.',
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
                        'errMsg'=>'Requested Data No Found.',
                     ];

                    return response()->json($responseData,200);
            }
        }
        catch(Exception $err){

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
}
