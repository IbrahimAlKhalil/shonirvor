<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('password/request', 'Auth\PasswordResetController@showRequestForm')->name('password.request');
Route::post('password/request', 'Auth\PasswordResetController@sendCode');
Route::get('password/reset/{user}', 'Auth\PasswordResetController@showResetForm')->name('password.reset');
Route::post('password/reset/{user}', 'Auth\PasswordResetController@reset');

Route::get('verification/{user}', 'Auth\RegisterController@verificationForm')->name('verification');
Route::post('verification/{user}', 'Auth\RegisterController@verification');

Route::prefix('command')->group(function () {

    Route::get('storage-link', 'CommandController@storage');
    Route::get('migrate-database', 'CommandController@migrateDatabase');
    Route::get('config-cache', 'CommandController@configCache');
    Route::get('route-cache', 'CommandController@routeCache');

}, '');

Route::post('sms/send/{user}', 'SmsController@send')->name('sms.send');

Route::post('notification/send/{user}', 'NotificationController@send')->name('notification.send');

Route::namespace('Frontend')->group(function () {

    Route::get('/', 'HomeController')->name('home');

    Route::get('profile/mobile-verification/{profile}', 'ProfileController@showMobileVerificationPage')->name('profile.mobile-verification.show');
    Route::post('profile/mobile-verification/{profile}', 'ProfileController@saveMobileEdit')->name('profile.mobile-verification.store');
    Route::post('profile/payment-receive-method/{profile}', 'ProfileController@paymentReceiveMethod')->name('profile.payment-receive-method');
    Route::resource('profile', 'ProfileController', ['only' => ['index', 'edit', 'update']]);

    Route::name('frontend.')->group(function () {

        Route::get('filter', 'FilterController')->name('filter');
//
//        Route::get('individual-service/{provider}', 'IndServiceController@show')->name('ind-service.show');
//        Route::get('organization-service/{provider}', 'OrgServiceController@show')->name('org-service.show');

        Route::prefix('applications')->name('applications.')->group(function () {

            Route::resource('individual-top-service', 'IndTopServiceApplicationController', [
                'except' => ['create', 'show', 'destroy'],
                'parameters' => ['individual-top-service' => 'application']
            ]);

            Route::resource('organization-top-service', 'OrgTopServiceApplicationController', [
                'except' => ['create', 'show', 'destroy'],
                'parameters' => ['organization-top-service' => 'application']
            ]);

            Route::resource('org-top-service', 'OrgTopServiceApplicationController', [
                'except' => ['create', 'show', 'destroy'],
                'parameters' => ['org-top-service' => 'application']
            ]);

            Route::resource('individual-service', 'IndRenewApplicationController', [
                'only' => ['index', 'store', 'edit', 'update'],
                'parameters' => ['individual-service' => 'application']
            ]);

            Route::resource('organization-service', 'OrgRenewApplicationController', [
                'only' => ['index', 'store', 'edit', 'update'],
                'parameters' => ['organization-service' => 'application']
            ]);


            Route::resource('ad', 'AdApplicationController', [
                'only' => ['index', 'store', 'edit', 'update'],
                'parameters' => ['ad' => 'application']
            ]);

            Route::resource('ad-renew', 'AdRenewApplicationController', [
                'only' => ['show', 'edit', 'store', 'update'],
                'parameters' => ['ad-renew' => 'ad']
            ]);

        }, '');

        Route::prefix('my-services')->name('my-service.')->group(function () {

            Route::post('individual-service/{id}/update-status', 'IndMyServiceController@updateStatus')->name('ind.update-status');
            Route::resource('individual-service', 'IndMyServiceController', [
                'only' => ['show', 'edit', 'update', 'destroy'],
                'parameters' => ['individual-service' => 'service'],
                'names' => 'ind'
            ]);

            Route::resource('organization-service', 'OrgMyServiceController', [
                'only' => ['show', 'edit', 'update', 'destroy'],
                'parameters' => ['organization-service' => 'service'],
                'names' => 'org'
            ]);

        }, '');

    }, '');

}, '');

