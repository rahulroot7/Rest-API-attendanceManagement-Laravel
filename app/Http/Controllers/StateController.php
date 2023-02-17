<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\State;
use App\Services\DataService;
use App\Services\MasterService;
use Illuminate\Routing\Controller;


class StateController extends Controller
{
    use DataService;

    protected $masterService;

    function __construct(){
        $this->masterService = new MasterService();
    }

    /**
     * @index
     * List of states
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $states = $this->states();
        return view('master.address.state.index', compact('states'));
    }

    /**
     * @store
     * Store state
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $this->masterService->stateUpdateOrCreate($request);
        return back()->with('success', 'State Create Successfully');
    }

    /**
     * @update
     * Update state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, State $state)
    {
        $this->masterService->stateUpdateOrCreate($request, $state->id);
        return back()->with('success', 'State Update Successfully');
    }

    function cities(State $state){
        $cities = $this->stateWiseCities($state->id);
        return response()->json(['status' => 'success', 'message' => 'Cities fetch successfully', 'data' => $cities]);

    }
}
