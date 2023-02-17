<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            'Admin',
            'Salesman'
        ];

        foreach ($designations as $designation){
            Designation::create([
                'name' => $designation
            ]);
        }
    }
}
