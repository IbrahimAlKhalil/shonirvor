<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('dashboard', 'Backend\HomeController@index')->name('backend.home');
Route::resource('profile', 'Frontend\ProfileController', ['only' => ['index', 'edit', 'update']]);

Route::resource('dashboard/dealer', 'Backend\DealerController', ['except' => ['create', 'store']]);
Route::get('dealer-instruction', 'Frontend\DealerRegistrationController@instruction')->name('dealer.instruction');
Route::resource('dealer-registration', 'Frontend\DealerRegistrationController', ['except' => ['create', 'show', 'delete']])->middleware('auth');
Route::resource('dealer-request', 'Backend\DealerRequestController', ['only' => ['index', 'show']]);
Route::post('dealer-request/approve/{id}', 'Backend\DealerRequestController@approve')->name('dealer-request.approve');
Route::delete('dealer-request/reject/{id}', 'Backend\DealerRequestController@reject')->name('dealer-request.reject');

Route::get('individual-service-provider', 'Frontend\IndServiceController@index')->name('frontend.ind-service.index');
Route::get('individual-service-provider/{provider}', 'Frontend\IndServiceController@show')->name('frontend.ind-service.show');

Route::get('organization-service-provider', 'Frontend\OrgServiceController@index')->name('frontend.org-service.index');
Route::get('organization-service-provider/{provider}', 'Frontend\OrgServiceController@show')->name('frontend.org-service.show');

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
}, '');