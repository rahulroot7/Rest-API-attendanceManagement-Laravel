<?php
namespace App\Helpers;

use App\Models\Attendance;
use App\Models\User;

class Helper
{
    static function getAttendanceInfo($date,$userId)
    {
        $user = User::where(['id' => $userId])->with('employee')->first();

        $date = date("Y-m-d",strtotime($date));
        $attendance = Attendance::where(['date'=>$date,'user_id' => $userId])->latest()->first();

        $data['late'] = 0;
        $data['first_punch'] = "";
        $data['last_punch'] = "";
        $data['first_punch_type'] = 'NA';
        $data['last_punch_type'] = 'NA';
        $data['secondary_leave_type'] = "";
        $data['leave_type'] = "";
        $data['description'] = "";
        $data['remarks'] = "Remark";

        $data['status'] = "N/A";
        if(!empty($attendance)){
            $data['status'] = $attendance->status;

            if($data['status'] != 'Absent'){
                if(!$attendance->punches->isEmpty()){
                    $firstPunch = $attendance->punches()->where('punch_type', 'check-in')->first();
                    $lastPunch = $attendance->punches()->where('punch_type', 'check-out')->first();

                    $data['first_punch'] = date("h:i A",strtotime($firstPunch->time));
                    $data['last_punch'] = date("h:i A",strtotime($lastPunch->time));
                }
            }

        }

        return $data;

    }//end of function

}
