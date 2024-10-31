<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\GeneralSetting;
use App\Models\Payroll;
use App\Models\Salary;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;



class PayrollsController extends Controller
{
    public function getEmployee(Request $request){
        $dataList = Employee::where('status',1)->whereNull('deleted_at')->get();
        return response()->json($dataList);
    }
 public function getPayrollInfo(Request $request){
    $dataList = Payroll::with('employeeInfo')->where('id', '=', $request->dataId)->first();
    try {
        $hourly_rate = Salary::where('employee_id', '=', $dataList->employee_id)
            ->where('type', '=', 'hourly_rate')->first()->salary;
    } catch (\Exception $e) {
        $hourly_rate = 0;
    }
    $responsedataList =[
       'dataList' => $dataList,
       'hourly_rate' => $hourly_rate,
    ];
    return response()->json($responsedataList);
}

    public function index(Request $request){
        $dataList = Payroll::with('employeeInfo')->get();
        return response()->json($dataList);
    }
    public function getEmployeeInfo(Request $request){
        $dataInfo = Employee::where('status',1)->where('id', $request->dataId)->first();
        $payrolls = Payroll::where('employee_id', '=', $request->dataId)
        ->where('month', '=', $request->month)
        ->where('year', '=', $request->year)->first();
    try {
        $basicSalary = Salary::where('employee_id', '=', $request->dataId)
            ->where('type', '=', 'basic')->first()->salary;
    } catch (\Exception $e) {
        $basicSalary = 0;
    }

    
    try {
        $hourly_rate = Salary::where('employee_id', '=', $request->dataId)
            ->where('type', '=', 'hourly_rate')->first()->salary;
    } catch (\Exception $e) {
        $hourly_rate = 0;
    }
        $responseData = [
            'dataInfo'=> $dataInfo,
            'basicSalary'=> $basicSalary,
            'hourly_rate' => $hourly_rate,
            'payrolls' => $payrolls,
        ];
        return response()->json($responseData);
    }

    public function dataInfoAdd(Request $request)
{
    $output = [];

    $input = $request->all();

    $allowances = [];
    if (isset($input['allowances'])) {
        foreach ($input['allowances'] as $allowance) {
            if (!empty($allowance['allowanceTitle'])) {
                $allowances[$allowance['allowanceTitle']] = $allowance['allowance'];
            }
        }
    }
    
    $deductions = [];
    if (isset($input['deductions'])) {
        foreach ($input['deductions'] as $deduction) {
            if (!empty($deduction['deductionTitle'])) {
                $deductions[$deduction['deductionTitle']] = $deduction['deduction'];
            }
        }
    }

    $payrollData = [
        'employee_id' => $input['dataId'],
        'month' => $input['month'],
        'year' => $input['year'],
        'basic' => $input['basicSalary'],  // Set basic salary here
        'overtime_hours' => $input['overtime_hours'],
        'overtime_pay' => $input['hourly_rate'],
        'allowances' => json_encode($allowances),
        'deductions' => json_encode($deductions),
        'total_deduction' => $input['totalDeduction'],
        'expense' => 0,
        'total_allowance' => $input['totalAllowance'],
        'net_salary' => $input['netSalary'],
        'status' => $request->status,
    ];

    // Use firstOrCreate to check if record exists, if not create with all fields
    $payroll = Payroll::firstOrCreate([
        'employee_id' => $input['dataId'],
        'month' => $input['month'],
        'year' => $input['year'],
    ], $payrollData);

    return ApiResponseHelper::formatSuccessResponseInsert();
}

public function dataInfoUpdate(Request $request)
{
    // Validate incoming request
   

    // Prepare allowances data
    $allowances = [];
    if (isset($request->allowances)) {
        foreach ($request->allowances as $allowance) {
            if (!empty($allowance['allowanceTitle'])) {
                $allowances[$allowance['allowanceTitle']] = $allowance['allowance'];
            }
        }
    }

    // Prepare deductions data
    $deductions = [];
    if (isset($request->deductions)) {
        foreach ($request->deductions as $deduction) {
            if (!empty($deduction['deductionTitle'])) {
                $deductions[$deduction['deductionTitle']] = $deduction['deduction'];
            }
        }
    }

    // Prepare payroll data
    $payrollData = [
        'basic' => $request->basicSalary,
        'overtime_hours' => $request->overtime_hours,
        'overtime_pay' => $request->hourly_rate,
        'allowances' => json_encode($allowances),
        'deductions' => json_encode($deductions),
        'total_deduction' => $request->totalDeduction,
        'expense' => 0,
        'total_allowance' => $request->totalAllowance,
        'net_salary' => $request->netSalary,
        'status' => $request->status,
    ];

    // Use updateOrCreate to update if record exists or create new if it doesn't
    DB::transaction(function () use ($payrollData, $request) {
        Payroll::updateOrCreate(
            ['id' => $request->dataId],
            $payrollData
        );
    });

    return ApiResponseHelper::formatSuccessResponseInsert();
}

public function downloadPDF($id)
{
    $payroll = Payroll::with('employeeInfo')->findOrFail($id); // Get payroll info by ID
    $generalSetting = GeneralSetting::find(1);
    $pdf = PDF::loadView('payroll.pdf', compact('payroll','generalSetting')); // Use the same HTML structure as the payslip view
    return $pdf->download('payslip-'.$payroll->employeeInfo->employeeID.'-'.$payroll->employeeInfo->full_name.'-'.$payroll->month.'-'.$payroll->year.'.pdf');
}

}
