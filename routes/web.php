<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


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
    return view('welcome');
});

Route::group(['middleware' => 'admin'], function () {

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/employee-list',[MainController::class,'employees'])->name('employee-list');
Route::get('/add-employee',[MainController::class,'add_employee'])->name('add-employee');
Route::post('/employee-list-add',[MainController::class,'employee_addPost'])->name('employee-list-add');
Route::delete('/data-delete/{id}',[MainController::class,'employee_delete'])->name('data-delete');
Route::get('/employee-edit/{id}',[MainController::class,'employee_edit'])->name('employee-edit');
Route::post('/employee-list-update/{id}',[MainController::class,'employee_update'])->name('employee-list-update');
Route::get('/create-teams',[MainController::class,'createteams'])->name('create-teams');
Route::post('/filtered-list',[MainController::class,'filtered_employees'])->name('filtered-list');
Route::get('/filter_list',[MainController::class,'filterlist'])->name('filter_list');
Route::post('/select-captains',[MainController::class,'selectCaptains'])->name('selectCaptains');
Route::get('/teams',[MainController::class,'Teams'])->name('teams');

});

Route::get('/login', [MainController::class, 'login'])->name('login');
Route::post('/loginpost', [MainController::class, 'loginpost']);
Route::get('/logout',[MainController::class,'logout']);
