<?php

namespace App\Services;

use App\Exceptions\DatabaseException;
use App\Exceptions\GlobalException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Services\FileService;
use App\Services\EmployeDocumentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDOException;

Class EmployeeService
{

    /**
     * @createorUpdate
     * Create or Update Employee with user
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    function createorUpdate($request)
    {
        try {
            DB::beginTransaction();
            $user = self::createOrUpdateUser($request);
            self::createOrUpdateEmployee($user, $request);
            User::with('employee')->find($user->id);
            DB::commit();
        }catch(PDOException $e){
                DB::rollback();
                throw new DatabaseException($e->getMessage());
            }
        catch(Exception $e){
                DB::rollback();
                throw new GlobalException($e->getMessage());
            }
    }

    /**
     * @createOrUpdateUser
     * Create Or Update User
     *
     * @param $request
     * @return mixed
     */
    function createOrUpdateUser($request){

        $empCode = $request->employee_code;
        $name = $request->first_name.' '.$request->last_name;

        return User::updateOrCreate(
            [
                'employee_code'   => $empCode,
            ],
            [
                'employee_code' => $empCode,
                'name' => $name,
                'email' => $request->email,
                'password' => Hash::make($empCode.'@123'),
                'role' => $request->role,
            ]
        );
    }

    /**
     * @createOrUpdateEmployee
     * Create Or Update Employee
     *
     * @param $user
     * @param $request
     * @return void
     */
    function createOrUpdateEmployee($user, $request){
        $filename = '';
        if ($request->file('avatar')) {
            $file = $request->file('avatar');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(config('constants.uploadPaths.profilePic'), $filename);
        }
        Employee::updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile_number' => $request->mobile_no,
                'department_id' => $request->department,
                'designation_id' => $request->designation,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'avatar' => $filename,
                'user_id' => $user->id,
                'manager_id' => $request->manager_id
            ]
        );
    }


}
