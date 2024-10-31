<?php

namespace App\Http\Controllers\ERP\HRM;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardInfo(){
        $month =  Carbon::now()->month;
        $totalEmployee= Employee::where('status','active')->count();
        $totalDepartment = Department::where('status',1)->count();
        $totalAwards = Award::count();
        $query = Award::with('employee')->orderBy('id','desc');
        $awards=$query->paginate(10);
      
        $birthdays = Employee::select('full_name','date_of_birth','profile_image')->whereRaw("MONTH(date_of_birth) = ?",[$month])->where('status','active')->orderBy('date_of_birth','asc')->get();
        $responsedata=[
             'totalEmployee'=>$totalEmployee,
             'totalDepartment'=>$totalDepartment,
             'totalAwards'=>$totalAwards,
             'birthdays'=>$birthdays,
             'awards'=>$awards,
           
        ];

        return response()->json($responsedata,200);
    }
}
