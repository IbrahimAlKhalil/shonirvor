<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Frontend\HomeController@index');


/*** Ibrahim ***/

Route::resource('dealer-registration', 'Frontend\DealerRegistrationController', ['only' => ['index', 'store', 'update', 'destroy']]);