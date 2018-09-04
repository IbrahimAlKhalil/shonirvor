<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('dashboard', 'Backend\HomeController@index')->name('backend.home');

Route::resource('dashboard/dealer', 'Backend\DealerController');


/*** Ibrahim ***/

Route::get('dealer-request/approve/{id}', 'Backend\DealerRequestController@approve')->name('dealer-request.approve');
Route::get('dealer-request/reject/{id}', 'Backend\DealerRequestController@reject')->name('dealer-request.reject');
Route::resource('dealer-request', 'Backend\DealerRequestController', ['only' => ['index', 'show']])->middleware('auth');


Route::middleware('auth')
    ->name('registration.')
    ->prefix('registration')
    ->group(function () {
        Route::resource('dealer', 'Frontend\DealerRegistrationController', ['only' => ['index', 'store']]);
    });

Route::view('service-provider-registration-instruction', 'frontend.registration.service-registration-instruction')->name('service-registration-instruction');

Route::middleware('auth')->group(function () {

    Route::resource('ind-service-registration', 'Frontend\IndServiceRegistrationController', ['only' => ['index', 'store', 'update']]);
    Route::resource('org-service-registration', 'Frontend\OrgServiceRegistrationController', ['only' => ['index', 'store', 'update', 'edit']]);
});

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::resource('ind-service-request', 'Backend\IndServiceRequestController', ['only' => ['index', 'show', 'store', 'destroy']]);
    Route::resource('org-service-request', 'Backend\OrgServiceRequestController', ['only' => ['index', 'show', 'store', 'destroy']]);

    Route::get('ind-service/disabled', 'Backend\IndServiceController@showDisabledAccounts')->name('ind-service.disabled');
    Route::get('org-service/disabled', 'Backend\OrgServiceController@showDisabledAccounts')->name('org-service.disabled');

    Route::get('ind-service/disabled/{id}', 'Backend\IndServiceController@showDisabled')->name('ind-service.show-disabled');
    Route::get('org-service/disabled/{id}', 'Backend\OrgServiceController@showDisabled')->name('org-service.show-disabled');

    Route::post('ind-service/activate', 'Backend\IndServiceController@activate')->name('ind-service.activate');
    Route::post('org-service/activate', 'Backend\OrgServiceController@activate')->name('org-service.activate');

    Route::resource('ind-service', 'Backend\IndServiceController', ['only' => ['index', 'show', 'destroy']]);
    Route::resource('org-service', 'Backend\OrgServiceController', ['only' => ['index', 'show', 'destroy']]);
});
