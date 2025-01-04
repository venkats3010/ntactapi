<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\EmployeesController;
use App\Http\Controllers\api\CostcodesController;
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
    Route::post('/login', [AuthController::class, 'login']);
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
    });

    Route::group(['prefix' => 'cost_codes'], function () {
        Route::get('/get', [CostcodesController::class, 'get']);
        Route::post('/store', [CostcodesController::class, 'store']);
        Route::post('/update/{id}', [CostcodesController::class, 'update']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/get', [UsersController::class, 'get']);
        Route::post('/store', [UsersController::class, 'store']);
        Route::post('/update/{id}', [UsersController::class, 'update']);
    });

});

