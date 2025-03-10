<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\EmployeesController;
use App\Http\Controllers\api\CostcodesController;
use App\Http\Controllers\api\JobsController;
use App\Http\Controllers\api\DevicesController;
use App\Http\Controllers\api\TimeSheetController;
use App\Http\Controllers\api\FieldTimeEntryController;
use App\Http\Controllers\api\UsersController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json('Hello');
});
Route::get('/abc', '\App\Http\Controllers\api\AuthController@index');

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
//Route::post('/abc', [AuthController::class, 'index']);
Route::group(['prefix' => 'auth'],function(){
    Route::post('/validatePhone', [AuthController::class, 'validatePhone']);
    Route::post('/validatePin', [AuthController::class, 'validatePin']);
    Route::post('/login', [AuthController::class, 'login']);
	
    Route::post('/admin-login', [LoginController::class, 'adminLogin']);
});

Route::group(['prefix' => 'dashboard'], function () {
    return response()->json('Hello');  
});
Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::group(['prefix' => 'employees'], function () {
        //Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('employees');
        Route::get('/get', [EmployeesController::class, 'get']);
        Route::post('/store', [EmployeesController::class, 'store']);
        Route::post('/update/{id}', [EmployeesController::class, 'update']);
        Route::delete('/destroy/{id}', [EmployeesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'cost_codes'], function () {
        Route::get('/get', [CostcodesController::class, 'get']);
        Route::post('/store', [CostcodesController::class, 'store']);
        Route::post('/update/{id}', [CostcodesController::class, 'update']);
        Route::delete('/destroy/{id}', [CostcodesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('/get', [JobsController::class, 'get']);
        Route::post('/store', [JobsController::class, 'store']);
        Route::post('/update/{id}', [JobsController::class, 'update']);
        Route::delete('/destroy/{id}', [JobsController::class, 'destroy']);
    });
	
    Route::group(['prefix' => 'devices'], function () {
        Route::get('/get', [DevicesController::class, 'get']);
        Route::post('/store', [DevicesController::class, 'store']);
        Route::post('/update/{id}', [DevicesController::class, 'update']);
        Route::delete('/destroy/{id}', [DevicesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('/get', [DashboardController::class, 'getCompany']);

    });

    Route::group(['prefix' => 'survayQuestionnaire'], function () {
        Route::get('/get', [DashboardController::class, 'getSurvayQuestionnaire']);

    });
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/get', [DashboardController::class, 'getRoles']);

    });
	
    Route::group(['prefix' => 'users'], function () {
        Route::get('/get', [UsersController::class, 'get']);
        Route::post('/store', [UsersController::class, 'store']);
        Route::post('/update/{id}', [UsersController::class, 'update']);
        Route::get('/destroy/{id}', [UsersController::class, 'destroy']);
        Route::post('/changepassword/{id}', [UsersController::class, 'changepassword']);
    });
    Route::group(['prefix' => 'logs'], function () {
        Route::get('/get', [DashboardController::class, 'getLogs']);
        Route::get('/create', [DashboardController::class, 'createLogs']);

    });
    Route::group(['prefix' => 'field_time'], function () {
        Route::get('/get', [FieldTimeEntryController::class, 'get']);
        Route::post('/clockin', [FieldTimeEntryController::class, 'clockin']);
        Route::post('/clockout', [FieldTimeEntryController::class, 'clockout']);
		
		
        Route::get('/getEntry', [FieldTimeEntryController::class, 'getEntry']);
        Route::post('/entry', [FieldTimeEntryController::class, 'entry']);

    });
    Route::group(['prefix' => 'timesheet'], function () {
        Route::get('/get', [TimeSheetController::class, 'get']);		
		//---------------Field-------------------
		Route::get('/getlast', [TimeSheetController::class, 'getlast']);
        Route::post('/clockin', [TimeSheetController::class, 'clockin']);
        Route::post('/clockout', [TimeSheetController::class, 'clockout']);
        Route::post('/update_survay', [TimeSheetController::class, 'update_survay']);
        Route::post('/update_image', [TimeSheetController::class, 'update_image']);
		//---------------Mobile Sync--------------------------
        Route::post('/saveClockinCheckout', [TimeSheetController::class, 'saveClockinCheckout']);
		//-------------Admin -----------------
		Route::get('/supervisor', [TimeSheetController::class, 'supervisor']);
        Route::post('/update/{id}', [TimeSheetController::class, 'update']);
		Route::post('/updatebulk', [TimeSheetController::class, 'updatebulk']);
		Route::get('/destroy/{id}', [TimeSheetController::class, 'destroy']);
		Route::get('/restore/{id}', [TimeSheetController::class, 'restore']);
		Route::get('/addNewRow/{id}', [TimeSheetController::class, 'addNewRow']);
		Route::post('/updateMultiple', [TimeSheetController::class, 'updateMultiple']);
		Route::post('/calculateHours', [TimeSheetController::class, 'calculateHours']);
		Route::post('/updateMultipleRecap', [TimeSheetController::class, 'updateMultipleRecap']);
		Route::post('/updateInjuryClockout/{id}', [TimeSheetController::class, 'updateInjuryClockout']);
		Route::get('/adminDashboard', [TimeSheetController::class, 'adminDashboard']);
		Route::get('/timesheetCounts', [TimeSheetController::class, 'timesheetCounts']);
		Route::get('/getRecapData', [TimeSheetController::class, 'getRecapData']);
		Route::get('/getSageData', [TimeSheetController::class, 'getSageData']);
    });
	Route::get('/getEmp', [EmployeesController::class, 'getEmpList']);
});

