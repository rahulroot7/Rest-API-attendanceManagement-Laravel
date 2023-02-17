<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
          'Admin',
          'Sale'
        ];

        foreach ($departments as $department){
            Department::create([
                'name' => $department
            ]);
        }
    }
}
