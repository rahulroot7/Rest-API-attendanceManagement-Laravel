<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\City;
use App\Services\DataService;
use App\Services\MasterService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CityController extends Controller
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
    public function index(Request $request)
    {
        $states = $this->states();
        $cities = [];

        if(isset($request->state_id)){
            $cities = $this->stateWiseCities($request->state_id);
        }

        return view('master.address.city.index', compact('cities', 'states'));
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
        $this->masterService->cityUpdateOrCreate($request);
        return back()->with('success', 'City Create Successfully');
    }

    /**
     * @update
     * Update state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $state
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, City $city)
    {
        $this->masterService->cityUpdateOrCreate($request, $city->id);
        return back()->with('success', 'City Update Successfully');
    }

}
