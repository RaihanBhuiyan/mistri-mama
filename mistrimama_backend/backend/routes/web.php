<?php

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

use Illuminate\Support\Facades\Artisan;

// Auth::routes();
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login.show');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('order/refer/{ref_code}', function($ref_code){
    return "thank you. this option on under construction... ". $ref_code;
});
Route::get('/avaiable-order', 'ServiceProvider\ServiceProviderController@avaiableOrder');
Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('role:admin|management|accountant|operation|marketing|b2b');
    Route::resource('role', 'ManageRoleController')->middleware('role:admin|marketing');
    
    /** Admin create new adminstrative user */

    Route::resource('users', 'User/UserController')->middleware('role:admin|marketing');
    
    Route::get('/profile', 'ProfileController@show')->name('profile.show');
    Route::get('/profile_edit', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile_update', 'ProfileController@update')->name('profile.update');

    Route::get('/update_media_status/{id}', 'SiteConfigsController@media_status_approve')->name('update_media_status.approve');
    Route::post('/update_media_status/{id}', 'SiteConfigsController@media_status_deny')->name('update_media_status.deny');

    Route::get('service-provider/become', 'ServiceProvider\ServiceProviderController@become')->name('service-provider.become');
    Route::get('service-provider/document-upload-request', 'ServiceProvider\ServiceProviderController@documentUploadRequest')->name('service-provider.document-upload-request');
    Route::get('service-provider/account-upgrade/{id}/{status}', 'ServiceProvider\ServiceProviderController@accountUpgrade')->name('service-provider.account-upgrade');
    Route::get('service-provider/low_balance', 'ServiceProvider\ServiceProviderController@lowBalance')->name('service-provider.low-balance');
    Route::resource('service-provider', 'ServiceProvider\ServiceProviderController');
    Route::get('service-provider-type-wise/{type?}', 'ServiceProvider\ServiceProviderController@index')->name('service-provider.type-wise');
    Route::get('service-provider/download/{id}', 'ServiceProvider\ServiceProviderController@download')->name('service-provider-download');
    Route::get('service-provider/become/delete/{id}', 'ServiceProvider\ServiceProviderController@become_delete')->name('service-provider.become.delete');

    //Route::post('service-provider-update', 'ServiceProvider\ServiceProviderController@update')->name('service-provider-update');;
    Route::get('service-provider-view', 'ServiceProvider\ServiceProviderController@showall')->name('service-provider-view');
    Route::get('service-provider-view/{serviceProvider}', 'ServiceProvider\ServiceProviderController@show')->name('service-provider-view');
    Route::get('service_provider_active/{id}/{status}', 'ServiceProvider\ServiceProviderController@active')->name('service_provider_active');

    Route::resource('category', 'Service\CategoryController')->middleware('role:admin|marketing');
    Route::resource('page', 'Page\PageController')->middleware('role:admin|marketing');
    Route::resource('slider', 'Page\SliderController')->middleware('role:admin|marketing');
    Route::resource('service', 'Service\ServiceController')->middleware('role:admin|marketing');
    Route::get('toggle-populer/{id}', 'Service\ServiceController@togglePopulerServiceBit')->name('toggle-populer')->middleware('role:admin|marketing');
    Route::resource('servicebit', 'Service\ServiceBitController')->middleware('role:admin|marketing');
    Route::get('remove_hot_service_bit/{id}', 'Service\ServiceBitController@removeHotServiceBit')->name('remove_hot_service_bit')->middleware('role:admin|marketing');
    Route::resource('promocode', 'Promo\PromoCodeController')->middleware('role:admin|marketing');
    Route::resource('offer', 'Offer\OfferController')->middleware('role:admin|marketing');
    Route::resource('division', 'Area\DivisionController')->middleware('role:admin|marketing');
    Route::resource('cluster', 'Area\ClusterController')->middleware('role:admin|marketing');
    Route::resource('zone', 'Area\ZoneController')->middleware('role:admin|marketing');

    Route::get('comrade', 'Comrade\ComradeController@index')->name('comrade.index');
    Route::get('comrade/approve/{id}', 'Comrade\ComradeController@approve')->name('comrade.approve');
    Route::get('comrade/deny/{id}', 'Comrade\ComradeController@deny')->name('comrade.deny');
    Route::get('comrade/active/{id}', 'Comrade\ComradeController@active')->name('comrade.active');
    Route::get('comrade/inactive/{id}', 'Comrade\ComradeController@inactive')->name('comrade.inactive');
    Route::get('comrade/request', 'Comrade\ComradeController@request')->name('comrade.request');
    Route::get('comrade/export', 'Comrade\ComradeController@comradeExport')->name('comrade.export');

    Route::resource('client', 'Client\ClientController')->middleware('role:admin|management|marketing');
    Route::post('clients/filter', 'Client\ClientController@clientsFilter')->name('clients.filter');
    Route::get('client/toggle/status/{id}', 'Client\ClientController@toggleStatus')->name('client.toggle.status');

    Route::get('order/ongoing', 'Order\OrderController@onGoingOrders')->name('order.ongoing')->middleware('role:admin|operation|marketing');
    Route::get('order/history', 'Order\OrderController@orderHistory')->name('order.history');
    Route::resource('order', 'Order\OrderController')->middleware('role:admin|operation|marketing');
    
    Route::resource('quickorder', 'Order\QuickOrderController')->middleware('role:admin|operation|marketing');
    
    Route::post('apply-promo-codes', 'Promo\PromoUserController@apply')->name('apply-promo-codes');
    
    // Career
    Route::get('careers', 'CareerController@index')->name('careers.index')->middleware('role:admin|marketing');
    Route::get('download/resume/{filename}', 'CareerController@download_resume')->name('resume.download');

    // Blog
    Route::resource('advertisement', 'AdvertisementController')->middleware('role:admin|marketing');
    Route::resource('blog', 'BlogsController')->middleware('role:admin|marketing');
    Route::post('leave_comment/{id}', 'BlogsController@comment')->name('leave_comment')->middleware('role:admin|marketing');

    Route::get('comment/removed/{id}', 'BlogsController@comment_removed')->name('comment.removed')->middleware('role:admin|marketing');
    
    Route::resource('blog-category', 'BlogCategoryController')->middleware('role:admin|marketing');
    Route::resource('risk-factors', 'RiskFactorController')->middleware('role:admin|marketing');
    Route::get('claims', 'ClaimController@index')->name('claims.index')->middleware('role:admin|marketing');
    
    /** new order from admin panel */
    
    Route::get('custom/bulkorder/create', 'Order\BlukOrder@index')->name('custom.bulkorder.create')->middleware('role:admin|operation|marketing');
    Route::post('custom/bulkorder/create', 'Order\BlukOrder@store')->name('custom.bulkorder.create')->middleware('role:admin|operation|marketing');
    

    Route::get('custom/order/create', 'Order\OrderController@create')->name('custom.order.create')->middleware('role:admin|operation|marketing');

    Route::get('custom/order/services/{category_id}/{for}', 'Order\OrderController@getServices')->name('custom.order.services');
    Route::get('custom/order/service-bit/{category_id}/{service_id}/{type}', 'Order\OrderController@getServiceBit')->name('custom.order.service.bit');

    Route::post('custom/order/service-bit/cart', 'Order\OrderController@cartToServiceBit')->name('custom.order.service.bit.cart');
    Route::post('custom/order/service-bit/cart/remove', 'Order\OrderController@removeServiceBitToCart')->name('custom.order.service.bit.cart.remove');
    Route::get('custom/order/service-bit/cart-selected/{service_id}', 'Order\OrderController@retriveSelectedServiceBit')->name('custom.order.service.bit.selected');
    Route::post('custom/order/create', 'Order\OrderController@customOrderStore')->name('custom.order.create')->middleware('role:admin|operation|marketing');

    Route::get('custom/order/user-search/{val?}', 'Order\OrderController@userSearch');
    Route::get('custom/order/user-selected/{id}', 'Order\OrderController@userSelected');

    Route::post('new/order/service-bit-qty', 'Order\OrderController@qtyUpdate')->name('admin.servicebit.qty');
    Route::get('new/order/total-price', 'Order\OrderController@TotalPrice')->name('admin.service.totalprice');
    Route::post('new/order/done', 'Order\OrderController@orderFinish')->name('admin.order.done');


    Route::post('order_item_quantity_edit', 'Order\OrderController@order_item_quantity_edit');

    // Route::get('test', 'NotificationController@AdminNotificationNewSp');
    Route::get('test', 'NotificationController@AdminNotificationNewSp');
    Route::get('test1', 'NotificationController@eventFire');
    Route::get('mark_as_read', 'NotificationController@markAsReadNotification')->name('mark_as_read');
    Route::get('notifications', 'NotificationController@index')->name('notifications.index');
    Route::get('notifications/create', 'NotificationController@create')->name('notifications.create');
    Route::post('notifications/create', 'NotificationController@store')->name('notifications.store');


    // Order Accept and Allowcating
    Route::get('order_accept/{order_id}/{status}', 'Order\OrderController@order_accept')->name('order_accept');
    Route::POST('order_cancel/{order_id}', 'Order\OrderController@order_cancel')->name('order_cancel');
    Route::get('/allowcate/{category_id}/{orderid}', 'ServiceProvider\ServiceProviderController@findServiceProviderWithCategory')->name('allocate.sp');
    Route::get('/allowcating/{order_id}/{service_provider_id}', 'Order\OrderSystemController@allowcatingServiceProvider')->name('allocating.sp');

    /** Recharge request admin & accountant */
    Route::get('/recharge/request', 'Account\RechargeRrequestController@index')->name('recharge.index');
    Route::get('/recharge/history', 'Account\RechargeRrequestController@history')->name('recharge.history');
    Route::get('/recharge-request/approve/{id}', 'Account\RechargeRrequestController@rechargeApprove')->name('recharge.approve');
    Route::get('/recharge-request/deny/{id}', 'Account\RechargeRrequestController@rechargeDeny')->name('recharge.deny');
    Route::get('/recharge-request/export', 'Account\RechargeRrequestController@rechargeExport')->name('recharge.export');
    Route::post('/recharge-request/import', 'Account\RechargeRrequestController@rechargeImport')->name('recharge.import');


    /** Cash Out request admin*/
    Route::get('/withdraw/request', 'Account\WithdrawRequestController@withdrawRequest')->name('withdraw.request');
    Route::get('/withdraw/request/adminapprove/{id}', 'Account\WithdrawRequestController@adminapprove')->name('withdraw.request.adminapprove')->middleware('role:admin');
    Route::get('/withdraw/history', 'Account\WithdrawRequestController@withdrawHistory')->name('withdraw.history');
    Route::get('/cash-out/request', 'Account\WithdrawRequestController@cashOutRequest')->name('cash_out.request')->middleware('role:admin|management|accountant|marketing');
    Route::get('/cash-out/history', 'Account\WithdrawRequestController@cashOutHistory')->name('cash_out.history')->middleware('role:admin|management|accountant|marketing');

    Route::post('/withdraw-request/approve/{id}', 'Account\WithdrawRequestController@aprrove')->name('withdraw.approve');
    Route::get('/withdraw-request/deny/{id}', 'Account\WithdrawRequestController@deny')->name('withdraw.deny');
    Route::get('/withdraw-request/export', 'Account\WithdrawRequestController@withdrawExport')->name('withdraw.export');
    Route::post('/withdraw-request/import', 'Account\WithdrawRequestController@withdrawImport')->name('withdraw.import');

    Route::get('/service_privider/transactions/{id}', 'Account\AccountController@servicePrividerTransactions')->name('service_privider.transactions');

    // Cash Book
    Route::get('transactions', 'Account\AccountController@index')->name('transactions.index');
    Route::post('transactions/filter', 'Account\AccountController@accountFilter')->name('transactions.filter');
    Route::get('/transaction/create', 'Account\AccountController@create')->name('transaction.create')->middleware('role:admin|accountant|marketing');
    Route::post('/transaction/create', 'Account\AccountController@store')->name('transaction.store')->middleware('role:admin|accountant|marketing');

    Route::resource('transaction/heading', 'Account\AccountsHeadingController')->middleware('role:admin|accountant|marketing');
    Route::get('account/headings/{type?}', 'Account\AccountsHeadingController@getAccountHeadings');

    Route::resource('jiggasha', 'JiggashaController')->middleware('role:admin|marketing');
    Route::resource('baboharbidhi', 'BaboharbidhiController')->middleware('role:admin|marketing');
    Route::resource('feedback', 'FeedbackController')->middleware('role:admin|marketing');
    Route::resource('setting', 'SettingsController')->middleware('role:admin|marketing');

    Route::get('/sp/order-history', 'ServiceProvider\ServiceProviderController@orderHistory')->middleware();

    Route::get('download-order-invoice/{type}/{order_id}', 'Order\OrderSystemController@downloadOrderInvoice');

    Route::get('test-mail-send', 'TestController@sendEmailReminder');

    
    /** B2B users */
    Route::prefix('b2b')->group(function () {
        Route::resource('ondemand', 'User\OnDemandController');
        Route::resource('affiliation', 'User\AffiliationController');
        Route::resource('b2buser', 'b2b\B2busersController')->middleware('role:admin|marketing|b2b');  
        Route::resource('inventory', 'b2b\B2binventoryController')->middleware('role:admin|marketing|b2b'); 
        Route::resource('projects', 'b2b\B2bprojectController')->middleware('role:admin|marketing|b2b'); 
    });

});

;
 Route::get('ddNewServicBit/{id}', 'Order\OrderController@addNewServicBit');
Route::post('/success', 'SslCommerzPaymentController@success');
Route::post('/fail', 'SslCommerzPaymentController@fail');
Route::post('/cancel', 'SslCommerzPaymentController@cancel');
Route::post('/ipn', 'SslCommerzPaymentController@ipn');

Route::get('/bakash/token', 'BkashController@token');
// Route::get('/slug-add', 'Service\ServiceController@addSlug');


Route::get('clear-s', function () {
    Artisan::call('down');
});
