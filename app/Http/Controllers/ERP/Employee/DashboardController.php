<?php

namespace App\Http\Controllers\ERP\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Employee;
use App\Models\Holyday;
use App\Models\Leavetype;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;

class DashboardController extends Controller
{
   public function dashboard(Request $request){
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
    $employees =  Employee::with(['attendance' => function($query) use($currentMonth, $currentYear) {
        $query->whereRaw('MONTH(date) = ?', [$currentMonth])->whereRaw('YEAR(date) = ?', [$currentYear]);
    }])->where('id',Auth::guard('employee-api')->user()->id)->count();
    $employeeInfo = Employee::with('bankDetails','salaries','getDesignation')->where('id',Auth::guard('employee-api')->user()->id)->first();
    $holidays = Holyday::orderBy('date', 'ASC')->get();

    $holiday_color = ['info', 'error', 'success', 'pending', ''];
    $holiday_font_color = ['blue', 'red', 'green', 'yellow', 'dark'];
    $totalEmployee = Employee::where('status','active')->count();
    $dataList=[
        'daysInMonth'=>$daysInMonth,
        'employees'=>$employees,
        'employeeInfo'=>$employeeInfo,
        'holidays'=>$holidays,
        'holiday_color'=>$holiday_color,
        'holiday_font_color'=>$holiday_font_color,
        'totalEmployee'=>$totalEmployee,
       
    
    ];
  return response()->json($dataList);
   }

   public  function countPresentDays($month, $year, $employeeId)
   {
       // Count full day presents
       $fullday = DB::table('attendences')
           ->whereYear('date', $year)
           ->whereMonth('date', $month)
           ->where('attendences.status', 'present')
           ->where('employee_id', $employeeId)
           ->count();
   
       // Count half day presents
       $halfday = DB::table('attendences')
           ->whereYear('date', $year)
           ->whereMonth('date', $month)
           ->where('attendences.status', 'absent')
           ->where('halfDayType', 'yes')
           ->where(function ($query) {
               $query->whereNull('application_status')
                   ->orWhere('application_status', 'approved');
           })
           ->where('employee_id', $employeeId)
           ->count();
   
       // Return the total number of present days, counting half days as half
       return $fullday + ($halfday / 2);
   }
   public static function absentEmployee($employeeID)
   {

       $absent = [];
       foreach (Leavetype::get() as $leave) {
           $half_day = Attendence::where('attendences.status', '=', 'absent')->where(function ($query) {
               $query->where('application_status', '=', 'approved')
                   ->orWhere('application_status', '=', null);
           })->where('employee_id', '=', $employeeID)//FOr unpaid Leaves
           ->where('halfDayType', '=', 'yes')
               ->where('leaveType', '=', $leave->leaveType)->count();

           // Added to casual
           $half = $half_day / 2;
           $absent[$leave->leaveType] = Attendence::where('attendences.status', '=', 'absent')->where(function ($query) {
               $query->where('application_status', '=', 'approved')
                   ->orWhere('application_status', '=', null);
           })->where('employee_id', '=', $employeeID)//For Unpaid Leaves
           ->where('leaveType', '=', $leave->leaveType)
           ->where(function ($query) {
               $query->where('halfDayType', '<>', 'yes')
                   ->orWhere('halfDayType', '=', null);
           })
           ->count();

           $absent[$leave->leaveType] += $half;

       }

       return $absent;
   }

   public function employeeInfo(){
    $employeeInfo = Employee::with('bankDetails','salaries','getDesignation')->where('id',Auth::guard('employee-api')->user()->id)->first();
    $joiningDate = $employeeInfo->joining_date ? Carbon::parse($employeeInfo->joining_date) : null;
    $exitDate = $employeeInfo->exit_date ? Carbon::parse($employeeInfo->exit_date) : Carbon::now();

    if($exitDate == null){
        $exitDate= Carbon::now();
    }
    if($joiningDate == null){
        return '-';
    }
    $diff = $exitDate->diff($joiningDate);
    $string =($d = $diff->d) ? ' '.$d.'d':'';
    $string=($m = $diff->m) ?($string ? ' ':'').$m.'m'.$string:$string;
    $string =($y =$diff->y) ? $y.'y'.$string :$string;
    $string = ($diff->d == 0 && $diff->m == 0 && $diff->y == 0) ? __('Join Today') : $string;
    $date = date('d');
    $month = date('m');
    $year = date('Y');
    $firstDay = $year . '-'.$month.'-'.$date;
    $employeeID=Auth::guard('employee-api')->user()->id;
    $presentCount=$this->countPresentDays($month, $year, $employeeID);
    $totalDays = date('t',strtotime($firstDay));
    $holiday_count = DB::table('holydays')
    ->whereMonth('date', $month)
    ->whereYear('date', $year)
    ->count();
    $workingDays = $totalDays - $holiday_count;
    $dayWiseWorking="$presentCount/$workingDays";
    $total_leave = Leavetype::sum('num_of_leave')+ ($employeeInfo->annual_leave ?? 0);
    $leaveLeft = array_sum($this->absentEmployee($employeeID)) . '/' . $total_leave;
    $dataList=[
    
        'employeeInfo'=>$employeeInfo,
        'string'=>$string,
        'workingDays'=>$workingDays,
        'holiday_count'=>$holiday_count,
        'presentCount'=>$presentCount,
        'dayWiseWorking'=>$dayWiseWorking,
        'total_leave'=>$total_leave,
        'leaveLeft'=>$leaveLeft,
        

       
    
    ];
  return response()->json($dataList);
   }
}
