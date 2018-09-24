<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::prefix('command')->group(function () {
    Route::get('storage-link', 'CommandController@storage');
}, '');

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('dashboard', 'Backend\HomeController@index')->name('backend.home');
Route::resource('profile', 'Frontend\ProfileController', ['only' => ['index', 'edit', 'update']]);

Route::get('individual-service-provider', 'Frontend\IndServiceController@index')->name('frontend.ind-service.index');
Route::get('individual-service-provider/{provider}', 'Frontend\IndServiceController@show')->name('frontend.ind-service.show');

Route::get('organization-service-provider', 'Frontend\OrgServiceController@index')->name('frontend.org-service.index');
Route::get('organization-service-provider/{provider}', 'Frontend\OrgServiceController@show')->name('frontend.org-service.show');

Route::post('individual-feedback', 'Frontend\IndFeedbackController@store')->name('ind-feedback.store');
Route::post('organization-feedback', 'Frontend\OrgFeedbackController@store')->name('org-feedback.store');

Route::get('notifications', 'NotificationController@show')->name('notification.show');
Route::post('notification/send/{user}', 'NotificationController@send')->name('notification.send');

Route::post('sms/send/{user}', 'SmsController@send')->name('sms.send');

/*** Ibrahim ***/
Route::view('service-provider-registration-instruction', 'frontend.registration.service-registration-instruction')->name('service-registration-instruction');

Route::middleware('auth')->group(function () {
    Route::resource('individual-service-registration', 'Frontend\IndServiceRegistrationController', [
        'only' => ['index', 'store', 'update', 'edit'], 'parameters' => [
            'individual-service-registration' => 'ind_id'
        ]]);
    Route::resource('organization-service-registration', 'Frontend\OrgServiceRegistrationController', [
        'only' => [
            'index', 'store', 'update', 'edit'
        ], 'parameters' => [
            'organization-service-registration' => 'org_id'
        ]]);
}, '');

Route::namespace('Backend')->prefix('dashboard')->group(function () {
    Route::resource('individual-service-request', 'IndServiceRequestController', ['only' => ['index', 'show', 'store', 'destroy'], 'parameters' => ['individual-service-request' => 'service-request']]);
    Route::resource('organization-service-request', 'OrgServiceRequestController', ['only' => ['index', 'show', 'store', 'destroy'], 'parameters' => ['organization-service-request' => 'service-request']]);

    Route::get('individual-service/disabled', 'IndServiceController@showDisabledAccounts')->name('individual-service.disabled');
    Route::get('organization-service/disabled', 'OrgServiceController@showDisabledAccounts')->name('organization-service.disabled');

    Route::get('individual-service/disabled/{id}', 'IndServiceController@showDisabled')->name('individual-service.show-disabled');
    Route::get('organization-service/disabled/{id}', 'OrgServiceController@showDisabled')->name('organization-service.show-disabled');

    Route::post('individual-service/activate', 'IndServiceController@activate')->name('individual-service.activate');
    Route::post('organization-service/activate', 'OrgServiceController@activate')->name('organization-service.activate');

    Route::resource('individual-service', 'IndServiceController', ['only' => ['index', 'show', 'destroy'], 'parameters' => ['individual-service' => 'ind']]);
    Route::resource('organization-service', 'OrgServiceController', ['only' => ['index', 'show', 'destroy'], 'parameters' => ['organization-service' => 'org']]);

    Route::resource('individual-category', 'IndCategoryController', ['only' => ['index', 'show', 'destroy', 'store', 'update'], 'parameters' => ['individual-category' => 'category']]);
    Route::resource('organization-category', 'OrgCategoryController', ['only' => ['index', 'show', 'destroy', 'store', 'update'], 'parameters' => ['organization-category' => 'category']]);

    Route::resource('individual-sub-category', 'IndSubCategoryController', ['only' => ['destroy', 'store', 'update'], 'parameters' => ['individual-sub-category' => 'sub_category']]);
    Route::resource('organization-sub-category', 'OrgSubCategoryController', ['only' => ['destroy', 'store', 'update'], 'parameters' => ['organization-sub-category' => 'sub_category']]);

    Route::name('profile.backend.')->prefix('profile')->group(function () {
        Route::resource('individual-service', 'IndProfileController', ['only' => ['show', 'destroy', 'update', 'edit', 'updatePending'], 'parameters' => ['individual-service' => 'provider']]);
        Route::resource('organization-service', 'OrgProfileController', ['only' => ['show', 'destroy', 'update', 'edit'], 'parameters' => ['organization-service' => 'provider']]);
    }, '');

    Route::resource('individual-service-edit', 'IndServiceEditController', ['only' => ['index', 'show', 'store', 'destroy'], 'parameters' => ['individual-service-edit' => 'service-edit']]);
    Route::resource('organization-service-edit', 'OrgServiceEditController', ['only' => ['index', 'show', 'store', 'destroy'], 'parameters' => ['organization-service-edit' => 'service-edit']]);
}, '');
