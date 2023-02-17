<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ChangeRequest;
use App\Models\User;
use App\Services\ChangeRequestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ChangeRequestController extends Controller
{
    protected $changeRequestService;

    function __construct(){
        $this->changeRequestService = new ChangeRequestService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fromDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $toDate = Carbon::now()->lastOfMonth()->format('Y-m-d');
        $user = auth('sanctum')->user();

        if(isset($request) && isset($request->from_date)){
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = date('Y-m-d', strtotime($request->to_date));
        }

        $changeRequests = $this->changeRequestService->filter($fromDate, $toDate, $user->id);
        return response()->json(['status' => 'success', 'message' => 'Change requests fetchs successfully', 'data' => $changeRequests], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['in-time', 'out-time', 'both'])], //Check-In, Check-Out
            'date' => ['required', 'date', 'before:today'],
            'in_time' => ['exclude_if:type,out-time', 'required', 'date_format:H:i'],
            'out_time' => ['exclude_if:type,in-time', 'required', 'date_format:H:i'],
            'remark' => ['required']
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'validation_error'=>$validator->errors()], 400);
        }

        $user = auth('sanctum')->user();

        $employee = $user->employee;

        try {

            $changeRequest = ChangeRequest::where(['date' => $request->date, 'user_id' => $user->id])->first();
            if(isset($changeRequest)){
                if($changeRequest->status == '0'){
                    $message = "Change request for $request->date already submitted and it is pending. Kindly tell to your manager to approve it";
                }
                if($changeRequest->status == '1'){
                    $message = "Change request for $request->date already approved.";
                }
                if($changeRequest->status == '2'){
                    $message = "Change request for $request->date already submitted and it is rejected. Kindly tell to your manager to approve it";
                }

                return response()->json(['status' => "error", 'message' => $message], 400);
            }


           $changeRequest = ChangeRequest::create([
                'user_id' => $user->id,
                'approver_id' => $employee->manager_id,
                'type' => $request->type,
                'date' => $request->date,
                'in_time' => $request->in_time ?? NULL,
                'out_time' => $request->out_time ?? NULL,
                'remark' => $request->remark
            ]);
            return response()->json(['status' => 'success', 'message' => 'Change Request create successfully', 'data' => $changeRequest], 200);
        }catch(\Exception $e){
            return response()->json(['status' => "error", 'message' => $e->getMessage()], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function show(ChangeRequest $changeRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(ChangeRequest $changeRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChangeRequest $changeRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChangeRequest  $changeRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChangeRequest $changeRequest)
    {
        //
    }
}
