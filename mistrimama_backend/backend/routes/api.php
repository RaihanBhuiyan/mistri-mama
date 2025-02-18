<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('hashmaking/{password}', function($password){
    return Hash::make($password);
});

Route::post('/register', 'Auth\Api\AuthController@register');
Route::post('/guest-register', 'Auth\Api\AuthController@guestRegister');
Route::post('/login', 'Auth\Api\AuthController@login');
Route::post('/forgot-password/{type?}', 'Auth\Api\AuthController@forgotPassword');
Route::post('/varify-password-otp', 'Auth\Api\AuthController@varifyPasswordOtp');
Route::post('/reset-password', 'Auth\Api\AuthController@resetPassword');
Route::post('/varify-otp', 'Auth\Api\AuthController@varifyOtp');

Route::post('/check_social_login', 'Auth\Api\AuthController@check_social_login');
Route::post('/send_otp', 'Auth\Api\AuthController@sendOtp');
Route::post('/social_authentication', 'Auth\Api\AuthController@social_authentication');

Route::post('/check-exists-user', 'Auth\Api\AuthController@checkExistsUser');
Route::post('/request-otp', 'Auth\Api\AuthController@requestOtp');
Route::post('/request-ota', 'Auth\Api\AuthController@requestOta');

/* Client */
Route::resource('client', 'Client\ClientController');
Route::post('client-register', 'Client\ClientController@store');
Route::post('sign-up-with-mfs-number', 'Client\ClientController@singUpWithMFSnumber');

Route::get('category/service/{slug}', 'Service\CategoryController@allService');
Route::get('category/service_by_id/{id}', 'Service\CategoryController@allServiceById');
Route::get('/category', 'Service\CategoryController@categoryList');

Route::get('division', 'Area\DivisionController@division');
Route::get('cluster/{division_id}', 'Area\ClusterController@cluster');
Route::get('zone/{cluster_id}', 'Area\ZoneController@zone');

Route::post('guest-order', 'Order\OrderController@store');
Route::post('quickorder', 'Order\OrderController@quickOrder');
Route::post('quickorderitems', 'Service\CategoryController@quickOrderItems');
Route::get('category_like', 'Service\CategoryController@category');
Route::get('get-service-bit/{id}', 'Service\ServiceBitController@getServiceBit');
Route::get('risk-factors/{type}', 'RiskFactorController@getRiskFactors');

/* All Pages api's */
Route::get('page/{page}', 'Page\PageController@loadPageInformations');
Route::get('slider', 'Page\SliderController@sliderJson');
Route::get('site-configs', 'SiteConfigsController@siteConfigs');

Route::get('offers', 'Offer\OfferController@allOffer');
Route::post('guest-apply-promo-codes', 'Promo\PromoUserController@apply');

Route::get('jiggasha/{esp}', 'JiggashaController@jiggasha');
Route::get('jiggasha/{client}', 'JiggashaController@jiggasha');
Route::get('projects', 'SiteConfigsController@projects');
Route::get('testimonials', 'SiteConfigsController@testimonials');
Route::get('jiggasha/{common}', 'JiggashaController@jiggasha');
Route::get('get_articles', 'BlogsController@getArticles');
Route::get('articles', 'BlogsController@articles');
Route::get('article/{id}', 'BlogsController@article');
Route::get('recent/articles', 'BlogsController@recent_articles');
Route::get('article/like/{id}', 'BlogsController@like_article');
Route::post('leave_comment/{id}', 'BlogsController@comment');
Route::get('advertisement/{place_name}', 'AdvertisementController@advertisement');
Route::get('baboharbidhi', 'BaboharbidhiController@baboharbidhi');
Route::post('careers', 'CareerController@store');
Route::post('contact_us', 'SiteConfigsController@mail_contact_us');
Route::post('become_service_provider', 'ServiceProvider\ServiceProviderController@become_service_provider');
Route::get('download-corporate-services', 'SiteConfigsController@download_corporate_services');

   // Assingn role permissions tool v2 - v4 
//Route::get('assign_Role', 'Auth\Api\AuthController@assign_Role');

// Client ID: 2
// Client secret: c4MLm1ED2Z0OfDDvcSRE6H0pWn8zzIsmwGgCe01i
// Password grant client created successfully.
// Client ID: 3
// Client secret: eOCI8GfYlcMgnzvOFO0q7i7F0eFDXOhPb4HbDSQW

