<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\FieldReport;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Class DashboardService{

    function dashboardData(){
        $loginUser = Auth::user();
        $adminRole = config('constants.user_roles.admin');
        if($loginUser->role !== $adminRole) {
            $teamIds = $loginUser->team()->pluck('id');
            $stateId = $loginUser->employee->state_id;
        }

        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');


        $query = Holiday::where('year', Date('Y'));
        if(isset($stateId)){
            $query = $query->where('state_id', $stateId);
        }
        $data['holidays'] = $query->orderBy('date', 'ASC')->get();

        $query  = FieldReport::where('date', $yesterday)->select(
            DB::raw("
             sum(stockiest_met) as stockiest_met,
             sum(distributor_met) as distributor_met,
             sum(chemist_met) as chemist_met,
             sum(TIME_TO_SEC(working_hrs)) as working_hrs"));

        if(isset($teamIds)){
            $query = $query->whereIn('user_id', $teamIds);
        }

        $yesterdayFieldReport = $query->first();

        $data['date'] = $today;
        $data['yesterday_distributor_met'] = $yesterdayFieldReport->distributor_met ?? 0;
        $data['yesterday_stockiest_met'] = $yesterdayFieldReport->stockiest_met ?? 0;
        $data['yesterday_chemist_met'] = $yesterdayFieldReport->chemist_met ?? 0;
        $data['yesterday_working_hrs'] = isset($yesterdayFieldReport->working_hrs) ? gmdate("H:i", $yesterdayFieldReport->working_hrs) : 0;

        if(isset($teamIds)){
            $users = User::whereIn('id', $teamIds)->cursor();
        }else{
            $users = User::get();
        }

        foreach ($users as $user){
            $employeeData['user_id'] = $user->id;
            $employeeData['avatar'] = $user->employee->avatar;
            $employeeData['name'] = $user->name;
            $employeeData['email'] = $user->email;
            $employeeData['status'] = 'Absent';
            $employeeData['check_in'] = '-';
            $employeeData['check_out'] = '-';
            $employeeData['working_hrs'] = '-';

            $attd = Attendance::where(['date' => $today, 'user_id' => $user->id])->first();
            if(isset($attd)){
                $employeeData['status'] = $attd->status;

                $checkIn = $attd->punches()->where('punch_type', 'check-in')->orderBy('id', 'ASC')->first();
                $checkOut = $attd->punches()->where('punch_type', 'check-out')->orderBy('id', 'DESC')->first();

                $employeeData['check_in'] = isset($checkIn->time) ? date('h:i A', strtotime($checkIn->time)) : '-';
                $employeeData['check_out'] = isset($checkOut->time) ? date('h:i A', strtotime($checkOut->time)) : '-';

                if(isset($checkIn->time)) {
                    $lastWorkingTime = Carbon::now()->format('H:i:s');
                    if (isset($checkOut->time)) {
                        $lastWorkingTime = $checkOut->time;
                    }

                    $startTime = Carbon::parse($checkIn->time);
                    $finishTime = Carbon::parse($lastWorkingTime);

                    $totalWorkingHrs = $finishTime->diff($startTime)->format('%H:%I:%S');
                }
                $employeeData['working_hrs'] = $totalWorkingHrs ?? '-';
            }

            $fieldReport = FieldReport::whereYear('date', date('Y'))->whereMonth('date', date('m'))->where('user_id', $user->id)->latest()->first();
            $employeeData['travelled_kms'] = isset($fieldReport) ? $fieldReport->travelled_km : 0;
            $data['employees'][] = $employeeData;
        }

        $curYear = date('Y');
        for($month = 1; $month <= 12; $month++){
            $query = FieldReport::whereYear('date', $curYear)->whereMonth('date', $month);
            if(isset($teamIds)){
                $query = $query->whereIn('user_id', $teamIds);
            }
            $data['travelled_kms'][] = $query->sum('travelled_km');
        }

        return $data;
    }

}
