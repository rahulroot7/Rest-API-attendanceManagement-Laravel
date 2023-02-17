<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
           'employee_code' => '00000',
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('00000@123'),
            'role' => '0'
        ]);

        Employee::create([
           'user_id' => $user->id,
           'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'mobile_number' => '1234567890',
            'department_id' => '1',
            'designation_id' => '1',
            'state_id' => '1',
            'city_id' => '1',
            'manager_id' => '0',
        ]);

        $user = User::create([
            'employee_code' => '1111',
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('1111@123'),
            'role' => '1'
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Manager',
            'last_name' => 'Manager',
            'email' => 'manager@gmail.com',
            'mobile_number' => '1234567899',
            'department_id' => '2',
            'designation_id' => '2',
            'state_id' => '1',
            'city_id' => '1',
            'manager_id' => '1',
        ]);

        $user = User::create([
            'employee_code' => '2222',
            'name' => 'Employee',
            'email' => 'employee@employee.com',
            'password' => Hash::make('2222@123'),
            'role' => '2'
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'employee',
            'last_name' => 'Employee',
            'email' => 'employee@employee.com',
            'mobile_number' => '1234567899',
            'department_id' => '2',
            'designation_id' => '2',
            'state_id' => '1',
            'city_id' => '1',
            'manager_id' => '2',
        ]);

    }
}