Route::namespace('Backend')->group(function () {

    Route::get('dashboard', 'DashboardController')->name('dashboard');

    Route::name('backend.')->group(function () {

        Route::prefix('dashboard')->group(function () {

            Route::prefix('requests')->name('request.')->group(function () {

                Route::resource('top-service', 'TopServiceRequestController', [
                    'except' => ['create', 'store', 'edit'],
                    'parameters' => ['top-service' => 'application']
                ]);

                Route::resource('individual-service-request', 'IndServiceRequestController', [
                    'except' => ['create', 'store', 'edit'],
                    'parameters' => ['individual-service-request' => 'application'],
                    'names' => 'ind-service-request'
                ]);

                Route::resource('organization-service-request', 'OrgServiceRequestController', [
                    'except' => ['create', 'store', 'edit'],
                    'parameters' => ['organization-service-request' => 'application'],
                    'names' => 'org-service-request'
                ]);

                Route::resource('ad', 'AdRequestController', [
                    'only' => ['index', 'show', 'update', 'destroy'],
                    'parameters' => ['ad' => 'application']
                ]);

                Route::resource('service-renew', 'ServiceRenewRequestController', [
                    'only' => ['index', 'show', 'update', 'destroy'],
                    'parameters' => ['service-renew' => 'application']
                ]);

                Route::resource('ad-edit', 'AdEditRequestController', [
                    'only' => ['index', 'show', 'update', 'destroy']
                ]);

                Route::resource('individual-service-edit', 'IndServiceEditRequestController', [
                    'only' => ['store', 'show', 'index', 'destroy'],
                    'parameters' => ['individual-service-edit' => 'application'],
                    'names' => 'ind-service-edit'
                ]);

                Route::resource('organization-service-edit', 'OrgServiceEditRequestController', [
                    'only' => ['store', 'show', 'index', 'destroy'],
                    'parameters' => ['organization-service-edit' => 'application'],
                    'names' => 'org-service-edit'
                ]);

            }, '');

            Route::put('user/refer-package/{user}', 'UserController@updateReferPackage')->name('user.refer-package');
            Route::put('user/pay-referrer/{user}', 'UserController@payReferrer')->name('user.pay-referrer');
            Route::post('user-filter', 'UserController@filter')->name('users.filter');
            Route::post('user-send-sms', 'UserController@sendSms')->name('users.send-sms');
            Route::resource('users', 'UserController', [
                'only' => ['index', 'show', 'destroy']
            ]);

            Route::prefix('packages')->name('package.')->group(function () {

                Route::resource('ind-service', 'IndServicePackageController', [
                    'parameters' => ['ind-service' => 'package']
                ]);
                Route::post('ind-service/restore', 'IndServicePackageController@restore')->name('ind-service.restore');

                Route::resource('org-service', 'OrgServicePackageController', [
                    'parameters' => ['org-service' => 'package']
                ]);
                Route::post('org-service/restore', 'OrgServicePackageController@restore')->name('org-service.restore');

                Route::resource('ind-top-service', 'IndTopServiceController', [
                    'only' => ['index', 'store', 'update', 'destroy'],
                    'parameters' => ['ind-top-service' => 'package']
                ]);
                Route::post('ind-top-service/restore', 'IndTopServiceController@restore')->name('ind-top-service.restore');


                Route::resource('org-top-service', 'OrgTopServiceController', [
                    'only' => ['index', 'store', 'update', 'destroy'],
                    'parameters' => ['ind-top-service' => 'package']
                ]);
                Route::post('org-top-service/restore', 'OrgTopServiceController@restore')->name('org-top-service.restore');

                Route::resource('referrer', 'ReferrerPackageController', [
                    'only' => ['index', 'store', 'update', 'destroy'],
                    'parameters' => ['referrer' => 'package']
                ]);
                Route::post('referrer/restore', 'ReferrerPackageController@restore')->name('referrer.restore');

                Route::resource('ad', 'AdPackageController', [
                    'only' => ['index', 'store', 'update', 'destroy'],
                    'parameters' => ['ad' => 'package']
                ]);
                Route::post('ad/restore', 'AdPackageController@restore')->name('ad.restore');

            }, '');

            Route::resource('notices', 'NoticeController', [
                'only' => ['index', 'store', 'update', 'destroy']
            ])->names('notice');

            Route::prefix('area')->name('area.')->group(function () {

                // Show
                Route::get('division', 'AreaController@division')->name('division');
                Route::get('district/{division}', 'AreaController@district')->name('district');
                Route::get('thana/{district}', 'AreaController@thana')->name('thana');
                Route::get('union/{thana}', 'AreaController@union')->name('union');
                Route::get('village/{union}', 'AreaController@village')->name('village');

                // Store
                Route::post('division', 'AreaController@storeDivision')->name('division.store');
                Route::post('district/{divisionId}', 'AreaController@storeDistrict')->name('district.store');
                Route::post('thana/{districtId}', 'AreaController@storeThana')->name('thana.store');
                Route::post('union/{thanaId}', 'AreaController@storeUnion')->name('union.store');
                Route::post('village/{villageId}', 'AreaController@storeVillage')->name('village.store');

                // Update
                Route::put('division/{division}', 'AreaController@updateDivision')->name('division.update');
                Route::put('district/{district}', 'AreaController@updateDistrict')->name('district.update');
                Route::put('thana/{thana}', 'AreaController@updateThana')->name('thana.update');
                Route::put('union/{union}', 'AreaController@updateUnion')->name('union.update');
                Route::put('village/{village}', 'AreaController@updateVillage')->name('village.update');

                // Destroy
                Route::delete('division/{division}', 'AreaController@destroyDivision')->name('division.destroy');
                Route::delete('district/{district}', 'AreaController@destroyDistrict')->name('district.destroy');
                Route::delete('thana/{thana}', 'AreaController@destroyThana')->name('thana.destroy');
                Route::delete('union/{union}', 'AreaController@destroyUnion')->name('union.destroy');
                Route::delete('village/{village}', 'AreaController@destroyVillage')->name('village.destroy');

            }, '');

        }, '');

    }, '');

}, '');

