<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    protected $dashboardService;
    function __construct(){
        $this->dashboardService = new DashboardService();
    }
    /**
     * dashboard
     *
     * Show the application dashboard.
     * @return void
     */
    function dashboard()
    {
        $data = $this->dashboardService->dashboardData();
        return view('dashboard', compact('data'));
    }
}
