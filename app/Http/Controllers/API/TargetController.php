<?php

namespace App\Http\Controllers\API;

use App\Models\Target;
use App\Services\TargetService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class TargetController extends Controller
{

    use TargetService;

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
            'year'  => 'required',
            'month'  => 'required',
            'user_id'  => 'required',
            'target'  => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => "error", 'validation_error' => $validation->errors()], 400);
        }

        $year = $request->year;
        $month  = $request->month;

        $target = Target::where(['year' => $year, 'month' => $month, 'user_id' => $request->user_id])->first();
        if (isset($target)) {
            return response()->json(['status' => "error", 'message' => "Target already added"], 409);
        }

        $response = $this->createOrUpdateEmpTarget($request);
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
    public function show(Target $target)
    {
        return response()->json(['status' => 'success', 'message' => 'Target fetch successfully', 'data' => $target], 200);
    }

    /**
     * @update
     * Update employee monthly target
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Target $target)
    {
        $validation =  Validator::make($request->all(), [
            'year'  => 'required',
            'month'  => 'required',
            'target'  => 'required|numeric',
            'user_id'  => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => "error", 'validation_error' => $validation->errors()], 400);
        }

        $response = $this->createOrUpdateEmpTarget($request, $target->id);
        $resStatus = $response->status();
        $resData = $response->getData();

        if($resStatus === 200){
            return response()->json(['status' => 'success', 'message' => 'Target updated successfully', 'data' => $resData->data], 200);
        }

        return response()->json(['status' => 'error', 'message' => $resData->message], $resStatus);
    }

    /**
     * @empTargetStatus
     * Employee target status of a year and month
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function empTargetStatus(Request $request){

        $user = auth('sanctum')->user();

        $response = $this-> empTarget($request->year, $request->month, $user->id);
        $resStatus = $response->status();
        $resData = $response->getData();

        if($resStatus === 200){
            return response()->json(['status' => 'success', 'message' => 'Target Fetch successfully', 'data' => $resData->data], 200);
        }

        return response()->json(['status' => 'error', 'message' => $resData->message], $resStatus);
    }


}
