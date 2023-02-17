<?php

namespace App\Services;

use App\Models\City;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Holiday;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Trait DataService{

    private function departments()
    {
        return Department::get();
    }

    private function designations()
    {
        return Designation::get();
    }

    private function states()
    {
        return State::get();
    }

    private function cities()
    {
        return City::with('state')->get();
    }

    private function stateWiseCities($stateId)
    {
        return City::where('state_id', $stateId)->get();
    }

    private function holidays($stateId = '', $year = '')
    {
        if($year === ''){
            $year = date('Y');
        }
        $query = Holiday::with('state')->where(['year' => $year]);
        if($stateId !== ''){
            $query = $query->where(['state_id' => $stateId]);
        }
        return $query->select('id', 'year', 'name', 'state_id', 'date')->get();
    }

    private function users($role = ['1', '2']){
        return User::join('employees', 'employees.user_id', '=', 'users.id')
            ->whereIn('role', $role)
            ->where('users.id', '!=', Auth::id())
            ->select('users.id', 'employee_code', 'name', 'first_name', 'last_name', 'users.email', 'avatar')
            ->get();
    }
}
