<?php

namespace App\Http\Controllers\API;

use App\Models\TravelCoordinates;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TravelCoordinatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'date'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'validation_error' => $validator->errors()], 400);
        }

        try{
            $user = auth('sanctum')->user();
            DB::beginTransaction();
            $travelCoordinates = TravelCoordinates::where(['date' => $request->date, 'user_id' => $user->id])->get();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Coordinates Added Successfully', 'data' => $travelCoordinates], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'coordinates'  => 'required',
            'distance' => 'required',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'validation_error' => $validator->errors()], 400);
        }

        try{
            $user = auth('sanctum')->user();
            DB::beginTransaction();

            $data = TravelCoordinates::create([
                'user_id' => $user->id,
                'date' => date('Y-m-d'),
                'coordinates' => $request->coordinates,
                'distance' => $request->distance,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Coordinates Added Successfully', 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    }


}
