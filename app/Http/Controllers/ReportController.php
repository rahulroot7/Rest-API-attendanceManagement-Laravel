<?php

namespace App\Http\Controllers;

use App\Models\FieldReport;
use App\Services\DataService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    use DataService;

    protected $reportService;
    function __construct(){
        $this->reportService = new ReportService();
    }


    public function field(Request $request)
    {
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d');

        if(isset($request->submit) && $request->submit === 'filter') {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = date('Y-m-d', strtotime($request->to_date));
        }

        $reports = $this->reportService->fieldReport($fromDate, $toDate, $request->user_id ?? "");
        $users = $this->users();
        return view('reports.field', compact('reports', 'users'));
    }

}
