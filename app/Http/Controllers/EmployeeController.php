<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TravelCoordinates;
use App\Models\User;
use App\Services\DataService;
use App\Services\MasterService;
use Illuminate\Http\Request;
use \App\Http\Requests\EmployeeRequest;
use App\Services\EmployeeService;

use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    use DataService;

    protected $employeeService;

    function __construct(){

        $this->employeeService = new EmployeeService();

        $this->authorizeResource(Employee::class, 'employee');
    }

    /**
     * @index
     * get employee list
     *
     * @return void
     */
    function index()
    {
        $employees = Employee::with('user', 'department', 'designation')->get();
        return view('employee.index', compact('employees'));
    }

    /**
     * @create
     * create employee form
     *
     * @return void
     */
    public function create()
    {
        $departments = $this->departments();
        $designations = $this->designations();
        $states = $this->states();

        $managerRole = config('constants.user_roles.manager');
        $managers = $this->users([$managerRole]); // manager

        return view('employee.create', compact('departments', 'designations', 'states', 'managers'));
    }

    /**
     * @store
     * store Here we save the employee information
     *
     * @param  mixed $request
     * @return void
     */
    public function store(EmployeeRequest $request)
    {
        if(!isset($request->manager_id) || empty($request->manager_id)){
            $adminRole = config('constants.user_roles.manager');
            $admin = User::where('role', $adminRole)->first();
            $request->manager_id = $admin->id;
        }

        $this->employeeService->createorUpdate($request);
        return redirect()->route('employees.index')->with('success', 'Employee Create Successfully');

    }

    /**
     * @edit
     * Edit employee form
     *
     * @param Employee $employee
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function edit(Employee $employee)
    {
        $departments = $this->departments();
        $designations = $this->designations();
        $states = $this->states();
        $managerRole = config('constants.user_roles.manager');
        $managers = $this->users([$managerRole]); // manager
        return view('employee.edit', compact('employee', 'departments', 'designations', 'states', 'managers'));
    }

    /**
     * @update
     * Update employee record with user
     *
     * @param Request $request
     * @param Employee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    function update(request $request, Employee $employee)
    {
        $this->employeeService->createorUpdate($request);
        return redirect()->route('employees.index')->with('success', 'Employee Update Successfully');
    }

    /**
     * @track
     * Track Employee
     *
     * @param Request $request
     * @return void
     */
    function track(Request $request, User $user){
        $date = date('Y-m-d');
        if(isset($request->date)){
            $date = date('Y-m-d', strtotime($request->date));
        }

        $allMapPoints = [];
        $routeMapPoints = [];
        $firstCoordinate = TravelCoordinates::where(['date' => $date, 'user_id' => $user->id])->select('latitude as lat', 'longitude as lng', 'created_at')->first();

        if(isset($firstCoordinate)) {
            $mapPoints = [];
            $mapPoints['address']['lat'] = $firstCoordinate->lat ?? "";
            $mapPoints['address']['lng'] = $firstCoordinate->lng ?? "";
            $mapPoints['title'] = "check In";
            $mapPoints['icon'] = "check_in";
            $mapPoints['time'] = date('g:i a', strtotime($firstCoordinate->created_at ?? ""));
            $allMapPoints[] = $mapPoints;
            $routeMapPoints[] = $mapPoints;

            $coordinates = TravelCoordinates::where(['date' => $date, 'user_id' => $user->id])->select('latitude as lat', 'longitude as lng', 'created_at')->get();

            $maxCoordinate = 23;
            $offset = $this->skipOffset(count($coordinates), $maxCoordinate);

            $count = 0;
            foreach ($coordinates as $coordinate) {
                $mapPoints = [];
                $mapPoints['address']['lat'] = $coordinate->lat;
                $mapPoints['address']['lng'] = $coordinate->lng;
                $mapPoints['title'] = "";
                $mapPoints['time'] = date('g:i a', strtotime($coordinate->created_at));
                $allMapPoints[] = $mapPoints;
                $count = 0;
                $routeMapPoints[] = $mapPoints;
            }

            $lastCoordinate = TravelCoordinates::where(['date' => $date, 'user_id' => $user->id])->select('latitude as lat', 'longitude as lng', 'created_at')->latest()->first();
            $mapPoints = [];
            $mapPoints['address']['lat'] = $lastCoordinate->lat;
            $mapPoints['address']['lng'] = $lastCoordinate->lng;
            $mapPoints['title'] = "check Out";
            $mapPoints['icon'] = "check_out";
            $mapPoints['time'] = date('g:i a', strtotime($lastCoordinate->created_at));
            $allMapPoints[] = $mapPoints;
            $routeMapPoints[] = $mapPoints;
        }

        return view('employee.tracking', compact('allMapPoints', 'routeMapPoints'));
    }

    function skipOffset($count, $maxCoordinate){

        for($i = 2; $count; $i++){
//            if($i == '10') {
                if ($i * $maxCoordinate >= $count) {
                    $factorial = $i;
                    break;
                }
                if ($i > $maxCoordinate) {
                    ++$count;
                }
//            }
        }
        return $factorial;
    }
}
