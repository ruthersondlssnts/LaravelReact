<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\isAdmin;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });







Route::group(['prefix' => '/v1'], function () {
    //Public Routes
    Route::post('login', [AuthController::class, 'login']);

    //Protected Routes
    Route::group(['middleware' => ['auth:sanctum']], function () {
        //user
        Route::get('user/{id}/edit', [AuthController::class, 'edit']);
        Route::put('user/{id}/update', [AuthController::class, 'update']);
        Route::put('user/{id}/changePassword', [AuthController::class, 'changePassword']);
        Route::post('logout', [AuthController::class, 'logout']);

        //admin
        Route::post('register', [AuthController::class, 'register'])->middleware('is_admin');
        Route::resource('role', RoleController::class)->middleware('is_admin');


        //manager
        Route::group(['middleware' => ['is_manager']], function () {
            Route::get('employee/search/{name}', [EmployeeController::class, 'search']);
            Route::resource('employee', EmployeeController::class);
            Route::get('unit/getBranchEmployees/{id}', [UnitController::class, 'getBranchEmployees']);
            Route::get('unit/getBranches/{ascendants}', [UnitController::class, 'getBranches']);
            Route::resource('unit', UnitController::class);
        });
    });
});
