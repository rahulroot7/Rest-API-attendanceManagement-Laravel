<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Mail;
use Hash;
use DB;
use Validator;
use App\Models\EmployeeProfile;
use App\Models\Shift;
use App\Models\LeaveAuthority;
use App\Models\CustomEmployee;
use App\Models\Country;

class UserController extends Controller
{

    /**
     * @login
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required',
            'password' => 'required',
            'device_id' => 'required',
            'device_type' => 'required', //Android, Ios
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'validation_error' => $validator->errors(), ''], 400);
        }

        $credentials = $request->only(['employee_code', 'password']);
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role != '2') {
                return response()->json(['status' => 'error', 'message' => "You are not authorize to use this application!"], 401);
            }

            $user->device_id = $request->device_id;
            $user->device_type = $request->device_type;
            $user->save();

            $data['secret_token'] =  $user->createToken('MyApp')->plainTextToken;
            $employee = $user->employee;

            $department = $employee->department;
            $designation = $employee->designation;
            if (empty($user->employee->avatar)) {
                $avatar = config('constants.static.profilePic');
            } else {
                $avatar = config('constants.uploadPaths.profilePic') . $employee->avatar;
            }

            $userData = [
                'user_id' => $user->id,
                'emp_code' => $user->employee_code,
                'emp_name' => $user->name,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $user->email,
                'department' => ['id' => $department->id, 'name' => $department->name],
                'designation' => ['id' => $designation->id, 'name' => $designation->name],
                'avatar' => $avatar
            ];

            $data['user'] = $userData;
            $successMessage = 'Login Successfully';

            return response()->json(['status' => 'success', 'message' => $successMessage, 'data' => $data], 200);
        }
        return response()->json(['status' => 'error', 'message' => "Credentials do not match!"], 200);
    }

    /**
     * @logout
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();

        $user->device_id = null;
        $user->device_type = null;
        $user->save();
        return response()->json(['success' => 'Successfully logged out.']);
    }

    /**
     * @menu
     * App Side menu
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function menu(Request $request)
    {
        try {
            $menu = [];

            $menu[] = ["category_name" => "Attendance Management", "image" => "<i class=\"fa fa-calendar\"></i>", "type" => "myAttendance",  "subcategory" => NULL];

            $targetManagementOptions = [['val' => 'Add Achieved Target', 'type' => 'AddAchievedTarget'], ['val' => 'Report', 'type' => 'MyReport']];
            $targetManagement = ["category_name" => "Target Management", "image" => "<i class=\"fa fa-clock-o\"></i>", "subcategory" => $targetManagementOptions];
            if (count($targetManagement['subcategory']) > 0) {
                $menu[] = $targetManagement;
            }

            $locationTrackerPermissions = [['val' => 'Field Report', 'type' => 'SavedLocations']];
            $locationTracker = ["category_name" => "Location Tracker", "image" => "", "subcategory" => $locationTrackerPermissions];

            if (count($locationTracker['subcategory']) > 0) {
                $menu[] = $locationTracker;
            }

            // $menu[] = ["category_name" => "Salary Slip", "image" => "<i class=\"fa fa-plane fa-lg\"></i>", "type" => "SalarySlip", "subcategory" => NULL ];

            $menu[] = ["category_name" => "Holiday List", "image" => "<i class=\"fa fa-plane fa-lg\"></i>", "type" => "HolidayList", "subcategory" => NULL];

            return response()->json(['status' => 'success', 'message' => 'Side menu fetch successfully', 'data' => $menu], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}//end of class
