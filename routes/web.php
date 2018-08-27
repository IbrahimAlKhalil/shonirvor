<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'Frontend\HomeController@index')->name('frontend.home');

Route::get('/backend', 'Backend\HomeController@index')->name('backend.home');






/*** Ibrahim ***/