Route::view('service-provider-registration-instruction', 'frontend.registration.service-registration-instruction')->name('service-registration-instruction');

Route::middleware('auth')->group(function () {
    Route::resource('individual-service-registration', 'Frontend\IndServiceRegistrationController', [
        'only' => ['index', 'store', 'update', 'edit', 'destroy'],
        'parameters' => ['individual-service-registration' => 'ind']
    ]);
    Route::resource('organization-service-registration', 'Frontend\OrgServiceRegistrationController', [
        'only' => ['index', 'store', 'update', 'edit', 'destroy'],
        'parameters' => ['organization-service-registration' => 'org']
    ]);


    Route::middleware('throttle:60,15')->group(function () {
        Route::resource('chat', 'Frontend\ConversationController', ['only' => ['destroy', 'store']]);
        Route::get('chat', 'Frontend\ConversationController@index')->middleware('auth')->name('chat.index');
        Route::get('chat/get-accounts', 'Frontend\ConversationController@getAccounts')->name('chat.getAccounts');
        Route::get('chat/get-conversations', 'Frontend\ConversationController@getConversations')->name('chat.getConversations');

        Route::resource('chat-messages', 'Frontend\ChatMessageController', ['only' => ['index', 'store', 'destroy']]);
    });

}, '');


Route::namespace('Frontend')->name('frontend.')->group(function () {
    Route::resource('ad', 'AdController', [
        'only' => ['edit', 'update']
    ]);
}, '');


