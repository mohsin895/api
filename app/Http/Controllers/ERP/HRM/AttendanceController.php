<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Company;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponseHelper;


class AttendanceController extends Controller
{
    public function index(Request $request){
       
        $employeeList = Employee::get();
        $employees =  Employee::with(['attendance' => function($query) use($request) {
            $query->whereRaw('MONTH(date) = ?', [$request->month])->whereRaw('YEAR(date) = ?', [$request->year]);
        }]);
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if($request->employeeID == 'all') {
            $employees = $employees->get();
        } else {
            $employees = $employees->where('id', $request->employeeID)->get();
        }

        
        $final = [];
      

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);

        foreach($employees as $employee) {
            $final[$employee->id.'#'.$employee->full_name] = array_fill(1, $daysInMonth, '-');
            foreach($employee->attendance as $attendance) {
                $final[$employee->id.'#'.$employee->full_name][Carbon::parse($attendance->date)->day] =
                ($attendance->status == 'absent') ? 'absent' : 'present';

            }

           
        }

        $employeeAttendence = $final;
        $dataList=[
            'data'=>$employeeList,
           
            'ipAddress'=>$ipAddress,
            'employeeAttendence'=>$employeeAttendence,
            'daysInMonth'=>$daysInMonth,
        ];
      return response()->json($dataList);

   
   }

   public function markAttendance(Request $request)
   {
    $setting = Company::first();
       $date = $request->currentDate;
       $employees = Employee::leftJoin("attendences", function ($join) use ($date) {
           $join->on("attendences.employee_id", "=", "employees.id")
                ->where("attendences.date", "=", \DB::raw('"' . $date . '"'));
       });
   
       if ($request->employeeID == 'all') {
           $employees = $employees->get();
       } else {
           $employees = $employees->where('employees.id', $request->employeeID)->get();
       }
       $employeeList = Employee::get();
       $dataList = [
           'employeeList' => $employees,
           'getAllEmployee'=>$employeeList,
           'generalInfo'=>$setting,
       ];
   
       return response()->json($dataList);
   }

   public function updateEmployeeAttendanceInfo(Request $request){
    $setting = Company::first();
    $employeeInfo= Employee::where('employeeID',$request->dataId)->first();
    $attendanceInfo = Attendence::where('employee_id',$employeeInfo->id)->where('date',$request->date)->first();
    if(!empty($attendanceInfo)){
        

        if($request->status == 'absent'){
            $attendanceInfo->clock_in = NULL;
            $attendanceInfo->clock_out = NULL;
        }else{
        $attendanceInfo->clock_in = $request->clock_in;
        $attendanceInfo->clock_out = $request->clock_out;
        }
        
        $attendanceInfo->clock_out_ip_address = null;
        $attendanceInfo->status = $request->status;
        $attendanceInfo->save();
        if($attendanceInfo->save()){
               
            DB::commit();
            return ApiResponseHelper::formatSuccessResponseUpdate();
           }else{

            DB::commit();
            return ApiResponseHelper::formatFailedResponseUpdate();
           }

    }else{
        $workingAttendance = new Attendence();
        if($request->status == 'absent'){
            $workingAttendance->clock_in = NULL;
            $workingAttendance->clock_out = NULL;
            $workingAttendance->working_from = NULL;
        }else{
            $workingAttendance->clock_in = $request->clock_in;
        $workingAttendance->clock_out = $request->clock_out;
        $workingAttendance->working_from = 'office';
        }
        $workingAttendance->employee_id = $employeeInfo->id;
        $workingAttendance->date = $request->date;
  
        $workingAttendance->clock_in_ip_address = null;

        $workingAttendance->clock_out_ip_address = null;
        $workingAttendance->status = $request->status;
        // $workingAttendance->notes = $notes;
        
        $workingAttendance->office_start_time = $setting->office_start_time;
        $workingAttendance->office_end_time = $setting->office_end_time;
        $workingAttendance->save();
    }
    if($workingAttendance->save()){
               
        DB::commit();
        return ApiResponseHelper::formatSuccessResponseUpdate();
       }else{

        DB::commit();
        return ApiResponseHelper::formatFailedResponseUpdate();
       }

  
   }

    public function dataInfoAddOrUpdate($date_str)
    {
        $date = Carbon::parse($date_str)->format("Y-m-d");

        $data = json_decode(request()->get("data"), true);
        $employeeIDs = array_keys($data);

        \DB::beginTransaction();

        // Get all employee ids for this company

        $allEmployeeIDs = Employee::pluck("id");

        try {
            foreach ($allEmployeeIDs as $employeeID) {

                /** @var Attendance $attendance */
                $attendance = Attendance::firstOrCreate(['employee_id' => $employeeID, 'date' => $date]);

                if (in_array($employeeID, $employeeIDs)) {

                    // If employee's leave is approved but admin marks him present, then we remove his leave and mark him present
                    if ($attendance->application_status != 'approved' || ($attendance->application_status == 'approved' && $data[$employeeID]["status"] == 'true')) {

                        // We separately set all parameters for present and absent
                        // so that previously set values are overwritten. For example,
                        // if a person was marked present for a day but then he was updated as absent
                        // then his clocking details should be null to prevent wrong calculations
                        if ($data[$employeeID]["status"] == "true") {
                            $attendance->status = "present";
                            $attendance->leaveType = null;
                            $attendance->halfDayType = null;
                            $attendance->reason = '';
                            $attendance->application_status = null;

                            $clock_in = Carbon::createFromFormat('g:i A', $data[$employeeID]["clock_in"], admin()->company->timezone)
                                ->timezone('UTC');
                            $clock_out = Carbon::createFromFormat('g:i A', $data[$employeeID]["clock_out"], admin()->company->timezone)
                                ->timezone('UTC');

                            // When admin is updating, late mark should not be according to clock in/clock out

                            if ($data[$employeeID]["late"] == "true") {
                                $attendance->is_late = 1;
                            } else {
                                $attendance->is_late = 0;
                            }

                            $attendance->clock_in = $clock_in->format('H:i:s');
                            $attendance->clock_out = $clock_out->format('H:i:s');
                            $attendance->working_from = "Office";
                            $attendance->notes = "";
                        } else {
                            $attendance->status = "absent";
                            $attendance->leaveType = $data[$employeeID]["leaveType"];
                            $attendance->halfDayType = ($data[$employeeID]["halfDay"] == 'true') ? 'yes' : 'no';
                            $attendance->reason = $data[$employeeID]["reason"];
                            $attendance->application_status = null;
                            $attendance->is_late = 0;

                            $attendance->clock_in = null;
                            $attendance->clock_out = null;
                            $attendance->working_from = "";
                            $attendance->notes = "";
                        }

                        $attendance->office_start_time = admin()->company->office_start_time;
                        $attendance->office_end_time = admin()->company->office_end_time;
                        $attendance->last_updated_by = admin()->id;

                        $attendance->save();
                    }
                } else {
                    if ($attendance->status != "absent") {
                        $attendance->status = "present";
                        $attendance->leaveType = null;
                        $attendance->halfDayType = null;
                        $attendance->reason = '';
                        $attendance->application_status = null;
                        $attendance->last_updated_by = admin()->id;

                        $attendance->is_late = 0;

                        $attendance->clock_in = $this->data["active_company"]->office_start_time;
                        $attendance->clock_out = $this->data["active_company"]->office_end_time;
                        $attendance->office_start_time = admin()->company->office_start_time;
                        $attendance->office_end_time = admin()->company->office_end_time;
                        $attendance->clock_in_ip_address = null;
                        $attendance->clock_out_ip_address = null;
                        $attendance->working_from = 'Office';
                        $attendance->notes = '';
                        $attendance->save();
                    }
                }
            }
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        $this->date = Carbon::parse($date_str)->format("d M Y");

        if (admin()->company->attendance_notification == 1) {

            $employees = Employee::select('email', 'full_name')
                ->where('status', '=', 'active')
                ->get();

            //---- Attendance Marked EMAIL TEMPLATE-----

            // Do not send email notifications if there are more than x employees in database
            if ($employees->count() <= EmployeesController::$MAX_EMPLOYEES) {
                foreach ($employees as $employee) {
                    $email = "{$employee->email}";
                    $emailInfo = ['from_email' => $this->setting->email,
                        'from_name' => $this->setting->name,
                        'to' => $email,
                        'active_company' => admin()->company];
                    $fieldValues = ['NAME' => $employee->full_name, 'DATE' => $this->date];
                    EmailTemplate::prepareAndSendEmail('ATTENDANCE_MARKED', $emailInfo, $fieldValues);
                }
            }
            //---- Attendance Marked EMAIL TEMPLATE-----


        }

        return ["status" => "success",
            "message" => trans("messages.attendanceUpdateMessage", ["attendance" => date('d M Y', strtotime($date))]),
            'toastrHeading' => trans('messages.success'),
            'toastrMessage' => trans("messages.attendanceUpdateMessage", ["attendance" => date('d M Y', strtotime($date))]),
            'toastrType' => 'success',
            'action' => 'showToastr',
            'date' => $date];
    }

  
}
