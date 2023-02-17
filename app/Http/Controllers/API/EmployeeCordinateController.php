<?php

namespace App\Http\Controllers\API;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Employee;

class EmployeeCordinateController extends Controller
{

    public function getEmployeeData(){

        $url        = 'http://xeambpo.com/json.php';
        $data       = @file_get_contents($url);

        preg_match_all('#\{.*?\}|[^ ]+#', $data, $matches);
        dd($matches);

       //$exp_data  =  preg_match('/(.*?)(\\{[^}]*\\})(.*)/', $data, $match);
       // $exp_data =  explode('{}', $data);

       /*
        $user_id = ['185','106','5'];
        $employees = DB::table('users as user')
            ->join('employees as emp', 'user.id', 'emp.user_id')
            ->whereIn('user.id', $user_id)
            ->where('emp.isactive', 1)
            ->select('emp.user_id', 'user.employee_code', 'emp.fullname')->orderBy('emp.fullname', 'ASC')->get();

        return response()->json(['status' => 'success', 'data' => $employees]);
        */

    }


    public function saveEmployeeData(Request $request){

        $validation =  Validator::make($request->all(),[
            'user_id'      => 'required',
            'coordinates'  => 'required',
            'log_dates' => 'date_format:d-m-Y'
        ]);

        if ($validation->fails()) {
            return response()->json(['validation_error' => $validation->errors()], 400);
        }

        try {
            
            $logDates = $request->log_dates ?? '10-10-2020';
            $logDates = date('Y-m-d', strtotime($logDates));

            DB::table('emp_cordinates')->insert([
                'user_id' => $request->user_id,
                'coordinates' => $request->coordinates,
                'log_dates'    => $logDates
            ]);
            return response()->json(['status' => 'success', 'message' => 'Cordinates Added Successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something  went wrong'], 500);
        }

    }

    public function getEmployeeCordinate(Request $request){

        $validation =  Validator::make($request->all(),[
            'user_id'      => 'required',
            'log_dates' => 'date_format:d-m-Y'
        ]);

        if ($validation->fails()) {
            return response()->json(['validation_error' => $validation->errors()], 400);
        }

        $userId = $request->user_id;
        $logDates = $request->log_dates ?? '10-10-2020';
        $logDates = date('Y-m-d', strtotime($logDates));

        $emp_cordinates = DB::table('emp_cordinates as emp_cord')
            ->select('emp_cord.user_id','emp_cord.coordinates','emp_cord.log_dates')
            ->where(['user_id' => $userId, 'log_dates' => $logDates])
            ->get();

        if(empty($emp_cordinates)){
            return response()->json(['status' => 'error', 'message' => $emp_cordinates ], 201);
        }

        return response()->json(['status' => 'success', 'message' => $emp_cordinates ], 201);
    }

}