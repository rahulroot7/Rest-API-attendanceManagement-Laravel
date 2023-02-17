<?php

namespace App\Imports;

use App\Models\Designation;
use App\Models\Department;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use PDOException;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeSaveImport implements ToCollection, WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                DB::beginTransaction();
                $stateId = State::where('name', $row['state'])->get()->pluck('id')->toArray();
                $cityId = City::where('name', $row['city'])->get()->pluck('id')->toArray();
                $departmentId = Department::where('name', $row['department'])->get()->pluck('id')->toArray();
                $designationId = Designation::where('name', $row['designation'])->get()->pluck('id')->toArray();                
                $role = strtoupper($row['role']);
                if ($role == 'ADMIN') {
                    $roleId = '0';
                }
                if($role == 'MANAGER'){
                    $roleId = '1';
                }
                if($role == 'EMPLOYEE'){
                    $roleId = '2';
                }
                $name = $row['first_name'].' '.$row['last_name'];               
                $user = User::updateOrCreate(
                    [
                        'employee_code'   => $row['employee_code'],
                    ],
                    [
                        'employee_code' => $row['employee_code'],
                        'name' => $name,
                        'email' => $row['email'],
                        'password' => Hash::make($row['employee_code'].'@123'),
                        'role' => $roleId,
                    ]
                );
                Employee::updateOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                        'mobile_number' => $row['mobile_no'],
                        'department_id' => $departmentId['0'],
                        'designation_id' => $designationId['0'],
                        'state_id' => $stateId['0'],
                        'city_id' => $cityId['0'],
                        'user_id' => $user->id,
                        'manager_id' => $roleId,
                    ]
                );
                DB::commit();
            }catch(PDOException $e){
                DB::rollBack();
                throw new PDOException($e->getMessage());
            }
        }
    }
}
