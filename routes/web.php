<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::prefix('command')->group(function () {
    Route::get('storage-link', 'CommandController@storage');
}, '');

Route::get('/', 'Frontend\HomeController')->name('home');
Route::get('dashboard', 'Backend\DashboardController')->name('dashboard');

Route::get('filter', 'Frontend\FilterController')->name('frontend.filter');
Route::resource('profile', 'Frontend\ProfileController', ['only' => ['index', 'edit', 'update']]);

Route::get('individual-service-provider', 'Frontend\IndServiceController@index')->name('frontend.ind-service.index');
Route::get('individual-service-provider/{provider}', 'Frontend\IndServiceController@show')->name('frontend.ind-service.show');

Route::get('organization-service-provider', 'Frontend\OrgServiceController@index')->name('frontend.org-service.index');
Route::get('organization-service-provider/{provider}', 'Frontend\OrgServiceController@show')->name('frontend.org-service.show');

Route::post('individual-feedback', 'Frontend\FeedbackController@indStore')->name('ind-feedback.store');
Route::post('organization-feedback', 'Frontend\FeedbackController@orgStore')->name('org-feedback.store');

Route::get('dashboard/notifications', 'NotificationController@show')->name('notification.show');
Route::post('dashboard/notification/send/{user}', 'NotificationController@send')->name('notification.send');

Route::post('dashboard/sms/send/{user}', 'SmsController@send')->name('sms.send');

Route::get('dashboard/area/division', 'Backend\AreaController@division')->name('backend.area.division');
Route::get('dashboard/area/district/{division}', 'Backend\AreaController@district')->name('backend.area.district');
Route::get('dashboard/area/thana/{district}', 'Backend\AreaController@thana')->name('backend.area.thana');
Route::get('dashboard/area/union/{thana}', 'Backend\AreaController@union')->name('backend.area.union');
Route::get('dashboard/area/village/{union}', 'Backend\AreaController@village')->name('backend.area.village');

Route::post('dashboard/area/division', 'Backend\AreaController@storeDivision')->name('backend.area.division.store');
Route::post('dashboard/area/district/{divisionId}', 'Backend\AreaController@storeDistrict')->name('backend.area.district.store');
Route::post('dashboard/area/thana/{districtId}', 'Backend\AreaController@storeThana')->name('backend.area.thana.store');
Route::post('dashboard/area/union/{thanaId}', 'Backend\AreaController@storeUnion')->name('backend.area.union.store');
Route::post('dashboard/area/village/{villageId}', 'Backend\AreaController@storeVillage')->name('backend.area.village.store');

Route::delete('dashboard/area/division/{division}', 'Backend\AreaController@destroyDivision')->name('backend.area.division.destroy');
Route::delete('dashboard/area/district/{district}', 'Backend\AreaController@destroyDistrict')->name('backend.area.district.destroy');
Route::delete('dashboard/area/thana/{thana}', 'Backend\AreaController@destroyThana')->name('backend.area.thana.destroy');
Route::delete('dashboard/area/union/{union}', 'Backend\AreaController@destroyUnion')->name('backend.area.union.destroy');
Route::delete('dashboard/area/village/{village}', 'Backend\AreaController@destroyVillage')->name('backend.area.village.destroy');

Route::put('dashboard/area/division/{division}', 'Backend\AreaController@updateDivision')->name('backend.area.division.update');
Route::put('dashboard/area/district/{district}', 'Backend\AreaController@updateDistrict')->name('backend.area.district.update');
Route::put('dashboard/area/thana/{thana}', 'Backend\AreaController@updateThana')->name('backend.area.thana.update');
Route::put('dashboard/area/union/{union}', 'Backend\AreaController@updateUnion')->name('backend.area.union.update');
Route::put('dashboard/area/village/{village}', 'Backend\AreaController@updateVillage')->name('backend.area.village.update');

Route::resource('dashboard/ad', 'Backend\AdController', ['only' => ['index', 'store', 'update', 'destroy']])->names('backend.ad');

Route::resource('dashboard/notice', 'Backend\NoticeController', ['only' => ['index', 'store', 'update', 'destroy']])->names('backend.notice');

Route::post('individual-service/top/{ind}', 'Backend\IndServiceController@isTop')->name('ind-service.top');
Route::post('organization-service/top/{org}', 'Backend\OrgServiceController@isTop')->name('org-service.top');

