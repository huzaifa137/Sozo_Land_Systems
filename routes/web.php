<?php

use App\Http\Controllers\Master;
use Illuminate\Support\Facades\Route;

Route::post('admin-send', [Master::class, 'admin_check'])->name('admin-send');
Route::get('reload-captcha', [Master::class, 'reload_captcha']);

Route::group(['middleware' => ['AdminAuth']], function () {

    Route::get('/', [Master::class, 'login'])->name('home');

    Route::post('admin-registration', [Master::class, 'admin_register_data'])->name('admin-registration');
    Route::get('admin-dashboard', [Master::class, 'dashboard'])->name('admin-dashboard');
    Route::get('admin-buyer', [Master::class, 'admin_buyer'])->name('admin-buyer');
    Route::get('admin-logout', [Master::class, 'logout'])->name('admin-logout');
    Route::post('store-buyer-details', [Master::class, 'store_buyer_details'])->name('store-buyer-details');
    Route::get('Edit_sale/{id}', [Master::class, 'Edit_sale']);
    Route::get('delete_sale/{id}', [Master::class, 'delete_sale']);
    Route::get('view_specific_sale/{id}', [Master::class, 'view_specific_sale']);
    Route::post('store-new-receipt', [Master::class, 'store_new_receipt'])->name('store-new-receipt');
    Route::post('store-first-receipt', [Master::class, 'store_first_receipt'])->name('store-first-receipt');
    Route::post('store-agreement', [Master::class, 'store_agreement'])->name('store-agreement');
    Route::post('store-agreement-new-plot', [Master::class, 'store_agreement_new_plot'])->name('store-agreement-new-plot');
    Route::get('add-first-receipt/{id}', [Master::class, 'add_first_reciept']);
    Route::get('total-half-plots-in-estate/{id}', [Master::class, 'totalHalfPlots']);
    Route::get('fully-taken-half-plots-in-estate/{id}', [Master::class, 'fully_taken_half_plots']);
    Route::get('fully-not-taken-half-plots-in-estate/{id}', [Master::class, 'fully_not_taken_half_plots']);
    Route::get('Partially-taken-half-plots-in-estate/{id}', [Master::class, 'partially_taken_half_plots']);
    Route::get('back-on-market', [Master::class, 'back_on_market'])->name('back-on-market');
    Route::get('back-for-client-on-sale', [Master::class, 'back_for_client_on_sale'])->name('back-for-client-on-sale');
    Route::get('back-for-company-on-sale', [Master::class, 'back_for_company_on_sale'])->name('back-for-company-on-sale');
    Route::get('user-right-info', [Master::class, 'user_right_info'])->name('user-right-info');
    Route::get('user-information', [Master::class, 'userInformation'])->name('user-information');
    Route::get('delete-user/{id}', [Master::class, 'deleteUser'])->name('add-user');
    Route::get('edit-user/{id}', [Master::class, 'editUser']);
    Route::post('store-user-record', [Master::class, 'storeUserRecord'])->name('store-user-record');
    Route::get('admin-register', [Master::class, 'register']);
    Route::get('/edit/{id}/{user_id}', [Master::class, 'edit_sales']);
    Route::get('/delete/{id}/{plot_number}/{estate}', [Master::class, 'delete_sale']);
    Route::post('edit-user-info', [Master::class, 'edit_user_info'])->name('edit-user-info');
    Route::get('/resume', [Master::class, 'index']);
    Route::get('enter-saved-estate', [Master::class, 'enter_saved_estate'])->name('enter-saved-estate');
    Route::get('view-estate-pdf/{id}/{estate}', [Master::class, 'view_estate_pdf']);
    Route::get('view-receipt-records', [Master::class, 'view_receipt'])->name('view-receipt-records');
    Route::get('download_national/{id}', [Master::class, 'download_national_id']);
    Route::get('all-sales', [Master::class, 'all_sales'])->name('all-sales');
    Route::get('current-sales', [Master::class, 'recordsOnCurrentDate'])->name('current-sales');
    Route::get('weekly-records', [Master::class, 'weeklyRecords'])->name('weekly-records');
    Route::get('monthly-records', [Master::class, 'recordsInCurrentMonth'])->name('monthly-records');
    Route::get('payment-reminder', [Master::class, 'searchByPaymentDate'])->name('payment-reminder');
    Route::get('update-payment-reminder/{id}', [Master::class, 'update_payment_reminder']);
    Route::post('store-update-payment', [Master::class, 'store_update_payment_reminder'])->name('store-update-payment');
    Route::get('search-land', [Master::class, 'search_plot'])->name('search-land');
    Route::post('search-land-db', [Master::class, 'search_land_db'])->name('search-land-db');
    Route::post('search-plot-land-db', [Master::class, 'search_plot_land_db'])->name('search-plot-land-db');
    Route::get('resale/{id}', [Master::class, 'resale'])->name('resale/{id}');
    Route::get('resale-amount/{id}', [Master::class, 'resale_amount']);
    Route::post('store-resale-amount', [Master::class, 'store_resale_amount'])->name('store-resale-amount');
    Route::get('get-second-option', [Master::class, 'get_secound_option'])->name('get-second-option');
    Route::get('get-input-option', [Master::class, 'get_input_option'])->name('get-input-option');
    Route::get('attach-agreement/{id}', [Master::class, 'attach_agreement_view']);
    Route::post('attach-agreement-page', [Master::class, 'store_agreement_new'])->name('attach-agreement-page');
    Route::get('attach-receipt/{id}', [Master::class, 'attach_receipt_view']);
    Route::post('attach-receipt-page', [Master::class, 'store_attach_receipt_new'])->name('attach-receipt-page');
    Route::get('generate-invoice', [Master::class, 'generate_invoice'])->name('generate-invoice');
    Route::get('show-invoice', [Master::class, 'show_invoice'])->name('show-invoice');
    Route::get('add-expenditure', [Master::class, 'add_expenditure'])->name('add-expenditure');
    Route::post('store-expenditure', [Master::class, 'store_expenditure'])->name('store-expenditure');
    Route::get('expense-today', [Master::class, 'today_expense'])->name('expense-today');

    Route::get('estates', [Master::class, 'estates'])->name('estates');
    Route::get('plots', [Master::class, 'plots'])->name('plots');
    Route::get('add-estate', [Master::class, 'add_estate'])->name('add-estate');
    Route::get('view-estate/{id}', [Master::class, 'view_estate'])->name('view-estate');
    Route::get('download-estate/{id}', [Master::class, 'download_estate']);
    Route::get('total-plots-in-estate/{id}', [Master::class, 'all_plots_in_estate'])->name('total-plots-in-estate');
    Route::get('total-fully-paid-plots-in-estate/{id}', [Master::class, 'total_fully_paid_plots_in_estate']);
    Route::get('total-not-taken-plots-in-estate/{id}', [Master::class, 'total_not_taken_plots_in_estate']);
    Route::post('send-estate-data', [Master::class, 'store_estate'])->name('send-estate-data');
    Route::post('send-plot-data', [Master::class, 'send_plot_estate'])->name('send-plot-data');

// Houses Module
    Route::get('add-house', [Master::class, 'add_house'])->name('add-house');
    Route::post('send-house-data', [Master::class, 'send_house_data'])->name('send-house-data');

// RECIEPTS
    Route::get('pending-buyers', [Master::class, 'pending_buyers'])->name('pending-buyers');
    Route::get('pending-receipts', [Master::class, 'pending_receipts'])->name('pending-receipts');
    Route::get('pending-agreements', [Master::class, 'pending_agreements'])->name('pending-agreements');
    Route::get('add-reciept/{id}', [Master::class, 'add_reciept'])->name('add-reciept');
    Route::get('view-reciept/{id}', [Master::class, 'view_reciept'])->name('view-reciept');
    Route::get('view-reciept-info/{id}/{estate}', [Master::class, 'view_reciept_info']);
    Route::get('view-reciept-half-info/{id}/{estate}', [Master::class, 'view_half_plot_info']);
    Route::get('add-agreement/{id}', [Master::class, 'add_agreement'])->name('add-agreement');
    Route::post('store-first-receipt', [Master::class, 'store_first_receipt'])->name('store-first-receipt');

// Agreements
    Route::get('accomplished', [Master::class, 'accomplished_buyers'])->name('accomplished');
    Route::get('view-agreement/{id}', [Master::class, 'view_agreement'])->name('view-agreement');
    Route::get('download/{id}', [Master::class, 'download_agreement_receipt']);
    Route::get('download_receipt/{id}', [Master::class, 'download_receipt_payment']);

    // Sales
    Route::get('enter-saved-estate', [Master::class, 'enter_saved_estate'])->name('enter-saved-estate');
    Route::get('search-module', [Master::class, 'search_module'])->name('search-module');

    Route::get('update-reminder/{id}', [Master::class, 'updateReminder'])->name('update-reminder');
    Route::post('save-update-reminder', [Master::class, 'saveUpdateReminder'])->name('save-update-reminder');

    //  New Routes Included
    Route::get('all-buyers', [Master::class, 'all_buyers'])->name('all-buyers');
    Route::get('all-sellers', [Master::class, 'all_sellers'])->name('all-sellers');
    Route::get('all-agents', [Master::class, 'all_agents'])->name('all-agents');
    Route::get('all-estates', [Master::class, 'all_estates'])->name('all-estates');
    Route::get('all-plots', [Master::class, 'all_plots'])->name('all-plots');

    // Posters

    Route::get('all-posters', [Master::class, 'all_posters'])->name('all-posters');
    Route::get('view-estate-posters/{id}', [Master::class, 'view_estate_poster']);
    Route::get('total-plot-posters-in-estate/{id}', [Master::class, 'all_plot_posters_in_estate']);
    Route::get('total-plot-without-posters-in-estate/{id}', [Master::class, 'all_plot_without_posters_in_estate']);

    Route::post('save-plot-poster', [Master::class, 'save_plot_poster'])->name('save-plot-poster');
    Route::post('remove-plot-from-poster', [Master::class, 'remove_poster_from_plots'])->name('remove-plot-from-poster');

    Route::get('multiple-records', [Master::class, 'get_multiple_records'])->name('multiple-records');
    Route::get('repeatitive', [Master::class, 'get_repeatitive_records'])->name('repeatitive');
    Route::get('fetchPendingNumbers', [Master::class, 'fetchPendingNumbers'])->name('fetchPendingNumbers');

    Route::get('clearence-user-agreement/{userID}', [Master::class, 'clearenceUserAgreement']);
    Route::post('attach-seller-agreement', [Master::class, 'attachSellerAgreement'])->name('attach-seller-agreement');

    Route::get('/receipt/{id}', [Master::class, 'showReceipt'])->name('showReceipt');

    Route::get('/edit-plot-information/{plotInformation}', [Master::class, 'editPlotInformation']);
    Route::post('store-plot-updated-information', [Master::class, 'storePlotUpdatedInformation'])->name('store-plot-updated-information');

    Route::get('/edit-estate-information/{plotInformation}', [Master::class, 'editEstateInformation']);
    Route::post('store-estate-updated-information', [Master::class, 'storeEstateUpdatedInformation'])->name('store-estate-updated-information');
});
