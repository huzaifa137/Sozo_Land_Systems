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


Route::group(['middleware'=>['AdminAuth']], function(){

Route::post('admin-registration',[Master::class,'admin_register_data'])->name('admin-registration');

Route::get('admin-dashboard',[Master::class,'dashboard'])->name('admin-dashboard');
Route::get('admin-buyer',[Master::class,'admin_buyer'])->name('admin-buyer');

Route::post('update-data-form',[Master::class,'update_data_form'])->name('update-data-form');

Route::get('admin-logout',[Master::class,'logout'])->name('admin-logout');
Route::post('store-buyer-details',[Master::class,'store_buyer_details'])->name('store-buyer-details');

Route::get('Edit_sale/{id}',[Master::class,'Edit_sale']);
Route::get('delete_sale/{id}',[Master::class,'delete_sale']);
Route::get('view_specific_sale/{id}',[Master::class,'view_specific_sale']);

// Estates  and Plots Routes:

Route::get('estates',[Master::class,'estates'])->name('estates');
Route::get('plots',[Master::class,'plots'])->name('plots');
Route::get('add-estate',[Master::class,'add_estate'])->name('add-estate');
Route::get('view-estate/{id}',[Master::class,'view_estate']);


Route::post('send-estate-data',[Master::class,'store_estate'])->name('send-estate-data');
Route::post('send-plot-data',[Master::class,'send_plot_estate'])->name('send-plot-data');
// Houses Module

Route::get('add-house',[Master::class,'add_house'])->name('add-house');

Route::post('send-house-data',[Master::class,'send_house_data'])->name('send-house-data');


// RECIEPTS 

Route::get('pending-buyers',[Master::class,'pending_buyers'])->name('pending-buyers');
Route::get('pending-receipts',[Master::class,'pending_receipts'])->name('pending-receipts');

Route::get('pending-agreements',[Master::class,'pending_agreements'])->name('pending-agreements');


Route::get('add-reciept/{id}',[Master::class,'add_reciept'])->name('add-reciept');
Route::get('view-reciept/{id}',[Master::class,'view_reciept'])->name('view-reciept');
Route::get('add-agreement/{id}',[Master::class,'add_agreement'])->name('add-agreement');
Route::get('view-receipt-records',[Master::class,'view_receipt'])->name('view-receipt-records');

Route::post('store-new-receipt',[Master::class,'store_new_receipt'])->name('store-new-receipt');
Route::post('store-first-receipt',[Master::class,'store_first_receipt'])->name('store-first-receipt');
Route::post('store-agreement',[Master::class,'store_agreement'])->name('store-agreement');
Route::post('store-agreement-new-plot',[Master::class,'store_agreement_new_plot'])->name('store-agreement-new-plot');

Route::get('add-first-receipt/{id}',[Master::class,'add_first_reciept']);

// Agreements
Route::get('accomplished',[Master::class,'accomplished_buyers'])->name('accomplished');
Route::get('view-agreement/{id}',[Master::class,'view_agreement'])->name('view-agreement');
Route::get('download/{id}',[Master::class,'download_agreement_receipt']);
Route::get('download_receipt/{id}',[Master::class,'download_receipt_payment']);

Route::get('download_national/{id}',[Master::class,'download_national_id']);

// Sales

Route::get('all-sales',[Master::class,'all_sales'])->name('all-sales');
Route::get('current-sales',[Master::class,'recordsOnCurrentDate'])->name('current-sales');
Route::get('weekly-records',[Master::class,'weeklyRecords'])->name('weekly-records');
Route::get('monthly-records',[Master::class,'recordsInCurrentMonth'])->name('monthly-records');

// Payment reminders
Route::get('payment-reminder',[Master::class,'searchByPaymentDate'])->name('payment-reminder');
Route::get('update-payment-reminder/{id}',[Master::class,'update_payment_reminder']);
Route::post('store-update-payment',[Master::class,'store_update_payment_reminder'])->name('store-update-payment');

// Resale Module

Route::get('search-land',[Master::class,'search_plot'])->name('search-land');
Route::post('search-land-db',[Master::class,'search_land_db'])->name('search-land-db');
Route::post('search-plot-land-db',[Master::class,'search_plot_land_db'])->name('search-plot-land-db');


Route::get('search-land',[Master::class,'search_plot'])->name('search-land');
Route::get('resale',[Master::class,'resale'])->name('resale');
Route::get('resale-amount/{id}',[Master::class,'resale_amount']);
Route::post('store-resale-amount',[Master::class,'store_resale_amount'])->name('store-resale-amount');

// Load data dynamically.

Route::get('get-second-option',[Master::class,'get_secound_option'])->name('get-second-option');
Route::get('get-input-option',[Master::class,'get_input_option'])->name('get-input-option');

Route::get('attach-agreement/{id}',[Master::class,'attach_agreement_view']);
Route::post('attach-agreement-page',[Master::class,'store_agreement_new'])->name('attach-agreement-page');

Route::get('attach-receipt/{id}',[Master::class,'attach_receipt_view']);
Route::post('attach-receipt-page',[Master::class,'store_attach_receipt_new'])->name('attach-receipt-page');

Route::get('generate-invoice',[Master::class,'generate_invoice'])->name('generate-invoice');

Route::get('show-invoice',[Master::class,'show_invoice'])->name('show-invoice');

// Expenditures

Route::get('add-expenditure',[Master::class,'add_expenditure'])->name('add-expenditure');
Route::post('store-expenditure',[Master::class,'store_expenditure'])->name('store-expenditure');

Route::get('expense-today',[Master::class,'today_expense'])->name('expense-today');


// Search Module 

Route::get('search-module',[Master::class,'search_module'])->name('search-module');
});


 
Route::get('/',[Master::class,'login'])->name('/');
Route::post('admin-send',[Master::class,'admin_check'])->name('admin-send');


// Example in your routes/web.php file

// Route::middleware(['SuperAdminMiddleware'])->group(function () {

    // Route::get('admin-register',[Master::class,'register'])->name('admin-register');
    // Route::get('/edit/{id}', [Master::class,'edit_sales'])->middleware('can:edit,buyer');
    // Route::get('/delete/{id}', [Master::class,'delete_sale'])->middleware('can:delete,buyer');
    
// });

// Edit logic 

    Route::get('admin-register',[Master::class,'register'])->name('admin-register');
    Route::get('/edit/{id}', [Master::class,'edit_sales']);
    Route::get('/delete/{id}', [Master::class,'delete_sale']);

    Route::post('edit-user-info',[Master::class,'edit_user_info'])->name('edit-user-info');
    
Route::get('/resume', [Master::class, 'index']);