Route::get('scheme_generate', 'SchemeController@schemeGenerate');
Route::get('populer-services', 'Service\ServiceController@getPopulerServices');
Route::group(['middleware' => ['auth:api']], function () {
    
    Route::post('claim', 'ClaimController@store');
    Route::get('suggested-services', 'Service\ServiceController@getSuggestedServices');
    Route::get('notifications', 'NotificationController@getNotifications');
    Route::get('mark_as_read', 'NotificationController@markAsReadNotification');
    Route::post('change-password', 'Auth\Api\AuthController@changePassword');
    
    Route::post('order', 'Order\OrderController@store');
    Route::post('accept-order', 'Order\OrderSystemController@AcceptByServiceProvider')->middleware('role:esp|fsp');
    Route::post('/reject-order', 'ServiceProvider\ServiceProviderController@rejectOrder')->middleware('role:esp|fsp');
    Route::post('allowcate-comrade', 'Order\OrderSystemController@AcceptAndAllowcateComrade')->middleware('role:esp|fsp');
    Route::get('change-comrade/{order_id}/{comrade_id}', 'Order\OrderSystemController@changeComrade')->middleware('role:esp|fsp');
    Route::post('start-servicing', 'Order\OrderSystemController@startServicing')->middleware('role:esp|fsp|comrade');
    Route::post('finish-servicing', 'Order\OrderSystemController@finishServicing')->middleware('role:esp|fsp|comrade');
    Route::post('collect-payment', 'Order\OrderSystemController@paymentReceive')->middleware('role:esp|fsp|comrade');
    Route::get('pay/cash/{id}', 'Order\OrderSystemController@payCashByClient');
    
    Route::get('order-user-rating/{order_id}/{rating}', 'Order\OrderSystemController@orderUserRating')->middleware('role:esp|fsp|comrade');
    Route::get('order-sp-rating/{order_id}/{rating}', 'Order\OrderSystemController@orderServiceProviderRating');

    Route::post('/profile-update/sp', 'ServiceProvider\ServiceProviderController@updateInfo')->middleware('role:esp|fsp');
    Route::post('/profile-change/sp', 'ServiceProvider\ServiceProviderController@changeImage')->middleware('role:esp|fsp');
    Route::post('/profile-update/client', 'Client\ClientController@update');
    Route::post('/profile-image-change/client', 'Client\ClientController@changeImage');
    
    Route::get('/sp/services', 'ServiceProvider\ServiceProviderController@allServices'); 
    
    Route::get('/sp/comrades', 'ServiceProvider\ServiceProviderController@getComrades')->middleware('role:esp');
    Route::get('/sp/comrades/{category}', 'ServiceProvider\ServiceProviderController@getComradesByCategory')->middleware('role:esp');

    Route::get('/service-provider-details', 'ServiceProvider\ServiceProviderController@getServiceProviderDetails')->middleware('role:esp|fsp');
    Route::post('recharge-request', 'Account\RechargeRrequestController@store')->middleware('role:esp|fsp');
    Route::get('/sp/balance', 'ServiceProvider\ServiceProviderController@balance')->middleware('role:esp|fsp');
    Route::post('cashout-request', 'Account\WithdrawRequestController@create')->middleware('role:esp|fsp');
    
    Route::get('/sp/statements', 'ServiceProvider\ServiceProviderController@miniStatement')->middleware('role:esp|fsp');
    Route::get('/sp/statements-history', 'ServiceProvider\ServiceProviderController@miniStatementHistory')->middleware('role:esp|fsp');
    //statements-history-lager
    Route::get('/sp/statements-history-lager', 'ServiceProvider\ServiceProviderController@miniStatementHistoryLager')->middleware('role:esp|fsp');
    //statements-history-lager-export'
    Route::get('/sp/statements-history-lager-export', 'ServiceProvider\ServiceProviderController@statementsHistoryLagerExport')->middleware('role:esp|fsp');
    Route::get('/sp/last-recharge', 'ServiceProvider\ServiceProviderController@lastRecharge')->middleware('role:esp|fsp');
    Route::get('/sp/last-withdraw', 'ServiceProvider\ServiceProviderController@lastWithdraw')->middleware('role:esp|fsp');
    Route::get('/sp/last-order', 'ServiceProvider\ServiceProviderContrservice/purberkaajoller@lastOrder')->middleware('role:esp|fsp');
    Route::get('/sp/todays-income', 'ServiceProvider\ServiceProviderController@todaysIncome')->middleware('role:esp|fsp');
    Route::get('/sp/yesterdays-income', 'ServiceProvider\ServiceProviderController@yesterdaysIncome')->middleware('role:esp|fsp');
    Route::get('/sp/thismonth-income', 'ServiceProvider\ServiceProviderController@thisMonthIncome')->middleware('role:esp|fsp');
    Route::get('/sp/lastmonth-income', 'ServiceProvider\ServiceProviderController@lastMonthIncome')->middleware('role:esp|fsp');
    Route::get('/sp/self-order-this-month-income', 'ServiceProvider\ServiceProviderController@thisMonthSelfIncome')->middleware('role:esp|fsp');
    Route::get('/sp/self-order-this-month', 'ServiceProvider\ServiceProviderController@thisMonthSelfJob')->middleware('role:esp|fsp');
    Route::get('/sp/self-order-income', 'ServiceProvider\ServiceProviderController@totalSelfOrderIncome')->middleware('role:esp|fsp');
    Route::get('/sp/self-order', 'ServiceProvider\ServiceProviderController@totalSelfOrder')->middleware('role:esp|fsp');
    Route::get('/sp/mm-order-this-month-income', 'ServiceProvider\ServiceProviderController@thisMonthMMIncome')->middleware('role:esp|fsp');
    Route::get('/sp/mm-order-this-month', 'ServiceProvider\ServiceProviderController@thisMonthMMJob')->middleware('role:esp|fsp');
    Route::get('/sp/mm-order-income', 'ServiceProvider\ServiceProviderController@totalMMOrderIncome')->middleware('role:esp|fsp');
    Route::get('/sp/mm-order', 'ServiceProvider\ServiceProviderController@totalMMOrder')->middleware('role:esp|fsp');
    /** SP order history */
    Route::get('/sp/order-history', 'ServiceProvider\ServiceProviderController@orderHistory')->middleware('role:esp|fsp');
    Route::get('/sp/order-history-others', 'ServiceProvider\ServiceProviderController@orderHistoryOthers')->middleware('role:esp|fsp');
    Route::get('/sp/order-history-self', 'ServiceProvider\ServiceProviderController@orderHistorySelf')->middleware('role:esp|fsp');
    Route::get('/sp/order/cm', 'ServiceProvider\ServiceProviderController@orderHistory')->middleware('role:esp|fsp');

    // this route only for android api
    Route::get('/order-details/{id}', 'ServiceProvider\ServiceProviderController@orderDetails');
    // this route work on after job accept
    Route::get('/orderdetails/{id}', 'ServiceProvider\ServiceProviderController@order_details');

    Route::get('/avaiable-order', 'ServiceProvider\ServiceProviderController@newAvaiableOrder');
    Route::get('/waiting-orders', 'Order\OrderSystemController@WaitingOrders')->middleware('role:esp|fsp');
    Route::get('/ongoing-order', 'ServiceProvider\ServiceProviderController@allOngoingOrder')->middleware('role:esp|fsp');
    Route::get('/phone-order', 'ServiceProvider\ServiceProviderController@phoneOrder')->middleware('role:esp|fsp');

    //scheme
    Route::get('/sp/scheme', 'ServiceProvider\ServiceProviderController@scheme')->middleware('role:esp|fsp');
    Route::get('/sp/scheme-last-week', 'ServiceProvider\ServiceProviderController@schemeLastWeek')->middleware('role:esp|fsp');
  
    Route::get('user-available-promo-codes', 'Promo\PromoUserController@index');
    Route::post('user-save-promo-codes', 'Promo\PromoUserController@store');
    Route::post('user-apply-promo-codes', 'Promo\PromoUserController@apply');

    Route::get('/client-details', 'Client\ClientController@getClientDetails');
    Route::get('/user-order-count', 'Client\ClientController@CurrentOrderCountUser');
    Route::get('/user-mfs-number-history', 'Client\ClientController@userMfsNumberHistory');
    Route::get('/user-rewardpoint', 'Client\RewardPointController@currentRewardPointCount');
    Route::get('/user-rewardpoint-history', 'Client\RewardPointController@index');

    Route::post('/user-redeem-point', 'Client\RewardPointController@redeemPoint');
    Route::post('/user-cashout-request', 'Account\WithdrawRequestController@create');
    Route::get('/user-cashout-history', 'Account\WithdrawRequestController@userCashOutHistory');
    Route::get('/export-cashout-history', 'Account\WithdrawRequestController@exportCashOutHistory');

    Route::get('/user-orders', 'Client\ClientController@CurrentOrder');
    Route::get('/user/order-history', 'Client\ClientController@orderHistory');
    Route::get('/user-quick-orders', 'Order\OrderController@quickOrderHistory');

    Route::get('pay/ssl/{id}', 'Order\OrderSystemController@paySSL');

    Route::get('/user/check-waiting-payment', 'Order\OrderController@checkPaymentWaitingOrder');
    Route::get('/search-user/{val}', 'Order\OrderController@userSearch');
    Route::post('add_new_service/{id}', 'Order\OrderController@addNewServicBit');
    Route::post('pay/offline', 'Order\OrderController@payOffline');
    Route::get('/outstanding/add/{orderId}', 'Order\B2b\OnDeamandOrderController@outstandingBalanceAdd');

    Route::resource('comrade', 'Comrade\ComradeController')->middleware('role:esp'); 
    Route::get('/comrade-export', 'ServiceProvider\ServiceProviderController@comradeExport')->middleware('role:esp|fsp|comrade');
    Route::post('comrade-update/{comrade_id}', 'Comrade\ComradeController@update')->middleware('role:esp|fsp');;
    Route::GET('/comrade-profile', 'Comrade\ComradeController@comrade_profile')->middleware('role:comrade');
    Route::POST('/profile-update/comrade', 'Comrade\ComradeController@profile_update')->middleware('role:comrade');
    Route::post('/profile-image-change/comrade', 'Comrade\ComradeController@changeComradeImage')->middleware('role:comrade');
    Route::get('/allowcated-orders/comrade', 'Comrade\ComradeController@allowcatedOrders')->middleware('role:comrade');
    Route::get('/orders-history/comrade', 'Comrade\ComradeController@OrdersHistory')->middleware('role:comrade');

    Route::get('/item-status-change/{itemId}', 'Order\OrderItemController@itemStatusChange')->middleware('role:esp|fsp|comrade');
    Route::get('/item-quantity-update/{itemId}/{qty}', 'Order\OrderItemController@itemQuantityChange')->middleware('role:esp|fsp|comrade');

    Route::resource('career', 'CareerController');
    
    Route::POST('/give-feedback-rating-process', 'FeedbackController@giveFeedbackRatinProcess'); 
    Route::get('check-feedback-order/{type}', 'FeedbackController@checkFeedbackOrder');

    
    // Order History Download PDF
    // Order History Send Mail

    // Quick Order History Download PDF
    // Quick Quick History Send Mail

    // Order Details Download PDF
    // Order Details send to mail

    // Order Invoice Download PDF
    // Order Invoice Send Mail

    // Statement Download PDF
    // Statement send to mail

    // Cashout History Download PDF
    // Cashout History send to mail

    // Recharge History Download PDF
    // Recharge History send to mail

    // user and sp
    Route::get('download-order-invoice/{type}/{order_id}', 'Order\OrderSystemController@downloadOrderInvoice');
    Route::POST('/send-order-invoice-via-mail', 'Order\OrderSystemController@sendOrderInvoice');

    // user and sp
    Route::get('download-order-history/{type}', 'Order\OrderSystemController@downloadOrderHistory');
    Route::POST('/send-order-history-via-mail', 'Order\OrderSystemController@sendOrderHistory');

    // user
    Route::get('download-quick-order-history/{type}', 'Order\OrderSystemController@downloadQuickOrderHistory');
    Route::POST('/send-quick-order-history-via-mail', 'Order\OrderSystemController@sendQuickOrderHistory');

    // sp
    Route::get('download-statement/{type}', 'ServiceProvider\ServiceProviderController@downloadStatment')->middleware('role:esp|fsp');
    Route::POST('/send-statment-via-mail', 'ServiceProvider\ServiceProviderController@sendStatment');

    // sp
    Route::get('download-cashout-history/{type}', 'ServiceProvider\ServiceProviderController@downloadCashoutHistory')->middleware('role:esp|fsp');
    Route::POST('/send-cashout-history-via-mail', 'ServiceProvider\ServiceProviderController@sendCashoutHistory');

    // sp
    Route::get('download-recharge-history/{type}', 'ServiceProvider\ServiceProviderController@downloadRecharegHistory')->middleware('role:esp|fsp');
    Route::POST('/send-recharge-history-via-mail', 'ServiceProvider\ServiceProviderController@sendRechargeHistory');
    Route::POST('/service-provider-document-upload', 'ServiceProvider\ServiceProviderController@documentUpload');
});