<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $holidayArray = [
            [
                'year' => '2022',
                'state_id' => "29",
                'name' => "New Year",
                'date' => "2022-01-01",

            ],
            [
                'year' => '2022',
                'state_id' => "29",
                'name' => "Makarsakranti / Pongal",
                'date' => "2022-01-14",

            ],
            [
                'year' => '2022',
                'state_id' => "29",
                'name' => "Labour Day",
                'date' => "2022-05-01",

            ],
            [
                'year' => '2022',
                'state_id' => "29",
                'name' => "Gurunanak Jayanti",
                'date' => "2022-11-19",

            ]
        ];

        foreach ($holidayArray as $holiday){
            Holiday::create($holiday);
        }
    }
}
