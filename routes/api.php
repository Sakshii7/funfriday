<?php

use App\Http\Controllers\ApiController;
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

Route:: get('/employees-list',[ApiController::class,'employees']);
Route::post('/employee-list-add',[ApiController::class,'add_employee'])->name('employee-list-add');
Route::delete('/delete_employee/{id}',[ApiController::class,'employee_delete'])->name('delete_employee');
Route::post('/employee-list-update/{id}',[ApiController::class,'employee_update'])->name('employee-list-update');
Route:: post('/login',[ApiController::class,'login']);
Route:: post('/filtered-list',[ApiController::class,'filtered_employees'])->name('filtered-list');
Route::post('/teams_list',[ApiController::class,'teams'])->name('teams_list');
Route::post('/create_teams',[ApiController::class,'create_teams'])->name('create_teams');


