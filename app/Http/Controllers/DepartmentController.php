<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Services\MasterService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class DepartmentController extends Controller
{

    protected $masterService;

    function __construct(){
        $this->masterService = new MasterService();
    }

    /**
     * @index
     * All department list show
     *
     * @return void
     */
    function index()
    {
        $departments = Department::get();
        return view('master.department.index', compact('departments'));
    }

    /**
     * @store
     * Store department
     *
     * @param  mixed $request
     * @return void
     */
    public function store(DepartmentRequest $request)
    {
        $this->masterService->departmentUpdateOrCreate($request);
        return back()->with('success', 'Department Create Successfully');
    }

    /**
     * @update
     * Update Department
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function update(DepartmentRequest $request, Department $department)
    {
        $this->masterService->departmentUpdateOrCreate($request, $department->id);
        return back()->with('success', 'Department Update Successfully');
    }

}
