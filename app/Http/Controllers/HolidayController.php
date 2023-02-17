<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\MasterRequest;
use App\Models\Holiday;
use App\Services\DataService;
use App\Services\MasterService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class HolidayController extends Controller
{
    use DataService;

    protected $masterService;

    function __construct(){
        $this->masterService = new MasterService();
    }

    /**
     * @index
     * List of Holidays
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $states = $this->states();
        $holidays = [];
        if($request->submit === 'filter'){
            $holidays = $this->holidays($request->state_id, $request->year);
        }
        return view('master.holiday.index', compact('holidays', 'states'));
    }

    /**
     * @store
     * Store state
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MasterRequest $request)
    {
        $this->masterService->holidayUpdateOrCreate($request);
        return back()->with('success', 'Holiday Create Successfully');
    }

    /**
     * @update
     * Update state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $state
     * @return \Illuminate\Http\Response
     */
    public function update(MasterRequest $request, Holiday $holiday)
    {
        $this->masterService->holidayUpdateOrCreate($request, $holiday->id);
        return back()->with('success', 'Holiday Update Successfully');
    }

}
