<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;

use App\Models\Branch;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeBankDetails;
use App\Models\EmployeeDocument;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\Storage;
use Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
{
    // Get perPage value from the request
    $perPage = $request->perPage;

    // Start building the query
    $query = Employee::with('attendance', 'getDesignation', 'salaries')
        ->whereNull('deleted_at');

    // Check if a search term is provided
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;

        // Apply search filters
        $query->where(function($q) use ($search) {
            $q->where('employeeID', 'LIKE', "%$search%")
              ->orWhere('full_name', 'LIKE', "%$search%");
        });
    }

    // If perPage is 'all', get all records; otherwise, paginate
    if ($perPage === 'all') {
        $dataList = $query->get();
    } else {
        $dataList = $query->paginate($perPage);
    }

    return response()->json($dataList);
}

    
    public function getDepartment(){
        $dataList = Department::get();
        return response()->json($dataList, 200);
    }
    public function getBranch(){
        $dataList = Branch::get();
        return response()->json($dataList, 200);
    }
    public function getDesignation(Request $request, $id=null){
        $dataList = Designation::where('department_id',$id)->get();
        return response()->json($dataList, 200);
    }

    public function dataInfoAddOrUpdate (Request $request){
        try{
            DB::beginTransaction();

         $dataInfo = Employee::find($request->dataId);
         if(empty($dataInfo)){
            $dataInfo =new Employee();
            $dataInfo->full_name = $request->full_name;
            $dataInfo->employeeID = $request->employeeID;
            $dataInfo->email = $request->email;
            $dataInfo->date_of_birth = $request->dob;
            $dataInfo->gender = $request->gender;
            $dataInfo->password = Hash::make($request->password);
            $dataInfo->father_name = $request->father_name;
            $dataInfo->mobile_number = $request->mobile_number;
            $dataInfo->local_address = $request->local_address;
            $dataInfo->permanent_address = $request->permanent_address;
            $dataInfo->joining_date = $request->joining_date;
            $dataInfo->exit_date =$request->exit_date;
            $dataInfo->designations = $request->designations;
            if(isset($request->profileImage) && !is_null($request->file('profileImage')))
            {      
               
                $image=$request->file('profileImage');
                  
                    $imageName = $image->getClientOriginalName();
                       if (!Storage::disk('public')->exists('hrm')) {
                           Storage::disk('public')->makeDirectory('hrm');
                       }
                      
                      
                   Storage::disk('public')->put('hrm/', $image);
                   
                   if(!is_null($imageName)){
                      
                       $path ='/storage/app/public/hrm/'.$image->hashName();

                       $dataInfo->profile_image=$path;
                   }
               }

             
            $dataInfo->save();
            if($dataInfo->save()){
         
                    // UPLOAD THE DOCUMENTS  -----------------
                    $documents = ['resume', 'offerLetter', 'joiningLetter', 'agreement', 'idProof'];

                    foreach ($documents as $document) {
                        if ($request->hasFile($document)) {
                            // Get the file from the request
                            $file = $request->file($document);
                            $imageName = $file->getClientOriginalName();
                            
                           
                            if (!Storage::disk('public')->exists('hrm')) {
                                Storage::disk('public')->makeDirectory('hrm');
                            }
                       
                                Storage::disk('public')->put('hrm/', $image);
                                
                                if(!is_null($imageName)){
                                    
                                    $path ='/storage/app/public/hrm/'.$image->hashName();

                                    $filename=$path;
                                }
                          
                    
                            // Store or update the document record
                            $employeeDocument = EmployeeDocument::firstOrNew([
                                'employee_id' => $dataInfo->id,
                                'type' => $document
                            ]);
                            
                            $employeeDocument->filename = $filename;
                            $employeeDocument->type = $document;
                            $employeeDocument->save();
                        }
                    }
                    
            
        
                
                $this->insertBasciSalary($request,$dataInfo) && $this->insertHourlyRate($request,$dataInfo)
                && $this->InsertBankDetails($request,$dataInfo);
                DB::commit();
                    return ApiResponseHelper::formatSuccessResponseInsert();
            }else{

                DB::commit();
                return ApiResponseHelper::formatFailedResponseInsert();
            }
           

         }else{
               $dataInfo = Employee::find($request->dataId);
               $dataInfo->full_name = $request->full_name;
                $dataInfo->employeeID = $request->employeeID;
                $dataInfo->email = $request->email;
                $dataInfo->date_of_birth = $request->dob;
                $dataInfo->password = Hash::make($request->password);
                $dataInfo->father_name = $request->father_name;
                $dataInfo->gender = $request->gender;
                $dataInfo->mobile_number = $request->mobile_number;
                $dataInfo->local_address = $request->local_address;
                $dataInfo->permanent_address = $request->permanent_address;
                $dataInfo->joining_date = $request->joining_date;
                $dataInfo->designations = $request->designations;
                $dataInfo->exit_date = $request->exit_date ? $request->exit_date : null;

               if(isset($request->profileImage) && !is_null($request->file('profileImage')))
               {      
                  
                   $image=$request->file('profileImage');
                     
                       $imageName = $image->getClientOriginalName();
                          if (!Storage::disk('public')->exists('hrm')) {
                              Storage::disk('public')->makeDirectory('hrm');
                          }
                         
                         
                      Storage::disk('public')->put('hrm/', $image);
                      
                      if(!is_null($imageName)){
                         
                          $path ='/storage/app/public/hrm/'.$image->hashName();
   
                          $dataInfo->profile_image=$path;
                      }
                  }
   
               $dataInfo->save();
               if($dataInfo->save()){
                $documents = ['resume', 'offerLetter', 'joiningLetter', 'agreement', 'idProof'];

                foreach ($documents as $document) {
                    if ($request->hasFile($document)) {
                        // Get the file from the request
                        $file = $request->file($document);
                        $imageName = $file->getClientOriginalName();
                        
                       
                        if (!Storage::disk('public')->exists('hrm')) {
                            Storage::disk('public')->makeDirectory('hrm');
                        }
                   
                            Storage::disk('public')->put('hrm/', $image);
                            
                            if(!is_null($imageName)){
                                
                                $path ='/storage/app/public/hrm/'.$image->hashName();

                                $filename=$path;
                            }
                      
                
                        // Store or update the document record
                        $employeeDocument = EmployeeDocument::firstOrNew([
                            'employee_id' => $dataInfo->id,
                            'type' => $document
                        ]);
                        
                        $employeeDocument->filename = $filename;
                        $employeeDocument->type = $document;
                        $employeeDocument->save();
                    }
                }

                
                $this->InsertBankDetails($request,$dataInfo) && $this->updateSalaryDetails($request,$dataInfo);
               
                DB::commit();
                return ApiResponseHelper::formatSuccessResponseUpdate();
               }else{
   
                DB::commit();
                return ApiResponseHelper::formatFailedResponseUpdate();
               }
              
   
         }
     
         
           

      
        }catch(\Exception $err){
            DB::rollBack();
            return ApiResponseHelper::formatErrorResponse($err);
        }
    }

    public function getEmployeeInfo(Request $request)
    {
       $dataInfo=Employee::with('attendance','getDesignation','salaries','bankDetails')->find($request->dataId);

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
    
    public function dataInfoDelete(Request $request){
        try{
            DB::beginTransaction();
    
            $dataInfo = Employee::find($request->dataId);
            $dataInfo->deleted_at = Carbon::now();
    
            if(empty($dataInfo)){
                return ApiResponseHelper::formatDataNotFound();
            }
    
    
            if($dataInfo->save()){
                DB::commit();
                return ApiResponseHelper::formatSuccessResponseDelete();
            } else {
                DB::rollBack();
                return ApiResponseHelper::formatFailedResponseDelete();   
                 }
        } catch(\Exception $err){
         
            DB::rollBack();
            return ApiResponseHelper::formatErrorResponse($err);
        }
    }
    public function salaryDelete(Request $request){
        try{
            DB::beginTransaction();
    
            $dataInfo = Salary::find($request->dataId);
    
            if(empty($dataInfo)){
                return ApiResponseHelper::formatDataNotFound();
            }
    
    
            if($dataInfo->delete()){
                DB::commit();
                return ApiResponseHelper::formatSuccessResponseDelete();
            } else {
                DB::rollBack();
                return ApiResponseHelper::formatFailedResponseDelete();   
                 }
        } catch(\Exception $err){
         
            DB::rollBack();
            return ApiResponseHelper::formatErrorResponse($err);
        }
    }
    public function insertBasciSalary($request,$dataInfo){
        $salary = ($request->basicSalary != '') ? $request->basicSalary : 0;
        $salaryInfo = new Salary();
        $salaryInfo->employee_id = $dataInfo->id;
        $salaryInfo->salary=$salary;
        $salaryInfo->type='basic';
        $salaryInfo->remarks ='basicSalary';
        $salaryInfo->save();
        return true;
    }
    public function insertHourlyRate($request,$dataInfo){
        $salary = ($request->hourlyRate != '') ? $request->hourlyRate : 0;
        $salaryInfo = new Salary();
        $salaryInfo->employee_id = $dataInfo->id;
        $salaryInfo->salary=$salary;
        $salaryInfo->type='hourly_rate';
        $salaryInfo->remarks ='Hourly Rate';
        $salaryInfo->save();
        return true;
    }
    public function InsertBankDetails($request,$dataInfo){
       $bankDetail = EmployeeBankDetails::where('employee_id',$dataInfo->id)->first();
       if($bankDetail){
        $bankDetail->delete();
       }
        $bankInfo = new EmployeeBankDetails();
        $bankInfo->employee_id = $dataInfo->id;
        $bankInfo->account_name=$request->account_name;
        $bankInfo->account_number=$request->account_number;
        $bankInfo->bank =$request->bank;
        $bankInfo->bin=$request->bin;
        $bankInfo->branch=$request->branch;
        $bankInfo->ifsc =$request->tax_payer_id;
        $bankInfo->save();

        return true;
    }

    public function updateSalaryDetails($request, $dataInfo){
     $countSalary = Salary::where('employee_id',$dataInfo->id)->count();
     if($countSalary >0){
        $deleteSalary = Salary::where('employee_id',$dataInfo->id)->get();
        foreach($deleteSalary as $salary){
            $salaryInfo = Salary::find($salary->id);
            $salaryInfo->delete();
        }
     }
     if ($request->has('salaries')) {
        foreach ($request->input('salaries') as $salary) {
           
            Salary::create([
                'employee_id' => $dataInfo->id,
                'type' => $salary['type'], 
                'salary' => $salary['salary'], 
                'remarks'=> $salary['remarks'],
            ]);
        }
    }
     return true;
    }

    public function addSalary(Request $request){
        try{
            DB::beginTransaction();
            if ($request->has('type') && is_array($request->type)) {
                foreach ($request->type as $key =>$type) {
                    if (!empty($type)) {
                        $salary = new Salary();
                        $salary->employee_id = $request->dataId;
                        $salary->salary = $request->salary[$key] ?? null; 
                        $salary->type = $type;
                        $salary->save();
                    }
                }
            }
            DB::commit();
            return ApiResponseHelper::formatSuccessResponseInsert();
    
        }catch(\Exception $err){
            DB::rollBack();
            return ApiResponseHelper::formatErrorResponse($err);
        }
    }
    }
