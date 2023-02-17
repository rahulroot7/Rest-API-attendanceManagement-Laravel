<?php

namespace App\Http\Controllers\API;

use App\Models\FieldReport;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FieldReportController extends Controller
{
    protected $reportService;
    function __construct(){
        $this->reportService = new ReportService();
    }

    /**
     * @index
     * List field report
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'validation_error' => $validator->errors()], 400);
        }

        $user = auth('sanctum')->user();

        $data = $this->reportService->fieldReport($request->from_date, $request->to_date, $user->id, 'api');
        return response()->json(['status' =>'success', 'message' => "Field Report Fetch Successfully", 'data' => $data], 200);
    }

    /**
     * @store
     * Store Field Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'working_hrs' => 'required|between:-90,90',
            'travelled_km' => 'required|between:-180,180',
            'stockiest_met' => 'required',
            'distributor_met' => 'required',
            'chemist_met' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'validation_error' => $validator->errors()], 400);
        }

        try {

            DB::beginTransaction();

            $user = auth('sanctum')->user();
            $date = date('Y-m-d');

            if(FieldReport::where(['user_id' => $user->id, 'date' => $date])->first()) {
                return response()->json(['status' => 'error', 'message' => "Today's report already exist"], 400);
            }
            $data = FieldReport::create([
                'user_id' => $user->id,
                'date' => $date,
                'working_hrs' => $request->working_hrs,
                'travelled_km' => $request->travelled_km,
                'stockiest_met' => $request->stockiest_met,
                'distributor_met' => $request->distributor_met,
                'chemist_met' => $request->chemist_met
            ]);
            DB::commit();

            return response()->json(['status' =>'success', 'message' => 'Field record save successfully', 'data' => $data], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

}
