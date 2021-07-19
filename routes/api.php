<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\EmployeeController;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::prefix('v1')->group(function() {
    Route::post('login', [ApiController::class, 'authenticate']);
    Route::post('register', [ApiController::class, 'register']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('logout', [ApiController::class, 'logout']);
        Route::get('get_user', [ApiController::class, 'get_user']);
        Route::get('employees', [EmployeeController::class, 'index']);
        Route::get('employees/{id}', [EmployeeController::class, 'show']);
        Route::post('create', [EmployeeController::class, 'store']);
        Route::put('update/{employee}',  [EmployeeController::class, 'update']);
        Route::delete('delete/{employee}',  [EmployeeController::class, 'destroy']);
    });
});
