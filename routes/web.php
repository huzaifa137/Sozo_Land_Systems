<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Master;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[Master::class,'login']);
Route::post('admin-send',[Master::class,'admin_check'])->name('admin-send');


Route::get('admin-register',[Master::class,'register']);
Route::post('admin-registration',[Master::class,'admin_register_data'])->name('admin-registration');


Route::get('admin-dashboard',[Master::class,'dashboard'])->name('admin-dashboard');