<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ChangeRequestController;
use App\Http\Controllers\API\EmployeeTargetController;
use App\Http\Controllers\API\ExtraDataProviderController;
use App\Http\Controllers\API\FieldReportController;
use App\Http\Controllers\API\TargetController;
use App\Http\Controllers\API\TravelCoordinatesController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/login', [UserController::class, 'login']);
Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], static function () {
    Route::get('menu', [UserController::class, 'menu']);

    Route::group(['prefix' => 'attendances'], static function () {
        Route::get('last-three-days', [AttendanceController::class, 'lastThreeDaysAttd']);
        Route::post('store', [AttendanceController::class, 'store'])->middleware('auth:sanctum');
        Route::post('monthly-report', [AttendanceController::class, 'monthlyReport']);
    });

    Route::group(['prefix' => 'reports',], static function () {
        Route::group(['prefix' => 'field-reports'], static function () {
            Route::get('/', [FieldReportController::class, 'index']);
            Route::post('store', [FieldReportController::class, 'store']);
        });
    });

    Route::resource('targets', TargetController::class);
    Route::get('employee-target-status', [TargetController::class, 'empTargetStatus']);

    Route::resource('employee-targets', EmployeeTargetController::class);

    Route::get('employee-targets', [EmployeeTargetController::class, 'filter']);

    Route::group(['prefix' => 'data'], static function () {
        Route::get('holidays', [ExtraDataProviderController::class, 'holidayList']);
        Route::get('employees', [ExtraDataProviderController::class, 'employeesList']);
    });

    Route::group(['prefix' => 'travel-coordinates'], static function () {
        Route::get('/', [TravelCoordinatesController::class, 'index']);
        Route::post('store', [TravelCoordinatesController::class, 'store']);
    });

    Route::group(['prefix' => 'change-requests'], static function () {
        Route::get('/', [ChangeRequestController::class, 'index']);
        Route::post('store', [ChangeRequestController::class, 'store']);
    });
});
