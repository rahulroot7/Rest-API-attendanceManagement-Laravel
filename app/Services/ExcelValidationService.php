<?php

namespace App\Services;

use Symfony\Component\Finder\SplFileInfo;
use App\Models\Designation;
use App\Models\Department;
use App\Models\State;
use App\Models\City;

use Illuminate\Support\Facades\File;

class ExcelValidationService
{
    function employeeData($rows)
    {
        $allError = [];
        foreach ($rows as $row) {
            $recordErrors = [];            
            // get state
            $stateName =  $row['state'];
            $stateData = State::get()->pluck('name')->toArray();
            // get city
            $cityName =  $row['city'];
            $cityData = City::get()->pluck('name')->toArray();
           
            // get Designation
            $designationName = explode(',', $row['designation']);
            $designationData = Designation::get()->pluck('name')->toArray();
            // get Departments
            $departmentName = explode(',', $row['department']);
            $departmentData = Department::get()->pluck('name')->toArray();

            // get role
            $rolName = explode(',', $row['role']);
            $roletData = explode(',', $row['role']);
            // change uppercase

            $stateRecord = array_flip($stateData);
            $stateRecord = array_change_key_case($stateRecord, CASE_UPPER);
            $stateRecord = array_flip($stateRecord);

            $cityRecord = array_flip($cityData);
            $cityRecord = array_change_key_case($cityRecord, CASE_UPPER);
            $cityRecord = array_flip($cityRecord);

            $designationRecord = array_flip($designationData);
            $designationRecord = array_change_key_case($designationRecord, CASE_UPPER);
            $designationRecord = array_flip($designationRecord);

            $departmentRecord = array_flip($departmentData);
            $departmentRecord = array_change_key_case($departmentRecord, CASE_UPPER);
            $departmentRecord = array_flip($departmentRecord);

            $roleRecord = array_flip($roletData);
            $roleRecord = array_change_key_case($roleRecord, CASE_UPPER);
            $roleRecord = array_flip($roleRecord);

            // end uppercase
            $state = strtoupper($stateName);
            if (!in_array($state, $stateRecord)) {
                $recordErrors[] = 'state name Error';
            }

            $city = strtoupper($cityName);
            if (!in_array($city, $cityRecord)) {
                $recordErrors[] = 'city name Error';
            }

            foreach ($designationName as $record) {
                $designation = strtoupper($record);
                if (!in_array($designation, $designationRecord)) {
                    $recordErrors[] = 'designation name Error';
                }
            }

            foreach ($departmentName as $record) {
                $department = strtoupper($record);
                if (!in_array($department, $departmentRecord)) {
                    $recordErrors[] = 'department name Error';
                }
            }

            foreach ($rolName as $record) {
                $role = strtoupper($record);
                if ($role != 'ADMIN' && $role != 'MANAGER' && $role != 'EMPLOYEE') {
                    $recordErrors[] = 'role name Error';
                }
            }
            if ($recordErrors) {
                $error =  [];
                $error[] = $row['first_name'];
                $error[] = $row['email'];
                $error[] = $row['employee_code'];
                $allError[] = $error;

                $dataErr = $this->errrorArray($recordErrors);

                foreach ($dataErr as $arr) {
                    $allError[] =  $arr;
                }
            }
        }
        if ($allError) {
            return $allError;
        } else {
            return 'success';
        }
    }

    function errrorArray($recordErrors)
    {
        $allErr = [];
        foreach ($recordErrors as $err) {
            $error = [];
            $error[] = "";
            $error[] = "";
            $error[] = "";
            $error[] = $err;
            $allErr[] = $error;
        }
        return $allErr;
    }
}
