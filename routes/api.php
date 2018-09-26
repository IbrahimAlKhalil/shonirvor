<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->name('api.')->group(function () {

    Route::get('divisions/{id?}', 'AreaController@divisions')->name('divisions');
    Route::get('districts/{id?}', 'AreaController@districts')->name('districts');
    Route::get('thanas/{id?}', 'AreaController@thanas')->name('thanas');
    Route::get('unions/{id?}', 'AreaController@unions')->name('unions');
    Route::get('categories/{id?}', 'CategoryController@categories')->name('categories');
    Route::get('sub-categories/{id?}', 'SubCategoryController@subCategories')->name('sub-categories');

}, '');