/*** Ibrahim ***/
Route::view('service-provider-registration-instruction', 'frontend.registration.service-registration-instruction')->name('service-registration-instruction');

Route::middleware('auth')->group(function () {
    Route::resource('individual-service-registration', 'Frontend\IndServiceRegistrationController', [
        'only' => ['index', 'store', 'update', 'edit'], 'parameters' => [
            'individual-service-registration' => 'ind_id'
        ]]);
    Route::resource('organization-service-registration', 'Frontend\OrgServiceRegistrationController', [
        'only' => [
            'index', 'store', 'update', 'edit'
        ], 'parameters' => [
            'organization-service-registration' => 'org_id'
        ]]);
}, '');

Route::namespace('Backend')->prefix('dashboard')->group(function () {
    Route::resource('individual-service-request', 'IndServiceRequestController', ['only' => ['index', 'show', 'update', 'destroy'], 'parameters' => ['individual-service-request' => 'service-request']]);
    Route::resource('organization-service-request', 'OrgServiceRequestController', ['only' => ['index', 'show', 'update', 'destroy'], 'parameters' => ['organization-service-request' => 'service-request']]);

    Route::get('individual-service/disabled', 'IndServiceController@showDisabledAccounts')->name('individual-service.disabled');
    Route::get('organization-service/disabled', 'OrgServiceController@showDisabledAccounts')->name('organization-service.disabled');

    Route::get('individual-service/disabled/{id}', 'IndServiceController@showDisabled')->name('individual-service.show-disabled');
    Route::get('organization-service/disabled/{id}', 'OrgServiceController@showDisabled')->name('organization-service.show-disabled');

    Route::post('individual-service/activate', 'IndServiceController@activate')->name('individual-service.activate');
    Route::post('organization-service/activate', 'OrgServiceController@activate')->name('organization-service.activate');

    Route::resource('individual-service', 'IndServiceController', ['only' => ['index', 'show', 'destroy'], 'parameters' => ['individual-service' => 'ind']]);
    Route::resource('organization-service', 'OrgServiceController', ['only' => ['index', 'show', 'destroy'], 'parameters' => ['organization-service' => 'org']]);

    Route::resource('individual-category', 'IndCategoryController', ['only' => ['index', 'show', 'destroy', 'store', 'update'], 'parameters' => ['individual-category' => 'category']]);
    Route::resource('organization-category', 'OrgCategoryController', ['only' => ['index', 'show', 'destroy', 'store', 'update'], 'parameters' => ['organization-category' => 'category']]);

    Route::resource('individual-sub-category', 'IndSubCategoryController', ['only' => ['destroy', 'store', 'update'], 'parameters' => ['individual-sub-category' => 'sub_category']]);
    Route::resource('organization-sub-category', 'OrgSubCategoryController', ['only' => ['destroy', 'store', 'update'], 'parameters' => ['organization-sub-category' => 'sub_category']]);

    Route::name('profile.backend.')->prefix('profile')->group(function () {
        Route::post('individual-service/status', 'IndProfileController@updateStatus')->name('individual-service.update-status');
        Route::resource('individual-service', 'IndProfileController', ['only' => ['show', 'destroy', 'update', 'edit', 'updatePending'], 'parameters' => ['individual-service' => 'provider']]);
        Route::resource('organization-service', 'OrgProfileController', ['only' => ['show', 'destroy', 'update', 'edit'], 'parameters' => ['organization-service' => 'provider']]);
    }, '');

    Route::resource('individual-service-edit', 'IndServiceEditController', ['only' => ['index', 'show', 'store', 'destroy'], 'parameters' => ['individual-service-edit' => 'service-edit']]);
    Route::resource('organization-service-edit', 'OrgServiceEditController', ['only' => ['index', 'show', 'store', 'destroy'], 'parameters' => ['organization-service-edit' => 'service-edit']]);

    Route::name('contents.')->prefix('contents')->group(function () {
        Route::resource('registration-instruction', 'RegistrationInstructionController', ['only' => ['index', 'update']]);
        Route::resource('slider', 'SliderController', ['only' => ['index', 'update', 'destroy']]);
    }, '');

}, '');
