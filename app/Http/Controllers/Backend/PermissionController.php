<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Exception;
use DB;

class PermissionController extends Controller
{
    public function addOrRemovePermission(Request $request)
    {
        DB::beginTransaction();
        try{
            $isPermissionExist=Permission::with('roleInfo')
                                            ->where('roleId',$request->roleId)
                                                ->where('permissionCode',$request->permissionCode)
                                                    ->first();

            if(!empty($isPermissionExist)){

                $preStatus=$isPermissionExist->status;

                $isPermissionExist->status=($preStatus==1) ? 0:1;

                $isPermissionExist->updated_at=Carbon::now();

                if($isPermissionExist->save()){
                    
                    DB::commit();

                    $msg=($preStatus==1) ? 'Revoked.':'Invoked.';
                    $errMsgFlag=($preStatus==1) ? true:false;
                    $msgFlag=($preStatus==1) ? false:true;

                    $msg=$request->permissionName." Successfully ".$msg;

                    $responseData=[

                            'errMsgFlag'=>$errMsgFlag,
                            'msgFlag'=>$msgFlag,
                            'msg'=>$msg,
                            'errMsg'=>$msg
                        ];

                    return  response()->json($responseData,200);
                }
                else{

                    DB::rollBack();
                    
                    $errMsg=($preStatus==1) ? 'Revoked.':'Invoked.';

                    $errMsg=$request->permissionName." Failed To  ".$errMsg;

                    $responseData=[

                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>$errMsg,
                        ];

                    return  response()->json($responseData,200);
                }
            }
            else{

                $roleInfo=Role::find($request->roleId);

                $rolePermission=new Permission();

                $rolePermission->roleId=$request->roleId;

                $rolePermission->permissionName=$request->permissionName;


                $rolePermission->permissionCode=$request->permissionCode;

                $rolePermission->status=1;

                $rolePermission->created_at=Carbon::now();

                if($rolePermission->save()){

                    DB::commit();

                    $responseData=[

                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>$request->permissionName." Invoked Successfully.",
                            'errMsg'=>null,
                        ];

                    return  response()->json($responseData,200);
                }
                else{

                    DB::rollBack();
                    
                    $responseData=[

                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>$request->permissionName."Failed To Invoked.",
                        ];

                    return  response()->json($responseData,200);
                }
            }

        }
        catch(Exception $err){

            DB::rollBack();
            
            $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Something Went Wrong.Please Try Again.',
            ];

            return response()->json($responseData,200);
        }
    }
    
    public function getRolePermissionList(Request $request)
    {
        $permissionList=Permission::where('roleId',$request->dataId)
                                    ->where('status',1)
                                        ->pluck('permissionCode')
                                            ->toArray();

        $responseData=[
                    'errMsgFlag'=>false,
                    'msgFlag'=>true,
                    'msg'=>null,
                    'errMsg'=>null,
                    'dataList'=>array_map('intval',$permissionList),
                    'roleId'=>$request->dataId,
                ];

        return response()->json($responseData,200);
    }
    public function getRoleWisePermissionList(Request $request)
    {
        $roleName=Role::find($request->dataId);

        $permissionList=Permission::whereNull('deleted_at')
                                        ->where('status',1)
                                            ->where('roleId',$request->dataId)
                                            ->pluck('permissionCode')
                                                ->toArray();

        $responseData=[
            'errMsgFlag'=>(!empty($roleName && $permissionList)) ? false:true,
            'msgFlag'=>(!empty($roleName && $permissionList)) ? true:false,
            'errMsg'=>(!empty($roleName && $permissionList)) ? '':'আপনার অনুরোধ করা তথ্য পাওয়া যায় নি।',
            'msg'=>(!empty($roleName && $permissionList)) ? '':'',
            'roleName'=>(!empty($roleName)) ? $roleName->roleNameBn:'',
            'permissionList'=>(!empty($permissionList)) ? array_map('intval',$permissionList):[],
            'roleId'=>(!empty($roleName)) ? $roleName->id:'',
        ];

        return response()->json($responseData,200);
    }
}
