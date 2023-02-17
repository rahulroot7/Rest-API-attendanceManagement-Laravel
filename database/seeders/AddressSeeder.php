<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $url = "https://countriesnow.space/api/v0.1/countries/states";
            $data = ['country' => 'India'];
            $client = new \GuzzleHttp\Client();

            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body'    => json_encode($data)
            ]);

            $statusCode = $response->getStatusCode();
            $resBody = $response->getBody();
            if($statusCode === 200){
                $data =  json_decode($resBody, true);
                foreach ($data['data']['states'] as $state){
                    $stateName = $state['name'];
                    $stateData = State::where('name', $stateName)->first();
                    if(!isset($stateData)){
                        $stateData = State::create([
                            'name' => $stateName
                        ]);
                    }

                    try {
                        $url = "https://countriesnow.space/api/v0.1/countries/state/cities";
                        $data = ['country' => 'India', 'state' => strtolower($stateName)];
                        $client = new \GuzzleHttp\Client();

                        $response = $client->post($url, [
                            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                            'body'    => json_encode($data)
                        ]);

                        $statusCode = $response->getStatusCode();
                        $resBody = $response->getBody();
                        if($statusCode === 200){
                            $data =  json_decode($resBody, true);
                            foreach ($data['data'] as $city){
                                $cityData = City::where(['state_id' => $stateData->id, 'name' => $city])->first();
                                if(!isset($cityData)){
                                    City::create([
                                        'state_id' => $stateData->id,
                                        'name' => $city
                                    ]);
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        return false;
                    }
                }
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
