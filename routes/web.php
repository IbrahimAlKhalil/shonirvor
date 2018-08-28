<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');
Route::get('backend', 'Backend\HomeController@index')->name('backend.home');

Route::get('/backend', 'Backend\HomeController@index')->name('backend.home');
Route::resource('backend/dealer', 'Backend\DealerController');


/*** Ibrahim ***/

Route::resource('dealer-registration', 'Frontend\DealerRegistrationController', ['only' => ['index', 'store']]);

Route::get('dealer-request/approve/{id}', 'Backend\DealerRequestController@approve')->name('dealer-request.approve');
Route::get('dealer-request/reject/{id}', 'Backend\DealerRequestController@reject')->name('dealer-request.reject');
Route::resource('dealer-request', 'Backend\DealerRequestController', ['only' => ['index', 'show']]);
