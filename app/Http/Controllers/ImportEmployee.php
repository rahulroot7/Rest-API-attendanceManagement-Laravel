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
use App\Services\ExcelValidationService;
use Illuminate\Support\Facades\Log;
use App\Imports\EmployeeImport;
use App\Imports\EmployeeSaveImport;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

class ImportEmployee extends Controller
{
    use DataService;

    protected $employeeService;
    function __construct(){

        $this->employeeService = new EmployeeService();
        $this->excelValidateService = new ExcelValidationService();
    }
    
    /**
     * @index
     * show index page
     * @return void
     */
    public function index()
    {
        return view('import.import_employee');
    }
    
    /**
     * @store
     * save excel record 
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        Log::info("import employee stote  ImportEmpolyeeController/@import  **Upload Function start**");
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);
        Excel::import(new EmployeeImport, request()->file('file'));
        $data = Excel::toArray(new EmployeeImport, request()->file('file'));
        $error =  $this->excelValidateService->employeeData($data[0]);
        
        if ($error == 'success') {
            Excel::import(new EmployeeSaveImport(), request()->file('file'));
        } else {
            return Excel::download(new EmployeeExport($error), 'condidateError.xlsx');
        }
        Log::warning("candidate Controller/@import  **Upload Excelseet Done*");
        return redirect()->back()->with('success', 'All data Imported successfully !');
    }
}
