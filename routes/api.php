<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Api')->name('api.')->group(function () {

    Route::get('divisions/{id?}', 'AreaController@divisions')->name('divisions');
    Route::get('districts/{id?}', 'AreaController@districts')->name('districts');
    Route::get('thanas/{id?}', 'AreaController@thanas')->name('thanas');
    Route::get('unions/{id?}', 'AreaController@unions')->name('unions');
    Route::get('villages/{id?}', 'AreaController@villages')->name('villages');

    Route::get('categories/{id?}', 'CategoryController@categories')->name('categories');
    Route::get('sub-categories/{id?}', 'SubCategoryController')->name('sub-categories');

    Route::get('work-methods', 'WorkMethodController')->name('work-methods');

}, '');