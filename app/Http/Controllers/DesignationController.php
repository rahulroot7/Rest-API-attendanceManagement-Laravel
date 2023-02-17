<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignationRequest;
use App\Models\Designation;
use App\Services\MasterService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class DesignationController extends Controller
{
    protected $masterService;

    function __construct(){
        $this->masterService = new MasterService();
    }

    /**
     * @index
     * All designation
     *
     * @return void
     */
    function index()
    {
        $designations = Designation::get();
        return view('master.designation.index', compact('designations'));
    }

    /**
     * @store
     * Store designation
     *
     * @param  mixed $request
     * @return void
     */
    public function store(DesignationRequest $request)
    {
        $this->masterService->designationUpdateOrCreate($request);
        return back()->with('success', 'Designation Create Successfully');

    }

    /**
     * @update
     * Update Department
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function update(DesignationRequest $request, Designation $designation)
    {
        $this->masterService->designationUpdateOrCreate($request, $designation->id);
        return back()->with('success', 'Designation Update Successfully');
    }

}
