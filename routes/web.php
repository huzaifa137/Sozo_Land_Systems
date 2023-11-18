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


Route::get('admin-register',[Master::class,'register'])->name('admin-register');
Route::post('admin-registration',[Master::class,'admin_register_data'])->name('admin-registration');


Route::get('admin-dashboard',[Master::class,'dashboard'])->name('admin-dashboard');
Route::get('admin-buyer',[Master::class,'admin_buyer'])->name('admin-buyer');

Route::get('customer-sales',[Master::class,'customer_sale'])->name('customer-sales');

Route::get('admin-logout',[Master::class,'logout'])->name('admin-logout');
Route::post('store-buyer-details',[Master::class,'store_buyer_details'])->name('store-buyer-details');

Route::get('Edit_sale/{id}',[Master::class,'Edit_sale']);
Route::get('delete_sale/{id}',[Master::class,'delete_sale']);
Route::get('view_specific_sale/{id}',[Master::class,'view_specific_sale']);

// Estates  and Plots Routes:

Route::get('estates',[Master::class,'estates'])->name('estates');
Route::get('plots',[Master::class,'plots'])->name('plots');
Route::get('add-estate',[Master::class,'add_estate'])->name('add-estate');


Route::post('send-estate-data',[Master::class,'store_estate'])->name('send-estate-data');
Route::post('send-plot-data',[Master::class,'send_plot_estate'])->name('send-plot-data');
// Houses Module

Route::get('add-house',[Master::class,'add_house'])->name('add-house');

Route::post('send-house-data',[Master::class,'send_house_data'])->name('send-house-data');