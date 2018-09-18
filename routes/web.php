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

Route::get('dashboard/profile/individual-service/{provider}', 'Backend\IndProfileController@show')->name('backend.ind-service.profile');
Route::get('dashboard/profile/organization-service/{provider}', 'Backend\OrgProfileController@show')->name('backend.org-service.profile');

Route::post('individual-feedback', 'Frontend\IndFeedbackController@store')->name('ind-feedback.store');
Route::post('organization-feedback', 'Frontend\OrgFeedbackController@store')->name('org-feedback.store');

Route::get('notifications', 'NotificationController@show')->name('notification.show');
Route::post('notification/send/{user}', 'NotificationController@send')->name('notification.send');

Route::post('sms/send/{user}', 'SmsController@send')->name('sms.send');

/*** Ibrahim ***/
Route::view('service-provider-registration-instruction', 'frontend.registration.service-registration-instruction')->name('service-registration-instruction');

Route::middleware('auth')->group(function () {
    Route::resource('individual-service-registration', 'Frontend\IndServiceRegistrationController', [
        'only' => ['index', 'store', 'update'], 'parameters' => [
            'individual-service-registration' => 'ind_id'
        ]]);
    Route::resource('organization-service-registration', 'Frontend\OrgServiceRegistrationController', [
        'only' => [
            'index', 'store', 'update', 'edit'
        ], 'parameters' => [
            'organization-service-registration' => 'org_id'
        ]]);
}, '');

Route::prefix('dashboard')->group(function () {
    Route::resource('individual-service-request', 'Backend\IndServiceRequestController', ['only' => ['index', 'show', 'store', 'destroy']]);
    Route::resource('organization-service-request', 'Backend\OrgServiceRequestController', ['only' => ['index', 'show', 'store', 'destroy']]);

    Route::get('individual-service/disabled', 'Backend\IndServiceController@showDisabledAccounts')->name('individual-service.disabled');
    Route::get('organization-service/disabled', 'Backend\OrgServiceController@showDisabledAccounts')->name('organization-service.disabled');

    Route::get('individual-service/disabled/{id}', 'Backend\IndServiceController@showDisabled')->name('individual-service.show-disabled');
    Route::get('organization-service/disabled/{id}', 'Backend\OrgServiceController@showDisabled')->name('organization-service.show-disabled');

    Route::post('individual-service/activate', 'Backend\IndServiceController@activate')->name('individual-service.activate');
    Route::post('organization-service/activate', 'Backend\OrgServiceController@activate')->name('organization-service.activate');

    Route::resource('individual-service', 'Backend\IndServiceController', ['only' => ['index', 'show', 'destroy']]);
    Route::resource('organization-service', 'Backend\OrgServiceController', ['only' => ['index', 'show', 'destroy']]);

    Route::resource('individual-category', 'Backend\IndCategoryController', ['only' => ['index', 'show', 'destroy', 'store', 'update'], 'parameters' => ['individual-category' => 'ind_category']]);
    Route::resource('organization-category', 'Backend\OrgCategoryController', ['only' => ['index', 'show', 'destroy', 'store', 'update'], 'parameters' => ['organization-category' => 'org_category']]);

    Route::resource('individual-sub-category', 'Backend\IndSubCategoryController', ['only' => ['destroy', 'store', 'update'], 'parameters' => ['individual-sub-category' => 'ind_sub_category']]);
    Route::resource('organization-sub-category', 'Backend\OrgSubCategoryController', ['only' => ['destroy', 'store', 'update'], 'parameters' => ['organization-sub-category' => 'org_sub_category']]);
}, '');