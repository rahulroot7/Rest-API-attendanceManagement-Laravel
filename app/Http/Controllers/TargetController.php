<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Services\DataService;
use App\Services\EmployeeTargetService;
use App\Services\TargetService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TargetController extends Controller
{
    use EmployeeTargetService, DataService, TargetService;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->submit)){
            $year = $request->year;
            $month = $request->month;
            $filterType = $request->filter_type;
            if($filterType === 'daily'){
                $filterType = 'daily';
            }elseif($filterType === 'monthly'){
                $filterType = 'monthly';
            }
        }
        else{
            $year = date('Y');
            $month = date('m');
            $filterType = 'monthly';
        }

        if($filterType === 'daily'){
            $response = $this->filterDailyTargets($year, $month);
        }elseif($filterType === 'monthly'){
             $response = $this->filterMonthlyTargets($year, $month);
        }


        $resStatus = $response->status();
        $resData = $response->getData();
        if($resStatus === 200){
            $targets = $resData->data->targets->data;
            $users = $this->users();
            return view('target.index', compact('targets', 'filterType', 'users'));
        }
        return back()->with('error', $resData->message);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $target = Target::where(['year' => $request->year, 'month' => $request->month, 'user_id' => $request->user_id])->first();
        if(isset($target)){
            return back()->with('error', 'Target already added');
        }

        $response = $this->createOrUpdateEmpTarget($request);
        $resStatus = $response->status();
        if($resStatus === 200){
            return back()->with('success', 'Target Create Successfully');
        }
        $resData = $response->getData();
        return back()->with('error', $resData->message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target)
    {
        $response = $this->createOrUpdateEmpTarget($request, $target->id);
        $resStatus = $response->status();
        if($resStatus === 200){
            return back()->with('success', 'Target update successfully');
        }
        $resData = $response->getData();
        return back()->with('error', $resData->message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Target $target)
    {
        $response = $this->deleteEmpTarget($target->id);
        $resStatus = $response->status();
        if($resStatus === 200){
            return back()->with('success', 'Target delete successfully');
        }
        $resData = $response->getData();
        return back()->with('error', $resData->message);
    }
}
