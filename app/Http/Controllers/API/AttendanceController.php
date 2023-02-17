<?php

namespace App\Http\Controllers\API;

use App\Models\Attendance;
use App\Models\AttendancePunch;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    protected $attendanceService;

    function __construct(){
        $this->attendanceService = new AttendanceService();
    }

    /**
     * @lastThreeDaysAttd
     * Get last three days attendance of employee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function lastThreeDaysAttd()
    {

        $user = auth('sanctum')->user();


        $canCheckIn = false;

        $date[0] = date('Y-m-d');
        $date[1] = date('Y-m-d',strtotime("-1 days"));
        $date[2] =date('Y-m-d',strtotime("-2 days"));

        $attendances[0] = Attendance::where(['user_id' => $user->id, 'date' => $date[0]])->first();
        $attendances[1] = Attendance::where(['user_id' => $user->id, 'date' => $date[1]])->first();
        $attendances[2] = Attendance::where(['user_id' => $user->id, 'date' => $date[2]])->first();

        foreach($attendances as $key => $attendance ) {
            if(isset($attendance) && $attendance != "") {

                $firstPunch = $attendance->punches()
                    ->where(['punch_type'=>'Check-in'])->orderBy('time', 'asc')->first();

                $lastPunch = $attendance->punches()
                    ->where(['punch_type'=>'Check-out'])->orderBy('time', 'desc')
                    ->first();

                $attendance->first_punch = "N/A";
                if(isset($firstPunch)) {
                    $attendance->first_punch = date("g:i A",strtotime($firstPunch->time));
                }

                $attendance->last_punch = "N/A";
                if(isset($lastPunch)) {
                    $attendance->last_punch =  date("g:i A",strtotime($lastPunch->time));
                    if($key == '1'){
                        $canCheckIn = true;
                    }
                }


            }
            else {
                $status = "N/A";
                $response = $this->attendanceService->getDateInfo($date[$key]);
                $resStatus = $response->status();
                $resData = $response->getData();
                if($resStatus === 200 && $resData->data != '') {
                    $info = $resData->data;
                    if($info == 'Holiday'){
                        $status = 'H';
                        $canCheckIn = true;
                    }
                    if($info == 'Week-Off'){
                        $status = 'WO';
                        $canCheckIn = true;
                    }
                }
                $attendance = new Attendance;
                $attendance->last_punch = $status;
                $attendance->first_punch = $status;
                $attendance->date = $date[$key];
            }

            $data['attendance_data'][$key] = $attendance;
            $data['can_check_in'] = true;
        }

        return response()->json(['status' => 'success', 'message' => 'last Three days attendance get successfully', 'data' => $data]);
    }

    /**
     * @checkIn
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180',
            'punch_type' => ['required', Rule::in(['check-in', 'check-out'])], //Check-In, Check-Out
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'validation_error'=>$validator->errors()], 400);
        }

        try {

            DB::beginTransaction();

            $user = auth('sanctum')->user();

            $date = date("Y-m-d");

            /**
             * Check attendance and mark present if attendance is not marked yet
             */
            $attendance = Attendance::where(['user_id' => $user->id, 'date' => $date])->first();
            if(empty($attendance)){
                $attendance = Attendance::create(['user_id' => $user->id, 'date'=> $date,'status'=>'Present']);
            }

            $punchData = [
                'attendance_id' => $attendance->id,
                'time' => date("H:i:s"),
                'punch_type' => $request->punch_type,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];
            /**
             * Save attendance punch
             */
            $punch = AttendancePunch::create($punchData);


            $data['attendance'] = $attendance;
            $data['punch'] = $punch;
            DB::commit();

            $message = "Check In successfully";
            if($request->punch_type === 'check-out'){
                $message = "Check Out successfully";
            }
            return response()->json(['status' =>'success', 'message' => $message, 'data' => $data], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * @monthlyReport
     * Get monthly attendance report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function monthlyReport(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'month' => 'required|numeric',
            'year' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'validation_error'=>$validator->errors()], 400);
        }

        try {

            $user = auth('sanctum')->user();

            $begin = $request->year.'-'.$request->month.'-01';
            $begin = date('y-m-d', strtotime($begin));
            $end =  Carbon::parse($begin)->endOfMonth()->toDateString();
            $periods = CarbonPeriod::create($begin, $end);

            $presentCount = 0;
            $absentCount = 0;
            $weekOffCount = 0;
            $holidayCount = 0;

            foreach ($periods as $period) {
                $date = $period->format("Y-m-d");
                $attendance = Attendance::where(['date' => $date, 'user_id' => $user->id])->first();
                $attdRecord['date'] = $date;
                $attdRecord['status'] = "Absent";
                $attdRecord['check_in'] = "N/A";
                $attdRecord['check_out'] = "N/A";

                if (isset($attendance)) {
                    if ($attendance->status === 'Present') {
                        ++$presentCount;
                        $attdRecord['status'] = "Present";
                        $firstPunch = $attendance->punches()
                            ->where(['punch_type'=>'Check-in'])->orderBy('time', 'asc')->first();

                        $lastPunch = $attendance->punches()
                            ->where(['punch_type'=>'Check-out'])->orderBy('time', 'desc')
                            ->first();

                        $firstPunchAt = "N/A";
                        if(isset($firstPunch)) {
                            $firstPunchAt = date("h:i A",strtotime($firstPunch->on_time));
                        }

                        $lastPunchAt = "N/A";
                        if(isset($lastPunch)) {
                            $lastPunchAt =  date("h:i A",strtotime($lastPunch->on_time));
                        }

                        $attdRecord['check_in'] = $firstPunchAt;
                        $attdRecord['check_out'] = $lastPunchAt;
                    }
                }
                else {
                    $response = $this->attendanceService->getDateInfo($date);
                    $resStatus = $response->status();
                    $resData = $response->getData();
                    if($resStatus === 200 && $resData->data != '') {
                        $info = $resData->data;
                        $attdRecord['status'] = $info;

                        if($info === "Week-Off"){
                            ++$weekOffCount;
                        }

                        if($info === "Holiday"){
                            ++$holidayCount;
                        }

                    }
                    ++$absentCount;
                }
                $allAttdRecord[] = $attdRecord;
            }

            $data['total_present'] = $presentCount;
            $data['total_absent'] = $absentCount;
            $data['total_week_off'] = $weekOffCount;
            $data['total_holidays'] = $holidayCount;
            $data['attendances'] = $allAttdRecord;

            return response()->json(['status' =>'success', 'message' => 'Monthly Record Fetch successfully', 'data' => $data], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }


}
