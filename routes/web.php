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

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');


Route::middleware('auth')
    ->name('registration.')
    ->prefix('registration')
    ->group(function () {
        Route::resource('dealer', 'Frontend\DealerRegistrationController', ['only' => ['index', 'store']]);
    });


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
