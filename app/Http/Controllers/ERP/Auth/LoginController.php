<?php

namespace App\Http\Controllers\ERP\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){
       

            if(is_numeric($request->userName))
            $employeeData =['phone'=>$request->userName,'password'=>$request->password];
        else
           $employeeData=['email'=>$request->userName,'password'=>$request->password];
    
           $staffInfo = Staff::where('email',$request->userName)
           ->orWhere('phone', strtolower(trim($request->userName)))
           ->where('status', '!=',0)
           ->first();
           $employeeInfo = Employee::where('email',$request->userName)
           ->orWhere('phone', strtolower(trim($request->userName)))
           ->where('status', '!=',0)
           ->first();
           if($request->dataId == 1){
            if(!empty(($employeeInfo))){
                if($employeeInfo->status == 'active'){
                   if(Hash::check($request->password,$employeeInfo->password)){
                       if(Auth::guard('employee')->attempt($employeeData)){
   
                           $token = $employeeInfo->createToken(uniqid())->accessToken;
       
   
                        return response()->json([
                            'status' => true,
                            'token_type' => 'bearer',
                            'token' => $token,
                            'message' => 'Login successfully',
                            
                        ]);
                          
                          }
                   }else{
                       $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'UserName or Password is wrong. Please try again !'
   
                       ];
                      
                   }
   
                }else{
                   
                   $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Faild To Login'
   
                   ];
                   
                }
       
              }else{
            
               $responseData=[
                   'errMsgFlag'=>true,
                   'msgFlag'=>false,
                   'msg'=>null,
                   'errMsg'=>'Ussrname or Password Wrong . Please try again.',
               ];
              
              }

           }else{
            if(!empty(($staffInfo))){
                if($staffInfo->status ==1){
                   if(Hash::check($request->password,$staffInfo->password)){
                       if(Auth::guard('staff')->attempt($employeeData)){
   
                           $token = $staffInfo->createToken(uniqid())->accessToken;
       
   
                        return response()->json([
                            'status' => true,
                            'token_type' => 'bearer',
                            'token' => $token,
                            'message' => 'Login successfully',
                            'staffInfo' => $this->getLoginSatffInfo(),
                        ]);
                          
                          }
                   }else{
                       $responseData=[
                           'errMsgFlag'=>true,
                           'msgFlag'=>false,
                           'msg'=>null,
                           'errMsg'=>'UserName or Password is wrong. Please try again !'
   
                       ];
                      
                   }
   
                }else{
                   
                   $responseData=[
                       'errMsgFlag'=>true,
                       'msgFlag'=>false,
                       'msg'=>null,
                       'errMsg'=>'Faild To Login'
   
                   ];
                   
                }
       
              }else{
            
               $responseData=[
                   'errMsgFlag'=>true,
                   'msgFlag'=>false,
                   'msg'=>null,
                   'errMsg'=>'Ussrname or Password Wrong . Please try again.',
               ];
              
              }
           }
         
           return response()->json($responseData, 200);
       
      
    }


    public function getLoginEmployeeInfo(){
        $employeeInfo = Employee::where('id', Auth::guard('employee-api')->user()->id);
         
        $responseData =[
            'id'=> $employeeInfo->id,
            'employeeId' => $employeeInfo->employeeID,
            'full_name' => $employeeInfo->full_name,
            'email' => $employeeInfo->email,
            'phone' => $employeeInfo->mobile_number,

        ];
        return $responseData;
    }

     public function getLoginSatffInfo(){
         $staffInfo = Staff::find(Auth::guard('staff')->user()->id);
         $responseData =[
            'id' => $staffInfo->id,
            'email' => $staffInfo->email,
            'phone' => $staffInfo->phone,
            'name' => $staffInfo->name,
         ];

         return $responseData;
     }

     public function getLoginSatffInfoUpdate($dataId){
        $staffInfo = Staff::find($dataId);
        $responseData =[
           'id' => $staffInfo->id,
           'email' => $staffInfo->email,
           'phone' => $staffInfo->phone,
           'name' => $staffInfo->name,
        ];

        return $responseData;
    }

    public function updatePassword(Request $request){
        $dataInfo=Employee::find(Auth::guard('employee-api')->user()->id);

		if(!empty($dataInfo)) {

			

				if($request->password==$request->conPassword){

					$dataInfo->password=Hash::make($request->password);

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
	                    'errMsg'=>'Requested User Information Not Found.',
	                ];
		}

		return response()->json($responseData,200);
    }

    public function updateEmail(Request $request){
        $staffInfoCount = Staff::where('email', $request->email)->count();
        if($staffInfoCount > 0){
            $responseData = [
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Email Already Exists.',
            ];
            return response()->json($responseData, 202);
        }else{
            $updateEmail = Staff::where('id', $request->dataId)->first();
            $updateEmail->email = $request->email;
            $updateEmail->save();
            $responseData = [
                'errMsgFlag'=> false,
                'msgFlag'=> true,
                'msg'=> 'Update email Successfully',
                'errMsg'=> null,
                'staffInfo' => $this->getLoginSatffInfoUpdate($request->dataId)
               
            ];
            return response()->json($responseData, 200);
        }
    }


    public function passwordChange(Request $request)
	{
		$dataInfo=Staff::find(Auth::guard('staff-api')->user()->id);

		if(!empty($dataInfo)) {

		
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
	                    'errMsg'=>'Requested User Information Not Found.',
	                ];
		}

		return response()->json($responseData,200);
	}
}
