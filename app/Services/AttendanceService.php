<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

Class AttendanceService{

    /**
     * @getDateInfo
     * Get information of date is holiday or week off
     *
     * @param $date
     * @return \Illuminate\Http\JsonResponse
     */
    function getDateInfo($date){
        $data = "";
        $holiday = Holiday::where('date', $date)->first();
        if(isset($holiday)){
            $data = 'Holiday';
        }

        if(date("l", strtotime($date)) == 'Sunday'){
            $data = 'Week-Off';
        }
        return response()->json(['status' => 'success', 'message' => 'Get date information', 'data' => $data], 200);
    }

    function attendanceData($year, $month){
        $allAttdData = [];
        $users = User::select('id', 'employee_code', 'name')->where('id', '!=', Auth::id())->get();

        $beginDate = "$year-$month-01";
        $endDate = Carbon::parse("$year-$month-01")->endOfMonth()->toDateString();

        foreach ($users as $key => $user){
            $stateId = $user->employee->state_id;
            $attdData = [];
            $attdData['user'] = $user;
            $attdData['attd_status'] = $this->calculateAttdStatus($beginDate, $endDate, $user->id, $stateId);
            $allAttdData[] = $attdData;
        }
        return (object)$allAttdData;
    }

    function calculateAttdStatus($beginDate, $endDate, $userId, $userStateId){
        $holidayCount = 0;
        $presentCount = 0;
        $absentCount = 0;
        $leaveCount = 0;
        $weekOffCount = 0;

        $startTime = strtotime($beginDate);
        $endTime = strtotime($endDate);
        for ($i = $startTime; $i <= $endTime; $i += 86400) {
            $date = date( 'Y-m-d', $i );
            $attd = Attendance::where(['date' => $date, 'user_id' => $userId])->first();
            if(isset($attd) && $attd != ''){
                $status = $attd->status;
                switch ($status){
                    case "Present":
                        ++$presentCount;
                        break;
                    case "Leave":
                        ++$leaveCount;
                        break;
                    default:
                        ++$absentCount;
                        break;
                }
            }
            else{
                $holiday = Holiday::where(['date' => $date, 'state_id' => $userStateId])->first();
                if(isset($holiday) && $holiday != ''){
                    ++$holidayCount;
                }
                else{
                    $day = strtolower(date("l", strtotime($date)));
                    if($day === "sunday") {
                        ++$weekOffCount;
                    }else{
                        ++$absentCount;
                    }
                }
            }
        }

        $paidDays = $presentCount + $weekOffCount + $holidayCount;

        return ['holiday' => $holidayCount, 'leave' => $leaveCount, 'present' => $presentCount, 'absent' => $absentCount, 'week_off' => $weekOffCount, 'paid_days' => $paidDays];
    }
}
