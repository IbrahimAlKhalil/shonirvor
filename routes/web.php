<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('dashboard', 'Backend\HomeController@index')->name('backend.home');

Route::resource('dashboard/dealer', 'Backend\DealerController', ['except' => ['create', 'store']]);
Route::get('dealer-instruction', 'Frontend\DealerRegistrationController@instruction')->name('dealer.instruction');
Route::resource('dealer-registration', 'Frontend\DealerRegistrationController', ['except' => ['create', 'show', 'delete']])->middleware('auth');
Route::resource('dealer-request', 'Backend\DealerRequestController', ['only' => ['index', 'show']]);
Route::post('dealer-request/approve/{id}', 'Backend\DealerRequestController@approve')->name('dealer-request.approve');
Route::delete('dealer-request/reject/{id}', 'Backend\DealerRequestController@reject')->name('dealer-request.reject');

/*** Ibrahim ***/
Route::middleware('auth')
    ->name('registration.')
    ->prefix('registration/service-provider')
    ->group(function () {
        Route::view('', 'frontend.registration.service-provider-agreement')->name('service-provider.agreement');
        Route::resource('individual', 'Frontend\IndServiceProviderRegistrationController', ['only' => ['index', 'store']]);
        Route::resource('organization', 'Frontend\OrgServiceProviderRegistrationController', ['only' => ['index', 'store']]);
    });

Route::middleware('auth')
    ->name('service-provider-request.')
    ->prefix('backend/service-provider-request')
    ->group(function () {
        Route::resource('individual', 'Backend\IndServiceProviderRequestController', ['only' => ['index', 'show']]);
        Route::resource('organization', 'Backend\OrgServiceProviderRequestController', ['only' => ['index', 'show']]);
    });
