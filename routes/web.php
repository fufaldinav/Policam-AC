<?php
/*
 * Home Page
 */
Route::get('/', 'PortalController@index');
/*
 * Portal
 */
Route::group(['as' => 'portal.'], function () {
    Route::get('portal', 'PortalController@index')->name('index');
    Route::get('support', 'PortalController@support')->name('support');
    Route::get('contacts', 'PortalController@support')->name('contacts');
    Route::get('news', 'PortalController@news')->name('news');
    Route::get('news/{id?}', 'NewsController@getEntry')->name('news.entry');
});/*
 * Pages
 */
Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
    Route::get('/', 'PagesController@index')->name('index');
    Route::get('{id}', 'PagesController@getEntry')->name('entry');
});
/*
 * Observer
 */
Route::get('observer', 'ObserverController@index')->name('observer');
/*
 * API
 */
Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('events/{organizationId}/{count?}', 'ServersController@getEvents')->middleware('role:2,3,6,7,8');
    Route::resource('persons', 'PersonsController')->except(['create', 'edit']);
    Route::resource('photos', 'PhotosController')->only(['store', 'destroy']);
    Route::group(['prefix' => 'divisions', 'as' => 'divisions.'], function () {
        Route::get('{organizationId}/{withPersons?}', 'DivisionsController@getByOrganization');
        Route::post('/', 'DivisionsController@store');
        Route::post('{id}', 'DivisionsController@update');
        Route::delete('{id}', 'DivisionsController@destroy');
    });
    Route::get('codes', 'ReferralController@getCodes');
    Route::get('referral/divisions/{organizationId}/{type?}', 'ReferralController@getDivisions');
    Route::get('referral/organization/{organizationId}', 'ReferralController@getOrganization');
});
/*
 * Auth
 */
Auth::routes(['verify' => true]);
Route::get('logout', 'Auth\LoginController@logout');
Route::get('reg/{referral_code?}', 'ReferralController@parseLink');
Route::get('register/{referral_code}', 'Auth\RegisterController@showRegistrationForm');
Route::get('login/{referral_code}', 'Auth\LoginController@showLoginForm')->middleware('verified');
/*
 * Cards
 */
Route::group(['prefix' => 'cards', 'as' => 'cards.'], function () {
    Route::get('get_list', 'CardsController@getListOfUnknownCards');
    Route::get('get_list/{person_id}', 'CardsController@getListByPerson');
    Route::post('holder', 'CardsController@setHolder');
    Route::post('delete', 'CardsController@delete');
});
/*
 * Controllers
 */
Route::group(['prefix' => 'controllers', 'as' => 'controllers.'], function () {
    Route::get('get_list', 'ControllersController@getList');
    Route::get('set_door_params/{controller_id}/{open_time}/{?open_control}/{?close_control}', 'ControllersController@setDoorParams');
    Route::get('clear/{controller_id}', 'ControllersController@clear');
    Route::get('clear/{controller_id}/{device}', 'ControllersController@clear');
    Route::get('reload_cards/{controller_id}', 'ControllersController@reloadCards');
});
/*
 * Control Panel
 */
Route::group(['prefix' => 'cp', 'as' => 'cp.'], function () {
    Route::get('/', 'UsersController@index')->name('index');
    Route::get('classes', 'DivisionsController@classes')->name('classes')->middleware('role:2,3,7');
    Route::get('persons', 'PersonsController@page')->name('persons')->middleware('role:2,3,7');
    Route::get('statistics', 'StatisticsController@index')->name('statistics');
    Route::get('students', 'UsersController@students')->name('students');
    Route::get('timetable', 'TimetableController@page')->name('timetable')->middleware('role:1');

});
/*
 * Development
 */
Route::get('dev', 'DevController@index');
/*
 * Post registration
 */
Route::get('postreg', 'ReferralController@postreg');
/*
 * Server
 */
Route::post('server', 'ServersController@index');
Route::post('policont', 'ServersController@policont');
/*
 * Stream
 */
Route::get('stream', 'StreamController@index');
/*
 * Users
 */
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::post('token', 'UsersController@token');
    Route::get('organizations', 'UsersController@getOrganizations');
    Route::get('organizations/{type}', 'UsersController@getOrganizationsByType');
    Route::get('persons', 'UsersController@getPersons');
    Route::get('referral_codes', 'UsersController@getReferralCodes');
});
Route::get('users/notification/{hash}', 'UsersController@notification');
/*
 * Util
 */
Route::group(['prefix' => 'util', 'as' => 'util.'], function () {
    Route::post('save_errors', 'UtilsController@saveErrors');
    Route::post('card_problem', 'UtilsController@cardProblem');
});
