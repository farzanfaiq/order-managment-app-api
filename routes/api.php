<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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



// Users
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
});


Route::group(['middleware'=>'auth:api'],function(){
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    
if(Auth::user() && !Auth::user()->hasRole('customer')){
    // Route::resource('area-manager', Api\AreaManagerController::class);
    // Route::resource('rider', Api\RiderController::class);    
    
    Route::post('area-manager/', 'Api\AreaManagerController@store');
    Route::post('area-manager/{id}', 'Api\AreaManagerController@update');
    Route::delete('area-manager/{id}', 'Api\AreaManagerController@destroy');
    Route::get('area-manager/{id}', 'Api\AreaManagerController@show');
    Route::get('area-manager', 'Api\AreaManagerController@index');
    
    
    Route::post('rider/', 'Api\RiderController@store');
    Route::post('rider/{id}', 'Api\RiderController@update');
    Route::delete('rider/{id}', 'Api\RiderController@destroy');
    Route::get('rider/{id}', 'Api\RiderController@show');
    Route::get('rider', 'Api\RiderController@index');
}
});