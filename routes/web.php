<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChangeRequestController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DataSeedController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\ImportEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
})->middleware('guest');

Auth::routes();
//Route::post('/login', [LoginController::class, 'authenticate'])->name('login');

//Route::get('/truncate', function () {
//    \App\Models\Attendance::Truncate();
//    \App\Models\AttendancePunch::Truncate();
//    \App\Models\TravelCoordinates::Truncate();
//    \App\Models\FieldReport::Truncate();
//});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::group(['prefix' => 'master', 'middleware' => 'can:isAdmin'], static function () {
        Route::resource('departments', DepartmentController::class);
        Route::resource('designations', DesignationController::class);
        Route::resource('states', StateController::class);
        Route::get('states/{state}/cities', [StateController::class, 'cities'])->name('states.cities');
        Route::resource('cities', CityController::class);
        Route::resource('holidays', HolidayController::class);
    });
    Route::get('/cities-ajax', [CityController::class, 'getCities'])->name('getCities');


    Route::get('employees/{user}/track/{date}', [EmployeeController::class, 'track'])->name('employees.track');

    Route::resource('employees', EmployeeController::class);


    Route::resource('targets', TargetController::class);

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], static function () {
        Route::get('/field', [ReportController::class, 'field'])->name('field');
        Route::get('/targets', [ReportController::class, 'targets'])->name('targets');
    });

//    Route::get('/seed-states-cities', [DataSeedController::class, 'seedStatesCities']);

    Route::get('/map', [DataSeedController::class, 'map']);

    Route::group(['prefix' => 'attendances', 'as' => 'attendances.'], static function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('{user:id}/{year}/{month}/', [AttendanceController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'change-requests' , 'as' => 'changeRequests.'], static function () {
        Route::get('/', [ChangeRequestController::class, 'index'])->name('index');
        Route::get('{changeRequest}/{status}/change-status', [ChangeRequestController::class, 'changeStatus'])->name('ChangeStatus');
    });

    Route::group(['prefix' => 'imports', 'as' => 'imports.'], static function () {
        Route::get('/', [ImportEmployee::class, 'index'])->name('index');
        Route::post('/store', [ImportEmployee::class, 'store'])->name('store');
    });

});
