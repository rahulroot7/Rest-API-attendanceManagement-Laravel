<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use App\Models\User;
use App\Services\ChangeRequestService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $users = User::get();
        if(isset($request) && $request->submit == 'filter'){
                $fromDate = date('Y-m-d', strtotime($request->from_date));
                $toDate = date('Y-m-d', strtotime($request->to_date));
        }
        $changeRequests = $this->changeRequestService->filter($fromDate, $toDate, $request->user_id ?? "");

        return view('change_requests.index', compact('changeRequests', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, ChangeRequest $changeRequest)
    {
        if($changeRequest->status == '1'){
            return back()->with('error', 'Change Request Already Approved');
        }

        if($changeRequest->status == $request->status){
            return back()->with('error', 'Change Request Already of same status');
        }

        $changeRequest->status = $request->status;
        $changeRequest->save();

        return back()->with('success', 'Change Request status change successfully');
    }


}
