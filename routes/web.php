<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureTokenIsValid;
use \App\Http\Controllers\ForgotPasswordController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', '\App\Http\Controllers\LoginController@userLogin');
Route::POST('/sendLoginotp', '\App\Http\Controllers\LoginController@sendLoginotp');


//Route::get('/reset-password', '\App\Http\Controllers\SignupController@resetPassword');
Route::get('/forgotPassword', [ForgotPasswordController::class, 'index'])->name('forgotPassword');
Route::post('forgot-password', [ForgotPasswordController::class, 'submitForgotPasswordForm'])->name('forget.password.post');
Route::get('create-password/{token}', [ForgotPasswordController::class, 'showCreatePasswordForm'])->name('create.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::POST('/sendotp/', [ForgotPasswordController::class, 'sendotp'])->name('sendotp');
Route::get('/create-mpin/{uid}', [ForgotPasswordController::class, 'createMpin'])->name('createMpin');
Route::post('create-mpin-post', [ForgotPasswordController::class, 'submitCreateMpin'])->name('create.mpin.post');
Route::post('update-mpin/', [ForgotPasswordController::class, 'saveUpdateMpin'])->name('update.mpin.post');

Route::get('/forgotPasswordOtp', [ForgotPasswordController::class, 'forgotPasswordOtp'])->name('forgotPasswordOtp');
Route::POST('/update-Password', [ForgotPasswordController::class, 'saveUpdatePassword'])->name('saveUpdatePassword');

Route::get('/', '\App\Http\Controllers\LoginController@index');
Route::get('/mpinlogin/{uid}', '\App\Http\Controllers\LoginController@mpinIndex');
  Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::post('/checkLogin', '\App\Http\Controllers\LoginController@checkLogin');
    Route::get('/logout', '\App\Http\Controllers\LoginController@logout');

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', '\App\Http\Controllers\DashboardController@index')->name('dashboard');
        Route::post('/getDashboardData', '\App\Http\Controllers\DashboardController@getDashboardData');

		  Route::post('/get-user-modules', '\App\Http\Controllers\DashboardController@getUserModules');   
    });

    Route::group(['prefix' => 'users'], function () {
      Route::get('/', '\App\Http\Controllers\UsersController@index')->name('users');
      Route::get('/get', '\App\Http\Controllers\UsersController@get')->name('users.get');
      Route::get('/create', '\App\Http\Controllers\UsersController@create')->name('users.create');
      Route::post('/store', '\App\Http\Controllers\UsersController@store')->name('users.store');
      Route::get('edit/{id}', '\App\Http\Controllers\UsersController@edit')->name('users.edit');
      Route::post('/update', '\App\Http\Controllers\UsersController@update')->name('users.update');
      Route::delete('destroy/{id}', '\App\Http\Controllers\UsersController@destroy')->name('users.destroy');
      Route::post('/changepassword', '\App\Http\Controllers\UsersController@changepassword');
    });

    Route::group(['prefix' => 'profile'], function () {
      Route::get('/edit/{tpurl}', '\App\Http\Controllers\UsersController@profileedit')->name('profile.edit');
      Route::post('/update', '\App\Http\Controllers\UsersController@update')->name('profile.update');      
      Route::post('/getProfilePicture', '\App\Http\Controllers\UsersController@getProfilePicture');
      Route::post('/upload_profile_pic', '\App\Http\Controllers\UsersController@upload_profile_pic');
    });

        
   
    Route::group(['prefix' => 'employees'], function () {
      Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('employees');
      Route::get('/get', '\App\Http\Controllers\EmployeesController@get')->name('employees.get');
      Route::get('/create', '\App\Http\Controllers\EmployeesController@create')->name('employees.create');
      Route::post('/store', '\App\Http\Controllers\EmployeesController@store')->name('employees.store');
      Route::get('edit/{id}', '\App\Http\Controllers\EmployeesController@edit')->name('employees.edit');
      Route::post('/update', '\App\Http\Controllers\EmployeesController@update')->name('employees.update');
      Route::delete('destroy/{id}', '\App\Http\Controllers\EmployeesController@destroy')->name('employees.destroy');
    });

    Route::group(['prefix' => 'jobs'], function () {
      Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('jobs');
    });
    Route::group(['prefix' => 'costcodes'], function () {
      Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('costcodes');
    });

    Route::group(['prefix' => 'time-sheet'], function () {
      Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('timeSheet');
    });
    
    Route::group(['prefix' => 'payroll'], function () {
      Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('payroll');
    });

    Route::group(['prefix' => 'reports'], function () {
      Route::get('/', '\App\Http\Controllers\EmployeesController@index')->name('reports');
    });
    
	Route::get('/generate-pdf', '\App\Http\Controllers\PDFController@generatePDF');
	
	
});



Route::group(['prefix' => 'field'], function () {
  Route::get('/login', '\App\Http\Controllers\LoginController@fieldLogin');
  Route::group(['prefix' => 'time-entry'], function () {
    Route::get('/', '\App\Http\Controllers\FieldTimeEntryController@index');

    Route::get('/clockout-survay', '\App\Http\Controllers\FieldTimeEntryController@clockoutSurvay');

  });
});