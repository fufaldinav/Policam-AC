<?php
/*
 * Home Page
 */
Route::redirect('/', 'cp');
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
});
/*
 * Auth
 */
Auth::routes(['verify' => true]);
Route::get('reg', 'Auth\RegisterController@showRegistrationForm');
Route::get('reg/{?referral_code}', 'Auth\RegisterController@showRegistrationForm');
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
    Route::get('reload_cards/{controller_id}', 'ControllersController@reloadCards');
});
/*
 * Control Panel
 */
Route::group(['prefix' => 'cp', 'as' => 'cp.'], function () {
    Route::get('/', 'UsersController@index')->name('index');
    Route::get('classes', 'DivisionsController@classes')->name('classes')->middleware('role:2,3,7');
    Route::get('persons', 'PersonsController@page')->name('persons')->middleware('role:2,3,7');
    Route::get('statistics', 'UsersController@students')->name('students');
    Route::get('students', 'UsersController@students')->name('students');
    Route::get('timetable', 'TimetableController@page')->name('timetable')->middleware('role:1');

});
/*
 * Development
 */
Route::get('dev', 'DevController@index');
/*
 * Server
 */
Route::post('server', 'ServersController@index');
Route::post('policont', 'ServersController@policont');
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