Route::namespace('Backend')->prefix('dashboard')->group(function () {

    Route::get('individual-service/disabled', 'IndServiceController@showDisabledAccounts')->name('individual-service.disabled');
    Route::get('organization-service/disabled', 'OrgServiceController@showDisabledAccounts')->name('organization-service.disabled');

    Route::get('individual-service/disabled/{id}', 'IndServiceController@showDisabled')->name('individual-service.show-disabled');
    Route::get('organization-service/disabled/{id}', 'OrgServiceController@showDisabled')->name('organization-service.show-disabled');

    Route::post('individual-service/activate', 'IndServiceController@activate')->name('individual-service.activate');
    Route::post('organization-service/activate', 'OrgServiceController@activate')->name('organization-service.activate');

    Route::resource('individual-service', 'IndServiceController', [
        'only' => ['show', 'destroy'],
        'parameters' => ['individual-service' => 'ind']
    ]);
    Route::resource('organization-service', 'OrgServiceController', [
        'only' => ['show', 'destroy'],
        'parameters' => ['organization-service' => 'org']
    ]);

    Route::get('service-providers', 'FilterController@index')->name('service-filter');
    Route::post('service-filter', 'FilterController@getData')->name('service-filter.get-data');
    Route::post('service-providers/send-sms', 'FilterController@sendSms')->name('service-filter.send-sms');
    Route::post('service-providers/send-notification', 'FilterController@sendNotification')->name('service-filter.send-notification');

    Route::post('message-templates/delete', 'MessageTemplateController@delete')->name('message-templates.destroy');
    Route::resource('message-templates', 'MessageTemplateController', ['only' => ['store', 'update']]);


    Route::resource('individual-category', 'IndCategoryController', [
        'only' => ['index', 'show', 'destroy', 'store', 'update'],
        'parameters' => ['individual-category' => 'category']
    ]);
    Route::resource('organization-category', 'OrgCategoryController', [
        'only' => ['index', 'show', 'destroy', 'store', 'update'],
        'parameters' => ['organization-category' => 'category']
    ]);

    Route::resource('individual-sub-category', 'IndSubCategoryController', [
        'only' => ['destroy', 'store', 'update'],
        'parameters' => ['individual-sub-category' => 'sub_category']
    ]);
    Route::resource('organization-sub-category', 'OrgSubCategoryController', [
        'only' => ['destroy', 'store', 'update'],
        'parameters' => ['organization-sub-category' => 'sub_category']
    ]);

    // TODO:: This routes must be marged with "Frontend my-services"
//    Route::name('profile.backend.')->prefix('profile')->group(function () {
//        Route::post('individual-service/status', 'IndProfileController@updateStatus')->name('individual-service.update-status');
//        Route::resource('individual-service', 'IndProfileController', ['only' => [
//            'show', 'destroy', 'update', 'edit', 'updatePending'],
//            'parameters' => ['individual-service' => 'provider']
//        ]);
//        Route::resource('organization-service', 'OrgProfileController', [
//            'only' => ['show', 'destroy', 'update', 'edit'],
//            'parameters' => ['organization-service' => 'provider']
//        ]);
//    }, '');

//    Route::resource('individual-service-edit', 'IndServiceEditController', [
//        'only' => ['index', 'show', 'store', 'destroy'],
//        'parameters' => ['individual-service-edit' => 'service-edit']
//    ]);
//    Route::resource('organization-service-edit', 'OrgServiceEditController', [
//        'only' => ['index', 'show', 'store', 'destroy'],
//        'parameters' => ['organization-service-edit' => 'service-edit']
//    ]);

    Route::name('contents.')->prefix('contents')->group(function () {
        Route::resource('registration-instruction', 'RegistrationInstructionController', [
            'only' => ['index', 'update']
        ]);
        Route::resource('slider', 'SliderController', [
            'only' => ['index', 'update', 'destroy']
        ]);
    }, '');

}, '');

Route::get('payments', 'Frontend\PaymentController')->name('payments');

Route::get('/{slug}', 'Frontend\ServiceController@show')->where(['slug', '/^[A-Za-z0-9]+(?:[_\-\.]*)?(?:\w+)$/']);
Route::post('/indFeedback', 'Frontend\ServiceController@IndFeedbackStore')->name('indFeedback.store');
Route::post('/orgFeedback', 'Frontend\ServiceController@OrgFeedbackStore')->name('orgFeedback.store');
Route::delete('_WfdsfOGFYDTUIOJFdf', 'Frontend\ServiceController@deleteFeedback')->name('feedback.delete');
