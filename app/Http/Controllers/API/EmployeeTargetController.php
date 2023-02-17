<?php

namespace App\Http\Controllers\API;

use App\Models\EmployeeTarget;
use App\Models\Target;
use App\Services\EmployeeTargetService;
use App\Services\TargetService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeTargetController extends Controller
{
    use EmployeeTargetService, TargetService;

    /**
     * @store
     * Store employee monthly target
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validation =  Validator::make($request->all(), [
            'target_date'  => 'required|date',
            'target'  => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => "error", 'validation_error' => $validation->errors()], 400);
        }

        $targetDate = date('Y-m-d', strtotime($request->target_date));
        $year = date('Y', strtotime($request->target_date));
        $month = date('m', strtotime($request->target_date));

        $user = auth('sanctum')->user();
        $request->user_id = $user->id;

        $monthTarget = Target::where(['year' => $year, 'month' => $month, 'user_id' => $request->user_id])->first();
        if (!isset($monthTarget)) {
            return response()->json(['status' => "error", 'message' => "You have no target yet!!"], 409);
        }

        $target = EmployeeTarget::whereDate('date', $targetDate)->where(['user_id' => $request->user_id])->first();
        if (isset($target)) {
            return response()->json(['status' => "error", 'message' => "Target already added"], 409);
        }

        $response = $this->createOrUpdateTarget($request);
        $resStatus = $response->status();
        $resData = $response->getData();

        if($resStatus === 200){
            return response()->json(['status' => 'success', 'message' => 'Target created successfully', 'data' => $resData->data], 200);
        }

        return response()->json(['status' => 'error', 'message' => $resData->message], $resStatus);
    }

    /**
     * @edit
     * Edit target
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(EmployeeTarget $employeeTarget)
    {
        return response()->json(['status' => 'success', 'message' => 'Target fetch successfully', 'data' => $employeeTarget], 200);
    }

    /**
     * @update
     * Update employee monthly target
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, EmployeeTarget $employeeTarget)
    {
        $validation =  Validator::make($request->all(), [
            'target_date'  => 'required|date',
            'target'  => 'required|numeric'
        ]);

        if ($validation->fails()){
            return response()->json(['status' => "error", 'validation_error' => $validation->errors()], 400);
        }

        $request->user_id = $employeeTarget->user_id;

        $response = $this->createOrUpdateTarget($request, $employeeTarget->id);
        $resStatus = $response->status();
        $resData = $response->getData();

        if($resStatus === 200){
            return response()->json(['status' => 'success', 'message' => 'Target updated successfully', 'data' => $resData->data], 200);
        }

        return response()->json(['status' => 'error', 'message' => $resData->message], $resStatus);
    }

    /**
     * @filter
     * Filter targets
     *
     * @param Request $request
     * @return void
     */
    function filter(Request $request){
        $validation =  Validator::make($request->all(), [
            'year'  => 'required|numeric',
            'month'  => 'required|numeric',
            'type'  => ['required', Rule::in(['monthly', 'daily'])],
        ]);

        if ($validation->fails()){
            return response()->json(['status' => "error", 'validation_error' => $validation->errors()], 400);
        }

        $year = $request->year;
        $month = $request->month;
       $user = auth('sanctum')->user();
        if($request->type === 'monthly'){
            $response = $this->filterMonthlyTargets($year, $month, $user->id);
        }elseif($request->type === 'daily'){
            $response = $this->filterDailyTargets($year, $month, $user->id);
        }

        $resStatus = $response->status();
        $resData = $response->getData();

        if($resStatus === 200){
            return response()->json(['status' => 'success', 'message' => 'Target fetch successfully', 'data' => $resData->data], 200);
        }

        return response()->json(['status' => 'error', 'message' => $resData->message], $resStatus);

    }
}
